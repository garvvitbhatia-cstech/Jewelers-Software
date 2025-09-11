<?php
#set page meta content
$this->assign('title', SITE_TITLE.' :: Edit Distance');
$this->assign('meta_robot', 'noindex, nofollow');
?>
<!--  page-wrapper -->
<div id="page-wrapper">
    <div class="row">
        <!-- page header -->
        <div class="col-lg-12">
            <h1 class="page-header">Edit Distance</h1>
        </div>
        <!--end page header -->
    </div>
    <div class="row">
        <div class="col-lg-12">
            <a href="<?php e($this->Url->build(ADMIN_FOLDER.'distance-management'.'/'));?>" class="btn btn-info">Back To Listing</a><br />&nbsp;
        </div>
        <div class="col-lg-12">
            <!-- Form Elements -->
            <?php e($this->Flash->render()); ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    Edit distance information
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <?= $this->Form->create(NULL,array('id' => 'addForm', 'type' => 'file', 'inputDefaults' => array('label' => false,'div' => false), 'name' => 'addForm', 'csrfToken' => $this->request->getAttribute('csrfToken')));?>
                            <input type="hidden" name="edit_token" id="edit_token" value="<?php e($this->encryptData($editData->id))?>">
                            <div class="form-group">
                                <label>Type:<span class="mandatory_field">*</span></label>
                                <select name="weight_type_id" id="weight_type_id" onchange="getWeightByType(this.value); checkError(this.id);" confirmation="false" class="form-control">
                                   <?php if(isset($weightTypeList) && !empty($weightTypeList)){ ?>
                                        <?php foreach($weightTypeList as $key => $val): ?>
                                            <option <?php if($editData->weight_type_id == $key){e('selected');} ?> value="<?php e($key); ?>"><?php e($val); ?></option>
                                        <?php endforeach; ?>
                                    <?php } ?>
                                </select>
                                <span id="weight_type_idError" class="admin_login_error"></span> 
                            </div>

                         <div class="form-group">
                                <?php  $weightList = $this->Weight->getWeightById($editData->weight_id); ?>
                                <label>Weight:<span class="mandatory_field">*</span></label>

                                <select id="weight_id" name="weight_id" class="form-control" onchange="checkError(this.id);">

                                  <?php foreach($weightList as $key => $val): ?>
                                       <option <?php if($editData->weight_id == $key){e('selected');} ?> value="<?php e($key); ?>"><?php e($val); ?></option>
                                 <?php endforeach; ?>
                             </select>
                                <span id="weight_idError" class="admin_login_error"></span>
                            </div> 
                            
                            <div class="form-group">                            
                                <label>Distance From:<span class="mandatory_field">*</span></label>
                                <input type="text" value="<?php e($editData->dist_from); ?>" id="dist_from" name="dist_from" onkeyup="checkError(this.id);" confirmation="false" class="form-control">
                                <span id="dist_fromError" class="admin_login_error"></span>
                            </div>  
                            <div class="form-group">                            
                                <label>Distance To:<span class="mandatory_field">*</span></label>
                                <input type="text" id="dist_to" name="dist_to" value="<?php e($editData->dist_to); ?>" onkeyup="checkError(this.id);" confirmation="false" class="form-control">
                                <span id="dist_toError" class="admin_login_error"></span>
                            </div>  
                            <div class="form-group">                            
                                <label>Price:<span class="mandatory_field">*</span></label>
                                <input type="text" id="price" name="price" value="<?php e($editData->price); ?>" onkeyup="checkError(this.id);" confirmation="false" class="form-control">
                                <span id="priceError" class="admin_login_error"></span>
                            </div> 
                            <div class="form-group">
                                <label>Status</label>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" <?php if($editData->status == 1){e('checked');} ?> value="1" name="status">Active
                                    </label>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary submitBtn" id="submitBtn">Submit</button>
                            <?= $this->Form->end(); ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Form Elements -->
            <a href="<?php e($this->Url->build(ADMIN_FOLDER.'distance-management'.'/'));?>" class="btn btn-info">Back To Listing</a>
        </div>
    </div>
</div>

<script type="text/javascript">

     function getWeightByType(weightId){
      
        if(weightId != '' ){
            $.ajax({
                type: 'POST',
                url: '<?php e($this->Url->build('/ajax/getWeight/'));?>',
                data: {weightId:weightId},
                success: function(response){
                    $('#weight_id').html(response);
                }
            }); 
            return false;
        }
    }

$(document).ready(function(e){
	$('#price').filter_input({regex:'[0-9]'});
    $('#dist_from').filter_input({regex:'[0-9]'});
    $('#dist_to').filter_input({regex:'[0-9]'});

 /*get weight by selected weight type*/
   
    var frmSubmitted = 0;
    $('.submitBtn').click(function(){
        var flag = 0;
        if(frmSubmitted == 0){
            if($.trim($('#weight_type_id').val()) == ""){
                $('#weight_type_idError').show().html('Please select weight type.').slideDown();
                $('#weight_type_id').focus();
                frmSubmitted = 0;
                flag = 1; return false;
            }
            if($.trim($('#weight_id').val()) == ""){
                $('#weight_idError').show().html('Please select weight.').slideDown();
                $('#weight_id').focus();
                frmSubmitted = 0;
                flag = 1; return false;
            }
			if($.trim($('#dist_from').val()) == ""){
                $('#dist_fromError').show().html('Please enter distance from.').slideDown();
                $('#dist_from').focus();
                frmSubmitted = 0;
                flag = 1; return false;
            }
			if($.trim($('#dist_to').val()) == ""){
                $('#dist_toError').show().html('Please enter distance to.').slideDown();
                $('#dist_to').focus();
                frmSubmitted = 0;
                flag = 1; return false;
           }
			if($.trim($('#price').val()) == ""){
                $('#priceError').show().html('Please enter price.').slideDown();
                $('#price').focus();
                frmSubmitted = 0;
                flag = 1; return false;
            }
            if(flag == 0){
                $('.submitBtn').html('Processing...');
                $('#addForm').submit();
                frmSubmitted = 1;
                return true;
            }
        }else{
            return false;
        }
    });
});
</script>