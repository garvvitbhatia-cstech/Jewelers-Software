<?php
	#set page meta content
	$this->assign('title', SITE_TITLE.' :: Add Product');
	$this->assign('meta_robot', 'noindex, nofollow');
?>
<!--  page-wrapper -->
<div id="page-wrapper">
    <div class="row">
        <!-- page header -->
        <div class="col-lg-12">
            <h1 class="page-header">Add Product</h1>
        </div>
        <!--end page header -->
    </div>
    <div class="row">
        <div class="col-lg-12">
            <a href="<?php e($this->Url->build(ADMIN_FOLDER.'products'.'/'));?>" class="btn btn-info">Back To Listing</a><br />&nbsp;
        </div>
        <div class="col-lg-12">
            <!-- Form Elements -->
            <?php e($this->Flash->render()); ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    Add product information
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <?= $this->Form->create(NULL,array('id' => 'addForm', 'type' => 'file', 'inputDefaults' => array('label' => false,'div' => false), 'name' => 'addForm', 'csrfToken' => $this->request->getAttribute('csrfToken')));?>
                            <div class="form-group">                            
                                <label>Party Name</label>
                                <input type="text" id="party_name" name="party_name" onkeyup="checkError(this.id);" confirmation="false" class="form-control">
                                <span id="party_nameError" class="admin_login_error"></span>
                            </div> 
                            <div class="form-group">                            
                                <label>Party Phone</label>
                                <input type="tel" id="party_phone" name="party_phone" onkeyup="checkError(this.id);" confirmation="false" class="form-control">
                                <span id="party_phoneError" class="admin_login_error"></span>
                            </div>                       
                            <div class="form-group">
                                <label>Category</label>
                                <select name="category_id" id="category_id" onchange="checkError(this.id);" confirmation="false" class="form-control">                                	<option value="2">Root</option>
                                    <?php if(isset($categoryList) && !empty($categoryList)){?>
                                    	<?php foreach($categoryList as $key => $category): ?>
                                        	<option value="<?php e($key); ?>"><?php e($category); ?></option>
                                        <?php endforeach; ?>
                                    <?php } ?>
                                </select>
                                <span id="category_idError" class="admin_login_error"></span>
                            </div>
                            <div class="form-group">                    
                                <label>Type</label>
                                <select id="type" name="type" onkeyup="checkError(this.id);" confirmation="false" class="form-control">
                                	<option value="Gold">Gold</option>
                                    <option value="Silver">Silver</option>
                                </select>
                                <span id="typeError" class="admin_login_error"></span>
                            </div>
                            <div class="form-group">                            
                                <label>Product Name</label>
                                <input type="text" id="product_name" name="product_name" onkeyup="checkError(this.id);" confirmation="false" class="form-control">
                                <span id="product_nameError" class="admin_login_error"></span>
                            </div>
                            <div class="form-group">                            
                                <label>Purity</label>
                                <select id="purity" name="purity" onkeyup="checkError(this.id);" confirmation="false" class="form-control">
                                	<option value="14 K">14 K</option>
                                    <option value="18 K">18 K</option>
                                    <option value="22 K">22 K</option>
                                    <option value="24 K">24 K</option>
                                </select>
                                <span id="purityError" class="admin_login_error"></span>
                            </div>
                            <div class="form-group">                            
                                <label>Diamond/Stone Weight</label>
                                <input type="tel" id="diam_stone_wgt" name="diam_stone_wgt" onkeyup="checkError(this.id);" value="0" confirmation="false" class="form-control">
                                <span id="diam_stone_wgtError" class="admin_login_error"></span>
                            </div> 
                            <div class="form-group">                            
                                <label>Tunch</label>
                                <input type="tel" id="tunch" name="tunch" onkeyup="checkError(this.id);" value="0" confirmation="false" class="form-control">
                                <span id="tunchError" class="admin_login_error"></span>
                            </div> 
                            <div class="form-group">                            
                                <label>Wastage</label>
                                <input type="tel" id="wstg" name="wstg" onkeyup="checkError(this.id);" value="0" confirmation="false" class="form-control">
                                <span id="wstgError" class="admin_login_error"></span>
                            </div> 
                            <div class="form-group">                            
                                <label>Product Price</label>
                                <input type="tel" id="price" name="price" onkeyup="checkError(this.id);" maxlength="9" value="0" confirmation="false" class="form-control">
                                <span id="priceError" class="admin_login_error"></span>
                            </div> 
                            <div class="form-group">                            
                                <label>Gross Weight</label>
                                <input type="tel" id="gross_weight" name="gross_weight" onkeyup="checkError(this.id);" maxlength="9" value="0" confirmation="false" class="form-control">
                                <span id="gross_weightError" class="admin_login_error"></span>
                            </div>  
                            <div class="form-group">                            
                                <label>Net Weight</label>
                                <input type="tel" id="net_weight" name="net_weight" onkeyup="checkError(this.id);" maxlength="9" value="0" confirmation="false" class="form-control">
                                <span id="net_weightError" class="admin_login_error"></span>
                            </div> 
                            <div class="form-group">                            
                                <label>Worker Name</label>
                                <input type="text" id="worker_name" name="worker_name" onkeyup="checkError(this.id);" confirmation="false" class="form-control">
                                <span id="worker_nameError" class="admin_login_error"></span>
                            </div> 
                            <div class="form-group">                            
                                <label>Percentage</label>
                                <input type="text" id="percentage" name="percentage" onkeyup="checkError(this.id);" confirmation="false" class="form-control">
                                <span id="percentageError" class="admin_login_error"></span>
                            </div> 
                            <div class="form-group">                            
                                <label>Quantity</label>
                                <input type="tel" id="qty" name="qty" onkeyup="checkError(this.id);" confirmation="false" maxlength="6" value="0" class="form-control">
                                <span id="qtyError" class="admin_login_error"></span>
                            </div> 
                            <div class="form-group">                            
                                <label>HUID Code</label>
                                <input type="text" id="huid_code" name="huid_code" onkeyup="checkError(this.id);" confirmation="false" class="form-control">
                                <span id="huid_codeError" class="admin_login_error"></span>
                            </div> 
                            <div class="form-group">                            
                                <label>Tag Name</label>
                                <input type="text" id="tag_name" name="tag_name" onkeyup="checkError(this.id);" confirmation="false" class="form-control">
                                <span id="tag_nameError" class="admin_login_error"></span>
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
            <a href="<?php e($this->Url->build(ADMIN_FOLDER.'products'.'/'));?>" class="btn btn-info">Back To Listing</a>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function(e){
	
	$('#gross_weight').filter_input({regex:'[0-9.]'});
	$('#net_weight').filter_input({regex:'[0-9.]'});
	$('#qty').filter_input({regex:'[0-9]'});
	$('#price').filter_input({regex:'[0-9.]'});
	$('#party_name').filter_input({regex:'[A-Z a-z]'});
	$('#party_phone').filter_input({regex:'[0-9]'});
	
    var frmSubmitted = 0;
    $('.submitBtn').click(function(){
        var flag = 0;
        if(frmSubmitted == 0){
			if($.trim($('#party_name').val()) == ""){
                $('#party_nameError').show().html('Please enter party name.').slideDown();
                $('#party_name').focus();
                frmSubmitted = 0;
                flag = 1; return false;
            }
			if($.trim($('#party_phone').val()) == ""){
                $('#party_phoneError').show().html('Please enter party phone.').slideDown();
                $('#party_phone').focus();
                frmSubmitted = 0;
                flag = 1; return false;
            }
			if($.trim($('#product_name').val()) == ""){
                $('#product_nameError').show().html('Please enter product name.').slideDown();
                $('#product_name').focus();
                frmSubmitted = 0;
                flag = 1; return false;
            }			
			if($.trim($('#gross_weight').val()) == ""){
                $('#gross_weightError').show().html('Please enter gross weight.').slideDown();
                $('#gross_weight').focus();
                frmSubmitted = 0;
                flag = 1; return false;
            }
			if($.trim($('#net_weight').val()) == ""){
                $('#net_weightError').show().html('Please enter net weight.').slideDown();
                $('#net_weight').focus();
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