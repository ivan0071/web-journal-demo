<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\jui\DatePicker;
use yii\base\Widget;
use yii\grid\DataColumn;

/* @var $this yii\web\View */
/* @var $model backend\models\VolumeSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="volume-search">

	<?php if(isset($post_msg)){ ?>
	    <div class="alert alert-dismissable <?php echo "alert-".$post_msg["type"];?>" id="homepage-section-alert"> <?php /*alert-danger alert-success alert-warning */ ?>
		    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		    <strong><span id="homepage-section-alert-msg"></span><?php echo $post_msg["text"]; ?></strong>
		</div>
	<?php } ?>
	<h1><?php echo "Volume List" ?></h1>
	<hr>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'title:ntext',
        	[
        		'class' => DataColumn::className(), // this line is optional
        		'attribute' => 'title',
        		'label' => 'Volume title',
        		'value' =>function ($data) {
        			return displayColumnContent($data->title, 65);
        		},
        		"format" => "HTML",
        		'headerOptions' => ['style' => 'width:50%'],
        	],           
            'year',
        	//'created_on:datetime',
        	[
        		'class' => DataColumn::className(), // this line is optional
        		'attribute' => 'created_on',
        		'value' =>function ($data) {
        			if (isset($data->created_on))
        				return date("M d, Y, g:i:s A", strtotime($data->created_on));
        		},
        		'format' => 'HTML',
        	],        		
        	/*[
        		//'attribute' => 'created_on',
        		'header'		=> 'Created on',
        		'value'		=> 'created_on',
        		'format'	=> 'datetime',
        		'filter'	=> DatePicker::widget([
        			'model' 		=> $searchModel,
        			'attribute' 	=> 'created_on',
        			'clientOptions' => [
        				'autoclose' => true,
        				'format'	=> 'datetime',
    				]
    			]),
        	],*/            

        	['class' => 'yii\grid\ActionColumn'],
        ],
    ]); 
    
    function displayColumnContent($contenttext, $contentlimitlength){
    	$displaytext = $contenttext;
    	if(strlen($displaytext) > $contentlimitlength) $displaytext = substr($displaytext, 0, $contentlimitlength)."...";
    	return "<div title='".$contenttext."'>".$displaytext."</div>";
    }
    
    ?>
</div>
