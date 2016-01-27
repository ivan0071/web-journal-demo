<?php

namespace backend\controllers;

use Yii;
use common\models\User;
use common\models\CommonVariables;
use app\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\UserProfileForm;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    public function behaviors()
    {
        return [        		
        	'access' => [
        		'class' => AccessControl::className(),
        		'rules' => [
        			//not logged users do not have access to any action
        			/*[
        			 'actions' => ['login', 'error'],
        				'allow' => true,
        			],*/
        			//only logged users have access to actions
        			[
        				'actions' => [	'index', 'view', 'create', 'update', 'delete', 'profile', 'captcha',
        				],
        				'allow' => true,
        				'roles' => ['@'],
        			],
        		],
        	],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function actions()
    {
    	$this->layout = 'adminlayout';
    	return [
    			'error' => [
    					'class' => 'yii\web\ErrorAction',
    			],
    			'captcha' => [
    					'class' => 'yii\captcha\CaptchaAction',
    					'fixedVerifyCode' => YII_ENV_TEST ? 'testme2' : null,
    			],
    	];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    

    public function actionProfile()
    {
    	if (Yii::$app->user->isGuest) {
    		return $this->redirect(['error']);
    	}
    	 
    	$currentId = Yii::$app->user->identity->attributes["id"];
    	
        $model = new UserProfileForm();        
        $postErrorMessage = null;
        
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->updateUserProfile($currentId)) {
            	
           		if($user === "existing email and username error"){
           			$postErrorMessage = "The username and the email address have already been taken. Try with anothers.";
           		} else if($user === "existing email error"){
           			$postErrorMessage = "This email address has already been taken. Try with another one.";
           		} else if($user === "existing username error"){
            		$postErrorMessage = "This username has already been taken. Try with another one.";            		
            	} else {            	
	            	$searchModel = new UserSearch();
					$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
					
					return $this->render('index', [
						'searchModel' => $searchModel,
					    'dataProvider' => $dataProvider,
					]);
            	}
            }     	
        } 

        $currentUserModel = $this->findModel($currentId);
        $currentUser = [$model->formName() => $currentUserModel->attributes];
        $model->load($currentUser);
        
        if(isset($_POST[$model->formName()]["password"])){
        	$model->password = $_POST[$model->formName()]["password"];
        } else if(isset($currentUserModel->password_hash)){
        	$model->password = $currentUserModel->password_hash;
        }
        
        if(isset($_POST[$model->formName()]["repeat_password"])){
        	$model->repeat_password = $_POST[$model->formName()]["repeat_password"];
        } else if(isset($currentUserModel->password_hash)){
        	$model->repeat_password = $currentUserModel->password_hash;
        }
        
        if(isset($_POST[$model->formName()]["email"])){
        	$model->email = $_POST[$model->formName()]["email"];
        } else if(isset($currentUserModel->email)){
        	$model->email = $currentUserModel->email;
        }
        
        if(isset($_POST[$model->formName()]["repeat_email"])){
        	$model->repeat_email = $_POST[$model->formName()]["repeat_email"];
        } else if(isset($currentUserModel->email)){
        	$model->repeat_email = $currentUserModel->email;
        }        
        
        if(isset($_POST[$model->formName()]["salutation"])){
        	$model->salutation = $_POST[$model->formName()]["salutation"];
        } else if(isset($currentUserModel->salutation)){
        	$model->salutation = $currentUserModel->salutation;
        }        

        if(isset($_POST[$model->formName()]["first_name"])){
        	$model->first_name = $_POST[$model->formName()]["first_name"];
        } else if(isset($currentUserModel->first_name)){
        	$model->first_name = $currentUserModel->first_name;
        } 
        
        if(isset($_POST[$model->formName()]["middle_name"])){
        	$model->middle_name = $_POST[$model->formName()]["middle_name"];
        } else if(isset($currentUserModel->middle_name)){
        	$model->middle_name = $currentUserModel->middle_name;
        }
        
        if(isset($_POST[$model->formName()]["last_name"])){
        	$model->last_name = $_POST[$model->formName()]["last_name"];
        } else if(isset($currentUserModel->last_name)){
        	$model->last_name = $currentUserModel->last_name;
        }
        
        if(isset($_POST[$model->formName()]["initials"])){
        	$model->initials = $_POST[$model->formName()]["initials"];
        } else if(isset($currentUserModel->initials)){
        	$model->initials = $currentUserModel->initials;
        }
        
        if(isset($_POST[$model->formName()]["affiliation"])){
        	$model->affiliation = $_POST[$model->formName()]["affiliation"];
        } else if(isset($currentUserModel->affiliation)){
        	$model->affiliation = $currentUserModel->affiliation;
        }
        
        if(isset($_POST[$model->formName()]["signature"])){
        	$model->signature = $_POST[$model->formName()]["signature"];
        } else if(isset($currentUserModel->signature)){
        	$model->signature = $currentUserModel->signature;
        }
        
        if(isset($_POST[$model->formName()]["bio_statement"])){
        	$model->bio_statement = $_POST[$model->formName()]["bio_statement"];
        } else if(isset($currentUserModel->bio_statement)){
        	$model->bio_statement = $currentUserModel->bio_statement;
        }
  
        if(isset($_POST[$model->formName()]["orcid_id"])){
        	$model->orcid_id = $_POST[$model->formName()]["orcid_id"];
        } else if(isset($currentUserModel->orcid_id)){
        	$model->orcid_id = $currentUserModel->orcid_id;
        }
        
        if(isset($_POST[$model->formName()]["url"])){
        	$model->url = $_POST[$model->formName()]["url"];
        } else if(isset($currentUserModel->url)){
        	$model->url = $currentUserModel->url;
        }
        
        if(isset($_POST[$model->formName()]["phone"])){
        	$model->phone = $_POST[$model->formName()]["phone"];
        } else if(isset($currentUserModel->phone)){
        	$model->phone = $currentUserModel->phone;
        }
        
        if(isset($_POST[$model->formName()]["fax"])){
        	$model->fax = $_POST[$model->formName()]["fax"];
        } else if(isset($currentUserModel->fax)){
        	$model->fax = $currentUserModel->fax;
        }
        
        if(isset($_POST[$model->formName()]["mailing_address"])){
        	$model->mailing_address = $_POST[$model->formName()]["mailing_address"];
        } else if(isset($currentUserModel->mailing_address)){
        	$model->mailing_address = $currentUserModel->mailing_address;
        }
        
        if(isset($_POST[$model->formName()]["reviewer_interests"])){
        	$model->reviewer_interests = $_POST[$model->formName()]["reviewer_interests"];
        } else if(isset($currentUserModel->reviewer_interests)){
        	$model->reviewer_interests = $currentUserModel->reviewer_interests;
        }

        $common_vars = new CommonVariables();
    	
        if(isset($_POST[$model->formName()]["gender"])){
        	$additional_params["gender_opt"] = ['prompt' => '--- Select ---', 'options' => [$_POST[$model->formName()]["gender"] => ['Selected' => 'selected']]];
        } else if(isset($currentUserModel->gender)){
    		$additional_params["gender_opt"] = ['prompt' => '--- Select ---', 'options' => [$currentUserModel->gender => ['Selected' => 'selected']]];
    	} else {
    		$additional_params["gender_opt"] = ['prompt' => '--- Select ---'];
    	}
    	
    	if(isset($_POST[$model->formName()]["country"])){
        	$additional_params["country_opt"] = ['prompt' => '--- Select ---', 'options' => [$_POST[$model->formName()]["country"] => ['Selected' => 'selected']]];
        } else if(isset($currentUserModel->country)){
    		$additional_params["country_opt"] = ['prompt' => '--- Select ---', 'options' => [$currentUserModel->country => ['Selected' => 'selected']]];
    	} else {
    		$additional_params["country_opt"] = ['prompt' => '--- Select ---'];
    	}
    	
    	if(isset($_POST[$model->formName()]['send_confirmation'])){
    		$model->send_confirmation = $_POST[$model->formName()]['send_confirmation'];
    	} else if (isset($currentUserModel->send_confirmation)) {	
    		$model->send_confirmation = $currentUserModel->send_confirmation;
    	} else {
    		$model->send_confirmation = true;
    	}
    	
    	if(isset($_POST[$model->formName()]['is_reader'])){
    		$model->is_reader = $_POST[$model->formName()]['is_reader'];
    	} else if (isset($currentUserModel->is_reader)) {	
    		$model->is_reader = $currentUserModel->is_reader;
    	} else {
    		$model->is_reader = true;
    	}
    	
    	if(isset($_POST[$model->formName()]['is_author'])){
    		$model->is_author = $_POST[$model->formName()]['is_author'];
    	} else if (isset($currentUserModel->is_author)) {	
    		$model->is_author = $currentUserModel->is_author;
    	}
    	
    	if(isset($_POST[$model->formName()]['is_reviewer'])){
    		$model->is_reviewer = $_POST[$model->formName()]['is_reviewer'];
    	} else if (isset($currentUserModel->is_reviewer)) {	
    		$model->is_reviewer = $currentUserModel->is_reviewer;
    	}
    	
    	$additional_vars = (object)$additional_params;
    	 
	   	return $this->render('profile', [
	   			'model' => $model,
	   			'common_vars' => $common_vars,
    			'additional_vars' => $additional_vars,
	   			'post_error_msg' => $postErrorMessage
	   	]);
   	} 

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
