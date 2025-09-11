<?php
	#set page meta content
	$this->assign('title', SITE_TITLE.' :: View Invoice');
	$this->assign('meta_robot', 'noindex, nofollow');
	e($this->Element('/admin/jQuery'));
?>
<style>
	.profile_row{background: #f5f5f5;padding: 8px;margin-bottom: 10px;border:1px groove;}
</style>
<!--  page-wrapper -->
<div id="page-wrapper">
    <div class="row">
        <!-- page header -->
        <div class="col-lg-12">
            <h1 class="page-header">Invoice Details</h1>
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
                    View Invoice
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group profile_row">
                                <label style="min-width: 250px;">Invoice Id:</label>
                                <span><?php e(isCheckVal($order->invoice_no)); ?></span>
                            </div>
                            <div class="form-group profile_row">
                                <label style="min-width: 250px;">Party Name:</label>
                                <span><?php e(isCheckVal($order->customer_name)); ?></span>
                            </div>
                            <div class="form-group profile_row">
                                <label style="min-width: 250px;">Customer Contact:</label>
                                <span><?php e(isCheckVal($order->customer_contact)); ?></span>
                            </div>
                            <div class="form-group profile_row">
                                <label style="min-width: 250px;">Customer Address:</label>
                                <span><?php e(isCheckVal($order->customer_address)); ?></span>
                            </div>
                            <div class="form-group profile_row">
                                <label style="min-width: 250px;">Delivery Address:</label>
                                <span><?php e(isCheckVal($order->delivery_address)); ?></span>
                            </div>
                            <div class="form-group profile_row">
                                <label style="min-width: 250px;">Date:</label>
                                <span><?php e($order->date); ?></span>
                            </div>
                            <?php
								$advanceInfo = NULL;
								if(isset($orderData) && !empty($orderData->customer_name) && !empty($orderData->invoice_id)){
									$advanceInfo = $orderData->customer_name .' - '. $orderData->customer_contact. ' Invoice: ('.$orderData->invoice_id.')';
								}
							?>
                            <div class="form-group profile_row">
                                <label style="min-width: 250px;">Advance:</label>
                                <span><?php e($advanceInfo); ?></span>
                            </div>
                            <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Product</th>
                                        <th>Purity</th>
                                        <th>HUID Code</th>
                                        <th>Gross Wt</th>
                                        <th>Net Wt</th>                                    
                                        <th>Qty</th>
                                        <th>Dia/Stn Wt</th>
                                        <th>Labour</th>
                                        <th>Rate</th>
                                        <th>Total</th>
                                        <th>Preview</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if(isset($attachmentData) && !empty($attachmentData)){
									$grossTotal = 0;
                                    foreach($attachmentData as $key => $product){	
										$discount = $order->discount;
										$advance = 0;
										if(isset($orderData->advance_amt) && $orderData->advance_amt > 0){
											$advance = $orderData->advance_amt;
											$discount = $order->discount - $advance;
										}
										$grossTotal+= $product->grand_total;
                                ?>
                                	<tr>                                    	
                                        <td width="5%">
                                        	<?php e($key+1); ?>
                                        </td>
                                        <td width="20%">
                                        	<span><?php e($product->product_name); ?></span>
                                        </td>
                                        <td>
                                        	<span><?php e($product->purity); ?></span>
                                        </td>
                                        <td>
                                        	<span><?php e($product->huid_code); ?></span>
                                        </td>
                                        <td>
                                        	<span><?php e($product->gross_wt); ?></span>
                                        </td>
                                        <td>
                                        	<span><?php e($product->net_wt); ?></span>
                                        </td>
                                        <td>
                                        	<span><?php e($product->quantity); ?></span>
                                        </td>
                                        <td>
                                        	<span><?php e($product->diam_stone_wgt); ?></span>
                                        </td>                                        
                                         
                                        <td>
                                        	<span><?php e($product->labour); ?></span>
                                        </td>
                                        <td>
                                        	<span><?php e(number_format($product->price,2)); ?></span>
                                        </td>
                                        <td>
                                        	<?php $total = $product->grand_total; ?>
                                        	<span><?php e(number_format($total,2)); ?></span>
                                        </td>
                                        <td width="5%">
                                            <input type="file" style="display:none;" name="banner" class="picture_upload_btn bannerIcon<?php print $product->id; ?> form-control" id="UserProfile_<?php print $product->id; ?>"/>
                                            <label for="UserProfile_<?php print $product->id; ?>" id="bannerImage<?php print $product->id; ?>" fileid="<?php print $product->id; ?>" style="cursor:pointer;">                        	
                                                <?php
                                                    if($product->banner != ""){
                                                        $imgPath = WWW_ROOT.'img/banners/'.$product->banner;
                                                        if(is_file($imgPath)){
                                                            print $this->Html->image('banners/'.$product->banner, array('title'=>'', 'alt'=> '', 'width' => '90' ));
                                                        }
                                                    } else {
                                                        print $this->Html->image('no_image.jpg', array('title'=>'', 'alt'=> '', 'width' => '50' ));	
                                                    }
                                                ?> 
                                            </label>
                                            <?php
												if($product->banner != ""){
													$imgPath = WWW_ROOT.'img/banners/'.$product->banner;
													if(is_file($imgPath)){
														echo '<span onclick="deleteBanner('.$product->id.')" title="Delete" alt="Delete" style="cursor: pointer;"><i class="fa fa-trash-o fa-2x" style="color:red"></i></span>';
													}													
												}
											?> 
                                        </td>
                                    </tr>
                                <?php } } ?>
                                	<tr>
                                    <td></td><td></td><td></td><td></td><td></td>                                    
                                    <td></td><td></td><td></td><td></td><td></td>
                                    <td colspan="2" id="totalDiscount"><strong>₹<?php e(isCheckVal(number_format($grossTotal,2))); ?></strong></td>
                                    </tr>
                                    <tr>
                                    <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                                    <td style="border-right:1px solid #000;"><strong>GST (<?php e(isCheckVal($gst)); ?>%)</strong></td>
                                    <td colspan="2" id="totalDiscount"><strong>₹<?php e(isCheckVal(number_format($order->gst,2))); ?></strong></td>
                                    </tr>                                    
                                    <?php if($advance > 0){ ?>
                                    <tr>
                                    <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                                    <td style="border-right:1px solid #000;"><strong>Advance</strong></td>
                                    <td colspan="2" id="totalDiscount"><strong>- ₹<?php e(isCheckVal(number_format($advance,2))); ?></strong></td>
                                    </tr>
                                    <?php } ?>
                                    <?php if($discount > 0){ ?>
                                    <tr>
                                    <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                                    <td style="border-right:1px solid #000;"><strong>Discount</strong></td>
                                    <td colspan="2" id="totalDiscount"><strong>- ₹<?php e(isCheckVal(number_format($discount,2))); ?></strong></td>
                                    </tr>
                                    <?php } ?>
                                    <?php if($order->return_jewellery > 0){ ?>
                                    <tr>
                                    <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                                    <td style="border-right:1px solid #000;"><strong>Return Jewellery</strong></td>
                                    <td colspan="2" id="totalDiscount"><strong>- ₹<?php e(isCheckVal(number_format($order->return_jewellery,2))); ?></strong></td>
                                    </tr>
                                    <?php } ?>
                                    <tr>
                                    <td></td><td colspan="6"><b>Rs. <?php e($this->Ecommerce->convertInWords($order->amount)); ?></b></td><td></td><td></td>
                                    <td style="border-right:1px solid #000;"><strong>Closing Balance</strong></td>
                                    <td colspan="2" id="totalDiscount"><strong>₹<?php e(isCheckVal(number_format($order->amount,2))); ?></strong></td>
                                    </tr>
                                </tbody>                                
                         	</table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Form Elements -->
            <a href="<?php e($this->Url->build(ADMIN_FOLDER.'sales-manager'.'/'));?>" class="btn btn-info">Back To Listing</a>
            <a target="_blank" href="<?php e($this->Url->build(ADMIN_FOLDER.'print-invoice/'.base64_encode($this->encryptData($order->id))));?>" title="Print" class="btn btn-primary">Print Bill</a>
            <a href="<?php e($this->Url->build(ADMIN_FOLDER.'edit-invoice/'.base64_encode($this->encryptData($order->id))));?>" class="btn btn-warning">Edit</a>
        </div>
    </div>
</div>

<script type="text/javascript">
	function searchData (){
		window.location.href = '';	
	}
	function deleteBanner(rowId){
		swal({
			title: "Do you want to delete this image?",
			text: "",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: '#DD6B55',
			cancelButtonText: "No",
			confirmButtonText: 'Yes',
			closeOnConfirm: false,
			closeOnCancel: false
		},
		function(isConfirm){
			if (isConfirm){
			  swal("Deleted!", "", "success");
			  $.ajax({
					type: 'POST',
					dataType: 'JSON',
					url: '<?php echo $this->Url->build('/ecommerce/deleteBanner'); ?>',
					data: {rowId:rowId},
					success: function(msg){
						searchData();
					},error: function(ts) { 
						$('#Error500').modal('show');
					}
				})
			} else {
			  swal("Cancelled", "", "error");
			}
		});
	}
	
	$(".picture_upload_btn").on('change', function(){
		var bannerId = $(this).attr('id').split('_').pop().toLowerCase();
		var manish = 1;
		$('#errorMsgPopUp').text('Something went wrong. Please try again.');
		$('#bannerImage'+bannerId).html('');	
		var ext = $('.bannerIcon'+bannerId).val().split('.').pop().toLowerCase();
		
		if($.inArray(ext, ['jpeg', 'jpg', 'png', 'webp']) == -1){
			$('#errorMsgPopUp').text('Only jpg, png, webp files are allowed.');
			$('#Error500').modal('show');
			manish = 0;
			return false;
		}
		if(manish == 1){
			var formData = new FormData();
			var old_image = $('#oldImg'+bannerId).val();
			formData.append('bannerIcon', $('.bannerIcon'+bannerId)[0].files[0]);
			formData.append('model', 'Banner');
			formData.append('editId', bannerId);
			formData.append('old_image', old_image);
			$.ajax({
				url: '<?php echo $this->Url->build('/ecommerce/updateProductImage'); ?>',
				data: formData,
				processData: false,
				contentType: false,
				type: 'POST',
				dataType: 'JSON',
				success:function(response){
					if (response.data.status == 'Error'){
						$('#Error500').modal('show');
						return false;
					}else{
						$('#bannerImage'+bannerId).html('Processing...');
						setTimeout(function(){							
							searchData();
						}, 500);						
						
					}
				}
			});
		}
	});
	
</script>