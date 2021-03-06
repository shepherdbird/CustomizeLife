<?php

namespace app\modules\v1\models;

use Yii;

/**
 * This is the model class for table "notify".
 *
 * @property integer $id
 * @property integer $from
 * @property integer $to
 * @property string $kind
 * @property integer $msg_id
 * @property integer $created_at
 *
 * @property User $from0
 * @property User $to0
 */
class Notify extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notify';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['from', 'to', 'created_at','msg_id'], 'integer'],
            [['kind'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'from' => 'From',
            'to' => 'To',
            'kind' => 'Kind',
            'created_at' => 'Created At',
        	'msg_id'=> 'Msg Id'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFrom0()
    {
        return $this->hasOne(User::className(), ['id' => 'from']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTo0()
    {
        return $this->hasOne(User::className(), ['id' => 'to']);
    }
}
