<?php

namespace frontend\models;

use common\models\Article;
use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class QuestionFormModerate extends QuestionForm
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title', 'body'], 'string'],
            [
                ['title'],
                'validateTitle',
                'skipOnEmpty' => false,
                'skipOnError' => false,
            ],
        ];
    }
}
