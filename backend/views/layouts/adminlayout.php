<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
use kartik\sidenav\SideNav;
use yii\helpers\Url;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <link rel="shortcut icon" href="<?php echo Yii::$app->request->baseUrl; ?>/myfavicon1.ico" type="image/x-icon" />
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href='<?php echo Url::to(['/site/index']); ?>'>Open Journal System</a>
            </div>
            
            <ul class="nav navbar-top-links navbar-right">
            	<li>
               		<a href='<?php echo Yii::$app->urlManagerFrontEnd->createUrl('site/index'); ?>'>
                    	<div>
                        	<strong>Home</strong>
                        </div>
                    </a>
                </li>
                <li>
               		<a href='<?php echo Url::to(['/user/profile']); ?>'>
                    	<div>
                        	<strong>My Profile</strong>
                        </div>
                    </a>
                </li>
                <?php if (Yii::$app->user->isGuest) { ?>
                <li>
               		<a href='<?php echo Url::to(['/site/login']); ?>'>
                    	<div>
                        	<strong>Login</strong>
                        </div>
                    </a>
                </li>
                <?php } else { ?>
                <li>
               		<a href='<?php echo Url::to(['/site/logout']); ?>' data-method='POST'>
                    	<div>
                        	<strong><?php echo "Logout ( ". Yii::$app->user->identity->username ." )"; ?></strong>
                        </div>
                    </a>                    
                </li>                
                <?php } ?>
            </ul>
            <!-- /.navbar-header -->

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                    	<!-- 
                        <li class="sidebar-search">
                            <div class="input-group custom-search-form">
                                <input type="text" class="form-control" placeholder="Search...">
                                <span class="input-group-btn">
	                                <button class="btn btn-default" type="button">
	                                    <i class="fa fa-search"></i>
	                                </button>
	                            </span>
                            </div>
                            <!-- /input-group 
                        </li>-->
                        <li>
                            <a href='<?php echo Url::to(['/site/index']); ?>'><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                        </li>
                        <?php if (Yii::$app->session->get('user.is_admin') == true){ ?>
                        <li>
                            <a href='<?php echo Url::to(['/admin/home']); ?>'><i class="fa fa-home fa-fw"></i> Manage Home Page</a>
                        </li>
                        <?php } ?>
                        <?php if (Yii::$app->session->get('user.is_admin') == true){ ?>
                        <li>
							<a href='#'><i class="fa fa-comments-o fa-fw"></i> Announcements<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                            	<li>
                                    <a href='<?php echo Url::to(['/announcement/index']); ?>'><i class="fa fa-list fa-fw"></i> List All</a>
                                </li>
                                <li>
                                    <a href='<?php echo Url::to(['/announcement/create']); ?>'><i class="fa fa-plus fa-fw"></i> Create New</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->                            
                        </li>
                        <?php } ?>
                        <li>
                            <a href='#'><i class="fa fa-users fa-fw"></i> Users<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                       <?php if (Yii::$app->session->get('user.is_admin') == true){ ?>
                            
                            	<li>
                                    <a href='<?php echo Url::to(['/user/index']); ?>'><i class="fa fa-list fa-fw"></i> List All</a>
                                </li>
                                <li>
                                    <a href='<?php echo Url::to(['/user/index?type=admin']); ?>'><i class="fa fa-user fa-fw"></i> Admins</a>
                                </li>
                        <?php } ?>
                                <li>
                                    <a href='<?php echo Url::to(['/user/index?type=author']); ?>'><i class="fa fa-user fa-fw"></i> Authors</a>
                                </li>
                       <?php if (Yii::$app->session->get('user.is_admin') == true){ ?>
                                <li>
                                    <a href='<?php echo Url::to(['/user/index?type=editor']); ?>'><i class="fa fa-user fa-fw"></i> Editors</a>
                                </li>
                                <li>
                                    <a href='<?php echo Url::to(['/user/index?type=reviewer']); ?>'><i class="fa fa-user fa-fw"></i> Reviewers</a>
                                </li>
                                <li>
                                    <a href='<?php echo Url::to(['/user/create']); ?>'><i class="fa fa-plus fa-fw"></i> Create New User</a>
                                </li>
						<?php } ?>
								<li>
                                    <a href='<?php echo Url::to(['/user/index?type=unregisteredauthor']); ?>'><i class="fa fa-user fa-fw"></i> Unregistered Authors</a>
                                </li>
                                <li>
                                    <a href='<?php echo Url::to(['/user/createunregisteredauthor']); ?>'><i class="fa fa-plus fa-fw"></i> Create Unregistered Author</a>
                                </li>                        
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <?php if (Yii::$app->session->get('user.is_admin') == true){ ?>
                        <li>
                            <a href='#'><i class="fa fa-book fa-fw"></i> Volumes / Issues<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                            	<li>
                                    <a href='<?php echo Url::to(['/volume/index']); ?>'><i class="fa fa-list fa-fw"></i> List All</a>
                                </li>
                                <li>
                                    <a href='<?php echo Url::to(['/volume/create']); ?>'><i class="fa fa-plus fa-fw"></i> Create New</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <?php } ?>
                        <?php if (Yii::$app->session->get('user.is_admin') == true){ ?>
                        <li>
                            <a href='#'><i class="fa fa-edit fa-fw"></i> Issues / Sections<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                            	<li>
                                    <a href='<?php echo Url::to(['/issue/index']); ?>'><i class="fa fa-list fa-fw"></i> List All</a>
                                </li>
                                <li>
                                    <a href='<?php echo Url::to(['/issue/create']); ?>'><i class="fa fa-plus fa-fw"></i> Create New</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <?php } ?>
                        <?php if (Yii::$app->session->get('user.is_admin') == true){ ?>
                        <li>
                            <a href='#'><i class="fa fa-tasks fa-fw"></i> Sections<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                            	<li>
                                    <a href='<?php echo Url::to(['/section/index']); ?>'><i class="fa fa-list fa-fw"></i> List All</a>
                                </li>
                                <li>
                                    <a href='<?php echo Url::to(['/section/create']); ?>'><i class="fa fa-plus fa-fw"></i> Create New</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <?php } ?>
						<?php if ((Yii::$app->session->get('user.is_admin') == true) || (isset(Yii::$app->user) && isset(Yii::$app->user->identity) && (Yii::$app->user->identity->is_author == 1))){ ?>
                        <li>
                            <a href='#'><i class="fa fa-newspaper-o fa-fw"></i> Articles<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                            <?php if (Yii::$app->session->get('user.is_admin') == true){ ?>
                            	<li>
                                    <a href='<?php echo Url::to(['/article/index']); ?>'><i class="fa fa-list fa-fw"></i> List All</a>
                                </li>
                            <?php } ?>
							<?php if (isset(Yii::$app->user) && isset(Yii::$app->user->identity) && (Yii::$app->user->identity->is_author == 1)){ ?>
                                <li>
                                    <a href='<?php echo Url::to(['/article/myarticles']); ?>'><i class="fa fa-list fa-fw"></i> My Articles</a>
                                </li>
                                <li>
                                    <a href='<?php echo Url::to(['/article/create']); ?>'><i class="fa fa-plus fa-fw"></i> Create New</a>
                                </li>
                            <?php } ?>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <?php } ?>
                        <li>
                            <a href='#'><i class="fa fa-key fa-fw"></i> Keywords<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                            	<li>
                                    <a href='<?php echo Url::to(['/keyword/index']); ?>'><i class="fa fa-list fa-fw"></i> List All</a>
                                </li>
                                <li>
                                    <a href='<?php echo Url::to(['/keyword/create']); ?>'><i class="fa fa-plus fa-fw"></i> Create New</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <?php if (Yii::$app->session->get('user.is_reviewer') == true){ ?>
                        <li>
                            <a href='#'><i class="fa fa-tasks fa-fw"></i> Reviewer Menu<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                            	<li>
                                    <a href='<?php echo Url::to(['/articlereviewer/pending']); ?>'><i class="fa fa-arrow-down fa-fw"></i> Pending Reviews</a>
                                </li>
                                <li>
                                    <a href='<?php echo Url::to(['/articlereviewer/submitted']); ?>'><i class="fa fa-check-square-o fa-fw"></i> Submitted Reviews</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <?php } ?>
                        <?php if (Yii::$app->session->get('user.is_editor') == true){ ?>
                        <li>
                            <a href='#'><i class="fa fa-sliders fa-fw"></i> Editor Menu<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                            	<li>
                                    <a href='<?php echo Url::to(['/articleeditor/myissues']); ?>'><i class="fa fa-edit fa-fw"></i> My Issues</a>
                                </li>
                            	<li>
                                    <a href='<?php echo Url::to(['/articleeditor/myarticles']); ?>'><i class="fa fa-newspaper-o fa-fw"></i> My Articles</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <?php } ?>                        
						<!-- 
                        <li>
                            <a href="forms.html"><i class="fa fa-edit fa-fw"></i> Forms</a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-wrench fa-fw"></i> UI Elements<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="panels-wells.html">Panels and Wells</a>
                                </li>
                                <li>
                                    <a href="buttons.html">Buttons</a>
                                </li>
                                <li>
                                    <a href="notifications.html">Notifications</a>
                                </li>
                                <li>
                                    <a href="typography.html">Typography</a>
                                </li>
                                <li>
                                    <a href="icons.html"> Icons</a>
                                </li>
                                <li>
                                    <a href="grid.html">Grid</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level 
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-sitemap fa-fw"></i> Multi-Level Dropdown<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="#">Second Level Item</a>
                                </li>
                                <li>
                                    <a href="#">Second Level Item</a>
                                </li>
                                <li>
                                    <a href="#">Third Level <span class="fa arrow"></span></a>
                                    <ul class="nav nav-third-level">
                                        <li>
                                            <a href="#">Third Level Item</a>
                                        </li>
                                        <li>
                                            <a href="#">Third Level Item</a>
                                        </li>
                                        <li>
                                            <a href="#">Third Level Item</a>
                                        </li>
                                        <li>
                                            <a href="#">Third Level Item</a>
                                        </li>
                                    </ul>
                                    <!-- /.nav-third-level
                                </li>
                            </ul>
                            <!-- /.nav-second-level 
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-files-o fa-fw"></i> Sample Pages<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="blank.html">Blank Page</a>
                                </li>
                                <li>
                                    <a href="login.html">Login Page</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level 
                        </li>-->
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav> 
           
	<?php 
	    $currentControllerId = $this->context->id;
	    $currentActionId = $this->context->action->id;
	?>
	
    <div id="page-wrapper">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?php if(!Yii::$app->user->getIsGuest()){?>        
        	<!-- here your content page-->
            <?= $content ?>
        <?php } ?>
        
    </div>
</div>

<div class="clear"></div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; Ivan Stojanov <?= date('Y') ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
