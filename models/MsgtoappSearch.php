<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\v1\models\Msgtoapp;

/**
 * MsgtoappSearch represents the model behind the search form about `app\modules\v1\models\Msgtoapp`.
 */
class MsgtoappSearch extends Msgtoapp
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'msgid', 'appid'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }
    public $value;

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Msgtoapp::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        
        if($params!=false &&!empty($params['MsgtoappSearch'])){
        	//$b=$a;
        	//=app::find()->where("name= :name",[':name'=>'QQ'])->one();
        	//if()
        	foreach ($params['MsgtoappSearch'] as $name => $value1) {
        		if ($name==='appid' && $value1!=null){
        			$appinfo=app::findOne(['name' => $params['MsgtoappSearch']['appid']]);
        			 
        			$this->value=$appinfo['id'];
        			if($appinfo ==null){
        				$this->value=0;
        			}
        
        		}
        	}
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'msgid' => $this->msgid,
            'appid' => $this->value,
        ]);

        return $dataProvider;
    }
}
