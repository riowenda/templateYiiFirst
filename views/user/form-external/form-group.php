<?php 
use yii\helpers\Url;
?>
<div>
    <label>Group Name <i style="color:red;">*</i></label>
    <input type="text" class="form-control" name="group_name" id="group_name" placeholder="Group Name" required>
    <br>
    <label>Description</label>
    <textarea class="form-control" name="group_description" id="group_description" placeholder="Description"></textarea>
    <br>
    <button type="button" class="btn btn-primary" id="submit-group">Save</button>
</div>
<?php 
$base = Url::base();
$js = <<<JS
    $(document).ready(function(){
        $('#submit-group').click(function(){
            var source = "user";
            var group_name = $('#group_name').val();
            var group_description = $('#group_description').val();
            $.ajax({
                url: '{$base}/index.php?r=auth-item/create-group-ajax',
                type: 'POST',
                data: {
                    source: source,
                    group_name: group_name,
                    group_description: group_description,
                },
                success: function(data){
                    if(data==3){
                        alert('Group Name is already exist!');
                    }else if(data!=0){
                        alert('Create Group Success!');
                        $('#groupNew').modal('hide');
                        $('#groupForm').append(data);
                    }else{
                        alert('Create Group Failed!');
                    }
                }
            });
        });
    });
JS;
$this->registerJs($js);
