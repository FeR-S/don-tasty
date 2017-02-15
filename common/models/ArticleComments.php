<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "article_comments".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $article_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $body
 * @property integer $status
 */
class ArticleComments extends \yii\db\ActiveRecord
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
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article_comments';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'article_id', 'created_at', 'status'], 'required'],
            [['user_id', 'article_id', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['body'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'article_id' => 'Article ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'body' => 'Body',
            'status' => 'Status',
        ];
    }
}
