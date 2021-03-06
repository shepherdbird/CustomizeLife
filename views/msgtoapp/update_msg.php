<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\v1\models\Msgtoapp */

$this->title = '更新消息对用应用';
$this->params['breadcrumbs'][] = ['label' => 'Msgtoapps', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<html lang="en-US" style="padding-left:15px">
<div class="msgtoapp-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form_msg', [
        'model' => $model,
    ]) ?>

</div>
