<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */
$reviewLink = Yii::$app->urlManagerBackEnd->createAbsoluteUrl(['articlereviewer/view', 'id' => $modelArticleReviewer->article_id]);
?>
<div class="moveforreviewagainreviewer-form">
    <p>Dear <?= Html::encode($modelArticleReviewer->reviewer->fullName) ?>,</p>

    <p>This is a notification from the site "<?= \Yii::$app->name ?>".</p>
    
    <p>An article with the title '<?= $modelArticleReviewer->article->title ?>' has been improved and was sent for review again.</p>
    
    <p>You are set as one of the reviewer(s). You can see the article details and update your review by clicking <?= Html::a(Html::encode("here"), $reviewLink) ?>.</p>

	<p>Regards,<br/><?= \Yii::$app->name ?> team.</p>
</div>
