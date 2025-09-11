<?php
	#set page meta content
	$this->assign('title', SITE_TITLE.' :: Create GST Bill');
	$this->assign('meta_robot', 'noindex, nofollow');
?>
<?= $this->Html->css(array('/css/jquery-ui'));?>
<?= $this->Html->script('/js/jquery-ui');?>
<!--  page-wrapper -->
<div id="page-wrapper">
    <div class="row">
        <!-- page header -->
        <div class="col-lg-12">
            <h1 class="page-header">Create GST Bill</h1>
        </div>
        <!--end page header -->
    </div>
    <div class="row">
        <div class="col-lg-12">
            <a href="<?php e($this->Url->build(ADMIN_FOLDER.'sales-manager'.'/'));?>" class="btn btn-info">Back To Listing</a><br />&nbsp;
        </div>
        <div class="col-lg-12">
            <!-- Form Elements -->
            <?php e($this->Flash->render()); ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    Add GST invoice information
                </div>
                <div class="panel-body">
                    <div class="row">                        
						<?= $this->Form->create(NULL,array('id' => 'addForm', 'type' => 'file', 'inputDefaults' => array('label' => false,'div' => false), 'name' => 'addForm', 'csrfToken' => $this->request->getAttribute('csrfToken')));?>                            
                        <div class="col-lg-6 col-sm-6 col-xs-6 col-sm-6">
                        <div class="form-group">
                            <label>Party Name</label>
                            <input type="text" id="customer_name" name="customer_name" onkeyup="checkError(this.id);" confirmation="false" class="form-control">
                            <span id="customer_nameError" class="admin_login_error"></span>
                            <input type="hidden" name="customer_id" id="customer_id"/>
                        </div>
                        <div class="form-group">                            
                            <label>Phone Number</label>
                            <input type="tel" id="customer_contact" name="customer_contact" onkeyup="checkError(this.id);" maxlength="10" confirmation="false" class="form-control">
                            <span id="customer_contactError" class="admin_login_error"></span>
                        </div>
                        <div class="form-group">                            
                            <label>Address</label>
                            <input type="text" id="customer_address" name="customer_address" onkeyup="checkError(this.id);" confirmation="false" class="form-control">
                            <span id="customer_addressError" class="admin_login_error"></span>
                        </div> 
                         <div class="form-group">                            
                            <label>Place of supply</label>
                            <input type="text" id="delivery_address" name="delivery_address" value="Rajasthan" onkeyup="checkError(this.id);" confirmation="false" class="form-control">
                            <span id="delivery_addressError" class="admin_login_error"></span>
                        </div>                          
                                                                        
                        </div>
                        
                        <div class="col-lg-6 col-sm-6 col-xs-6 col-sm-6">
                        <div class="form-group">                            
                            <label>Payment Type</label>
                            <select name="payment_type" id="payment_type" onkeyup="checkError(this.id);" confirmation="false" class="form-control">
                            	<option value="COD">Cash</option>
                                <option value="UPI">UPI</option>
                                <option value="Net Banking">Net Banking</option>
                            </select>
                            <span id="payment_typeError" class="admin_login_error"></span>
                        </div> 
                        <div class="form-group">
                            <label>Dated</label>
                            <input type="text" id="date" name="date" onclick="checkError(this.id);" maxlength="10" value="<?php e(date('d-m-Y')); ?>" confirmation="false" class="form-control">
                            <span id="dateError" class="admin_login_error"></span>
                        </div>
                        <div class="form-group">                            
                            <label>Invoice Number</label>
                            <input type="text" id="invoice_no" name="invoice_no" readonly="readonly" onkeyup="checkError(this.id);" value="<?php echo $invoice; ?>" confirmation="false" class="form-control">
                            <span id="invoice_noError" class="admin_login_error"></span>
                        </div>                                             
                        <div class="form-group">                            
                            <label>Advance</label>
                            <input type="text" name="order_id" id="search_order_id" placeholder="search order by customer name or contact" autocomplete="" class="form-control"/>
                            <input type="hidden" name="order_invoice_id" id="order_invoice_id"/>
                        </div>
                        <!--<div class="form-group">                            
                            <label>Challan Number</label>
                            <input type="text" id="challan" name="challan" onkeyup="checkError(this.id);" confirmation="false" class="form-control">
                            <span id="challanError" class="admin_login_error"></span>
                        </div> 
                        <div class="form-group">                            
                            <label>Order Number</label>
                            <input type="text" id="order_no" name="order_no" onkeyup="checkError(this.id);" confirmation="false" class="form-control">
                            <span id="order_noError" class="admin_login_error"></span>
                        </div>                            
                        <div class="form-group">                            
                            <label>Dispatch Through</label>
                            <input type="text" id="dispatch_through" name="dispatch_through" onkeyup="checkError(this.id);" confirmation="false" class="form-control">
                            <span id="dispatch_throughError" class="admin_login_error"></span>
                        </div>-->
                        </div>    
                        
                        <div class="col-lg-12 col-sm-12 col-xs-12 col-sm-12 table-responsive">
                        
                            <hr />                                
                            <table class="table table-bordered table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Product</th>
                                    <th>Purity</th>
                                    <th>HUID Code</th>
                                    <th>Gross Wt /gm</th>
                                    <th>Net Wt /gm</th>                                    
                                    <th>Quantity</th>
                                    <th>Dia/Stn Wt</th>
                                    <th>Rate</th>
                                    <th>Labour</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="add_more">
                            <tr>
                                <td>1</td>
                                <td>
                                	<input type="hidden" name="tunch[]" id="tunch" class="tunch form-control" value="0"/>
                                	<input type="hidden" name="wstg[]" id="wstg" class="wstg form-control" value="0"/>
                                    <input type="hidden" name="product_id[]" id="product_id" class="product_id"/>
                                    <input type="tel" name="product_name[]" id="product_name" class="product_name form-control"/>
                                </td>
                                <td>
                                    <select name="purity[]" id="purity" class="purity form-control">
                                        <option value="14 K">14 K</option>
                                        <option value="18 K">18 K</option>
                                        <option value="22 K">22 K</option>
                                        <option value="24 K">24 K</option>
                                    </select>
                                </td>
                                <td><input type="text" readonly="readonly" name="huid_code[]" id="huid_code" class="huid_code form-control"/></td>
                                <td><input type="text" name="gross_wt[]" id="gross_wt" class="gross_wt form-control"/></td>
                                <td><input type="text" name="net_wt[]" id="net_wt1" onblur="setPrice('1')" class="net_wt form-control"/></td>
                                <td>
                                    <select name="quantity[]" id="quantity1" class="quantity form-control" onchange="setPrice('1');">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                    </select>
                                </td>
                                <td><input type="text" name="diam_stone_wgt[]" id="diam_stone_wgt" class="diam_stone_wgt form-control"/></td>
                                <td><input type="tel" onblur="setPrice('1');" name="price[]" id="price1" class="price form-control"/></td>
                                
                                <td><input type="tel" name="labour[]" onblur="setPrice('1');" id="labour1" class="labour form-control"/></td>
                                <td><input type="text" readonly="readonly" name="total[]" id="total1" class="total form-control"/></td>
                                <td><button type="button" class="btn btn-info addMoreBtn" onclick="add_more()">Add More</button></td>
                            </tr>
                            </tbody>
                            
                            <tr>
                                <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                                <td style="border-right:1px solid #000;"><strong>Grand Total</strong></td>
                                <td id="totalSum" colspan="4"><input type="text" readonly="readonly" class="form-control total_sum" value="0" /></td>
                            </tr>
                            <tr>
                            <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                            <td style="border-right:1px solid #000;"><strong>GST (<?php e($gst); ?>%)</strong></td>
                            <td id="totalDiscount" colspan="2"><input type="text" onblur="setTotal()" readonly="readonly" class="form-control gst" value="0" name="gst" /></td>
                            </tr>
                            <tr>
                            <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                            <td style="border-right:1px solid #000;"><strong>Discount</strong></td>
                            <td id="totalDiscount" colspan="2"><input type="tel" onblur="setTotal()" class="form-control discount" value="0" name="discount" /></td>
                            </tr>
                            <tr>
                            <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                            <td style="border-right:1px solid #000;"><strong>Old Gold/Silver</strong></td>
                            <td id="totalDiscount" colspan="4"><input type="tel" onblur="setTotal()" class="form-control return_jewellery" name="return_jewellery" value="0" /></td>
                            </tr>
                            <tr>
                            <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                            <td style="border-right:1px solid #000;"><strong>Total</strong></td>
                            <td id="grandTotal" colspan="2"></td>
                            <input type="hidden" id="hiddenTotal" name="grand_total"/>
                            </tr>                                
                            </table>                            
                            <button type="button" class="btn btn-primary submitBtn" id="submitBtn">Submit</button>
                        </div>
                        <?= $this->Form->end(); ?>                        
                    </div>
                </div>
            </div>
            <!-- End Form Elements -->
            <a href="<?php e($this->Url->build(ADMIN_FOLDER.'sales-manager'.'/'));?>" class="btn btn-info">Back To Listing</a>
        </div>
    </div>
