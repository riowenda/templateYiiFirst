<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\helpers\Url;

AppAsset::register($this);
$urlIndex = "/index.php?r=site%2Findex";
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="en">
<?= Html::csrfMetaTags() ?>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Sinar Group Web </title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

        <!-- Fonts -->
        <!-- Lato -->

        <!-- CSS -->

        <link rel="stylesheet" href="asset/css/bootstrap.min.css">
        <link rel="stylesheet" href="asset/css/font-awesome.min.css">
        <link rel="stylesheet" href="asset/css/animate.css">
        <link rel="stylesheet" href="asset/css/main.css">
        <!-- Responsive Stylesheet -->
        <link rel="stylesheet" href="asset/css/responsive.css">
    </head>
    <style type="text/css">
        .logo{
            color: white;
            font-size:30px;
        }
        body{
            color:white;
        }
    </style>
    <body id="body">

    	<div id="preloader">
    		<div class="book">
    		  <div class="book__page"></div>
    		  <div class="book__page"></div>
    		  <div class="book__page"></div>
    		</div>
    	</div>
        <div class="navbar-default navbar-fixed-top" id="navigation">
            <div class="container">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">
                        <i class="logo">Sinar Group</i>
                    </a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <nav class="collapse navbar-collapse" id="navbar">
                    <ul class="nav navbar-nav navbar-right" id="top-nav">
                        <li class="current"><a href="http://192.168.4.111">Home</a></li>
                    </ul>
                </nav><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </div>
        <section id="hero-area">
            <div class="page-wrapper">
                <!-- ===== Page-Container ===== -->
                <div class="container">
                    <?= Breadcrumbs::widget([
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    ]) ?>
                    <?= Alert::widget() ?>
                    <?= $content ?>
                </div>
            </div>
        </section>

        <footer>
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="block">
                            <p>Copyright &copy; <a href="#">Sinar Group</a>| All right reserved.</p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>


        <!-- Js -->
        <script src="asset/js/vendor/jquery-1.10.2.min.js"></script>
        <script src="asset/js/main.js"></script>

    </body>
</html>
