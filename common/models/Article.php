<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\base\Object;

/** 
 * This is the model class for table "article". 
 * 
 * @property integer $article_id
 * @property integer $section_id
 * @property string $title
 * @property string $abstract
 * @property string $content
 * @property string $pdf_content
 * @property string $page_from
 * @property string $page_to
 * @property integer $sort_in_section
 * @property integer $is_archived
 * @property integer $file_id
 * @property string $created_on
 * @property string $updated_on
 * @property integer $is_deleted
 * 
 * @property ArticleFile $file
 * @property Section $section
 * @property ArticleAuthor[] $articleAuthors
 * @property User[] $authors
 * @property ArticleKeyword[] $articleKeywords
 * @property Keyword[] $keywords
 * @property ArticleReviewer[] $articleReviewers
 * @property User[] $reviewers
 */ 
class Article extends \yii\db\ActiveRecord
{
	public $post_keywords = [];
	public $post_authors = [];
	public $post_reviewers = [];	
	public $file_attach;
		
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['section_id', 'title', 'abstract', 'content'/*, 'file_attach'*/], 'required'],
            [['section_id', 'sort_in_section', 'is_archived', 'is_deleted'], 'integer'],
            [['title', 'abstract', 'content', 'pdf_content'], 'string'],       		
            [['created_on', 'updated_on'], 'safe'],
            [['page_from', 'page_to'], 'string', 'max' => 6],
        	[['file_id'], 'exist', 'skipOnError' => true, 'targetClass' => ArticleFile::className(), 'targetAttribute' => ['file_id' => 'file_id']],
        	[['file_attach'], 'file', 'skipOnEmpty' => true, 'extensions' => 'doc, docx'],
        	[['section_id'], 'exist', 'skipOnError' => true, 'targetClass' => Section::className(), 'targetAttribute' => ['section_id' => 'section_id']],
        	[['post_reviewers', 'post_authors', 'post_keywords'], 'each', 'rule' => ['integer']],        		
        ];
    }
    
    public function scenarios()
    {
    	$scenarios = parent::scenarios();
    	$scenarios['section_crud'] = ['title']; //Scenario Attributes that will be validated
    	return $scenarios;
    }
    
    public static function deleteByIDs($deletedIDs = []){
    
    	try {
    		foreach ($deletedIDs as $deletedID){
    			if (($currentModel = Article::findOne($deletedID)) !== null) {
    				$currentModel->delete();
    			}
    		}
    	} catch (Exception $e) {
    		return false;
    	}
    
    	return true;
    }    

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSection()
    {
        return $this->hasOne(Section::className(), ['section_id' => 'section_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFile()
    {
    	return $this->hasOne(ArticleFile::className(), ['file_id' => 'file_id']);
    }
    
    public static function  get_sections(){
    	$sections = Section::find()->all();
    	$sections = ArrayHelper::map($sections, 'section_id', 'volumeissuesectiontitle');
    	return $sections;
    }    

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticleAuthors()
    {
    	return $this->hasMany(ArticleAuthor::className(), ['article_id' => 'article_id'])
    				->orderBy(['sort_order' => SORT_ASC]);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthors()
    {
        return $this->hasMany(User::className(), ['id' => 'author_id'])->viaTable('article_author', ['article_id' => 'article_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticleKeywords()
    {
        return $this->hasMany(ArticleKeyword::className(), ['article_id' => 'article_id'])
        			->orderBy(['sort_order' => SORT_ASC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKeywords()
    {
        return $this->hasMany(Keyword::className(), ['keyword_id' => 'keyword_id'])->viaTable('article_keyword', ['article_id' => 'article_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticleReviewers()
    {
        return $this->hasMany(ArticleReviewer::className(), ['article_id' => 'article_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReviewers()
    {
        return $this->hasMany(User::className(), ['id' => 'reviewer_id'])->viaTable('article_reviewer', ['article_id' => 'article_id']);
    }
    
    public function uploadFile($file_attach)
    {
    	try {
    		if(!isset($file_attach)) {
    			Yii::error("Article->uploadFile(1): input function parameters not valid", "custom_errors_articles");
    			return null;
    		}    		

    		$articleFile = new ArticleFile();
    		$articleFile->user_id = Yii::$app->user->identity->attributes["id"];
    		$articleFile->file_original_name = $file_attach->baseName.'.'.$file_attach->extension;
    		$articleFile->file_name = md5(uniqid(rand(), true)).'.'.$file_attach->extension;
    		$articleFile->file_mime_type = $file_attach->type;
    		$articleFile->created_on = date("Y-m-d H:i:s");
  		
    		if(!$articleFile->save()){
    			Yii::error("Article->uploadFile(2): ".json_encode($articleFile->getErrors()), "custom_errors_articles");
    			return null;
    		}
			$file_attach->saveAs('@web/uploads/'.$articleFile->file_name);
    		return $articleFile->file_id;
    		
    	} catch (Exception $e) {
    		Yii::error("Article->uploadFile(3): ".json_encode($e), "custom_errors_articles");
    		return null;
    	}    	
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
    	return [
    			'article_id' => 'Article ID',
    			'section_id' => 'Section name',
    			'title' => 'Article title',
    			'abstract' => 'Abstract',
    			'content' => 'Content',
    			'pdf_content' => 'Pdf file',
    			'page_from' => 'Page from',
    			'page_to' => 'Page to',
    			'sort_in_section' => 'Sort in section',
    			'is_archived' => 'Is archived',
    			'file_id' => 'File ID',
    			'file_attach' => 'File',    			
    			'created_on' => 'Created on',
            	'updated_on' => 'Updated on',
            	'is_deleted' => 'Is deleted',    			
    			'post_keywords' => 'Keywords',
    			'post_authors' => 'Authors',
    			'post_reviewers' => 'Reviewers',
    	];
    }
}