<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "log_login".
 *
 * @property int $id
 * @property int $user_id
 * @property string $login_time
 * @property string $ip_address
 * @property string $user_agent
 * @property int $status
 */
class LogLogin extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'log_login';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'login_time', 'ip_address', 'user_agent', 'status'], 'required'],
            [['user_id', 'status'], 'integer'],
            [['time'], 'safe'],
            [['ip_address'], 'string', 'max' => 20],
            [['user_agent'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'login_time' => 'Login Time',
            'ip_address' => 'Ip Address',
            'user_agent' => 'User Agent',
            'status' => 'Status',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    //add function save log login
    public static function saveLogLogin($user_id, $user_agent, $status)
    {
        $log = new LogLogin();
        $log->user_id = $user_id;
        $log->login_time = date('Y-m-d H:i:s');
        $log->ip_address = Yii::$app->request->userIP;
        $log->user_agent = $user_agent;
        $log->status = $status;
        $log->save();
    }
}
