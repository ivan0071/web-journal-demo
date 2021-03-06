<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\DataColumn;

/* @var $this yii\web\View */
/* @var $model common\models\Issue */

$this->title = $model->title;
?>
<div class="issue-view">

    <h2><i>Issue: </i><?php echo Html::encode($this->title) ?></h2>
	<hr>
	
    <p>
        <?= Html::a('Update Issue', ['update', 'id' => $model->issue_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete Issue', ['delete', 'id' => $model->issue_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('View Volume', ['volume/view', 'id' => $model->volume_id], ['class' => 'btn btn-default']) ?>
    </p>
    
    <?php 
    
   		$issue = $model;
	    $issueImagesPath = Yii::$app->urlManagerCommon->createUrl('images/issues/cover.jpg');
	    if(isset($issue->cover_image) && ($issue->cover_image > 0) && isset($issue->coverimage)){
	    	$modelImage = $issue->coverimage;
	    
	    	if ($modelImage) {
	    		$issueImagesPath = Yii::$app->urlManagerCommon->createUrl('images/issues') . DIRECTORY_SEPARATOR . $model->volume_id . DIRECTORY_SEPARATOR;
	    		$issueImagesPath = $issueImagesPath . $modelImage->path;
	    	}
	    }
    
    ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            // 'issue_id',            
            'title:ntext',
        	// 'volume_id',
        	[
        		'class' => DataColumn::className(), // this line is optional
        		'attribute' => 'volume_id',
        		'label' => 'Volume title',
        		'value' => $model->volume->title,
        		"format" => "HTML",
        	],
        	[
        		'class' => DataColumn::className(), // this line is optional
        		'attribute' => 'abstract',
        		'value' => '<a class="btn btn-info btn-xs" href="'.\Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['search/pdfviewissue', 'id' => $model->issue_id]).'">View in PDF</a>',
        		'label' => 'Content',
        		'format' => 'HTML'
        	],        		
        	[
        		'class' => DataColumn::className(), // this line is optional
        		'attribute' => 'published_on',
        		'value' => (isset($model->published_on)) ? date("M d, Y, g:i:s A", strtotime($model->published_on)) : null,
        		'format' => 'HTML'
        	],
    		[
    			'class' => DataColumn::className(), // this line is optional
    			'attribute' => 'cover_image',
    			'value' => "<div><img class='volume-view-image' src='".$issueImagesPath."'/></div>",
    			'format' => 'HTML'
    		],
        	[
        		'class' => DataColumn::className(), // this line is optional
        		'attribute' => 'is_current',
        		'value' => ($model->is_current == 0) ? "<div class='glyphicon glyphicon-remove'></div>" : "<div class='glyphicon glyphicon-ok'></div>",
        		'format' => 'HTML'
        	],        		
        	[
        		'class' => DataColumn::className(), // this line is optional
        		'attribute' => 'is_special_issue',
        		'value' => ($model->is_special_issue == 0) ? "<div class='glyphicon glyphicon-remove'></div>" : "<div class='glyphicon glyphicon-ok'></div>",
        		'format' => 'HTML'
        	],
        	//'special_title:ntext',
        	[
        		'class' => DataColumn::className(), // this line is optional
        		'attribute' => 'special_title',
        		'value' => (($model->is_special_issue) && (isset($model->special_title)) && (strlen($model->special_title) > 0)) ? $model->special_title : null,
        		'format' => 'HTML'
        	],       
        	//'special_editor',
        	[
        		'class' => DataColumn::className(), // this line is optional
        		'attribute' => 'special_editor',
        		'value' => (($model->is_special_issue) && (isset($model->specialEditor))) ? $model->specialEditor->fullName : null,
        		'format' => 'HTML'
        	],      
            // 'sort_in_volume',
        	[
        		'class' => DataColumn::className(), // this line is optional
        		'attribute' => 'created_on',
        		'value' => (isset($model->created_on)) ? date("M d, Y, g:i:s A", strtotime($model->created_on)) : null,        			
        		'format' => 'HTML'
        	],
        	[
        		'class' => DataColumn::className(), // this line is optional
        		'attribute' => 'updated_on',
        		'value' => (isset($model->updated_on)) ? date("M d, Y, g:i:s A", strtotime($model->updated_on)) : null, 
        		'format' => 'HTML'
        	],
            //'created_on:datetime',
            //'updated_on:datetime',
            // 'is_deleted',
        ],
    ]) ?>
    
    <?php 
    	if(isset($model->sections) && (count($model->sections) > 0)){
    ?>
			<h2><i>Section(s):</i></h2>
	    	<hr>    		    
    <?php
    		foreach ($model->sections as $index => $section){
    ?>
				<p>
					<?= Html::a('View Section', ['section/view/'.$section->section_id], ['class' => 'btn btn-success']) ?>
    				<?= Html::a('Update Section', ['section/update/'.$section->section_id], ['class' => 'btn btn-primary']) ?>
				</p>
    <?php
			    echo DetailView::widget([
			    	'model' => $section,
			    	'attributes' => [			    			
			    		'title:ntext',
			    		//'created_on:datetime',
			    		//'updated_on:datetime',
			    		[
			    			'class' => DataColumn::className(), // this line is optional
			    			'attribute' => 'created_on',
			    			'value' => (isset($section->created_on)) ? date("M d, Y, g:i:s A", strtotime($section->created_on)) : null,
			    			'format' => 'HTML'
			    		],
			    		[
			    			'class' => DataColumn::className(), // this line is optional
			    			'attribute' => 'updated_on',
			    			'value' => (isset($section->updated_on)) ? date("M d, Y, g:i:s A", strtotime($section->updated_on)) : null,
			    			'format' => 'HTML'
			    		],
			    	],
			    ]);    
    		}    
    	} else {
    ?>
    		<hr>
    		<h2>There are no any <i>Sections</i> for this <i>Issue</i> yet!</h2>
	    	<hr>
    <?php		
    	}
    ?>

</div>
