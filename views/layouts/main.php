<?php

/* @var $this \yii\web\View */
/* @var $content string */
use Yii;
use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

AppAsset::register($this);
$urlMenu = "/index.php?r=";
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="en">
<?= Html::csrfMetaTags() ?>
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="rio">
    <?php $this->head() ?>
    <title>TOP Farm</title>
    <!-- ===== Bootstrap CSS ===== -->
    <link href="<?=Url::base();?>/cubic/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <!-- ===== Animation CSS ===== -->
    
    <link href="<?=Url::base();?>/plugins/components/custom-select/custom-select.css" rel="stylesheet" type="text/css" />
    <link href="<?=Url::base();?>/plugins/components/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" />
    <link href="<?=Url::base();?>/plugins/components/switchery/dist/switchery.min.css" rel="stylesheet" />
    <link href="<?=Url::base()?>/plugins/components/bootstrap-table/dist/bootstrap-table.min.css" rel="stylesheet" type="text/css" />
    <link href="<?=Url::base();?>/cubic/css/animate.css" rel="stylesheet">
    <!-- ===== Custom CSS ===== -->
    <link href="<?=Url::base();?>/cubic/css/style.css" rel="stylesheet">
    <!-- ===== Color CSS ===== -->
    <link href="<?=Url::base();?>/cubic/css/colors/purple.css" id="theme" rel="stylesheet">

</head>
<style>
    .custom-class td, .custom-class th {
        font-size: 14px !important;
        padding: 6.5px !important;
    }
    .custom-class .form-control {
        font-size: 14px !important;
        padding: 6px !important;
        height: 36px !important;
    }
  
    h1 {
        font-size: 23px !important;
    }
    h2 {
        font-size: 20px !important;
    }
    h3 {
        font-size: 18px !important;
    }
    h4 {
        font-size: 16px !important;
    }
    h5 {
        font-size: 14px !important;
    }
    p {
        font-size: 14px !important;
    }
    .summary{
        font-size: 14px !important;
    
    }
</style>
<body class="mini-sidebar">
    <!-- ===== Main-Wrapper ===== -->
    <div id="wrapper">
        <div class="preloader">
            <div class="cssload-speeding-wheel"></div>
        </div>
        <!-- ===== Top-Navigation ===== -->
        <nav class="navbar navbar-dark navbar-static-top m-b-0" >
            <div class="navbar-header" >
                <a class="navbar-toggle font-20  hidden-sm hidden-md hidden-lg " href="javascript:void(0)" data-toggle="collapse" data-target=".navbar-collapse">
                    <i class="fa fa-bars"></i>
                </a>
                <div class="top-left-part">
                    <a class="logo" href="index.html">
                        <b>
                            TOP
                        </b>
                        <span>
                          <b style="color:white">Farm</b>
                        </span>
                    </a>

                </div>
                <ul class="nav navbar-top-links navbar-left hidden-xs" >
                    <li>
                        <a href="javascript:void(0)" class="sidebartoggler font-20 waves-effect waves-light"><i class="icon-arrow-left-circle"></i></a>
                    </li>

                </ul>

                    <ul class="nav navbar-top-links navbar-right pull-right">
                        <?php if(!Yii::$app->user->isGuest){?>
                        <li>
                            <a href="<?php echo Yii::$app->urlManager->createUrl(['site/logout']); ?>" data-method="post"><b style="font-size:17px; color:white">Logout (<?=Yii::$app->user->identity->username?>)&nbsp<i class="fa fa-power-off"></i></b></a>
                        </li>
                    <?php }else{?>
                    <li>
                            <a href="<?php echo Yii::$app->urlManager->createUrl(['site/login']); ?>"><b style="font-size:17px; color:white">Login &nbsp<i class="fa fa-sign-in"></i></b></a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </nav>
        <!-- ===== Top-Navigation-End ===== -->
        <!-- ===== Left-Sidebar ===== -->
        <aside class="sidebar" >
            <div class="scroll-sidebar">
                <div class="user-profile">
                    <div class="dropdown user-pro-body">

                        <p class="profile-text m-t-15 font-16"><a href="javascript:void(0);"> </a></p>
                    </div>
                </div>
                <nav class="sidebar-nav">
                    <ul id="side-menu">
                       
                        <li> <a href="<?=Url::base();?><?=$urlMenu?>/user/reset-account" aria-expanded="false"><i class="fa fa-key fa-fw"></i>
                            <span class="hide-menu"> Reset Password </span></a>
                        </li>
                        <li>
                            <a class="waves-effect" href="javascript:void(0);" aria-expanded="false"><i class="fa fa-user fa-fw"></i> <span class="hide-menu"> Akun
                                <span class="fa fa-caret-down pull-right"></span>
                            </span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="<?=Url::base();?><?=$urlMenu?>/user">User</a></li>
                                <li><a href="<?=Url::base();?><?=$urlMenu?>/auth-item/group">Group</a></li>
                                    <li><a href="<?=Url::base();?><?=$urlMenu?>/auth-item/permission">Master Permission</a></li>
                                    <li><a href="<?=Url::base();?><?=$urlMenu?>/auth-menu">Master Menu</a></li>
                                <li><a href="<?=Url::base();?><?=$urlMenu?>/auth-item-child">Group Access</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="<?=Url::base();?><?=$urlMenu?>/log-system" class="waves-effect"><i class="fa fa-file-text fa-fw"></i> <span class="hide-menu">
                                Log</span></a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>
        <!-- ===== Left-Sidebar-End ===== -->
        <!-- ===== Page-Content ===== -->
        <div class="page-wrapper">
            <!-- ===== Page-Container ===== -->
            <div class="container-fluid">

                <?= Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]) ?>
                <?= Alert::widget() ?>
                <?= $content ?>
            </div>
        </div>
        <!-- footer -->
    </div>


    <script src="<?=Url::base();?>/plugins/components/jquery/dist/jquery.min.js"></script>
    <script src="<?=Url::base();?>/cubic/js/jquery.slimscroll.js"></script>
    <!-- ===== Wave Effects JavaScript ===== -->
    <script src="<?=Url::base();?>/cubic/js/waves.js"></script>
    <!-- ===== Menu Plugin JavaScript ===== -->
    <script src="<?=Url::base();?>/cubic/js/sidebarmenu.js"></script>
    <!-- ===== Custom JavaScript ===== -->
    <script src="<?=Url::base();?>/cubic/js/custom.js"></script>
    
    <!-- ===== Plugin JS ===== -->
    <script src="<?=Url::base();?>/plugins/components/dropzone-master/dist/dropzone2.js"></script>
    <script src="<?=Url::base();?>/plugins/components/chartist-js/dist/chartist.min.js"></script>
    <script src="<?=Url::base();?>/plugins/components/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.min.js"></script>
    

    <script src="<?=Url::base();?>/plugins/components/styleswitcher/jQuery.style.switcher.js"></script>
    <script type="text/javascript" src="<?=Url::base();?>/webcam/js/filereader.js"></script>
    <script type="text/javascript" src="<?=Url::base();?>/webcam/js/main.js"></script>
    <script src="<?=Url::base();?>/plugins/components/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="<?=Url::base();?>/asset/js/select2.min.js"></script> 
    <script src="<?=Url::base();?>/plugins/components/switchery/dist/switchery.min.js"></script>
   
<?php $this->endBody() ?>  
</body>
</html>

<?php $this->endPage() ?>
