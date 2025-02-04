<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'Reset Password';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-reset-password">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form-reset-password', [
        'model' => $model,
    ]) ?>

</div>
