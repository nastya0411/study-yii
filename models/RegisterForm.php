<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class RegisterForm extends Model
{
    public string $login = "";
    public string $password = "";
    public string $full_name = "";
    public string $phone = "";
    public string $email = "";



    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [["login", "password", "full_name", "phone", "email"], 'required'],
            ["password", "string", 'min' => 6],
            [['login', 'password', 'full_name', 'phone', 'email'], 'string', 'max' => 255],
            ['full_name', 'match', 'pattern' => "/^[а-яА-ЯёЁ\s]+$/ui", 'message' => 'Только символы кириллицы и пробелы'],
            // email has to be a valid email address
            ['email', 'email'],
            ['phone', 'match', 'pattern' =>  '/^\+7\([\d]{3}\)-[\d]{3}-[\d]{2}-[\d]{2}$/', 'message' => 'Телефон в формате 
            +7(ХХХ)-ХХХ-ХХ-ХХ'],
            ['login', 'unique', 'targetClass' => User::class],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            "login" => "Логин",
            "password"  => "Пароль",
            "full_name"  => "ФИО",
            "phone"  => "Телефон",
            "email"  => "Электронная почта",
        ];
    }

    public function userRegister(): false|object
    {
        if ($this->validate()) {


            $user = new User();
            //$user -> login = $this -> login;
            //$user -> attributes = $this -> attributes;
            $user->load($this->attributes, '');
            $user->role_id = Role::getRoleId('user');
            $user->password = Yii::$app->security->generatePasswordHash($user->password);
            $user->auth_key = Yii::$app->security->generateRandomString();

            if (! $user->save()) {
                Yii::debug($user->errors);
                return false;
            }
        }

        //Yii::debug($user -> attributes);

        return $user ?? false;
    }
}
