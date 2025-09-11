<?php
	#set page meta content
	$this->assign('title', SITE_TITLE.' :: Edit Order');
	$this->assign('meta_robot', 'noindex, nofollow');
	e($this->Element('/admin/jQuery'));
?>
<?= $this->Html->css(array('/css/jquery-ui'));?>
<?= $this->Html->script('/js/jquery-ui');?>
<!--  page-wrapper -->
<div id="page-wrapper">
    <div class="row">
        <!-- page header -->
        <div class="col-lg-12">
            <h1 class="page-header">Edit Order</h1>
        </div>
        <!--end page header -->
    </div>
    <div class="row">
        <div class="col-lg-12">
            <a href="<?php e($this->Url->build(ADMIN_FOLDER.'order-manager/'));?>" class="btn btn-info">Back To Listing</a><br />&nbsp;
        </div>
        <div class="col-lg-12">
            <!-- Form Elements -->
            <?php e($this->Flash->render()); ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    Edit order information
                </div>
                <div class="panel-body">
                    <div class="row">                        
                    	<?= $this->Form->create(NULL,array('id' => 'addForm', 'type' => 'file', 'inputDefaults' => array('label' => false,'div' => false), 'name' => 'addForm', 'csrfToken' => $this->request->getAttribute('csrfToken')));?>                            
                            <div class="col-lg-12 col-sm-12 col-xs-12 col-sm-12">
                            <input type="hidden" name="edit_token" id="edit_token" value="<?php e($this->encryptData($editData->id))?>">
                            <div class="form-group">                            
                                <label>Phone Number</label>
                                <input type="tel" id="customer_contact" name="customer_contact" onkeyup="checkError(this.id);" maxlength="10" confirmation="false" class="form-control" value="<?php e($editData->customer_contact)?>">
                                <span id="customer_contactError" class="admin_login_error"></span>
                            </div>
                            <div class="form-group">
                                <label>Party Name</label>
                                <input type="text" id="customer_name" name="customer_name" onkeyup="checkError(this.id);" confirmation="false" value="<?php e($editData->customer_name)?>" class="form-control">
                                <span id="customer_nameError" class="admin_login_error"></span>
                                <input type="hidden" name="customer_id" id="customer_id"/>
                            </div>
                            <div class="form-group">                            
                                <label>Address</label>
								<input type="text" id="customer_address" name="customer_address" value="<?php e($editData->customer_address)?>" onkeyup="checkError(this.id);" confirmation="false" class="form-control">
                                <span id="customer_addressError" class="admin_login_error"></span>
                            </div>                                                       
                            <div class="form-group">
                                <label>Approx. Delivery Date</label>
                                <input type="text" id="delivery_date" name="delivery_date" value="<?php e($editData->delivery_date)?>" onclick="checkError(this.id);" autocomplete="off" confirmation="false" class="form-control">
                                <span id="delivery_dateError" class="admin_login_error"></span>
                            </div>
                            
                            <div class="col-lg-4 col-sm-4 col-xs-4 col-sm-4">
                            	<div class="form-group">
                            		<label>Return Gold (in grams)</label>                                
                                	<input type="tel" id="return_gold" name="return_gold" placeholder="Return Gold (in grams)" maxlength="9" value="<?php e($editData->return_gold)?>" onkeyup="checkError(this.id);" confirmation="false" class="form-control">
                                	<span id="return_goldError" class="admin_login_error"></span>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-4 col-xs-4 col-sm-4">
                            	<div class="form-group">
                            		<label>Percentage</label>                                
                                	<input type="tel" id="percentage" name="percentage" placeholder="Percentage" maxlength="2" value="<?php e($editData->percentage)?>" onclick="checkError(this.id);" autocomplete="off" confirmation="false" class="form-control">
                                	<span id="percentageError" class="admin_login_error"></span>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-4 col-xs-4 col-sm-4">
                            	<div class="form-group">
                            		<label>Gold Amount</label>                                
                                	<input type="tel" id="return_gold_calc" name="return_gold_amt" value="<?php e($editData->return_gold_amt)?>" placeholder="Amount" class="form-control"/>
                                	<span id="return_gold_amtError" class="admin_login_error"></span>
                                </div>
                            </div>
                                                 
                            <div class="form-group">                            
                                <label>Return Silver (in grams)</label>
                                <span id="return_silver_calc"></span>
								<input type="tel" id="return_silver" name="return_silver" maxlength="9" value="<?php e($editData->return_silver)?>" onkeyup="checkError(this.id);" confirmation="false" class="form-control">
                                <span id="return_silverError" class="admin_login_error"></span>
                            </div>  
                            <div class="form-group">                            
                                <label>Advance Amount</label>
								<input type="tel" id="advance_amt" name="advance_amt" value="<?php e($editData->advance_amt)?>" onkeyup="checkError(this.id);" value="0" confirmation="false" class="form-control">
                                <span id="advance_amtError" class="admin_login_error"></span>
                            </div> 
                            <div class="form-group">                            
                                <label>Remarks</label>
                                <textarea id="remarks" name="remarks" onkeyup="checkError(this.id);" rows="5" confirmation="false" class="form-control"><?php e($editData->remarks)?></textarea>
                                <span id="remarksError" class="admin_login_error"></span>
                            </div>   
                            </div>    
                            
                            <div class="col-lg-12 col-sm-12 col-xs-12 col-sm-12">
                            
                            	<hr />
                               	<b>Note: Only (jpg, jpeg, webp images are allowed)</b>
                                <hr />                               
                                <table class="table table-bordered table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>S.No.</th>
                                        <th>Product</th>                                        
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                </thead>
                                <tbody>
                                <?php if(isset($attachmentData) && !empty($attachmentData)){ 
									foreach($attachmentData as $key => $product){
								?>
                                	<tr class="box">                                    	
                                        <td>
                                        	<?php e($key+1); ?>
											<input type="hidden" name="edit_img_id[]" value="<?php e($product->id); ?>"/>	
                                            <input type="hidden" name="old_img[]" value="<?php e($product->product_image); ?>"/>											
                                        </td>
                                        <td width="85%"><input type="text" name="product_name_edit[]" id="product_name" value="<?php e($product->product_name); ?>" class="product_name form-control"/><br />
                                        	
                                            <input type="file" name="product_image_edit[]" onchange="document.getElementById('previewImg<?php e($key); ?>').src = window.URL.createObjectURL(this.files[0]);"/><br />
                                            <?php
												if(!empty($product->product_image)){
												$imgPath = WWW_ROOT.'img/products/'.$product->product_image;
												if(is_file($imgPath) && file_exists(WWW_ROOT.'img/products/'.$product->product_image)){
													e($this->Html->image('products/'.$product->product_image, array('class' => 'img-rounded', 'title'=>$product->product_name, 'alt'=> $product->product_name, 'id' => 'previewImg'.$key.'', 'width' => '100','style' => 'margin: 0px 4px 17px 7px;')));
											?>
                                            	<a href="javascript:void(0);" onclick="showImage('<?php e(trim($product->product_image)) ?>')";>View Image</a>
											<?php } } ?><br />
                                        <textarea cols="27" rows="3" name="comment_edit[]" id="comment" class="form-control"><?php e($product->comment); ?></textarea></td>
                                        <td>
                                        <a href="javascript:void(0);" onclick="deleteRecord('ProductOrder','<?php e(base64_encode($this->encryptData($product->id))); ?>','0');" title="Delete" class="btn btn-danger">Delete</a>
                                        </td>
                                    </tr>
                                <?php } } ?>
                                <tr>
                                	<td><?php e($attachmentData->count()+1); ?></td>
                                    <td width="85%"><input type="text" name="product_name[]" id="product_name" class="product_name form-control"/><br />
                                    	<input type="file" name="product_image[]" class="product_image" accept="image/*" onchange="document.getElementById('previewImg<?php e($attachmentData->count()+1); ?>').src = window.URL.createObjectURL(this.files[0]); $('#previewImg<?php e($attachmentData->count()+1); ?>').show();"/><br />
                                    	<img src="#" style="display:none;" id='previewImg<?php e($attachmentData->count()+1); ?>' width="100px"/><br />
                                    <textarea cols="27" rows="3" name="comment[]" id="comment" class="form-control"></textarea></td>
                                    <td><button type="button" class="btn btn-info addMoreBtn" onclick="add_more()">Add More</button></td>
                                </tr>
                                </tbody>
                                <tbody class="add_more"></tbody>
                                </table>
                            
                            	<button type="button" class="btn btn-primary submitBtn" id="submitBtn">Submit</button>
                            </div>
                   		<?= $this->Form->end(); ?>                        
                    </div>
                </div>
            </div>
            <!-- End Form Elements -->
            <a href="<?php e($this->Url->build(ADMIN_FOLDER.'order-manager/'));?>" class="btn btn-info">Back To Listing</a>
        </div>
    </div>
