<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AppcommentsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$this->title = $dataProvider->models[0]['appid'];
$this->params['breadcrumbs'][] = $this->title;
?>
<html lang="en-US" style="padding-left:15px">
<div class="appcomments-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?//= Html::a('创建评论', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
           // 'appid',
            'userid',
            //'userthumb',
            'usernickname',
            'commentstars',
            'comments',
            // 'created_at',
			[
				'attribute' => 'created_at',
				'label'=>'创建时间',
				'value'=>
				function($model){
				return  date('Y-m-d H:i:s',$model->created_at);   //主要通过此种方式实现
							},
				'headerOptions' => ['width' => '170'],
			],
            // 'title',

            	[
							'class' => 'yii\grid\ActionColumn',
				'template' => '{deleteone}',
													'buttons' => [
																				'deleteone' => function ($url, $model, $key) {
																					$options = [
																							'title' => Yii::t('yii', 'Delete'),
																									'aria-label' => Yii::t('yii', 'Delete'),
																											'data-confirm' => Yii::t('yii', '确定要删除该条记录?'),
																													'data-method' => 'post',
																													'data-pjax' => '0',
			        	];
										        	return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, $options);
										        },
									      	],
										],
        ],
    ]); ?>

</div>
