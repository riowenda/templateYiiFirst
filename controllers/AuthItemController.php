<?php

namespace app\controllers;

use Yii;
use app\models\AuthItem;
use app\models\AuthItemSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * AuthItemController implements the CRUD actions for AuthItem model.
 */
class AuthItemController extends Controller
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
            // 'access' => [
            //     'class' => \yii\filters\AccessControl::className(),
            //     'rules' => [
            //         [
            //             //action all in
            //             'actions' => ['group','permission','view','viewPermission','create',
            //                 'create-permission','update','update-permission','delete','deletePermission', 
            //                 'get-add-permission','create-group-ajax'
            //             ],
            //             'allow' => true,
            //             'roles' => ['managePermission'],
            //         ],
            //     ],
            // ],
        ];
    }

    public function actionGroup()
    {
        $searchModel = new AuthItemSearch();
        $type = 1; // tipe auth item untuk group
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $type);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionPermission()
    {
        $searchModel = new AuthItemSearch();
        $type = 2; // tipe auth item untuk access
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $type);

        return $this->render('index-permission', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AuthItem model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new AuthItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AuthItem();

        if ($model->load(Yii::$app->request->post())) {
            $model->type = 1;
            if($model->save()){
                return $this->redirect(['group']);
            }else{
                print_r($model->errors);
                die();
            }
            
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionCreatePermission()
    {
        $model = new AuthItem();

        if ($model->load(Yii::$app->request->post())) {
            $model->type = 2;
            if($model->save())
            {
                return $this->redirect(['permission']);
            }else{
                Yii::$app->session->setFlash('error', 'Data gagal disimpan');
            }
        }

        return $this->render('create-permission', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing AuthItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Data berhasil diubah');
            return $this->redirect(['group']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionUpdatePermission($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['permission']);
        }

        return $this->render('update-permission', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing AuthItem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['group']);
    }

    public function actionDeletePermission($id)
    {
        $model = $this->findModel($id);
        if($model->type == 2){
            $model->delete();
            return $this->redirect(['permission']);
        }else if($model->type == 1){
            $model->delete();
            return $this->redirect(['group']);

        }else{
            Yii::$app->session->setFlash('error', 'Data gagal dihapus');
        }
    }

    /**
     * Finds the AuthItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return AuthItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AuthItem::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    //ajax
    public function actionGetAddPermission()
    {
        if(isset($_POST['menu_select']))
        {
            $menu_list_now = $_POST['menu_select'];
            $new_permission = AuthItem::find()->where(['type' => 2])->andWhere(['menu_id'=>$menu_list_now])->all();
            $list_permission = [];
            foreach ($new_permission as $key => $value) {
                $list_permission[] = $value->name;
            }
            return json_encode($list_permission);
        }
    }

    public function actionCreateGroupAjax()
    {
        if(isset($_POST['group_name'])){
            $model = new AuthItem();
            $model->name = $_POST['group_name'];
            $model->description = $_POST['group_description'];
            $list_group = AuthItem::find()->where(['type' => 1])->all();
            $model->type = 1;

            //cek ketersediaan nama group
            foreach ($list_group as $key => $value) {
                if($value->name == $model->name){
                    return 3;
                    break;
                }
                
            }
            if($model->save()){
                //update form select group
                return "<option value='".$model->name."'>".$model->name."</option>";
                
            }else{
                return 0;
            }
            
        }
    }
}
