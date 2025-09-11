<?php
#set page meta content
$this->assign('title', SITE_TITLE.' :: Edit State');
$this->assign('meta_robot', 'noindex, nofollow');
?>
<!--  page-wrapper -->
<div id="page-wrapper">
    <div class="row">
        <!-- page header -->
        <div class="col-lg-12">
            <h1 class="page-header">Edit State Details</h1>
        </div>
        <!--end page header -->
    </div>
    <div class="row">
        <div class="col-lg-12">
            <a href="<?php e($this->Url->build(ADMIN_FOLDER.'state-management'.'/'));?>" class="btn btn-info">Back To Listing</a><br />&nbsp;
        </div>
        <div class="col-lg-12">
            <!-- Form Elements -->
            <?php e($this->Flash->render()); ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    Update state information
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <?= $this->Form->create(NULL,array('id' => 'editForm', 'type' => 'file', 'inputDefaults' => array('label' => false,'div' => false), 'name' => 'editForm', 'csrfToken' => $this->request->getAttribute('csrfToken')));?>
                            <input type="hidden" name="edit_token" id="edit_token" value="<?php e($this->encryptData($editData->id))?>">
                            <div class="form-group">
                                <label>Country Name:</label>                                
                                <select id="country_id" name="country_id" class="form-control" onchange="checkError(this.id);">
                                	<?php if(isset($countryList) && !empty($countryList)){ ?>
                                    	<?php foreach($countryList as $key => $val): ?>
                                        	<option <?php if($editData->country_id == $key){e('selected');} ?> value="<?php e($key); ?>"><?php e($val); ?></option>
                                        <?php endforeach; ?>
                                    <?php } ?>
									</select>
                                <span id="country_idError" class="admin_login_error"></span>
                            </div>
                            <div class="form-group">
                                <label>State:</label>
                                <input type="text" id="state" name="state" onkeyup="checkError(this.id);" value="<?php e($editData->state); ?>" confirmation="false" class="form-control">
                                <span id="stateError" class="admin_login_error"></span>
                            </div>
                            <div class="form-group">
                                <label>Abbreviation</label>
                                <input type="text" name="abbreviation" id="abbreviation" value="<?php e($editData->abbreviation); ?>" onkeyup="checkError(this.id);" class="form-control" /> 
                                <span id="abbreviationError" class="admin_login_error"></span>
                            </div>
                            <?php
								$isExistCity = $this->State->getCityExist($editData->id);
								if($isExistCity > 0){
							?>
                            <input type="hidden" name="status" value="1"/>
                            <?php }else{ ?>
                            <div class="form-group">
                                <label>Status</label>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" <?php if($editData->status == 1){e('checked'); }?> value="1" name="status">Active
                                    </label>
                                </div>
                            </div>
                            <?php } ?>
                            <button type="button" class="btn btn-primary submitBtn" id="submitBtn">Submit</button>
                            <?= $this->Form->end(); ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Form Elements -->
            <a href="<?php e($this->Url->build(ADMIN_FOLDER.'country-management'.'/'));?>" class="btn btn-info">Back To Listing</a>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function(e) {
	$('#zipcode_format0').filter_input({regex:'[# ]'});
	$('#phone_no_format').filter_input({regex:'[()# -]'});
   
    var frmSubmitted = 0;
    $('.submitBtn').click(function(){
        var flag = 0;
        if(frmSubmitted == 0){
            if($.trim($('#country_id').val()) == ""){
                $('#country_idError').show().html('Please select country name.').slideDown();
                $('#country_id').focus();
                frmSubmitted = 0;
                flag = 1; return false;
            }
            if($.trim($('#state').val()) == ""){
                $('#stateError').show().html('Please enter state name.').slideDown();
                $('#state').focus();
                frmSubmitted = 0;
                flag = 1; return false;
            }
			if($.trim($('#abbreviation').val()) == ""){
                $('#abbreviationError').show().html('Please enter state abbreviation.').slideDown();
                $('#abbreviation').focus();
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