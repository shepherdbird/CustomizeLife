<?php

namespace app\controllers;

use Yii;
use app\modules\v1\models\Hobby;
use app\models\HobbySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * HobbyController implements the CRUD actions for Hobby model.
 */
class HobbyController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Hobby models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new HobbySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Hobby model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Hobby model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Hobby();

        if ($model->load(Yii::$app->request->post())) {
        	$row=Hobby::findOne([
        			'hobby' => $model->hobby,
        	] );
        		
        	if($row){
        		//echo "<script> alert('已经是好友！'); </script>";
        		$this->alert("已经存在！","jump","index");
        		return $this->redirect(['index']);
        	}else{
        		$model->save();
            return $this->redirect(['index']);
        	}
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
    
    function alert($tip = "", $type = "", $url = "") {
    	$js = "<script>";
    	if ($tip)
    		$js .= "alert('" . $tip . "');";
    	switch ($type) {
    		case "close" : //关闭页面
    			$js .= "window.close();";
    			break;
    		case "back" : //返回
    			$js .= "history.back(-1);";
    			break;
    		case "refresh" : //刷新
    			$js .= "parent.location.reload();";
    			break;
    		case "top" : //框架退出
    			if ($url)
    				$js .= "top.location.href='" . $url . "';";
    				break;
    		case "jump" : //跳转
    			if ($url)
    				$js .= "window.location.href='" . $url . "';";
    				break;
    		default :
    			break;
    	}
    	$js .= "</script>";
    	echo $js;
    	if ($type) {
    		exit();
    	}
    }

    /**
     * Updates an existing Hobby model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Hobby model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Hobby model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Hobby the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Hobby::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
