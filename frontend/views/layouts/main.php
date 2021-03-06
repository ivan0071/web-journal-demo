<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);
//include('./../web/css/plusMinusCarousel.css');
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

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'Open Journal Systems',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    
    $currentControllerId = $this->context->id;
    $currentActionId = $this->context->action->id;
    
    $menuItems = [
        ['label' => 'Home', 'url' => ['/site/index']],
    ];    
    $menuItems[] = ['label' => 'About', 'url' => ['/site/about'],
    				'active' => (($currentControllerId == 'site' && $currentActionId == 'about') 	||
    							 ($currentControllerId == 'about' && $currentActionId == 'sitemap')),
    ];    
    $menuItems[] = [
    	'label' => 'Contact Us', 'url' => ['/site/contact'],
    ];
    if (Yii::$app->user->isGuest) {
    	$menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
        $menuItems[] = ['label' => 'Register', 'url' => ['/site/signup']];        
    } else {
    	$menuItems[] = ['label' => 'User Panel', 'url' => Yii::$app->urlManagerBackEnd->createUrl('site/index'),];
    }
    $menuItems[] = ['label' => 'Search', 'url' => ['/search/index'],
    				'active' => (($currentControllerId == 'search' && $currentActionId == 'index') 			||
    							 ($currentControllerId == 'search' && $currentActionId == 'volume') 		||
    							 ($currentControllerId == 'search' && $currentActionId == 'issue')			||
    							 ($currentControllerId == 'search' && $currentActionId == 'section')		||
    							 ($currentControllerId == 'search' && $currentActionId == 'article')		||
    							 ($currentControllerId == 'search' && $currentActionId == 'keyword')		||
    							 ($currentControllerId == 'search' && $currentActionId == 'user')),    		
    				];
    $menuItems[] = ['label' => 'Current', 'url' => ['/site/current']];
    $menuItems[] = ['label' => 'Archive', 'url' => ['/site/archive']];
    $menuItems[] = ['label' => 'Blog', 'url' => ['/site/announcement'], 
				    'active' => (($currentControllerId == 'site' && $currentActionId == 'announcement') 	||
					    		 ($currentControllerId == 'site' && $currentActionId == 'announcementdetails')),
				   ];
    //$menuItems[] = ['label' => 'Contact', 'url' => ['/site/contact']];
    if (!(Yii::$app->user->isGuest)) {
         $menuItems[] = [
            'label' => 'Logout',// (' . Yii::$app->user->identity->username . ')',
            'url' => ['/site/logout'],
            'linkOptions' => ['data-method' => 'post']
        ];
    }   
    
   
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; Ivan Stojanov <?= date('Y') ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
