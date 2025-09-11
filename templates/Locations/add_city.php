<?php
#set page meta content
$this->assign('title', SITE_TITLE.' :: Add City');
$this->assign('meta_robot', 'noindex, nofollow');
?>
<!--  page-wrapper -->
<div id="page-wrapper">
    <div class="row">
        <!-- page header -->
        <div class="col-lg-12">
            <h1 class="page-header">Add City Details</h1>
        </div>
        <!--end page header -->
    </div>
    <div class="row">
        <div class="col-lg-12">
            <a href="<?php e($this->Url->build(ADMIN_FOLDER.'city-management'.'/'));?>" class="btn btn-info">Back To Listing</a><br />&nbsp;
        </div>
        <div class="col-lg-12">
            <!-- Form Elements -->
            <?php e($this->Flash->render()); ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    Add city information
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <?= $this->Form->create(NULL,array('id' => 'addForm', 'type' => 'file', 'inputDefaults' => array('label' => false,'div' => false), 'name' => 'addForm', 'csrfToken' => $this->request->getAttribute('csrfToken')));?>
                            <div class="form-group">
                                <label>Country Name:</label>                                
                                <select id="country_id" name="country_id" onchange="getStateByCountry(this.value); checkError(this.id);" class="form-control">
                                <option value="">Select Country</option>
								<?php if(isset($countryList) && !empty($countryList)){ ?>
                                    <?php foreach($countryList as $key => $val): ?>
                                        <option value="<?php e($key); ?>"><?php e($val); ?></option>
                                    <?php endforeach; ?>
                                <?php } ?>
								</select>
                                <span id="country_idError" class="admin_login_error"></span>
                            </div>
                            <div class="form-group">
                                <label>State:</label>
                                <select id="state_id" name="state_id" class="form-control" onchange="checkError(this.id);">
                                	<option value="">Select State</option>
                                </select>
                                <span id="state_idError" class="admin_login_error"></span>
                            </div>
                            <div class="form-group">
                                <label>City Name</label>
                                <input type="text" name="city" id="city"  onkeyup="checkError(this.id);" class="form-control" /> 
                                <span id="cityError" class="admin_login_error"></span>
                            </div>
                            <div class="form-group">
                                <label>Status</label>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" checked="checked" value="1" name="status">Active
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
            <a href="<?php e($this->Url->build(ADMIN_FOLDER.'state-management'.'/'));?>" class="btn btn-info">Back To Listing</a>
        </div>
    </div>
</div>

<script type="text/javascript">
	/*************State List***************/
	function getStateByCountry(countryId){
		if(countryId != '' && $.isNumeric(countryId)){
			$.ajax({
				type: 'POST',
				url: '<?php e($this->Url->build('/ajax/getState/'));?>',
				data: {countryId:countryId},
				success: function(response){
					$('#state_id').html(response);
				}
			});	
			return false;
		}
	}
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
			if($.trim($('#state_id').val()) == ""){
                $('#state_idError').show().html('Please select state name.').slideDown();
                $('#state_id').focus();
                frmSubmitted = 0;
                flag = 1; return false;
            }
			if($.trim($('#city').val()) == ""){
                $('#cityError').show().html('Please enter city name.').slideDown();
                $('#city').focus();
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
</script>