<?php

namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "articles".
 *
 * @property integer $id
 * @property string $title
 * @property string $body
 * @property integer $user_id
 * @property integer $category_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $source
 * @property integer $rating
 * @property integer $views
 * @property integer $status
 */
class Article extends \yii\db\ActiveRecord
{


    const STATUS_MODERATION = 1;
    const STATUS_PUBLIC = 2;
    const STATUS_DELETED = 0;

    /**
     * @return array
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_MODERATION => 'На проверке',
            self::STATUS_PUBLIC => 'Активная',
            self::STATUS_DELETED => 'Удалена',
        ];
    }

    /**
     * @param $status_id
     * @return mixed
     */
    public function getStatusName($status_id) {
        return self::getStatuses()[$status_id];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'articles';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'created_at', 'status', 'category_id', 'body'], 'required'],
            [['user_id', 'category_id', 'rating', 'views', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['body'], 'string', 'max' => 1024],
            [['source'], 'string', 'max' => 512],
            [['status'], 'in', 'range' => array_keys(self::getStatuses())]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'body' => 'Body',
            'user_id' => 'User ID',
            'category_id' => 'Category ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'source' => 'Source',
            'rating' => 'Rating',
            'views' => 'Views',
            'status' => 'Status',
        ];
    }

    /**
     * @return array
     */
    public static function getCategories(){
        $categories = Category::find()->asArray()->all();
        return ArrayHelper::map($categories, 'id', 'title');
    }

    public static function getArticles() {
        return $dataProvider = new ActiveDataProvider([
            'query' => self::find(),
        ]);
    }

    public function beforeValidate()
    {
        if($this->isNewRecord) {
            $this->created_at = date("Y-m-d H:i:s");
        } else {
            $this->updated_at = date("Y-m-d H:i:s");
        }
        return parent::beforeValidate(); // TODO: Change the autogenerated stub
    }
}
