<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\AuthAssignment;
use yii\filters\AccessControl;
use yii\helpers\Security;
use app\models\ResetPassword;
use app\models\LogCrud;


/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                //'only' => ['index', 'create', 'update', 'delete', 'view', 'sql-upload', 'no-asset', 'barcode'],
                'rules' => [
                    [
                        'actions' => ['view','index'],
                        'allow' => true,
                        'roles' => ['readUser'],
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['createUser'],
                    ],
                    [
                        'actions' => ['update','reset-password','change-status','add-pt-access','delete-pt-access'],
                        'allow' => true,
                        'roles' => ['updateUser'],
                    ],
                    [
                        'actions'=>['reset-account'],
                        'allow'=>true,
                        'roles'=>['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

   
    /**
     * Displays a single User model.
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
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();

        if ($model->load(Yii::$app->request->post())) {
            $trans_user = Yii::$app->db->beginTransaction();
            //$model->password = Yii::$app->getSecurity()->generatePasswordHash($model->password);
            $model->auth_key = Yii::$app->security->generateRandomString();
            $model->password_reset_token = '';
            $model->password = Yii::$app->security->generatePasswordHash($model->password);
            $model->status = '1';
            if($model->save()){
                $auth = Yii::$app->authManager;
                $auth->revokeAll($model->id);
                $role = $auth->getRole($_POST['auth_group']);
                $auth->assign($role, $model->id);
                
                $trans_user->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            }else{
                print_r($model->getErrors());
                $trans_user->rollback();
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        //cek apakah group dev , selain dev tidak boleh mengganti group dev
        $group_select = AuthAssignment::find()->where(['user_id' => $id])->one();
        $group_user = AuthAssignment::find()->where(['user_id' => Yii::$app->user->identity->id])->one();
        if($group_select){
            if($group_select->item_name == 'developer' &&
            $group_user->item_name != 'developer')
            {
                Yii::$app->session->setFlash('error', 'You are not allowed to change account for this user');
                return $this->redirect(['index']);
            }
        }
        
        if ($model->load(Yii::$app->request->post())) {
            
            if($model->save())
            {
                //update assignment
                $auth = Yii::$app->authManager;
                $auth->revokeAll($id);
                $role = $auth->getRole($_POST['auth_group']);
                $auth->assign($role, $id);
                if(isset($_POST['pt_access'])){
                    //delete all pt access
                    PtAccess::deleteAll(['user_id' => $id]);
                    foreach($_POST['pt_access'] as $pt){
                        if($this->savePtAccess($model->id, $pt)){
                            continue;
                        }else{
                            break;
                        }
                    }
                }
                return $this->redirect(['view', 'id' => $model->id]);
            }
            else{
                echo "MODEL NOT SAVED";
                print_r($model->getAttributes());
                print_r($model->getErrors());
                exit;
            }
            
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionResetPassword($id)
    {
        //cek apakah group dev , selain dev tidak boleh mengganti group dev
        $group_select = AuthAssignment::find()->where(['user_id' => $id])->one();
        $group_user = AuthAssignment::find()->where(['user_id' => Yii::$app->user->identity->id])->one();
        if($group_select){
            if($group_select->item_name == 'dev' && $group_user->item_name != 'dev')
            {
                Yii::$app->session->setFlash('error', 'You are not allowed to change account for this user');
                return $this->redirect(['index']);
            }
        }

        $model = ResetPassword::findOne($id);
        if($model->load(Yii::$app->request->post()))
        {
            $model->password = Yii::$app->security->generatePasswordHash($model->password);
            $model->retype = Yii::$app->security->generatePasswordHash($model->retype);
            if($model->save(false))
            {
                //save log
                LogCrud::saveLog("user",'update',"reset password user id : ".$model->id);
                return $this->redirect(['view', 'id' => $model->id]);
            }else{
                echo "MODEL NOT SAVED";
                print_r($model->getAttributes());
                print_r($model->getErrors());
                exit;
            }
        }

        return $this->render('reset-password', [
            'model' => $model,
        ]);
    }

    public function actionResetAccount()
    {
        $id = Yii::$app->user->identity->id;
        $model = ResetPassword::findOne($id);
        if($model->load(Yii::$app->request->post()))
        {
            $model->password = Yii::$app->security->generatePasswordHash($model->password);
            $model->retype = Yii::$app->security->generatePasswordHash($model->retype);
            if($model->save(false))
            {
                //save log
                LogCrud::saveLog("user",'update',"reset password user id : ".$model->id);
                return $this->redirect(['reset-password', 'id' => $model->id]);
            }else{
                echo "MODEL NOT SAVED";
                print_r($model->getAttributes());
                print_r($model->getErrors());
                exit;
            }
        }

        return $this->render('reset-password', [
            'model' => $model,
        ]);
    }

    public function actionChangeStatus()
    {
        if(isset($_GET['id']))
        {
            $model = $this->findModel($_GET['id']);
            $model->status = $_GET['status'];
            if($model->save(false))
            {
                //save log
                LogCrud::saveLog('user', 'update', $model->id.'|'.$model->username.'| status:'.$model->status);
                Yii::$app->session->setFlash('success', 'Status changed');
                return $this->redirect(['view', 'id' => $model->id]);
            }else{
                echo "MODEL NOT SAVED";
                print_r($model->getAttributes());
                print_r($model->getErrors());
                exit;
            }
                
        }
    }
    
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    

    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
