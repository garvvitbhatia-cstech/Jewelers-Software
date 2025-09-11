<?php
#set page meta content
$this->assign('title', SITE_TITLE.' :: Edit Sub Admin');
$this->assign('meta_robot', 'noindex, nofollow');
?>
<!--  page-wrapper -->
<div id="page-wrapper">
    <div class="row">
        <!-- page header -->
        <div class="col-lg-12">
            <h1 class="page-header">Edit Sub Admin Details</h1>
        </div>
        <!--end page header -->
    </div>
    <div class="row">
        <div class="col-lg-12">
            <a href="<?php e($this->Url->build(ADMIN_FOLDER.'agent-management'.'/'));?>" class="btn btn-info">Back To Listing</a><br />&nbsp;
        </div>
        <div class="col-lg-12">
            <!-- Form Elements -->
            <?php e($this->Flash->render()); ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    Update sub-admin information
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <?= $this->Form->create(NULL,array('id' => 'editForm', 'type' => 'file', 'inputDefaults' => array('label' => false,'div' => false), 'name' => 'editForm', 'csrfToken' => $this->request->getAttribute('csrfToken')));?>
                            <input type="hidden" name="edit_token" id="edit_token" value="<?php e($this->encryptData($editData->id))?>">
                            <div class="form-group">
                                <label>Unique ID:</label>
                                <input type="text" readonly="readonly" value="<?php e($editData->unique_id);?>" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Username:</label>
                                <input type="text" readonly="readonly" value="<?php e($this->decryptData($editData->username));?>" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>First Name:</label>
                                <input type="text" id="first_name" name="first_name" value="<?php e($editData->first_name);?>" onkeyup="checkError(this.id);" confirmation="false" class="form-control">
                                <span id="first_nameError" class="admin_login_error"></span>
                            </div>
                             <div class="form-group">
                                <label>Last Name:</label>
                                <input type="text" id="last_name" name="last_name" value="<?php e($editData->last_name);?>" onkeyup="checkError(this.id);" confirmation="false" class="form-control">
                                <span id="last_nameError" class="admin_login_error"></span>
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="text" id="email" name="email" value="<?php e($this->decryptData($editData->email));?>" onkeyup="checkError(this.id);" confirmation="false" class="form-control">
                                <span id="emailError" class="admin_login_error"></span>
                            </div>
                            <div class="form-group">
                                <label>Password:</label>
                                <input type="text" id="password" name="password" onkeyup="checkError(this.id);" value="<?php e($this->decryptData($editData->password));?>" confirmation="false" class="form-control">
                                <span id="passwordError" class="admin_login_error"></span>
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
            <a href="<?php e($this->Url->build(ADMIN_FOLDER.'agent-management'.'/'));?>" class="btn btn-info">Back To Listing</a>
        </div>
    </div>
</div>

<script type="text/javascript">  
$(document).ready(function(e) {
	$('#contact').filter_input({regex:'[0-9+ -]'});
   
    var frmSubmitted = 0;
    $('.submitBtn').click(function(){
        var flag = 0;
        if(frmSubmitted == 0){
           if($.trim($('#first_name').val()) == ""){
                $('#first_nameError').show().html('Please enter first name.').slideDown();
                $('#first_name').focus();
                frmSubmitted = 0;
                flag = 1; return false;
            }
			if($.trim($('#last_name').val()) == ""){
                $('#last_nameError').show().html('Please enter last name.').slideDown();
                $('#last_name').focus();
                frmSubmitted = 0;
                flag = 1; return false;
            }
            if($.trim($('#email').val()) == ""){
                $('#emailError').show().html('Please enter email address.').slideDown();
                $('#email').focus();
                frmSubmitted = 0;
                flag = 1; return false;
            }else if($.trim($('#email').val()) != ""){
				var filter = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
				if(!filter.test($('#email').val())){
					$('#emailError').show().html('Please enter valid email address.').slideDown();
					$('#email').focus();
					frmSubmitted = 0;
					flag = 1; return false;					
				}	
			}
			if($.trim($('#password').val()) == ""){
                $('#passwordError').show().html('Please enter password.').slideDown();
                $('#password').focus();
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