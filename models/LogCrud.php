<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "log_crud".
 *
 * @property int $id
 * @property string $table
 * @property string $action
 * @property string|null $detail
 * @property int $user_id
 * @property string $ip_address
 * @property string $created_at
 */
class LogCrud extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'log_crud';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['table', 'action', 'user_id', 'ip_address'], 'required'],
            [['detail'], 'string'],
            [['user_id'], 'integer'],
            [['created_at'], 'safe'],
            [['table', 'action', 'ip_address'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'table' => 'Table',
            'action' => 'Action',
            'detail' => 'Detail',
            'user_id' => 'User ID',
            'ip_address' => 'Ip Address',
            'created_at' => 'Created At',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public static function saveLog($table, $action, $detail)
    {
        $model = new LogCrud();
        $model->table = $table;
        $model->action = $action;
        $model->detail = $detail;
        $model->user_id = Yii::$app->user->identity->id;
        $model->ip_address = Yii::$app->request->userIP;
        $model->created_at = date('Y-m-d H:i:s');
        $model->save();
    }
}
