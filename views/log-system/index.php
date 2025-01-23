<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\LogCrud;
use app\models\AttachmentAsset;
use app\models\Asset;
/* @var $this yii\web\View */
/* @var $searchModel app\models\LogLoginSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->registerCss("
    .custom-class td, .custom-class th {
        font-size: 14px !important;
        padding: 5px !important;
    }
    .custom-class .form-control {
        font-size: 14px !important;
        padding: 5px !important;
        height: 25px !important;
    }


");
$this->title = 'Log System';

//$this->params['breadcrumbs'][] = $this->title;
?>

<div class="log-login-index">

    <h1><?= Html::encode($this->title) ?></h1>

   

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options' => ['class' => 'custom-class'],
        'tableOptions' => ['class' => 'table custom-class'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute'=>'action',
                'format'=>'raw',
                'filter' => Html::activeDropDownList($searchModel, 'action', [
                    'Login' => 'Login',
                    'Update' => 'Update',
                    'Create' => 'Create',
                    'Delete' => 'Delete',
                ], ['class' => 'form-control', 'prompt' => 'All']),
                'value'=> function($model, $key, $index, $column){
                    //replace _ with -
                    
                    $detail = LogCrud::find()->where(['id'=>$model['id']])->one();
                    if($model['action'] != 'Login'){
                        
                        $id_value = explode('|', $detail['detail'])[0];
                        $id_value = preg_replace('/\D/', '', $id_value);
                        $id_value = preg_replace('/old:/', '-', $id_value);
                        $id_value = preg_replace('/id:/', '-', $id_value);
                        $url_table = preg_replace('/_/', '-', $model['table']);
                        $addon = "view";
                        $table_v = $model['table'];
                        if($model['table'] == 'auth_column_access'){
                            $addon = "update";
                            $table_v = 'Column Access';
                        }
                        if($model['table'] == 'auth_item_child'){
                            $id_value = explode('.', $detail['detail'])[0];
                            $id_value = preg_replace('/group :/', '', $id_value);
                            $addon = "update";
                            $table_v = 'Group Access';
                        }
                        if($model['table'] == 'attachment_asset'){
                            $id_attachment = explode('.', $detail['detail'])[0];
                            $id_attachment = preg_replace('/name :/', '', $id_value);
                            $attachment_data = AttachmentAsset::find()->where(['id'=>$id_attachment])->one();
                            if(isset($attachment_data['asset_id'])){
                                $id_value = $attachment_data['id'];
                            }else{
                                $id_value = null;
                            }

                            $url_table = 'asset';
                        }
                    
                        $table_v = preg_replace('/_/', ' ', $table_v);
                            $action_msg = $model['action'].' on table ';
                            if($model['action'] == 'delete'){
                                $action_msg .= $model['table'];
                                if($id_value != null){
                                    $action_msg .= ' id : '.$id_value;
                                }
                            }else{
                            $action_msg .= Html::a($table_v, ['/'.$url_table.'/'.$addon, 'id' => $id_value]);
                            }
                            return $action_msg;
                        
                    }else{
                        return $model['action'];
                    }
                }
                
            ],
            [
                'attribute'=>'user',
            ],
            'time',
            'ip_address',
            //'status',

        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
