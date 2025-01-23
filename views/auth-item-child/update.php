<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AuthItemChild */

$this->title = 'Group Permission: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'List Auth', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="auth-item-child-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'model_menu'=>$model_menu,
        'model_permission' => $model_permission,
    ]) ?>

</div>
