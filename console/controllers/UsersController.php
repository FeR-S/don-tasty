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
use yii\console\Controller;
use common\models\User;
use yii\base\Model;
use yii\console\Exception;
use yii\helpers\Console;

class UsersController extends Controller
{
    public function actionIndex()
    {
        echo 'yii users/create-admin' . PHP_EOL;
        echo 'yii users/remove' . PHP_EOL;
        echo 'yii users/activate' . PHP_EOL;
        echo 'yii users/change-password' . PHP_EOL;
    }

    public function actionCreateAdmin()
    {
        $model = new User();
        $this->readValue($model, 'username');
//        $this->readValue($model, 'email');
        $model->setPassword($this->prompt('Password:', [
            'required' => true,
            'pattern' => '#^.{6,255}$#i',
            'error' => 'More than 6 symbols',
        ]));
        $model->role = User::ROLE_ADMIN;
        $model->email = 'qweqwe@mail.com';
        $model->status = User::STATUS_ACTIVE;
        $model->generateAuthKey();
        $this->log($model->save());

//        $model->validate();
//        var_dump($model->getErrors());

//        $redis = \Yii::$app->redis;
//        $redis->hmset(
//            'user:' . $model->getId() . ':info',
//            'id', $model->getId(),
//            'username', $model->username,
//            'email', $model->email
//        );

//		$this->log($redisUser->save());
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


