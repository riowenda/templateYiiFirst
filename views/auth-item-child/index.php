<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\AuthMenu;
use app\models\AuthItem;
use app\models\AuthItemChild;
/* @var $this yii\web\View */
/* @var $searchModel app\models\AuthItemChildSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Group Permission';
$this->params['breadcrumbs'][] = $this->title;

$permission = AuthItem::find()->where(['type'=>2])->all();
$has_permission = AuthItemChild::find()->where(['parent'=>'admin'])->all();

$group = AuthItem::find()->where(['type'=>1])->all();
$has_menu = Authmenu::find()->where(['name'=>'isAuthor'])->all();
?>
<div class="auth-item-child-index">

    <h1><?= Html::encode($this->title) ?></h1>

   

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <div class="table-responsive">
        <?php
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'options' => ['class' => 'custom-class'],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                [
                    'attribute' => 'name',
                    'label' => 'Group Name',
                    'filter' => ArrayHelper::map($group,'name','name'),
                ],
                [
                    'attribute' => 'menu_all',
                    'label' => 'Access Menu',
                    'format' => 'raw',
                    'value' => function($model){
                        $has_permission = AuthItemChild::find()->where(['parent'=>$model->name])->all();
                        $menu = [];
                        $permission_name = [];
                        foreach($has_permission as $val){
                            $permission_name[] = $val->child;
                            $has_menu = AuthItem::find()->select(['menu_id'])->distinct()->where(['name'=>$val->child])->all();
                            foreach($has_menu as $val){
                                $data_menu = AuthMenu::find()->where(['id'=>$val->menu_id])->all();
                                foreach($data_menu as $val){
                                    $menu[] = $val->name;
                                }
                            }
                        }
                        if(count($menu) == 0){
                            return '<span class="label label-danger">No Access </span>';
                        }else{
                            return '<ul><li>'.implode('</li><li>', array_unique($menu)).'</li></ul>';
                        }
                        


                    }
                ],

                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update}',
                    'buttons' => [
                        'update' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                                        'title' => Yii::t('app', 'view'),
                                        'class' => 'btn btn-primary btn-xs',
                            ]);
                        },
                    ],
                ],
            ],
        ]); ?>
    </div>
    <?php Pjax::end(); ?>

</div>
