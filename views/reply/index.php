<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ReplySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '';
$this->params['breadcrumbs'][] = $this->title;
?>
<html lang="en-US" style="padding-left:15px">
<div class="reply-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('创建回复', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'msgid',
            'content',
            'fromid',
            'toid',
            // 'isread',
            // 'created_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>