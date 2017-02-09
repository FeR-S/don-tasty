<?php

namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\helpers\Url;

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
class Article extends ActiveRecord
{
    public $image;

    const STATUS_MODERATION = 1;
    const STATUS_PUBLIC = 2;
    const STATUS_DELETED = 0;

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'slug' => [
                'class' => 'Zelenin\yii\behaviors\Slug',
                'slugAttribute' => 'slug',
                'attribute' => 'title',
                // optional params
                'ensureUnique' => true,
                'replacement' => '-',
                'lowercase' => true,
                'immutable' => false,
                // If intl extension is enabled, see http://userguide.icu-project.org/transforms/general.
                'transliterateOptions' => 'Russian-Latin/BGN; Any-Latin; Latin-ASCII; NFD; [:Nonspacing Mark:] Remove; NFC;'
            ]
        ];
    }

    /**
     * @return bool|string
     */
    public static function getImagePath()
    {
        return Yii::getAlias('@frontend/web/uploads/article_images/');
    }

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
    public function getStatusName($status_id)
    {
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
            [['title', 'created_at', 'status', 'category_id', 'body', 'announcement'], 'required'],
            [['user_id', 'category_id', 'rating', 'views', 'status'], 'integer'],
            [['created_at', 'updated_at', 'slug'], 'safe'],
            [['title', 'slug'], 'string', 'max' => 255],
            [['body'], 'string', 'max' => 2048],
            [['announcement'], 'string', 'max' => 1024],
            [['source'], 'string', 'max' => 512],
            [['status'], 'in', 'range' => array_keys(self::getStatuses())],
            [['image'], 'file', 'skipOnEmpty' => true, 'extensions' => 'jpg'],
            ['title', 'unique', 'message' => 'Такой заголовок уже существует. Заголовок статьи должен быть уникальным!'],
        ];
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return '/articles/' . $this->category->slug . '/' . $this->slug;
    }

    /**
     * @return string
     */
    public function getImageUrl()
    {
        $image_path = self::getImagePath() . $this->id . '.jpg';
        if (file_exists($image_path)) {
            return Yii::getAlias('@public_site') . '/uploads/article_images/' . $this->id . '.jpg';
        } else {
            return Yii::getAlias('@public_site') . '/uploads/article_images/default.png';
        }
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
//        var_dump($this->slug);die;
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
//        var_dump($this->slug);die;
    }

    /**
     * @return bool
     */
    public function upload()
    {
        if ($this->validate() and $this->image) {
            return $this->image->saveAs(Yii::getAlias('@frontend/web/uploads/article_images/') . $this->id . '.' . $this->image->extension);
        } else {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Заголовок',
            'body' => 'Текст',
            'user_id' => 'User ID',
            'category_id' => 'Category ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'source' => 'Источник информации для статьи',
            'rating' => 'Rating',
            'views' => 'Views',
            'status' => 'Status',
            'image' => 'Изображение',
            'announcement' => 'Краткий ответ',
        ];
    }

    /**
     * @return ActiveDataProvider
     */
    public static function getArticles()
    {
        return $dataProvider = new ActiveDataProvider([
            'query' => self::find()->where(['status' => Article::STATUS_PUBLIC]),
        ]);
    }


    /**
     *
     */
    public function afterDelete()
    {
        parent::afterDelete(); // TODO: Change the autogenerated stub
        $fileName = self::getImagePath() . $this->id . '.jpg';
        if (file_exists($fileName)) {
            unlink($fileName);
        }
    }

    /**
     * @return bool|string
     */
    public function removeImage()
    {
        $fileName = self::getImagePath() . $this->id . '.jpg';
        if (file_exists($fileName) and unlink($fileName)) {
            return $this->id . '.jpg';
        }
        return false;
    }

    /**
     * @param $id
     * @return bool|string
     */
    public static function removeImageStatic($id)
    {
        $fileName = self::getImagePath() . $id . '.jpg';
        if (file_exists($fileName) and unlink($fileName)) {
            return $id . '.jpg';
        }
        return false;
    }


    /**
     * @param $category_id
     * @return int|string
     */
    public static function getArticlesCount($category_id)
    {
        return Article::find()->where(['category_id' => $category_id])->count();
    }

    /**
     * @return bool
     */
    public function beforeValidate()
    {
        if ($this->isNewRecord) {
            $this->created_at = date("Y-m-d H:i:s");
        } else {
            $this->updated_at = date("Y-m-d H:i:s");
        }
        return parent::beforeValidate(); // TODO: Change the autogenerated stub
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
