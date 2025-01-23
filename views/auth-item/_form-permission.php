<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\AuthMenu;

/* @var $this yii\web\View */
/* @var $model app\models\AuthItem */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="auth-item-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
    
    <label>Menu Category</label>
    <select class="form-control" name="AuthItem[menu_id]" required>
        <?php 
            if(isset($model->menu_id)){
                $menu_select = AuthMenu::find()->where(['id' => $model->menu_id])->all();
                $menu = AuthMenu::find()->where(['!=', 'id', $model->menu_id])->all();
                foreach($menu_select as $m){
                    echo "<option value='$m->id' selected>$m->name</option>";
                }
                foreach($menu as $m){
                    echo "<option value='$m->id'>$m->name</option>";
                }
            }else{
                $menu = AuthMenu::find()->all();
                echo "<option value='' selected disabled>Select Menu Category</option>";
                foreach($menu as $m){
                    echo "<option value='$m->id'>$m->name</option>";
                }
            }
            
            

        ?>
      
    </select>
    <br>
    
    <?= $form->field($model, 'data')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
