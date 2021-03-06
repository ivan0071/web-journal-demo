<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */
$articleLink = Yii::$app->urlManagerBackEnd->createAbsoluteUrl(['article/view', 'id' => $modelArticleAuthor->article_id]);
?>
Dear <?= $modelArticleAuthor->author->fullName ?>,

This is a notification from the site "<?= \Yii::$app->name ?>".
    
Your article with the title '<?= $modelArticleAuthor->article->title ?>' has been sent for review again, after it was moved back for improvements. While the article is 'under review' status, changes to it can not be made.
    
You are set as correspondent author. Please see the article details and track it's status on the following link: <?= $articleLink ?>.

Regards,

<?= \Yii::$app->name ?> team.