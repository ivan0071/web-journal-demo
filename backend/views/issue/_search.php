<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\jui\DatePicker;
use yii\base\Widget;
use yii\grid\DataColumn;
use yii\web\UrlManager;
use common\models\Issue;

/* @var $this yii\web\View */
/* @var $model backend\models\IssueSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="issue-search">

	<?php if(isset($post_msg)){ ?>
	    <div class="alert alert-dismissable <?php echo "alert-".$post_msg["type"];?>" id="homepage-section-alert"> <?php /*alert-danger alert-success alert-warning */ ?>
		    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		    <strong><span id="homepage-section-alert-msg"></span><?php echo $post_msg["text"]; ?></strong>
		</div>
	<?php } ?>
	<h1><?php echo "Issue List" ?></h1>
	<hr>	
	
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
    	'rowOptions' => function ($data) {
        	if($data->is_current == 1){
        		$test = " is current";
        		return [
        				'class' => 'is_current',
        				'title' => 'Current Issue',
        		];
        	} else {
        		$test = "is NOT current";
        	}
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'issue_id',
            //'title:ntext',
        	[
        		'class' => DataColumn::className(), // this line is optional
        		'attribute' => 'title',
        		'label' => 'Issue title',
        		'value' =>function ($data) {
        			return displayColumnContent($data->title, 45);
        		},
        		"format" => "HTML",
        		'headerOptions' => ['style' => 'width:35%'],
        	],            
        	[
        		'class' => DataColumn::className(), // this line is optional
        		'attribute' => 'volume_id',
        		'label' => 'Volume title',
        		'value' =>function ($data) {
        			return displayColumnContent($data->volume->title, 40);
        		},
        		"format" => "HTML",
        		'filter'=>Issue::get_volumes(),
        		'headerOptions' => ['style' => 'width:30%'],
        	],            
        	[
        		'class' => DataColumn::className(), // this line is optional
        		'attribute' => 'is_special_issue',
        		'value' =>function ($data) {
        			if ($data->is_special_issue == 0)
        				return "<div class='glyphicon glyphicon-remove'></div>";
        			else
        				return "<div class='glyphicon glyphicon-ok'></div>";
		         },
        		"format" => "HTML",
        		'filter'=>[
        				"1" => "Yes",
        				"0" => "No"        				
		         ],
        		
        	],
        	[
        		'class' => DataColumn::className(), // this line is optional
        		'attribute' => 'published_on',
        		'value' =>function ($data) {
        			if (isset($data->published_on))
        				return date("M d, Y, g:i:s A", strtotime($data->published_on));
        		},        			
        		'format' => 'HTML',
        		//'headerOptions' => ['style' => 'width:15%'],
        	],         	
        	//'published_on:datetime',
            // 'special_title:ntext',
            // 'special_editor',
            // 'cover_image',
            // 'sort_in_volume',
            // 'created_on',
            // 'updated_on',
            // 'is_deleted',

            ['class' => 'yii\grid\ActionColumn']
        ],
    ]); 
    
    function displayColumnContent($contenttext, $contentlimitlength){
    	$displaytext = $contenttext;
    	if(strlen($displaytext) > $contentlimitlength) $displaytext = substr($displaytext, 0, $contentlimitlength)."...";
    	return "<div title='".$contenttext."'>".$displaytext."</div>";
    }
    
    ?>
</div>
