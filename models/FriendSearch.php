<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\v1\models\Friend;
use app\modules\v1\models\User;

/**
 * FriendSearch represents the model behind the search form about `app\modules\v1\models\Friend`.
 */
class FriendSearch extends Friend
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'myid', 'friendid','isfriend'], 'integer'],
            [['friendnickname','mynickname'], 'string'],
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
    public $userinc;
    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        

        $this->load($params);
        
        if(isset($params['FriendSearch']['mynickname'])&&!empty($params['FriendSearch']['mynickname'])){
        	$query = Friend::find()->join('INNER JOIN','user','friends.myid=user.id');
        	$query->andFilterWhere(['like', 'user.nickname', $params['FriendSearch']['mynickname']]);
        }
        else{
        	$query = Friend::find()->join('INNER JOIN','user','friends.friendid=user.id');
        	
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        
        $query->andFilterWhere([
        		'friends.isfriend' => 1,
        		]);
        
        
        if($params!=false &&!empty($params['FriendSearch'])){
        	//$b=$a;
        	//=app::find()->where("name= :name",[':name'=>'QQ'])->one();
        	//if()
        	foreach ($params['FriendSearch'] as $name => $value1) {
        		if ($name==='myid' && $value1!=null){
        			$appinfo=User::findOne(['phone' => $params['FriendSearch']['myid']]);
        			 
        			$this->value=$appinfo['id'];
        			if($appinfo ==null){
        				$this->value=0;
        			}
        
        		}
        		if ($name==='friendid'&&$value1!=null){
        			$appinfo=User::findOne(['phone' => $params['FriendSearch']['friendid']]);
        			$this->userinc=$appinfo['id'];
        			if($appinfo ==null){
        				$this->userinc=0;
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
            //'id' => $this->id,
            'myid' => $this->value,
            'friendid' => $this->userinc,
        	//'isfriend'=>$this->isfriend,
        ]);
        
        if (isset($params['FriendSearch'])&&isset($params['FriendSearch']['friendnickname'])){
       	 	$query->andFilterWhere(['like', 'user.nickname', $params['FriendSearch']['friendnickname']]);
       	 	
         }
        return $dataProvider;
    }
}
