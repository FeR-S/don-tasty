<?php
/**
 * Created by PhpStorm.
 * User: dsfre
 * Date: 25.01.2016
 * Time: 12:21
 */

namespace console\controllers;

use app\models\RedisMethods;
use app\models\RedisUser;
use common\models\Article;
use yii\console\Controller;
use common\models\User;
use yii\base\Model;
use yii\console\Exception;
use yii\helpers\Console;

class ArticlesController extends Controller
{
    public function actionIndex()
    {
        echo 'yii articles/create' . PHP_EOL;
    }

    public function actionCreate()
    {
        $model = new Article();
        $this->readValue($model, 'category_id');
        $model->title = 'Что такое Lorem Ipsum?';
        $model->body = 'Lorem Ipsum - это текст-"рыба", часто используемый в печати и вэб-дизайне. Lorem Ipsum является стандартной "рыбой" для текстов на латинице с начала XVI века. В то время некий безымянный печатник создал большую коллекцию размеров и форм шрифтов, используя Lorem Ipsum для распечатки образцов. Lorem Ipsum не только успешно пережил без заметных изменений пять веков, но и перешагнул в электронный дизайн. Его популяризации в новое время послужили публикация листов Letraset с образцами Lorem Ipsum в 60-х годах и, в более недавнее время, программы электронной вёрстки типа Aldus PageMaker, в шаблонах которых используется Lorem Ipsum.';
        $model->user_id = 1;
        $model->source = 'H. Rackham, 1914 год.';
        $model->status = 2;
        $model->validate();
        $this->log($model->save());
    }

//    public function actionRemove()
//    {
//        $username = $this->prompt('Username:', ['required' => true]);
//        $model = $this->findModel($username);
//        $this->log($model->delete());
//    }

//    public function actionActivate()
//    {
//        $username = $this->prompt('Username:', ['required' => true]);
//        $model = $this->findModel($username);
//        $model->status = User::STATUS_ACTIVE;
//        $model->removeEmailConfirmToken();
//        $this->log($model->save());
//    }

//    public function actionChangePassword()
//    {
//        $username = $this->prompt('Username:', ['required' => true]);
//        $model = $this->findModel($username);
//        $model->setPassword($this->prompt('New password:', [
//            'required' => true,
//            'pattern' => '#^.{6,255}$#i',
//            'error' => 'More than 6 symbols',
//        ]));
//        $this->log($model->save());
//    }

    /**
     * @param string $username
     * @throws \yii\console\Exception
     * @return User the loaded model
     */
    private function findModel($username)
    {
        if (!$model = User::findOne(['username' => $username])) {
            throw new Exception('User not found');
        }
        return $model;
    }

    /**
     * @param Model $model
     * @param string $attribute
     */
    private function readValue($model, $attribute)
    {
        $model->$attribute = $this->prompt(mb_convert_case($attribute, MB_CASE_TITLE, 'utf-8') . ':', [
            'validator' => function ($input, &$error) use ($model, $attribute) {
                $model->$attribute = $input;
                if ($model->validate([$attribute])) {
                    return true;
                } else {
                    $error = implode(',', $model->getErrors($attribute));
                    return false;
                }
            },
        ]);
    }

    /**
     * @param bool $success
     */
    private function log($success)
    {
        if ($success) {
            $this->stdout('Success!', Console::FG_GREEN, Console::BOLD);
        } else {
            $this->stderr('Error!', Console::FG_RED, Console::BOLD);
        }
        echo PHP_EOL;
    }
}


