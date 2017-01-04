<?php
namespace frontend\controllers;

use Yii;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\CommonVariables;
use common\models\HomepageSection;
use common\models\Announcement;
use common\models\User;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => [	
                        				'logout', 'userpanel', 
                        				'asynch-alert-duplicate-user'
                        			],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                	'asynch-alert-duplicate-user' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
    	$common_vars = new CommonVariables();

    	$homeSections = HomepageSection::find()
				    	->where(['is_deleted' => false, 'is_visible' => true])
				    	->orderBy('sort_order')
				    	->all();
				    	 
    	return $this->render('index', [
    			'model' => $homeSections,
    			'common_vars' => $common_vars
    	]);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            //return $this->goBack();
            return $this->redirect(Yii::$app->urlManagerBackEnd->createUrl('site/index'));
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        $post_msg = null;
        
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
            	if(isset($user) && isset($user["duplicate_message"])){
            		if($user["duplicate_message"] === "existing email and username error"){
            			$post_msg["type"] = "warning";
            			$post_msg["text"] = "The username and the email address have already been taken. Try with anothers.<br><br>";
            			$post_msg["text"] .= "If this was not you, click <a id='duplicatetrigger' onclick='userScript_clickDuplicateUser(\"".$user["duplicate_username_user"]->id."\",\"".$user["duplicate_email_user"]->id."\")'><b>here</b></a> to send an instructions via email!";
            		} else if($user["duplicate_message"] === "existing email error"){
            			$post_msg["type"] = "warning";
            			$post_msg["text"] = "This email address has already been taken. Try with another one.<br><br>";
            			$post_msg["text"] .= "If this was not you, click <a id='duplicatetrigger' onclick='userScript_clickDuplicateUser(\"".intval(0)."\",\"".$user["duplicate_email_user"]->id."\")'><b>here</b></a> to send an instructions via email!";
            		} else if($user["duplicate_message"] === "existing username error"){
            			$post_msg["type"] = "warning";
            			$post_msg["text"] = "This username has already been taken. Try with another one.";
            			//$post_msg["text"] .= "If this was not you, click <a id='duplicatetrigger' onclick='userScript_clickDuplicateUser(\"".$user["duplicate_username_user"]->id."\",\"".intval(0)."\")'><b>here</b></a> to send an instructions via email!";
            		}
            	} else if (Yii::$app->getUser()->login($user)) {
            		Yii::$app->session->set('user.is_admin', false /*$user->is_admin*/);
            		Yii::$app->session->set('user.is_editor', false /*$user->is_editor*/);
            		Yii::$app->session->set('user.is_reader', $user->is_reader);
            		Yii::$app->session->set('user.is_author', $user->is_author);
            		Yii::$app->session->set('user.is_reviewer', $user->is_reviewer);
            		            		
                    return $this->goHome();
                }                
            }        	
        }
        
        $common_vars = new CommonVariables();      
       
        if(isset($_POST['SignupForm']['gender'])){
        	$model->gender_opt = ['prompt' => '--- Select ---', 'options' => [$_POST['SignupForm']['gender'] => ['Selected' => 'selected']]];
        } else {
        	$model->gender_opt = ['prompt' => '--- Select ---'];
        }
        
        if(isset($_POST['SignupForm']['country'])){
        	$model->country_opt = ['prompt' => '--- Select ---', 'options' => [$_POST['SignupForm']['country'] => ['Selected' => 'selected']]];
        } else {
        	$model->country_opt = ['prompt' => '--- Select ---'];
        }
        
        if(isset($_POST['SignupForm']['send_confirmation'])){
        	$model->send_confirmation = $_POST['SignupForm']['send_confirmation'];
        } else {
        	$model->send_confirmation = true;
        }
        
        if(isset($_POST['SignupForm']['is_reader'])){
        	$model->is_reader = $_POST['SignupForm']['is_reader'];
        } else{
        	$model->is_reader = true;
        }
        
        if(isset($_POST['SignupForm']['is_author'])){
        	$model->is_author = $_POST['SignupForm']['is_author'];
        } else{
        	$model->is_author = true;
        }
        
        if(isset($_POST['SignupForm']['is_reviewer'])){
        	$model->is_reviewer = $_POST['SignupForm']['is_reviewer'];
        }        

        return $this->render('signup', [
            'model' => $model,
        	'common_vars' => $common_vars,
        	'post_msg' => $post_msg
        ]);
    }
    
    /**
     * Displays user panel page.
     *
     * @return mixed
     */
    public function actionUserpanel()
    {
    	return $this->render('userpanel');
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
    
    /**
     * Report duplicate email to admin.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionReportDuplicateEmail($token)
    {
    	$post_msg = null;
	    try {
	    	if (empty($token) || !is_string($token)) {
	    		$post_msg["type"] = "warning";
	    		$post_msg["text"] = "Helper token cannot be blank.";	    		 
	    	}
	        $user = User::findByHelperToken($token);
	        if (!$user) {
	        	$post_msg["type"] = "danger";
	        	$post_msg["text"] = "Wrong helper token. It may be already used or expired.";
	        } else {
	        	$user->helper_token = null;
	        	$user->updated_at = date("Y-m-d H:i:s");
	        	if ($user->save()) {
	        		$email_sent = Yii::$app->mailer->compose(['html' => 'duplicateUserAdmin-html', 'text' => 'duplicateUserAdmin-text'], ['user' => $user])
		        		->setTo(Yii::$app->params['adminEmail'])
		        		->setFrom([$user->email => $user->fullName])
		        		->setSubject("User Email Violation Report!")
		        		->send();
	        		if($email_sent) {
	        			$post_msg["type"] = "success";
	        			$post_msg["text"] = "Report for violation of the user email has been successfully sent";
	        		}
	        	} else {
	        		Yii::error("SiteController->actionReportDuplicateEmail(1): ".json_encode($user->getErrors()), "custom_errors_users");
	        		$post_msg["type"] = "danger";
	        		$post_msg["text"] = "Failure! Report for violation of the user email has not been sent";
	        	}
	        }	        	        
    	} catch (Exception $e) {
    		Yii::error("SiteController->actionReportDuplicateEmail(2): ".json_encode($e->getMessage()), "custom_errors_users");
    		throw new BadRequestHttpException($e->getMessage());
    	}    

    	return $this->render('reportDuplicateEmail', [
    			'post_msg' => $post_msg,
    	]);
    }
    
    /**
     * Displays search page.
     *
     * @return mixed
     */
    public function actionSearch()
    {
    	$model = new ContactForm();
    	if ($model->load(Yii::$app->request->post()) && $model->validate()) {
    		if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
    			Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
    		} else {
    			Yii::$app->session->setFlash('error', 'There was an error sending email.');
    		}
    
    		return $this->refresh();
    	} else {
    		return $this->render('search', [
    				'model' => $model,
    		]);
    	}
    }
    
    /**
     * Displays current page.
     *
     * @return mixed
     */
    public function actionCurrent()
    {
    	$model = new ContactForm();
    	if ($model->load(Yii::$app->request->post()) && $model->validate()) {
    		if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
    			Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
    		} else {
    			Yii::$app->session->setFlash('error', 'There was an error sending email.');
    		}
    
    		return $this->refresh();
    	} else {
    		return $this->render('current', [
    				'model' => $model,
    		]);
    	}
    }
    
    /**
     * Displays announcement page (blog posts).
     *
     * @return mixed
     */
    public function actionAnnouncement()
    {
    	$announcements = Announcement::find()
				    	->where([
				    			'is_deleted' => false,
				    			'is_visible' => true
				    	])
				    	->orderBy([
				    			'sort_order' => SORT_ASC,
				    			'announcement_id' => SORT_DESC
				    	])
				    	->all();

    	return $this->render('announcement', [
    			'model' => $announcements,
    	]);
    }
    
    /**
     * Displays announcement page (blog posts).
     *
     * @return mixed
     */
    public function actionAnnouncementdetails($id)
    {
    	$announcement = Announcement::find()
				    	->where([
				    			'announcement_id' => $id,
				    			'is_deleted' => false,
				    			'is_visible' => true
				    	])
				    	->one();
    
    	return $this->render('announcementDetails', [
    			'model' => $announcement,
    	]);
    }
    
    /*
     * Asynch functions called with Ajax - User (click on link for notifying users about duplicate email)
     */
    public function actionAsynchAlertDuplicateUser()
    {
    	$usernameUserID = Yii::$app->getRequest()->post('usernameUserID');
    	$emailUserID = Yii::$app->getRequest()->post('emailUserID');
    	 
    	if($emailUserID != 0) {
    		$type = "email";
    		if($emailUserID == $usernameUserID){
    			$type = "username (".$usernameUser->username.") and email";
    		}
    		$emailUser = User::findOne([
    				'id' => $emailUserID
    		]);
    		if(isset($emailUser)){
    			$emailUser->helper_token = Yii::$app->security->generateRandomString() . '_' . time();
    			$emailUser->updated_at = date("Y-m-d H:i:s");
    			if ($emailUser->save()) {
    				return \Yii::$app->mailer->compose(['html' => 'duplicateUserReportToken-html', 'text' => 'duplicateUserReportToken-text'], ['user' => $emailUser, 'type' => $type])
    				->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name . ' robot'])
    				->setTo($emailUser->email)
    				->setSubject('Report for duplicate user!')
    				->send();
    			} else {
    				Yii::error("SiteController->actionAsynchAlertDuplicateUser(1): ".json_encode($emailUser->getErrors()), "custom_errors_users");
    				return "Failure! Report for duplicate user has not been sent.";
    			}
    		}
    	}
    
    	return "Report for duplicate user has been successfully sent.";
    }
}
