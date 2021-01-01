<?php
namespace app\models;
use yii\base\Model;

class SignUpForm extends Model
{
    //Модель для валидации

    public $username;
    public $password;

    public function rules(){
        return [
              [['username', 'password'], 'required', 'message' => 'Заполните поля!'],
              ['username', 'unique', 'targetClass' => User::className(), 'message' => 'Данное имя пользователя уже существует!']
        ];

    }
    public function attributeLabels()
    {
        return [
            'username' => 'Логин',
            'password' => 'Пароль'
        ];
    }
}