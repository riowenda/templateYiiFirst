<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\AuthItem;
use app\models\AuthMenu;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model app\models\AuthItemChild */
/* @var $form yii\widgets\ActiveForm */
$no_access_menu = AuthMenu::find()->where(['not in', 'id', $model_menu])->all();
?>
<!-- select2 lib -->
<link rel="stylesheet" type="text/css" href="<?=Url::base()?>/asset/css/cdn-select2.min.css" />
<link rel="stylesheet" type="text/css" href="<?=Url::base()?>/asset/css/github-select2.css">

<div class="auth-item-child-form">
     <?php $form = ActiveForm::begin();?>
        <div class="col-md-12">
            <?php if(!empty($no_access_menu)){ ?>
                <button type="button" class="pull-right btn btn-warning" data-target="#modalAddMenu" data-toggle="modal" id="btnAddColumn" data-nomor="1" data-menu="<?=implode(',',$model_menu)?>"><i class="fa fa-plus"></i> Add New Menu Access</button>
            <?php }else{ ?>
                <i class="pull-right">There is no menu access that you can add</i><br>
                <button type="button" class="pull-right btn btn-warning" id="btnAddColumn"  disabled><i class="fa fa-plus"></i> Add New Menu Access</button>
            <?php } ?>
        </div>
        <div class="">
            <div class="row">
                <div class="col-md-6">
                    <h2>Menu</h2>
                </div>
                <div class="col-md-6">
                    <h2>Permission</h2>
                </div>
                <?php
                    foreach($model_menu as $i){
                        $nama_menu = AuthMenu::find()->where(['id'=>$i])->one();
                ?>
                <div class="col-md-12">
                        <hr>
                    </div>
                    <div class = "col-md-12">
                        <div class="col-md-6">
                            <b style="font-size:20px"><?=$nama_menu->name?></b>
                        </div>
                        <div class="col-md-6">
                            <?php
                                $permission = AuthItem::find()->where(['type'=>2])->andWhere(['menu_id'=> $i])->all();
                                foreach($permission as $val){
                                    $checked = false;
                                    if(in_array($val->name, $model_permission)){
                                        $checked = true;
                                    }
                            ?>
                                <div class="checkbox">
                                    <label>
                                        <?= Html::checkbox($i.'-'.'permission[]', $checked, ['value' => $val->name]) ?>
                                        <?= $val->data ?>
                                    </label>
                                </div>
                            <?php }
                            ?>
                        </div>
                    </div>
                <?php } ?>
                
                <div class="col-md-12">
                    <hr>
                </div>
                <div id="addColumn"></div>
                
                <div class="form-group">
                    <?= Html::submitButton('Update', ['class' => 'btn btn-success', 'name'=>'submit-update']) ?>
                </div>
            </div>
        </div>
    <?php ActiveForm::end(); ?>
</div>
<!-- modal bootstrap create add menu -->
<div class="modal fade" id="modalAddMenu" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Add New Menu Access</h4>
            </div>
            <!-- create yii form active with custom url action -->
            <?php $form = ActiveForm::begin([
                'action' => ['auth-menu/add-menu-to-group'],
                'method' => 'post',
            ]); ?>
                <div class="modal-body">
                    
                        <label for="selectMenu">Select Menu (Support Multiple)</label>
                        <select class="form-control locationMultiple" id="selectMenu" name="menu_id[]" multiple="multiple">
                            <option value="" disabled>-- Select Menu --</option>
                            <?php
                                foreach($no_access_menu as $val){
                            ?>
                                <option value="<?=$val->id?>"><?=$val->name?></option>
                            <?php } ?>
                        </select>
                        <input type="hidden" name="group" value="<?=$model->name?>">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="btnSaveMenu">Save changes</button>
                </div>
            <?php ActiveForm::end() ?>
        </div>
    </div>
<!-- end modal -->

