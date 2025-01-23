<?php

namespace app\controllers;

use Yii;
use app\models\AuthMenu;
use app\models\AuthMenuSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\AuthItem;

/**
 * AuthMenuController implements the CRUD actions for AuthMenu model.
 */
class AuthMenuController extends Controller
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
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        //action all in
                        'actions' => ['create','update','index','add-menu-to-group','get-add-menu'],
                        'allow' => true,
                        'roles' => ['developer'],
                    ],
                    [
                        //action all in
                        'actions' => ['add-menu-to-group','get-add-menu'],
                        'allow' => true,
                        'roles' => ['managePermission'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all AuthMenu models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AuthMenuSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AuthMenu model.
     * @param integer $id
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
     * Creates a new AuthMenu model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AuthMenu();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing AuthMenu model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionAddMenuToGroup()
    {
        if(isset($_POST['group'])){
            $group = $_POST['group'];
            $menu_id = $_POST['menu_id'];
            foreach($menu_id as $i=>$menu_id)
            {
                $auth_item_permission = AuthItem::find()->where(['menu_id'=>$menu_id])->one();
                //add item permission to group
                $auth = Yii::$app->authManager;
                $role = $auth->getRole($group);
                $permission = $auth->getPermission($auth_item_permission['name']);
                $auth->addChild($role, $permission);
            }
            return $this->redirect(['auth-item-child/update', 'id' => $group]);            

        }
    }

    /**
     * Deletes an existing AuthMenu model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the AuthMenu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AuthMenu the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AuthMenu::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    //Ajax
    public function actionGetAddMenu()
    {
        if(isset($_POST['menu_list_now']))
        {
            $menu_list_now = $_POST['menu_list_now'];
            $new_list_menu = AuthMenu::find()->where(['not in','id',$menu_list_now])->all();
            $list_menu = [];
            foreach($new_list_menu as $key=>$val)
            {
                $list_menu[$key]['name'] = $val['name'];
                $list_menu[$key]['id'] = $val['id'];
            }
            return json_encode($list_menu);
        }
    }
}
