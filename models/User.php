<?php
namespace app\models;

use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use Yii;

class User extends ActiveRecord implements IdentityInterface
{
    public static function tableName()
    {
        return 'users'; // имя вашей таблицы
    }

    public function rules()
    {
        return [
            [['username', 'email', 'password', 'fio', 'phone'], 'required'],
            [['username', 'email'], 'unique'],
            ['email', 'email'],
            [['fio', 'phone'], 'string'],
        ];
    }

    /**
     * Устанавливаем пароль (хешируем и сохраняем в поле password)
     */
    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Проверяем пароль
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    // Реализация IdentityInterface

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null; // если не используете токены
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return null; // если нет поля auth_key
    }

    public function validateAuthKey($authKey)
    {
        return false; // если нет поля auth_key
    }

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * Проверяет, является ли пользователь администратором
     * @return bool
     */
    public function getIsAdmin()
{
    return $this->role == 1;
}
}
