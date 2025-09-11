 <?php
#set page meta content
$this->assign('title', SITE_TITLE.' :: Edit Weight');
$this->assign('meta_robot', 'noindex, nofollow');
?>
<!--  page-wrapper -->
<div id="page-wrapper">
    <div class="row">
        <!-- page header -->
        <div class="col-lg-12">
            <h1 class="page-header">Edit Weight</h1>
        </div>
        <!--end page header -->
    </div>
    <div class="row">
        <div class="col-lg-12">
            <a href="<?php e($this->Url->build(ADMIN_FOLDER.'weight-management'.'/'));?>" class="btn btn-info">Back To Listing</a><br />&nbsp;
        </div>
        <div class="col-lg-12">
            <!-- Form Elements -->
            <?php e($this->Flash->render()); ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    Update weight information
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <?= $this->Form->create(NULL,array('id' => 'editForm', 'type' => 'file', 'inputDefaults' => array('label' => false,'div' => false), 'name' => 'editForm', 'csrfToken' => $this->request->getAttribute('csrfToken')));?>
                            <input type="hidden" name="edit_token" id="edit_token" value="<?php e($this->encryptData($editData->id))?>"> 
                            <div class="form-group">
                                <label>Type:<span class="mandatory_field">*</span></label>
                                <select name="weight_type_id" id="weight_type_id" onchange="checkError(this.id);" confirmation="false" class="form-control">
                                   <?php if(isset($weightTypeList) && !empty($weightTypeList)){ ?>
                                        <?php foreach($weightTypeList as $key => $val): ?>
                                            <option <?php if($editData->weight_type_id == $key){e('selected');} ?> value="<?php e($key); ?>"><?php e($val); ?></option>
                                        <?php endforeach; ?>
                                    <?php } ?>
                                </select>
                                <span id="weight_type_idError" class="admin_login_error"></span> 
                            </div>
                            <div class="form-group">
                                <label>Weight:<span class="mandatory_field">*</span></label>
                                <input type="text" id="weight" name="weight" value="<?php e($editData->weight);?>" onkeyup="checkError(this.id);" confirmation="false" class="form-control">
                                <span id="weightError" class="admin_login_error"></span>
                            </div>
                            
                            <div class="form-group">
                                <label>Status</label>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" <?php if($editData->status == 1){e('checked'); }?> value="1" name="status">Active
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
            <a href="<?php e($this->Url->build(ADMIN_FOLDER.'weight-management'.'/'));?>" class="btn btn-info">Back To Listing</a>
        </div>
    </div>
</div>

<script type="text/javascript">
	
$(document).ready(function(e) {
   $('#weight').filter_input({regex:'[0-9]'});
    
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
            if($.trim($('#weight').val()) == ""){
                $('#weightError').show().html('Please enter weight.').slideDown();
                $('#weight').focus();
                frmSubmitted = 0;
                flag = 1; return false;
            }

            if(flag == 0){
                $('.submitBtn').html('Processing...');
                $('#editForm').submit();
                frmSubmitted = 1;
                return true;
            }
        }else{
            return false;
        }
    });
});
</script>