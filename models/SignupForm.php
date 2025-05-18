<?php
namespace app\models;

use yii\base\Model;
use app\models\User;

class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $fio;
    public $phone;

    public function rules()
    {
        return [
            [['username', 'email', 'password', 'fio', 'phone'], 'required'],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['username', 'unique', 'targetClass' => User::class, 'message' => 'Этот логин уже занят.'],
            ['email', 'unique', 'targetClass' => User::class, 'message' => 'Этот email уже зарегистрирован.'],
            ['password', 'string', 'min' => 6],
            ['phone', 'string', 'max' => 20],
            ['fio', 'string', 'max' => 255],
        ];
    }

    public function signup()
{
    if (!$this->validate()) {
        return null;
    }

    $user = new User();
    $user->username = $this->username;
    $user->email = $this->email;
    $user->fio = $this->fio;
    $user->phone = $this->phone;
    $user->setPassword($this->password); // здесь хешируется и сохраняется в $user->password

    return $user->save() ? $user : null;
}

}
