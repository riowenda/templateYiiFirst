<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AuthItem */

$this->title = 'Update Permission: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Permissions', 'url' => ['permission']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="auth-item-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form-permission', [
        'model' => $model,
    ]) ?>

</div>
