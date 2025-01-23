<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\User;
use app\models\AuthItem;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\AuthAssignment */
/* @var $form yii\widgets\ActiveForm */
$users = User::find()->select(['username', 'id'])->indexBy('id')->column();
?>
<link rel="stylesheet" type="text/css" href="<?=Url::base()?>/asset/css/cdn-select2.min.css" />
<link rel="stylesheet" type="text/css" href="<?=Url::base()?>/asset/css/github-select2.css">
<div class="auth-assignment-form">

    <?php $form = ActiveForm::begin(); ?>
    <label>User <b style="color:red;">*</b></label>
    <select class="form-control locationMultiple" id="user_id" name="AuthAssignment[user_id]" disabled>
        <?php if(isset($model->user_id)){?>
            <option selected value="<?=$model->user_id?>"><?=$users[$model->user_id]?></option>
        <?php }else{ ?>
            <option selected disabled="" value="">Select User</option>
        <?php } ?>
        <?php
            foreach($users as $key => $value){?>
                <option value="<?=$key?>"><?=$value?></option>
        <?php }
        ?>
    </select>
    <br>
    
    <label>Group <b style="color:red;">*</b></label>
    <select class="form-control locationMultiple" id="group_id" name="AuthAssignment[item_name]" required>
        <?php if(isset($model->item_name)){?>
            <?php $group_filter_select = AuthItem::find()->where(['type'=>1])->andWhere(['<>','name',$model->item_name])->all(); ?>
            <option value="<?=$model->item_name?>"><?=$model->item_name?></option>
        <?php }else{ ?>
            <?php $group_filter_select = AuthItem::find()->where(['type'=>1])->all(); ?>
            <option selected disabled="" value="">Select Group</option>
        <?php } ?>
        <?php
            foreach($group_filter_select as $val){?>
                <option value="<?=$val['name']?>"><?=$val['name']?></option>

        <?php }
        ?>
    </select>
    <br>
    
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
