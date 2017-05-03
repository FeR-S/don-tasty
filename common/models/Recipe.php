<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "recipe".
 *
 * @property integer $id
 * @property string $title
 * @property integer $user_id
 * @property integer $category_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $status
 * @property string $slug
 * @property string $description
 */
class Recipe extends \yii\db\ActiveRecord
{

    public $image;

    const STATUS_MODERATION = 1;
    const STATUS_PUBLIC = 2;
    const STATUS_DELETED = 0;

    const SCENARIO_PUBLIC = 'scenario_public';
    const SCENARIO_MODERATE = 'scenario_moderate';

    const DEFAULT_IMG_EXT = '.jpg';
    const DEFAULT_IMG_PATH = 'uploads/recipes_images/default.jpg';


    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
            ],
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
     * @return array
     */
    public static function getRussianMonths()
    {
        return [
            1 => 'Января',
            2 => 'Февраля',
            3 => 'Марта',
            4 => 'Апреля',
            5 => 'Мая',
            6 => 'Июня',
            7 => 'Июля',
            8 => 'Августа',
            9 => 'Сентября',
            10 => 'Октября',
            11 => 'Ноября',
            12 => 'Декабря'
        ];
    }

    /**
     * @return bool|string
     */
    public static function getImagePath()
    {
        return Yii::getAlias('@frontend/web/uploads/recipes_images/');
    }


    /**
     * @return array
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_MODERATION => 'На проверке',
            self::STATUS_PUBLIC => 'Активный',
            self::STATUS_DELETED => 'Удален',
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
        return 'recipe';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            ['title', 'unique', 'message' => 'Такой заголовок уже существует. Заголовок статьи должен быть уникальным!'],

            [['title', 'slug', 'description'], 'string', 'max' => 255],

            [['image'], 'file', 'skipOnEmpty' => true, 'extensions' => 'jpg'],

            [['status'], 'in', 'range' => array_keys(self::getStatuses()), 'on' => self::SCENARIO_PUBLIC],

            [['user_id', 'category_id', 'created_at', 'updated_at', 'status'], 'integer'],
        ];
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return '/recipes/' . $this->category->slug . '/' . $this->slug;
    }

    /**
     * @return string
     */
    public function getImageUrl()
    {
        $image_path = self::getImagePath() . $this->id . self::DEFAULT_IMG_EXT;
        if (file_exists($image_path)) {
            return Yii::getAlias('@public_site') . '/uploads/recipes_images/' . $this->id . self::DEFAULT_IMG_EXT;
        } else {
            return Yii::getAlias('@public_site') . self::DEFAULT_IMG_PATH;
        }
    }

    /**
     * @return string
     */
    public function getRedisKey()
    {
        $reflect = new \ReflectionClass($this::className());
        return 'model:' . $reflect->getShortName() . ':id:' . $this->id;
    }

    /**
     * @return bool
     */
    public function upload()
    {
        if ($this->validate() and $this->image) {
            return $this->image->saveAs(Yii::getAlias('@frontend/web/uploads/recipes_images/') . $this->id . '.' . $this->image->extension);
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
            'title' => 'Title',
            'user_id' => 'User ID',
            'category_id' => 'Category ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status' => 'Status',
            'slug' => 'Slug',
            'description' => 'Description',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }
}
