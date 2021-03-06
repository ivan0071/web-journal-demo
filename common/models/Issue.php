<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "issue".
 *
 * @property integer $issue_id
 * @property integer $volume_id
 * @property string $title
 * @property string $published_on
 * @property integer $is_special_issue
 * @property string $special_title
 * @property integer $special_editor
 * @property integer $cover_image
 * @property string $created_on
 * @property string $updated_on
 * @property integer $is_deleted
 * @property integer $is_current
 */
class Issue extends \yii\db\ActiveRecord
{
	public $post_editors = [];
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'issue';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'volume_id'], 'required'],
            [['volume_id', 'is_special_issue', 'special_editor', 'is_deleted', 'is_current'], 'integer'],
            [['title', 'special_title'], 'string'],
            [['published_on', 'created_on', 'updated_on'], 'safe'],
			[['special_editor'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['special_editor' => 'id']],
            [['cover_image'], 'exist', 'skipOnError' => true, 'targetClass' => Image::className(), 'targetAttribute' => ['cover_image' => 'image_id']],
            [['volume_id'], 'exist', 'skipOnError' => true, 'targetClass' => Volume::className(), 'targetAttribute' => ['volume_id' => 'volume_id']],        ]; 
    }    
    
    public function scenarios()
    {
    	$scenarios = parent::scenarios();
    	$scenarios['volume_crud'] = ['title']; //Scenario Attributes that will be validated
    	return $scenarios;
    }
    
    
    public function uploadIssueImage($volume_id)
    {
    	$issueImagesPathDIR = Yii::getAlias('@common') . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'issues' . DIRECTORY_SEPARATOR . $volume_id . DIRECTORY_SEPARATOR;
    	if (!file_exists($issueImagesPathDIR)) {
    		mkdir($issueImagesPathDIR, 0777, true);
    	}
    	
    	if(isset($this->cover_image) && isset($this->cover_image->baseName) && isset($this->cover_image->extension)){
    		$issueImagesPathFILE = $issueImagesPathDIR . $this->cover_image->baseName . '.' . $this->cover_image->extension;
    		if (!file_exists($issueImagesPathFILE)) {
    			$this->cover_image->saveAs($issueImagesPathFILE);
    		}
    	}
    		
   		return true;
    }   
    
    public static function deleteByIDs($deletedIDs = []){
    	
    	try {
    		foreach ($deletedIDs as $deletedID){
    			if (($currentModel = Issue::findOne($deletedID)) !== null) {
    				$currentModel->delete();
    			}
    		}    		
    	} catch (Exception $e) {
    		return false;
    	}
    	
    	return true;
    }
    
    public function getSpecialEditor()
    {
    	return $this->hasOne(User::className(), ['id' => 'special_editor'])->one();
    }
    
    public function getVolume()
    {
    	return $this->hasOne(Volume::className(), ['volume_id' => 'volume_id']);
    }
    
    public function getCoverimage()
    {
    	return $this->hasOne(Image::className(), ['image_id' => 'cover_image']);
    }   
    
    public static function  get_volumes(){
    	$volumes = Volume::find()->all();
    	$volumes = ArrayHelper::map($volumes, 'volume_id', 'title');
    	return $volumes;
    }
    
    public function getSections()
    {
    	return $this->hasMany(\common\models\Section::className(),  ['issue_id' => 'issue_id'])
    				->andOnCondition(['is_deleted' => 0])
    				->orderBy(['sort_in_issue' => SORT_ASC]);
    }
    
    public function getVolumeissuetitle()
    {
    	return $this->volume->title." >> ".$this->title;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'issue_id' => 'Issue ID',
            'volume_id' => 'Volume name',
            'title' => 'Issue title',
            'published_on' => 'Published on',
            'is_special_issue' => 'Is special issue',
            'special_title' => 'Special title',
            'special_editor' => 'Special editor',
        	'post_editors' => 'Special (Guest) editor',
            'cover_image' => 'Cover image',
            'created_on' => 'Created on',
            'updated_on' => 'Updated on',
            'is_deleted' => 'Is deleted',
        	'is_current' => 'Is current',        		
        ];
    }
    
}
