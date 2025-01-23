<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\AuthRule;
use app\models\AuthMenu;
/* @var $this yii\web\View */
/* @var $searchModel app\models\AuthItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'List Permission';
$this->params['breadcrumbs'][] = $this->title;
$list_menu = AuthMenu::find()->all();
?>
<div class="auth-item-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Permission', ['create-permission'], ['class' => 'btn btn-success btn-sm']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options' => ['class' => 'custom-class'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            'description:ntext',
            [
                'attribute'=>'menu',
                'label'=>'Menu Group',
                'filter' => Html::activeDropDownList(
                            $searchModel, 'menu', yii\helpers\ArrayHelper::map($list_menu, 'id', 'name'),
                            ['class'=>'form-control','prompt' => 'All']
                        ),
                'value' => function($model){
                    return $model->authMenu->name;
                }
            ],
            [
                //this value is create, update, delete, manage
                'attribute' => 'data',
                'filter' => ['create'=>'Create', 'update'=>'Update', 'delete'=>'Delete', 'manage'=>'Manage'],
                'value' => function($model){
                    return $model->data;
                }
            ],
            [
                'attribute' => 'created_at',
                'value' => function($model){
                    return date('d-m-Y H:i:s', $model->created_at);
                }
            ],
            [
                'attribute' => 'updated_at',
                'value' => function($model){
                    return date('d-m-Y H:i:s', $model->updated_at);
                }
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'buttons' => [
                    //custom url
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['update-permission', 'id' => $model->name], [
                                    'title' => Yii::t('app', 'update'),
                        ]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete-permission', 'id' => $model->name], [
                                    'title' => Yii::t('app', 'delete'),
                                    'data' => [
                                        'confirm' => 'Are you sure you want to delete this item?',
                                        'method' => 'post',
                                    ],
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
