<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\helpers\Security;

use yii\web\IdentityInterface;



class User extends \yii\db\ActiveRecord  implements IdentityInterface
{

    public $retype;

    public static function tableName()
    {
        return 'user';
    }

    public function rules()
    {
        return [
            [['username'], 'required'],
            [['username'], 'unique'],
            [['name'],'string'],
            [['email'], 'email'],
            [['password'], 'string'],
           // ['retype', 'compare', 'compareAttribute'=>'password', 'skipOnEmpty' => false, 'message'=>"Passwords don't match"],
          
        ];
    }

    public function attributeLabels()
    {
        return [
            'userid' => 'Userid',
            'id' => 'ID User',
            'username' => 'Username',
            'password' => 'Password',
            'name' => 'Name',
            'email'=> 'Email',
        ];
    }

    public function getAuth_assignment()
    {
        return $this->hasOne(AuthAssignment::className(), ['user_id' => 'id']);
    }



    public static function findByPasswordResetToken($token)
    {
        $expire = \Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        if ($timestamp + $expire < time()) {
            // token expired
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token
        ]);
    }

    /**
     * @inheritdoc
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        // Implementasikan jika Anda menggunakan token akses
    }

    public function getId()
    {
        return $this->id; // ID pengguna
    }

    public function getAuthKey()
    {
        return $this->auth_key; // Kunci autentikasi
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey; // Validasi kunci autentikasi
    }

    public function validatePassword($password)
    {
        return Yii::$app->getSecurity()->validatePassword($password, $this->password); // Ganti 'password_hash' menjadi 'password'
    }

}
