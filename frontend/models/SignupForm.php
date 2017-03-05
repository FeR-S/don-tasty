<?php
namespace frontend\models;

use yii\base\Model;
use common\models\User;
use Yii;


/**
 * Class SignupForm
 * @package frontend\models
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;

    public $image;

    public $first_name;
    public $last_name;
    public $age;
    public $work_experience;
    public $city;
    public $specialization;
    public $status;
    public $role;

    public $verifyCode;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email', 'password', 'first_name', 'last_name', 'age', 'work_experience', 'city', 'specialization'], 'required'],

            [['age', 'specialization'], 'integer'],

            [['first_name', 'last_name', 'work_experience', 'city'], 'string'],

            ['username', 'trim'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'string', 'min' => 6],

            [['role', 'status'], 'safe'],

            [['image'], 'file', 'skipOnEmpty' => true, 'extensions' => 'jpg'],

//            ['verifyCode', 'captcha'],
        ];
    }

    /**
     * @return bool|string
     */
    public static function getImagePath()
    {
        return Yii::getAlias('@frontend/web/uploads/users/');
    }


    /**
     * @param $user_id
     * @return bool|string
     */
    public function removeImage($user_id)
    {
        $fileName = self::getImagePath() . $user_id . '.jpg';
        if (file_exists($fileName) and unlink($fileName)) {
            return $user_id . '.jpg';
        }
        return false;
    }


    /**
     * @param $user_id
     * @return bool
     */
    public function upload($user_id)
    {
        if ($this->validate() and $this->image) {
            return $this->image->saveAs(Yii::getAlias('@frontend/web/uploads/users/') . $user_id . '.' . $this->image->extension);
        } else {
            return false;
        }
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
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->setAttributes($this->getAttributes());
        $user->setPassword($this->password);
        $user->generateAuthKey();

        if ($user->validate() and $user->save()) {
            return $user;
        } else {
            return null;
        }
    }
}
