<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\models\AuthItem;
use app\models\AuthAssignment;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
$user_group = AuthAssignment::find()->where(['user_id'=>$model->id])->one();
$array_status = ['1'=>'Active','2'=>'Inactive'];
$model->password = "";
?>
<link rel="stylesheet" type="text/css" href="<?=Url::base()?>/asset/css/cdn-select2.min.css" />
<link rel="stylesheet" type="text/css" href="<?=Url::base()?>/asset/css/github-select2.css">
<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    
    <?php if(!isset($model->id)){?>
      <br>
      <p><i>*Untuk tujuan login ke dalam program! Silakan catat password yang Anda tulis!</i></p>
      <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
      <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
      <!-- button show password -->
      <?= Html::checkbox('reveal-password', false, ['id' => 'reveal-password']) ?> <?= Html::label('Show password', 'reveal-password') ?>
      <br>
    <?php } ?>
    

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
    <label>Group
      <button type="button" style="font-size:9px" class="btn btn-primary btn-xs" data-toggle="modal" 
      data-target="#groupNew">
        <span class="fa fa-plus" ></span>
      </button>
    </label>
    <select name="auth_group" class="form-control" id="groupForm" required >
        <?php 
        if($user_group){ 
            $group__filter_select = AuthItem::find()->where(['type'=>1])->andWhere(['not in','name',[$user_group->item_name, 'developer']])->all();  
        ?>
            <option value="<?=$user_group->item_name?>"><?=$user_group->item_name?></option>
        <?php 
        }else{ 
            $group__filter_select = AuthItem::find()->where(['type'=>1])->andWhere(['not in','name',['developer']])->all();  
        ?>
            <option selected disabled value="">Select Group</option>
        <?php } ?>
        <?php foreach ($group__filter_select as $key => $value) { ?>
            <option value="<?=$value->name?>"><?=$value->name?></option>
        <?php } ?>
      <!-- add link to create new group -->

    </select>
    <br>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<!-- modal group new -->
<div class="modal fade" id="groupNew" tabindex="-1" role="dialog" aria-labelledby="exampmyModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Form Add New Group</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?php
          $model_group = new AuthItem(); 
        ?>
        <?= $this->render('form-external/form-group', [
            'model' => $model_group,
        ]) ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<?php
$this->registerJs("jQuery('#reveal-password').change(function(){jQuery('#user-password').attr('type',this.checked?'text':'password');})");
?>