</div>
<style>
	.ui-autocomplete{width:460px !important; margin:102px 0px 0px 690px !important;}
</style>
<script type="text/javascript">
	function setTotal(){
		if($('.discount').val() == ''){
			$('.discount').val(0);
		}
		if($('.return_jewellery').val() == ''){
			$('.return_jewellery').val(0);
		}
		var gstchr = '<?php e($gst); ?>';
		var grandTotal = 0;
		$("input[name='total[]']").each(function(){
			var firstAmt = parseInt($(this).val());
			if($.isNumeric(firstAmt)){
				grandTotal = parseFloat(grandTotal)+parseFloat(firstAmt);
			}
		});
		var return_jewellery = $('.return_jewellery').val();
		var discount = $('.discount').val();
		var totalamt = parseFloat(grandTotal);
		var gstAmt = (parseInt(totalamt)/100)*parseInt(gstchr);		
		var netAmt = parseFloat(parseInt(gstAmt)+parseInt(totalamt))-(parseInt(discount)+parseInt(return_jewellery));
		
		$(".gst").val(gstAmt.toFixed(2));
		$('.total_sum').val(totalamt);
		$("#grandTotal").text(netAmt.toFixed(2));
		$('#hiddenTotal').val(netAmt.toFixed(2));
	}
	
	function setPrice(counter){		
		var qty = $('#quantity'+counter).val();
		var price = $('#price'+counter).val();
		var weight = $('#net_wt'+counter).val();
		
		if(qty == '' || qty == 'undefined'){
			$('#quantity'+counter).val(1);
			qty = 1;	
		}
		if(price == '' || price == 'undefined'){
			$('#price'+counter).val(0);
			price = 0;
		}
		if(weight == '' || weight == 'undefined'){
			$('#net_wt'+counter).val(0);
			weight = 0;
		}
		
		if($('#labour'+counter).val() == ''){
			var labour = $('#labour'+counter).val(0);	
		}else{
			var labour = $('#labour'+counter).val();	
		}
		
		var total_product = (parseInt(price)*parseFloat(weight)*parseInt(qty));
		var totalPrice = parseInt(total_product)+(parseInt(labour)*parseFloat(weight));
		
		$('#total'+counter).val(totalPrice.toFixed(2));				
		setTotal();		
	}

	$(function(){ 
		$("#customer_name").autocomplete({
		  source: "<?php e($this->Url->build('/ecommerce/searchCustomer'.'/'));?>",
		  minLength: 2,
		  dataType: "JSON",
		  select:function(event, ui){
			$("#customer_name").val(ui.item.name);
			$("#customer_id").val(ui.item.customer_id);
			$("#customer_address").val(ui.item.address);
			$("#customer_contact").val(ui.item.contact);
		  }
		});
	});
	$(function(){ 
		$("#customer_contact").autocomplete({
		  source: "<?php e($this->Url->build('/ecommerce/searchCustomerByPhone'.'/'));?>",
		  minLength: 2,
		  dataType: "JSON",
		  select:function(event, ui){
			$("#customer_name").val(ui.item.name);
			$("#customer_id").val(ui.item.customer_id);
			$("#customer_address").val(ui.item.address);
			$("#customer_contact").val(ui.item.contact);
		  }
		});
	});
	$(function(){ 
		$("#search_order_id").autocomplete({
		  source: "<?php e($this->Url->build('/ecommerce/searchOrderByName'.'/'));?>",
		  minLength: 2,
		  dataType: "JSON",
		  select:function(event, ui){
				var return_gold = parseInt(ui.item.return_gold);
			  	var totaladv = parseInt(ui.item.advance)+parseInt($(".discount").val());
				$(".discount").val(totaladv);
				$('.return_jewellery').val(return_gold);
				$('#order_invoice_id').val(ui.item.invoice_id);
				setTotal();
		  	}
		});
	});
	