</div>
<style>
	.ui-autocomplete{width:460px !important; margin:102px 0px 0px 690px !important;}
</style>

<script type="text/javascript">

	function searchData(){
		window.location.href = '';	
	}
	
	function showImage(imgname){
		var path = SiteUrl+'img/products/'+imgname;
		$('#imageDiv').html('');
		$('#imageDiv').html('<img src="'+path+'" class="img-responsive">');
		$('.mynewmodal').modal('show');
	}
	
	$(function(){
		$("#customer_contact").autocomplete({
		  source: "<?php e($this->Url->build('/ecommerce/searchCustomerByPhone'.'/'));?>",
		  minLength: 2,
		  dataType: "JSON",
		  select:function(event, ui){
			$("#customer_name").val(ui.item.name);
			$("#customer_id").val(ui.item.customer_id);
			$("#customer_address").val(ui.item.address);
		  }
		});
	});
	
	function getCurrentPrice(type,price,percent){
		$.ajax({			
			type: 'POST',
			dataType:'JSON',
			url: '<?php e($this->Url->build('/ecommerce/getCurrentPrice'));?>',
			data: {type:type,price:price,percent:percent},
			success: function(msg){
				if(type == 'gold'){
					$('#return_gold_calc').val(msg.amount);
				}else{
					$('#return_silver_calc').val(msg.amount);					
				}
				return false;
			},error: function(ts){
				$('#searchbuttons').html('Search');
				$('#error500').modal('show');
			}
		});		
	}
		
