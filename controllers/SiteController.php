<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\httpclient\Client;
use yii\helpers\Url;
use app\models\User;
use app\models\LogLogin;
use app\base\Security;


class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }
    public function actionError()
    {
        $exception = Yii::$app->errorHandler->exception;

        if ($exception !== null) {
            // Memeriksa tipe error
            if ($exception instanceof \yii\web\NotFoundHttpException) {
                $message = 'Halaman yang Anda cari tidak ditemukan. error #404';
            } elseif ($exception instanceof \yii\web\ForbiddenHttpException) {
                $message = 'Anda tidak memiliki akses untuk halaman ini.';
            } else {
                $message = 'Terjadi kesalahan pada server.';
            }
            // Render view 'error' dengan pesan khusus
            return $this->render('error', [
                'name' => $exception->getName(),
                'message' => $message,
            ]);
        }
    }
    public function beforeAction( $action ) {
        if ( parent::beforeAction ( $action ) ) {

             //change layout for error action after
             //checking for the error action name
             //so that the layout is set for errors only
            if ( $action->id == 'error' ) {
                $this->layout = 'main_user';
            }
            return true;
        }
    }

    public function actions()
    {
        return [
            
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function readCookie($name){
        return Yii::$app->request->cookies[$name]->value;
    }

    //halaman login dan proses login
    public function actionLogin()
    {
        $this->layout = 'main_user';
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new LoginForm();
       
        if ($model->load(Yii::$app->request->post())) {
            // Cari user berdasarkan username
            $user = User::findByUsername($model->username);

            if ($user) {
                // Validasi password
                if (Yii::$app->getSecurity()->validatePassword($model->password, $user->password)) {
                    // Login berhasil
                    Yii::$app->user->login($user);
    
                    // Simpan log login
                    LogLogin::saveLogLogin($user->id, Yii::$app->request->userAgent, 1);
                    
                    return $this->goHome();
                } else {
                    // Password salah
                    $model->addError('password', 'Username atau password salah.');
                    
                    // Simpan log login dengan status gagal
                    LogLogin::saveLogLogin($user->id, Yii::$app->request->userAgent, 2);
                }
            } else {
                Yii::$app->session->setFlash('error', "Username tidak ditemukan");
            }
    
            // Reset password field
    
            // Render ulang form login dengan error
            return $this->render('login', [
                'model' => $model,
            ]);
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        $this->layout="main_user";
        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionFormBuild()
    {
        return $this->render('form-build');
    }

}
