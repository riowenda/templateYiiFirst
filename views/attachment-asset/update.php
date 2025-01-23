<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AttachmentAsset */

$this->title = 'Update Attachment Asset: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Attachment Assets', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="attachment-asset-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
