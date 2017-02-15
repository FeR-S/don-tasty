<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "users".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $role
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $first_name
 * @property string $last_name
 * @property integer $age
 * @property string $work_experience
 * @property string $city
 */

class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    const STATUS_MODERATION = 5;

    const ROLE_ADMIN = 1;
    const ROLE_LAWYER = 2;

    const SPECIALIZATION_GP = 1;
    const SPECIALIZATION_UP = 2;

    /**
     * @return array
     */
    public static function getSpecializations() {
        return [
            self::SPECIALIZATION_GP => 'Гражданско-правовая',
            self::SPECIALIZATION_UP => 'Уголовно-правовая'
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['username', 'auth_key', 'password_hash', 'email'], 'required'],
            [['role', 'status', 'age', 'specialization'], 'integer'],
            [['username', 'password_hash', 'password_reset_token', 'email', 'first_name', 'last_name', 'work_experience', 'city'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['email'], 'unique'],
            [['created_at', 'updated_at'], 'safe'],
            [['password_reset_token'], 'unique'],
            [['username'], 'unique'],
            ['status', 'in', 'range' => array_keys(self::getStatuses())],
            ['role', 'in', 'range' => array_keys(self::getRoles())],
            ['specialization', 'in', 'range' => array_keys(self::getSpecializations())],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Логин',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'role' => 'Роль',
            'status' => 'Статус',
            'created_at' => 'Дата создания профиля',
            'updated_at' => 'Дата редактирования профиля',
            'first_name' => 'Имя',
            'last_name' => 'Фамилия',
            'age' => 'Возраст',
            'work_experience' => 'Опыт работы',
            'city' => 'Город',
        ];
    }

    /**
     * @inheritdoc
     */
    public static function getRoles()
    {
        return [
            self::ROLE_ADMIN => 'Админ',
            self::ROLE_LAWYER => 'Юрист'
        ];
    }

    /**
     * @param $role_id
     * @return bool
     */
    public static function isAdmin($role_id)
    {
        return $role_id == self::ROLE_ADMIN;
    }


    /**
     * @param $role_id
     * @return bool
     */
    public static function isLawyer($role_id)
    {
        return $role_id == self::ROLE_LAWYER;
    }

    /**
     * @inheritdoc
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @inheritdoc
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_ACTIVE => 'Активный',
            self::STATUS_MODERATION => 'На проверке',
            self::STATUS_DELETED => 'Удален',
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * @return bool
     */
    public function beforeValidate()
    {
        if ($this->isNewRecord) {
            $this->created_at = date("Y-m-d H:i:s");
            $this->status = self::STATUS_MODERATION;
            $this->role = self::ROLE_LAWYER;
        } else {
            $this->updated_at = date("Y-m-d H:i:s");
        }
        return parent::beforeValidate(); // TODO: Change the autogenerated stub
    }
}
