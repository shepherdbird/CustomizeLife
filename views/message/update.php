<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\v1\models\Message */

$this->title = '更新消息: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Messages', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<html lang="en-US" style="padding-left:15px">
<div class="message-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
