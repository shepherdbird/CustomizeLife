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
use app\modules\v1\models\Tag;
use app\modules\v1\models\Appofkind;
use app\modules\v1\models\Apptopicture;
use app\modules\v1\models\Appcomments;
use app\modules\v1\models\Message;
use yii\filters\AccessControl;
use app\modules\v1\models\Usertoapp;
use app\modules\v1\models\CollectPerson;
use app\modules\v1\models\Usertohobby;
use app\modules\v1\models\Hobby;

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
		] )->orderBy('downloadcount desc');
		
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
		$model=new Tag();
		//$aa = (new \yii\db\Query ())->select ( 'kind' )->from ( 'appofkind f' )->all ();
		$aa = $model->findBySql ( "select distinct second from tag where second >''" )->all ();
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
	#	if(!isset($data['phone']){
	#		return $appinfo ;
	#	}
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
		if (!isset($data['phone'])){
			return $result;
		}
                $userinfo=User::find()->where([ 
					'phone' => $data ['phone'] 
			])->one();
		$colinfo=CollectPerson::find()->where([
				'app' => $data ['appid'],
				'userid'=>$userinfo->id
		])->one();
		if($colinfo){
			$result['collect']="1";
		}else{
			$result['collect']="0";
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
		//var_dump($appcomment);
		if($appcomment->save()){
// 		$appl = new Appl ();
// 		$appinfo = $appl->find ()->where ( [
// 				'id' => $data ['appid']
// 		] )->one ();
		$appinfo=Appl::findOne([
				'id' => $data ['appid']
		]);
		$appinfo->stars=($appinfo->stars*$appinfo->commentscount+$data['commentstars']*2)/($appinfo->commentscount+1);
		$appinfo->stars=number_format($appinfo->stars,1);
		$appinfo['commentscount']+=1;
		$appinfo->save();
		echo json_encode ( array (
				'flag' => 1,
				'msg' => 'summit success!'
		) );
		}else{
			echo json_encode ( array (
					'flag' => 0,
					'msg' => 'summit failed!'
			) );
		}
	}
	public function actionSearch(){
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		$model=new Appl();
		$data=Yii::$app->request->post();
		
		$aa[]=array();
		$aa[]= (new \yii\db\Query ())->select ( 'a.*' )->from ( 'app a' )
		->join('INNER JOIN', 'apptoreltag ar','a.id=ar.appid')
		->join('INNER JOIN', 'reltag r','ar.tagid=r.id')
		->where ( ['like','tag',$data['name']] )->orWhere(['like','name',$data['name']])->all();
		
		$aa[] =(new \yii\db\Query ())->select ( 'a.*' )->from ( 'app a' )
        		->join('INNER JOIN', 'appofkind ak','a.id=ak.appid')
        		->join('INNER JOIN', 'tag t','ak.kindid=t.id')
        		->where ( ['like','second',$data['name']] )->all();
		
		$ans=array();
		if($aa){
							foreach($aa as $a1){
								foreach ($a1 as $a){
								$point=0;
								for($j=0;$j<count($ans);$j++){
									if($a['id']==$ans[$j]['id']){
										$point=1;
										break;
									}
								}
								if($point==0){
									$ans[]=$a;
								}
							}
							}
							}
		return $ans;
	}
	public function actionRecommend(){
// 		$connection = \Yii::$app->db;
// 		$command = $connection->createCommand('SELECT * FROM user u,usertoapp ua,app a on(u.id=ua.userid and ua.appid=a.id and u.famous=1) orderby a.downloadcount');
// 		$posts = $command->queryAll();
// 		$aa = (new \yii\db\Query ())->select ( 'phone,nickname,thumb,famous,appid,name,downloadcount' )->from ( 'user u' )->join ( 'LEFT JOIN', 'usertoapp ua', 'u.id=ua.userid' )
// 		->join ( 'LEFT JOIN', 'app a', 'ua.appid=a.id' )
// 		->where ( [
// 				'u.famous' => 1
// 		] )
// 		->orderBy('a.downloadcount desc')
// 		->all ();
		$aa = (new \yii\db\Query ())->select ( 'phone,nickname,thumb,follower,shared' )->from ( 'user' )
		->where ( [
			'famous' => 1
		] )
		->orderBy('authKey desc')
		->limit(6)
 		->all ();
		return $aa;
	}
	public function actionRecommendAll(){
		$aa = (new \yii\db\Query ())->select ( 'phone,nickname,thumb,follower,shared' )->from ( 'user u' )
		->where ( [
				'famous' => 1
		] )
		->groupBy('phone')
		->orderBy('follower desc')
		->limit(30)
		->all ();
		return $aa;
	}
	public function actionRecommendHot(){
		$aa = (new \yii\db\Query ())->select ( 'phone,nickname,thumb,follower,shared,max(m.created_at) as created_at' )->from ( 'user u' )
		->join('INNER JOIN', 'msg m','m.userid=u.id')
		->where ( [
				'famous' => 1
		] )
		->groupBy('phone')
		->orderBy('shared desc')
		->limit(30)
		->all ();
		return $aa;
	}
	public function actionRecommendNew(){
		$aa = (new \yii\db\Query ())->select ( 'phone,nickname,thumb,follower,shared,max(m.created_at) as created_at' )->from ( 'user u' )
		->join('INNER JOIN', 'msg m','m.userid=u.id')
		->where ( [
				'u.famous' => 1
		] )
		->groupBy('phone')
		->orderBy('created_at desc')
		->limit(30)
		->all ();
		return $aa;
	}
	public function actionSharedby(){
		$data=Yii::$app->request->post();
		$userinfo=User::findOne([
				'phone'=>$data['phone']
		]);
		$userinfo['shared']+=1;
		if($userinfo->save()){
			echo json_encode ( array (
					'flag' => 1,
					'msg' => 'Success!'
			) );
		}else{
			echo json_encode ( array (
					'flag' => 0,
					'msg' => 'Failed!'
			) );
		}
	}
	public function actionGuess(){
		$data=Yii::$app->request->post();
		$userinfo=User::findOne([
				'phone'=>$data['phone']
		]);
		
		$hobbyinfo=(new \yii\db\Query ())->select ( 'h.hobby' )->from ( 'usertohobby u' )
		->join('INNER JOIN', 'hobby h','u.hobbyid=h.id')
		->where ( [
				'u.userid' => $userinfo->id
		] )->all();
		$userinfo['hobby']="";
		if(count($hobbyinfo)>0){
			foreach($hobbyinfo as $hobby){
				$userinfo['hobby']=$userinfo['hobby'].$hobby['hobby']." ";
			}
		}
		
		$userinfo['hobby']=trim($userinfo['hobby']);
		$arrs=explode(' ', $userinfo['hobby']);
		
		if(trim($userinfo['hobby'])==''){
			//echo "mycscsd";
			return Appl::findBySql('select * from app order by downloadcount desc limit 10')->all();
		}
		
		$ans=array();
		$num=0;
		for($i=0;$i<count($arrs);$i++,$num++){

		$aa[]=array();
		$aa[]= (new \yii\db\Query ())->select ( 'a.*' )->from ( 'app a' )
		->join('INNER JOIN', 'apptoreltag ar','a.id=ar.appid')
		->join('INNER JOIN', 'reltag r','ar.tagid=r.id')
		->where ( ['like','tag',$arrs[$i]] )->orWhere(['like','name',$arrs[$i]])->all();
		
		$aa[] =(new \yii\db\Query ())->select ( 'a.*' )->from ( 'app a' )
        		->join('INNER JOIN', 'appofkind ak','a.id=ak.appid')
        		->join('INNER JOIN', 'tag t','ak.kindid=t.id')
        		->where ( ['like','second',$arrs[$i]] )->all();
		
		if($aa){
							foreach($aa as $a1){
								foreach ($a1 as $a){
								$point=0;
								for($j=0;$j<count($ans);$j++){
									if($a['id']==$ans[$j]['id']){
										$point=1;
										break;
									}
								}
								if($point==0){
									$ans[]=$a;
								}
							}
							}
							}
			
		}
	   if(count($ans)<10){
			$add=(new \yii\db\Query ())->select ( '*' )->from ( 'app' )->orderBy('downloadcount desc')->limit(10-count($ans))->all();
		
		
		foreach($add as $a){
		         $point=0;
								for($j=0;$j<count($ans);$j++){
									if($a['id']==$ans[$j]['id']){
										$point=1;
										break;
									}
								}
								if($point==0){
									$ans[]=$a;
								}
		}
	   }
		return $ans;
	}
	public function actionTagCommend(){
		$model=new Tag();
		$Tag1s=$model->findBySql ( "select distinct first from tag where commend=1 and second=''" )->all ();
		$ans=array();
		foreach ($Tag1s as $Tag1){
			$model2=new Tag();
			$Tag2s=$model2->find()->select('second')->from('tag')->where('second>\'\' and first=:id',['id'=>$Tag1->first])->all();
			$ans[$Tag1->first]=array();
			for($i=0;$i<count($Tag2s);$i++){
				$ans[$Tag1->first][$i]=$Tag2s[$i]['second'];
			}
		}
		return $ans;
	}
	public function  actionWork(){
		$data=Yii::$app->request->post();
		$userinfo=User::findOne([
				'phone'=>$data['phone']
		]);
		
		$profinfo=(new \yii\db\Query ())->select ( 'h.profession' )->from ( 'usertoprof u' )
		->join('INNER JOIN', 'profession h','u.profid=h.id')
		->where ( [
				'u.userid' => $userinfo ['id']
		] )->all();
		$model['job']="";
		if(count($profinfo)>0){
			foreach($profinfo as $prof){
				$model['job']=$model['job'].$prof['profession']." ";
			}
		}
		
		$userinfo['job']=trim($userinfo['job']);
		$arrs=explode(' ', $userinfo['job']);
		
		if(trim($userinfo['job'])==''){
			//echo "mycscsd";
			return Appl::findBySql('select * from app order by downloadcount desc limit 10')->all();
		}
		
		$ans=array();
		$num=0;
		for($i=0;$i<count($arrs);$i++,$num++){

		$aa[]=array();
		$aa[]= (new \yii\db\Query ())->select ( 'a.*' )->from ( 'app a' )
		->join('INNER JOIN', 'apptoreltag ar','a.id=ar.appid')
		->join('INNER JOIN', 'reltag r','ar.tagid=r.id')
		->where ( ['like','tag',$arrs[$i]] )->orWhere(['like','name',$arrs[$i]])->all();
		
		$aa[] =(new \yii\db\Query ())->select ( 'a.*' )->from ( 'app a' )
        		->join('INNER JOIN', 'appofkind ak','a.id=ak.appid')
        		->join('INNER JOIN', 'tag t','ak.kindid=t.id')
        		->where ( ['like','second',$arrs[$i]] )->all();
		
		if($aa){
							foreach($aa as $a1){
								foreach ($a1 as $a){
								$point=0;
								for($j=0;$j<count($ans);$j++){
									if($a['id']==$ans[$j]['id']){
										$point=1;
										break;
									}
								}
								if($point==0){
									$ans[]=$a;
								}
							}
							}
							}
		}
		
		if(count($ans)<10){
			$add=(new \yii\db\Query ())->select ( '*' )->from ( 'app' )->orderBy('downloadcount desc')->limit(10-count($ans))->all();
		
		
		foreach($add as $a){
		$point=0;
								for($j=0;$j<count($ans);$j++){
									if($a['id']==$ans[$j]['id']){
										$point=1;
										break;
									}
								}
								if($point==0){
									$ans[]=$a;
								}
		}
		}
		
		//$ans=array_unique($ans);
		return $ans;
	}
}
