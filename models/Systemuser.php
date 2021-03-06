<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "systemuser".
 *
 * @property integer $id
 * @property string $name
 * @property string $pwd
 */
class Systemuser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'systemuser';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'pwd'], 'required'],
            [['name'], 'string', 'max' => 20],
            [['pwd'], 'string', 'max' => 255],
            [['name'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '账号',
            'pwd' => '密码',
        ];
    }
}
