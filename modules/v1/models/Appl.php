<?php

namespace app\modules\v1\models;

use Yii;

/**
 * This is the model class for table "app".
 *
 * @property integer $id
 * @property string $name
 * @property string $version
 * @property string $profile
 * @property string $android_url
 * @property string $ios_url
 * @property double $stars
 * @property integer $downloadcount
 * @property integer $commentscount
 * @property string $introduction
 * @property integer $updated_at
 * @property string $size
 * @property string $icon
 * @property string $updated_log
 * @property string $package
 *
 * @property Appcomments[] $appcomments
 * @property Appofkind[] $appofkinds
 * @property Apptopicture[] $apptopictures
 * @property CollectPerson[] $collectPeople
 * @property Usertoapp[] $usertoapps
 */
class Appl extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'app';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [[ 'downloadcount', 'commentscount', 'updated_at'], 'integer'],
        	[['stars'],'double'],
            [['name', 'version', 'profile', 'android_url', 'ios_url', 'introduction', 'size', 'icon', 'updated_log','package'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'version' => 'Version',
            'profile' => 'Profile',
            'android_url' => 'Android Url',
            'ios_url' => 'Ios Url',
            'stars' => 'Stars',
            'downloadcount' => 'Downloadcount',
            'commentscount' => 'Commentscount',
            'introduction' => 'Introduction',
            'updated_at' => 'Updated At',
            'size' => 'Size',
            'icon' => 'Icon',
            'updated_log' => 'Updated Log',
        	'package'=>'Package',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAppcomments()
    {
        return $this->hasMany(Appcomments::className(), ['appid' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAppofkinds()
    {
        return $this->hasMany(Appofkind::className(), ['appid' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApptopictures()
    {
        return $this->hasMany(Apptopicture::className(), ['appid' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollectPeople()
    {
        return $this->hasMany(CollectPerson::className(), ['app' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsertoapps()
    {
        return $this->hasMany(Usertoapp::className(), ['appid' => 'id']);
    }
}
