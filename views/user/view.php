<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <a href="<?=Url::to(['user/reset-password','id'=>$model->id])?>" class="btn btn-warning">Reset Password</a>
        <?php if($model->status == 1){ ?>
            <a href="<?=Url::to(['user/change-status','id'=>$model->id,'status'=>2])?>" class="btn btn-danger btn-status-action">Deactivate User</a>
        <?php }else{ ?>
            <a href="<?=Url::to(['user/change-status','id'=>$model->id,'status'=>1])?>" class="btn btn-warning btn-status-action">Activate User</a>
        <?php } ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            'name',
            [
                'attribute'=>'group',
                'format'=>'raw',
                'value' => function($model){
                    if($model->auth_assignment){
                        return $model->auth_assignment->item_name;
                    }else{
                        return '<span class="label label-default text-dark">No Group</span>';
                    }
                },
            ],
            'email:email',
            [
                'attribute'=>'status',
                'format'=>'raw',
                'value' => function($model){
                    if($model->status == 1){
                        return '<span class="label label-success">Active</span>';
                    }else{
                        return '<span class="label label-danger">Inactive</span>';
                    }
                },
            ],
        ],
    ]) ?>

</div>
<?php 
$js = <<< JS
$(document).ready(function(){
    
    $(".btn-status-action").click(function(){
        var r = confirm("Are you sure to change status?");
        if (r == true) {
            return true;
        } else {
            return false;
        }
    });
});
JS;
$this->registerJs($js);