$(document).ready(function(e){
	
	var labour = '<?php e($labour); ?>';	
	$(function(){ 
		$(".product_name").autocomplete({
		  source: "<?php e($this->Url->build('/ecommerce/searchProduct'.'/'));?>",
		  minLength: 2,
		  dataType: "JSON",
		  select:function(event, ui){			  
				var qty = ui.item.quantity;
				//var total = parseInt(ui.item.price)+parseInt(labour);
				var sum = (parseInt(ui.item.price) * parseFloat(ui.item.net_weight)) * parseInt(1);
				var total = (parseFloat(sum))+(parseInt(labour) * parseFloat(ui.item.net_weight));
				if(qty > 0 && qty <= 10){
					var $html = '';
					for(var i = 1; i <= qty; i++) {
					  $html+= '<option value="'+i+'">'+i+'</option>';
					}
					$(this).parent().next().next().next().next().next().find('.quantity').html($html);
					$(this).parent().next().next().next().next().next().find('.quantity').val(1);
				}
				$(this).parent().find('.product_id').val(ui.item.pid);
				$(this).parent().next().find('.purity').val(ui.item.purity);
				$(this).parent().next().next().find('.huid_code').val(ui.item.huid_code);
				$(this).parent().next().next().next().find('.gross_wt').val(ui.item.gross_weight);
				$(this).parent().next().next().next().next().find('.net_wt').val(ui.item.net_weight);
				$(this).parent().next().next().next().next().next().find('.diam_stone_wgt').val(ui.item.diam_stone_wgt);
				$(this).parent().next().next().next().next().next().next().find('.diam_stone_wgt').val(ui.item.diam_stone_wgt);
				$(this).parent().next().next().next().next().next().next().next().find('.price').val(ui.item.price);
				$(this).parent().next().next().next().next().next().next().next().next().find('.labour').val(labour);
				$(this).parent().next().next().next().next().next().next().next().next().next().find('.total').val(total);
				
				setTotal();
		  	}
		});
	});
	
	$("#date").datepicker({
		dateFormat: 'dd-mm-yy',
		changeYear: true,
		changeMonth: true,
		maxDate: 0,
	});
	
	$('#customer_contact').filter_input({regex:'[0-9]'});
	$('#date').filter_input({regex:'[0-9-]'});
	$('#qty').filter_input({regex:'[0-9]'});
	$('.price').filter_input({regex:'[0-9.]'});
	$('.quantity').filter_input({regex:'[0-9]'});
	$('.discount').filter_input({regex:'[0-9]'});
	$('#search_product_id').filter_input({regex:'[0-9]'});
	$('.labour').filter_input({regex:'[0-9]'});
	$('.product_name').filter_input({regex:'[0-9]'});
			
    var frmSubmitted = 0;
    $('.submitBtn').click(function(){
        var flag = 0;
        if(frmSubmitted == 0){
			if($.trim($('#customer_name').val()) == ""){
                $('#customer_nameError').show().html('Please enter customer name.').slideDown();
                $('#customer_name').focus();
                frmSubmitted = 0;
                flag = 1; return false;
            }
			if($.trim($('#customer_id').val()) == ""){
                $('#customer_nameError').show().html('Please enter customer name.').slideDown();
                $('#customer_name').focus();
                frmSubmitted = 0;
                flag = 1; return false;
            }			
			if($.trim($('#customer_address').val()) == ""){
                $('#customer_addressError').show().html('Please enter customer address.').slideDown();
                $('#customer_address').focus();
                frmSubmitted = 0;
                flag = 1; return false;
            }
			if($.trim($('#delivery_address').val()) == ""){
                $('#delivery_addressError').show().html('Please enter delivery address.').slideDown();
                $('#delivery_address').focus();
                frmSubmitted = 0;
                flag = 1; return false;
            }
			if($.trim($('#customer_contact').val()) == ""){
                $('#customer_contactError').show().html('Please enter customer contact.').slideDown();
                $('#customer_contact').focus();
                frmSubmitted = 0;
                flag = 1; return false;
            }
			if($.trim($('#date').val()) == ""){
                $('#dateError').show().html('Please enter delivery date.').slideDown();
                $('#date').focus();
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

var x = 2;
function add_more(){	
	var count = x++;
	$('.addMoreBtn').html('...');
	$.ajax({
		type: 'POST',
		url: '<?php e($this->Url->build('/ecommerce/load_products'));?>',
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
	setPrice(row);
}
</script>