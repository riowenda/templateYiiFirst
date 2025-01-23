<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
$model->password ="";
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'retype')->passwordInput(['maxlength' => true]) ?>
    <?= Html::checkbox('reveal-password', false, ['id' => 'reveal-password']) ?> <?= Html::label('Show password', 'reveal-password') ?>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>
    <input type="hidden" name="User[status]" value="1">
    <?php ActiveForm::end(); ?>

</div>
<?php
$this->registerJs("jQuery('#reveal-password').change(function(){jQuery('#resetpassword-password').attr('type',this.checked?'text':'password');})");
?>
