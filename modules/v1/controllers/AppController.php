<?php

namespace app\modules\v1\controllers;

use Yii;
use yii\data\Pagination;
use yii\data\ActiveDataProvider;
use yii\widgets\LinkPager;
use yii\rest\ActiveController;
//use yii\rest\ActiveController;
use app\modules\v1\models\Appl;
use app\modules\v1\models\User;
use app\modules\v1\models\Appofkind;
use app\modules\v1\models\Apptopicture;
use app\modules\v1\models\Appcomments;
use app\modules\v1\models\Message;
use yii\filters\AccessControl;
use app\modules\v1\models\app\modules\v1\models;

class AppController extends ActiveController {
	public $modelClass = 'app\modules\v1\models\Appl';
	public $serializer = [ 
			'class' => 'yii\rest\Serializer',
			'collectionEnvelope' => 'items' 
	];
	public function actionKind() {
		$data = Yii::$app->request->post ();
		
		$query = Appl::find ()->select ( '*' )->join ( 'INNER JOIN', 'appofkind', 'app.id=appofkind.appid' )->where ( [ 
				'appofkind.kind' => $data ['kind'] 
		] );
		
		$pages = new Pagination ( [ 
				'totalCount' => $query->count (),
				'pageSize' => '3' 
		] );
		$models = $query->offset ( $pages->offset )->limit ( $pages->limit )->all ();
		
		$result = array ();
		$result ['item'] = array ();
		foreach ( $models as $model ) {
			$result ['item'] [] = $model;
		}
		$result ['_meta'] = array (
				'pageCount' => $pages->pageCount,
				'currentPage' => $pages->page + 1 
		);
		
		return $result;
	}
	public function actionAllkind(){
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		$model=new Appofkind();
		//$aa = (new \yii\db\Query ())->select ( 'kind' )->from ( 'appofkind f' )->all ();
		$aa = $model->findBySql ( "select distinct kind from appofkind" )->all ();
		return $aa;
	}
	public function actionGetapp(){
		$data=Yii::$app->request->post();
		$appl=new Appl();
		$apptopic=new Apptopicture();
		$appcom=new Appcomments();
		$appinfo = $appl->find ()->where ( [ 
					'id' => $data ['appid'] 
			] )->one ();
		$result=array();
		$result['basic']=$appinfo;
		$apppics = $apptopic->find ()->where ( [
				'appid' => $data ['appid']
		] )->all ();
		$result['picture_urls']=array();
		foreach ($apppics as $apptopic){
			$result['picture_urls'][]=$apptopic;
		}
		$appcoms = $appcom->find ()->where ( [
				'appid' => $data ['appid']
		] )->all ();
		$result['comments']=array();
		foreach ($appcoms as $appcomment){
			$result['comments'][]=$appcomment;
		}
		return $result;
	}
	public function actionSubmitcomment(){
		$data=Yii::$app->request->post();
		$model=new User();
		$aa = $model->findBySql ( "select * from user where phone=" . $data['phone'] )->all ();
		$appcomment=new Appcomments();
		$appcomment->userid=$aa[0]['id'];
		$appcomment->userthumb=$aa[0]['thumb'];
		$appcomment->usernickname=$aa[0]['nickname'];
		$appcomment->commentstars=$data['commentstars'];
		$appcomment->comments=$data['comments'];
		$appcomment->title=$data['title'];
		$appcomment->created_at=time();
		$appcomment->appid=$data['appid'];
		$appcomment->save();
// 		$appl = new Appl ();
// 		$appinfo = $appl->find ()->where ( [
// 				'id' => $data ['appid']
// 		] )->one ();
		$appinfo=Appl::findOne([
				'id' => $data ['appid']
		]);
		$appinfo->stars=($appinfo->stars*$appinfo->commentscount+$data['commentstars'])/($appinfo->commentscount+1);
		$appinfo['commentscount']+=1;
		$appinfo->save();
		echo json_encode ( array (
				'flag' => 1,
				'msg' => 'summit success!'
		) );
	}
	public function actionSearch(){
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		$model=new Appl();
		$data=Yii::$app->request->post();
		//$aa = (new \yii\db\Query ())->select ( 'kind' )->from ( 'appofkind f' )->all ();
		$aa = $model->find()->where(['like','name',$data['name']])->all();
		return $aa;
	}
}
