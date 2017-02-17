<?php

namespace frontend\models;

use common\models\Article;
use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class QuestionForm extends Article
{
    public $title;
    public $body;
    public $verifyCode;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title', 'body'], 'string'],
            ['verifyCode', 'captcha'],
            [
                ['title'],
                'validateTitle',
                'skipOnEmpty' => false,
                'skipOnError' => false,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'title' => 'Тема',
            'body' => 'Ситуация',
            'verifyCode' => 'Код подтверждения',
        ];
    }

    /**
     * @param $attribute
     * @param $params
     * @return bool
     */
    public function validateTitle($attribute, $params)
    {
        $article_exist = Article::find()->where([
            'LOWER(title)' => mb_strtolower($this->title)
        ])->one();

        if ($article_exist) {
            $this->addError($attribute, 'Статья на данную тему уже подготавливается. Пожалуйста, предложите другую.');
            return false;
        }
    }


    /**
     * @return array|bool
     */
    public function saveQuestion()
    {
        if ($this->validate()) {
            $article = new Article();
            $article->title = $this->title;
            $article->body = $this->body;
            $article->status = Article::STATUS_QUESTION;
            $article->category_id = Article::CATEGORY_QUESTION;

            if ($article->validate() and $article->save()) {
                return true;
            }

            return false;
        }

        return false;
    }
}
