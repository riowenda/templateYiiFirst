<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AttachmentAsset */

$this->title = 'Create Attachment Asset';
$this->params['breadcrumbs'][] = ['label' => 'Attachment Assets', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="attachment-asset-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