$(document).ready(function(e){
	$(document).on('blur','#percentage',function(){
		var price = $('#return_gold').val();
		var percent = $(this).val();
		if(percent > 0 && price > 0){
			getCurrentPrice('gold',price,percent);
		}else{
			$('#return_gold_calc').val(0);	
		}
	});
	
	$(function(){ 
		$(".product_name").autocomplete({
		  source: "<?php e($this->Url->build('/ecommerce/searchOrderProduct'.'/'));?>",
		  minLength: 2,
		  dataType: "JSON",
		  select:function(event, ui){
			$(this).parent().find('.product_name').val(ui.item.product_name);
		  }
		});
	});
	
	$("#delivery_date").datepicker({
		dateFormat: 'dd-mm-yy',
		changeYear: true,
		changeMonth: true,
		minDate: '-1Year',
		maxDate: '+5Year',
	});
	
	$('#customer_contact').filter_input({regex:'[0-9]'});
	$('#date').filter_input({regex:'[0-9-]'});
	$('#qty').filter_input({regex:'[0-9]'});
	$('#price').filter_input({regex:'[0-9.]'});
	$('#quantity').filter_input({regex:'[0-9]'});
	$('#advance_amt').filter_input({regex:'[0-9]'});		
	$('#return_silver').filter_input({regex:'[0-9.]'});
	$('#return_gold').filter_input({regex:'[0-9.]'});
	$('#percentage').filter_input({regex:'[0-9.]'});	
	$('#return_gold_calc').filter_input({regex:'[0-9.]'});
	
    var frmSubmitted = 0;
    $('.submitBtn').click(function(){
        var flag = 0;
        if(frmSubmitted == 0){
			if($.trim($('#customer_contact').val()) == ""){
                $('#customer_contactError').show().html('Please enter customer contact.').slideDown();
                $('#customer_contact').focus();
                frmSubmitted = 0;
                flag = 1; return false;
            }
			if($.trim($('#customer_name').val()) == ""){
                $('#customer_nameError').show().html('Please enter customer name.').slideDown();
                $('#customer_name').focus();
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

var x = '<?php e($attachmentData->count()+2); ?>';
function add_more(){	
	var count = x++;
	$('.addMoreBtn').html('...');
	$.ajax({
		type: 'POST',
		url: '<?php e($this->Url->build('/ecommerce/load_order_products'));?>',
		data: {count:count},
		success: function(msg){			
			$('.add_more').append(msg);
			$('.addMoreBtn').html('Add More');
			return false;
		},error: function(ts){
            $('#searchbuttons').html('Search');
            $('#error500').modal('show');
        }
	});	
}

function remove(row){
	$('#remove_'+row).remove();
}
</script>

<div class="modal mynewmodal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p id="imageDiv"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>