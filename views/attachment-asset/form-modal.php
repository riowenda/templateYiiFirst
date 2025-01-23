<?php 
use yii\helpers\Url;
use app\models\Pt;
?>
<link href="<?=Url::base();?>/plugins/components/dropzone-master/dist/dropzone.css" rel="stylesheet" type="text/css" />
<div class="modal fade" id="addAttachment" tabindex="-1" role="dialog" aria-labelledby="exampmyModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Form Upload Attachments</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="white-box">
                    <!-- <h3 class="box-title m-b-0"> </h3> -->
                    <p class="text-muted"><i>Noted : 
                        <ul>
                            <li>File Max Size : 5MB</li>
                            <li><i>Supported Type File : PDF & JPG/JPEG</i></li>
                        </ul>
                    </p>
                    <!-- yii form to custom url -->
                    <?php $form = \yii\widgets\ActiveForm::begin(['action' => ['attachment-asset/create'],
                    'options' => ['enctype' => 'multipart/form-data', 'class'=>'dropzone','id'=>'my-dropzone']
                    ]) ?>

                            <div class="fallback">

                            <input name="dokumen" type="file" multiple />
                            </div>
                            <input type="hidden" name="asset_id" id="asset_id" value="<?=$asset_id?>">
                    <?php \yii\widgets\ActiveForm::end() ?>

            </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" data-dismiss="modal" id="confirm-attachment">OK</button>
            </div>
            </div>
        </div>
    </div>
</div>
<?php 
$base = Url::base();
$script = <<< JS
    $('#confirm-attachment').click(function(){
        $.ajax({
            url: '{$base}/index.php?r=attachment-asset/show-file',
            type: 'post',
            data: {asset_id: $('#asset_id').val()},
            success: function(data){
                $("#file-add").html(data);
            }
        });
    });

    $(document).on('click', '.delete-attachment', function(){
        //add dialog confirm before delete
        dialog = confirm("Are you sure want to delete this file?");
        if(!dialog){
            return false;
        }

        var asset_id = $(this).attr('data-asset-id');
        var name = $(this).attr('data-name');
        $.ajax({
            url: '{$base}/index.php?r=attachment-asset/delete&name='+name+'&asset_id='+asset_id,
            type: 'post',
            data: {name: name, asset_id: asset_id},
            success: function(data){
                if(data){
                    alert(data);
                    $.ajax({
                        url: '{$base}/index.php?r=attachment-asset/show-file',
                        type: 'post',
                        data: {asset_id: $('#asset_id').val()},
                        success: function(data){
                            $("#file-add").html(data);
                        }
                    });
                }
                
            }
        });
    });

    //download file
    $(document).on('click', '.download-attachment', function(){
        var asset_id = $(this).attr('data-asset-id');
        var name = $(this).attr('data-name');
        $.ajax({
            url: '{$base}/index.php?r=attachment-asset/download&name='+name+'&asset_id='+asset_id,
            type: 'post',
            data: {name: name, asset_id: asset_id},
            success: function(data){
                return data;
                
            }
        });
    });

    Dropzone.options.dzattachment= {
    maxFilesize: 500,
  }
    
JS;
$this->registerJs($script);

