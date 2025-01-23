<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Category;
use app\models\Pt;
use app\models\Location;

/* @var $this yii\web\View */
/* @var $searchModel app\models\LogInSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Dashboard';

$datasets = [];
$color[0] = 'rgb(54, 162, 235)';
$color[1] = 'rgb(54, 162, 235)';
$color[2] = 'rgb(75, 192, 192)';
$color[3] = 'rgb(255, 205, 86)';
$c = 0;
$list_category = Category::find()->where(['id'=>1])->orderBy(['name'=>'SORT_ASC'])->all();
$list_pt = Pt::find()->orderBy(['name'=>'SORT_ASC'])->all();

// $data = [];
// $data_pt = ['AYM','AGW','AKY'];
// $data[0]['name']="Fixed Asset";
// $data[1]['name']="Low Asset";
// $data[0]['data'] = [2,2,1];
// $data[1]['data'] = [4];
function getModel($cat_id){
    $condition = "";
    if(isset($_GET['search_all'])){
        $condition = "AND (pt.name LIKE '%".$_GET['search_all']."%' OR (
            SELECT id from category where name LIKE '%".$_GET['search_all']."%'))";
    }
    $model_grafik = Yii::$app->db->createCommand(
        "SELECT pt.name, COUNT(asset.id) AS total_asset FROM pt 
        LEFT JOIN asset ON pt.id = asset.pt_id AND asset.category_id = $cat_id GROUP BY pt.id ORDER BY pt.name;
        "
        )
        ->queryAll();
    
    return $model_grafik;
}
foreach($list_pt as $o=>$pt){
    $data_pt[] = $pt['name'];
}
foreach($list_category as $j=>$cat){
    $data[$j]['name']=$cat['name'];
    $data_raw[$j] = "";
        foreach(getModel($cat['id']) as $i=>$p){
                $data_raw[$j] .= $p['total_asset'];
                $data_raw[$j] .= ",";
                
        }
        $data_raw[$j] = substr($data_raw[$j], 0, -1);
        $data[$j]['data'] = explode(',',$data_raw[$j]);
}
$datasets = $data;

//location query
$list = [];
$jumlah = [];
$datasetsLoc = [];
$dataLoc = [];


$list_location = Location::find()->orderBy(['location'=>'SORT_ASC'])->all();
function getModelLoc($cat_id_loc){
    $model_grafik = Yii::$app->db->createCommand(
        "SELECT loc.location, IFNULL(count(a.id), 0) as total_asset
        FROM location loc
        LEFT JOIN asset a ON loc.id = a.location_id AND a.category_id = 1
        GROUP BY loc.location
        ORDER BY loc.location ASC
        "
        )
        ->queryAll();
    
    return $model_grafik;
}
foreach($list_location as $o=>$loc){
    $data_loc[] = preg_replace('/[.,]/', '-', $loc['location']);
}
    foreach($list_category as $j=>$cat){
        $dataLoc[$j]['name']=$cat['name'];
        $data_raw[$j] = "";
            foreach(getModelLoc($cat['id']) as $i=>$p){
                    $data_raw[$j] .= $p['total_asset'];
                    $data_raw[$j] .= ",";
                    
            }
            $data_raw[$j] = substr($data_raw[$j], 0, -1);
            $dataLoc[$j]['data'] = explode(',',$data_raw[$j]);
    }
 
        
   
$datasetsLoc = $dataLoc;
//print_r(getModel(1,2));
//print_r(json_encode($data));
?>
<style>
    body {
      background-color: #f2f2f2;
      font-family: Arial, sans-serif;
    }

    /* Style the heading */
    h1 {
      font-size: 48px;
      text-align: center;
      margin-bottom: 50px;
      color: #333333;
    }
</style>

<div class="row">
    <div class="col-md-6">
        <div class="white-box" style="margin-right:2px;">
            <h2>Asset By Company</h2>
            
            <?= \onmotion\apexcharts\ApexchartsWidget::widget([
                'type' => 'bar', // default area
                'height' => '450', // default 350
                'chartOptions' => [
                    'chart' => [
                        'toolbar' => [
                            'show' => true,
                            'autoSelected' => 'zoom'
                        ],
                    ],
                    'xaxis' => [
                            'categories' => $data_pt,
                    ],
                    'colors' => $color[3],
                    'plotOptions' => [
                        'bar' => [
                            'horizontal' => true,
                                'columnWidth' => '55%',
                            'endingShape' => 'rounded'
                        ],
                    ],
                    'dataLabels' => [
                        'enabled' => false
                    ],
                    'stroke' => [
                        'show' => true,
                        'colors' => ['transparent']
                    ],
                    'legend' => [
                        'verticalAlign' => 'bottom',
                        'horizontalAlign' => 'left',
                    ],
                ],
                    'series' => $datasets,
                ]);
                ?>
        </div>
    </div>

    <div class="col-md-6">
        <div class="white-box">
        <h2>Asset By Location</h2>
        <?= \onmotion\apexcharts\ApexchartsWidget::widget([
            'type' => 'bar', // default area
            'height' => '450', // default 350
            'chartOptions' => [
                'chart' => [
                    'toolbar' => [
                        'show' => true,
                        'autoSelected' => 'zoom'
                    ],
                ],
                'xaxis' => [
                        'categories' => $data_loc,
                ],
                'colors' => $color,
                'plotOptions' => [
                    'bar' => [
                        'horizontal' => true,
                            'columnWidth' => '55%',
                        'endingShape' => 'rounded'
                    ],
                ],
                'dataLabels' => [
                    'enabled' => false
                ],
                'stroke' => [
                    'show' => true,
                    'colors' => ['transparent']
                ],
                'legend' => [
                    'verticalAlign' => 'bottom',
                    'horizontalAlign' => 'left',
                ],
            ],
                'series' => $datasetsLoc,
            ]);
            ?>
            </div>
    </div>
    <div class="col-md-12 text-center" style="margin-bottom:3px;">
        <button class="btn btn-warning" onclick="window.location.href='<?= Url::to(['asset/index']) ?>'">View All Asset >></button>
    </div>
</div>


    
    
    