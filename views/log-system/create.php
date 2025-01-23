<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\LogLogin */

$this->title = 'Create Log Login';
$this->params['breadcrumbs'][] = ['label' => 'Log Logins', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="log-login-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
