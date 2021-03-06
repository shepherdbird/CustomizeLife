<?php

namespace app\modules\v1\models;

use Yii;

/**
 * This is the model class for table "appofkind".
 *
 * @property integer $id
 * @property integer $appid
 * @property integer $kind
 * @property integer $status
 *
 * @property App $app
 * @property Tag $kind0
 */
class Appofkind extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'appofkind';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['appid', 'kindid'], 'required'],
            [['appid', 'kindid', 'status'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'appid' => 'Appid',
            'kindid' => 'Kind',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApp()
    {
        return $this->hasOne(App::className(), ['id' => 'appid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKind0()
    {
        return $this->hasOne(Tag::className(), ['id' => 'kindid']);
    }
}
