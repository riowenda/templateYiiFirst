<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\AuthItemChild;
use app\models\AuthItem;

/* @var $this yii\web\View */
/* @var $model app\models\AuthItemChild */

$this->title = 'Data Permission For : '.$model->name;
// $this->params['breadcrumbs'][] = ['label' => 'Auth Item Children', 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="auth-item-child-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <!-- <p>
        <?php // Html::a('Update', ['update', 'parent' => $model->parent, 'child' => $model->child], ['class' => 'btn btn-primary']) 
        //Html::a('Delete', ['delete', 'parent' => $model->parent, 'child' => $model->child], [
           // 'class' => 'btn btn-danger',
            //'data' => [
              //  'confirm' => 'Are you sure you want to delete this item?',
                //'method' => 'post',
            //],
        //]) ?>
    </p> -->

     <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            [
                'attribute'=>'permission',
                'format'=>'raw',
                'value'=> function($model){
                    $item_permission = AuthItem::find()->where(['type'=>2])->andWhere(['rule_name'=>$model->name])->all();
                    $permission = [];
                    foreach($item_permission as $val){
                        $permission[] = $val->data;
                    }
                    return implode(', ', $permission);
                }
            ],
        ],
    ]) ?>

</div>
