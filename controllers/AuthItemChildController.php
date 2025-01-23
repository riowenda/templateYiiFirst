<?php

namespace app\controllers;

use Yii;
use app\models\AuthItemChild;
use app\models\AuthItemChildSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\AuthItem;
use app\models\AuthItemSearch;
use yii\helpers\ArrayHelper;
use app\models\AuthRule;
use app\models\AuthRuleSearch;
use app\models\RulePermissionSearch;
use app\models\AuthMenu;
use app\models\LogCrud;
use yii\filters\AccessControl;
use app\models\AuthAssignment;

/**
 * AuthItemChildController implements the CRUD actions for AuthItemChild model.
 */
class AuthItemChildController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
        // return [
        //     'access' => [
        //         'class' => \yii\filters\AccessControl::className(),
        //         'rules' => [
        //             [
        //                 //action all in
        //                 'actions' => ['index','view','create','update','delete'],
        //                 'allow' => true,
        //                 'roles' => ['managePermission'],
        //             ],
        //         ],
        //     ],
        // ];
    }

    /**
     * Lists all AuthItemChild models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AuthItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,1);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionView($id)
    {
        
        
        $model = AuthItem::find()->where(['name' => $id])->one();
        $has_permission = AuthItemChild::find()->where(['parent'=>$id])->all();
        $rule_data = [];
        foreach($has_permission as $val){
            $permission_name[] = $val->child;
            $has_rule = AuthItem::find()->select(['rule_name'])->distinct()->where(['name'=>$val->child])->all();
            foreach($has_rule as $val){
                $rule_data[] = $val->rule_name;
            }
        }
        // $dataProvider = new \yii\data\ArrayDataProvider([
        //     'allModels' => $rule_data,
        //     'pagination' => [
        //         'pageSize' => 10,
        //     ],
        // ]);
        $searchModel = new RulePermissionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, array_unique($rule_data));
        return $this->render('view', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Creates a new AuthItemChild model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AuthItemChild();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'parent' => $model->parent, 'child' => $model->child]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing AuthItemChild model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $parent
     * @param string $child
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $group_user = AuthAssignment::find()->select(['item_name'])->where(['user_id'=>Yii::$app->user->identity->id])->one();
        //cek jika selain group dev tidak bisa melihat group dev
        if($group_user->item_name != 'dev' && $id == 'dev'){
            Yii::$app->session->setFlash('warning', 'Cannot change group dev !');
            return $this->redirect(['auth-item-child/index']);
        }
        $model = AuthItem::find()->where(['name' => $id])->one();
        $has_permission = AuthItemChild::find()->where(['parent'=>$id])->all();
        $rule_data = [];
        $permission_name = [];
        foreach($has_permission as $val){
            $permission_name[] = $val->child;
        }
        $has_rule = AuthItem::find()->select('menu_id')->distinct()->where(['in','name', $permission_name])->all();
        foreach($has_rule as $val){
            $data_menu_name = AuthMenu::find()->select(['id'])->distinct()->where(['id'=>$val->menu_id])->all();
            foreach($data_menu_name as $val){
                $rule_data[] = $val->id;
            }
        }
        

        //jika form di submit
        if (isset($_POST['submit-update'])) {
            $model = AuthItem::find()->where(['name' => $id])->one();
            $has_permission = AuthItemChild::find()->where(['parent'=>$id])->all();
            foreach($has_permission as $val){
                $permission_name[] = $val->child;
            }
            $data_id_menu = AuthItem::find()->select('menu_id')->where(['in','name', $permission_name])->all();
            $data_permission_old = AuthItemChild::find()->select('child')->where(['parent'=>$id])->all();
            //data permission lama ke array
            $permission_old = [];
            foreach($data_permission_old as $val){
                $permission_old[] = $val->child;
            }
            $transaction_child = AuthItemChild::getDb()->beginTransaction();
            try {
                AuthItemChild::deleteAll(['parent' => $id]);
                $transaction_child->commit();
                foreach($data_id_menu as $i){
                    if(isset($_POST[$i->menu_id.'-permission'])){
                        $menu_name = $_POST[$i->menu_id.'-permission'];
                        
                        foreach($menu_name as $val){
                                $model_permission = new AuthItemChild();
                                $model_permission->parent = $id;
                                $model_permission->child = $val;
                                if($model_permission->save()){
                                    Yii::$app->session->setFlash('success', 'Data berhasil diubah');
                                    unset($model_permission);
                                }else{
                                    //batalkan transaksi menghapus data lama
                                    $transaction_child->rollBack();
                                    unset($model_permission);
                                }
                        }
                    }
                }
                $data_permission_new = AuthItemChild::find()->select('child')->where(['parent'=>$id])->all();
                //data permission baru ke array
                $permission_new = [];
                foreach($data_permission_new as $val){
                    $permission_new[] = $val->child;
                }
                //simpan log old dan new
                LogCrud::saveLog('auth_item_child', 'update', 'group :'.$id.'. old: '.implode(',',$permission_old).' 
                . new: '.implode(',',$permission_new));
            } catch(\Exception $e) {
                $transaction_child->rollBack();
                throw $e;
            } catch(\Throwable $e) {
                $transaction_child->rollBack();
                throw $e;
            }

            return $this->redirect(['update', 'id' => $id]);
        }
        return $this->render('update', [
            'model' => $model,
            'model_menu' => $rule_data,
            'model_permission' => $permission_name,
        ]);
    }

    //menghapus data permission
    public function actionDelete($parent, $child)
    {
        $this->findModel($parent, $child)->delete();

        return $this->redirect(['index']);
    }

    // protected function findModel($parent, $child)
    // {
    //     if (($model = AuthItemChild::findOne(['parent' => $parent, 'child' => $child])) !== null) {
    //         return $model;
    //     }

    //     throw new NotFoundHttpException('The requested page does not exist.');
    // }
}
