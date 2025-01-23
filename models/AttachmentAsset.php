<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "attachment_asset".
 *
 * @property int $id
 * @property int $asset_id
 * @property string $path
 * @property string $type
 * @property string $created_at
 */
class AttachmentAsset extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'attachment_asset';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['asset_id', 'file', 'type', 'created_at', 'filename'], 'required'],
            [['asset_id'], 'integer'],
            [['created_at','file'], 'safe'],
            [['filename'], 'string', 'max'=>255]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'asset_id' => 'Asset ID',
            'file' => 'File',
            'type' => 'Type',
            'created_at' => 'Created At',
        ];
    }
}
