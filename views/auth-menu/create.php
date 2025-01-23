<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AuthMenu */

$this->title = 'Create Auth Menu';
$this->params['breadcrumbs'][] = ['label' => 'Auth Menus', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-menu-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
