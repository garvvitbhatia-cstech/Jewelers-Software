<?php
	#set page meta content
	$this->assign('title', SITE_TITLE.' :: View Order');
	$this->assign('meta_robot', 'noindex, nofollow');
?>
<style>
	.profile_row{background: #f5f5f5;padding: 8px;margin-bottom: 10px;border:1px groove;}
</style>
<!--  page-wrapper -->
<div id="page-wrapper">
    <div class="row">
        <!-- page header -->
        <div class="col-lg-12">
            <h1 class="page-header">Order Details</h1>
        </div>
        <!--end page header -->
    </div>
    <div class="row">
        <div class="col-lg-12">
            <a href="<?php e($this->Url->build(ADMIN_FOLDER.'order-management'.'/'));?>" class="btn btn-info">Back To Listing</a><br />&nbsp;
        </div>
        <div class="col-lg-12">
            <!-- Form Elements -->
            <?php e($this->Flash->render()); ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    View order
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group profile_row">
                                <label style="min-width: 250px;">Invoice Id:</label>
                                <span><?php e(isCheckVal($order->invoice_id)); ?></span>
                            </div>
                            <div class="form-group profile_row">
                                <label style="min-width: 250px;">Party Name:</label>
                                <span><?php e(isCheckVal($order->customer_name)); ?></span>
                            </div>
                            <div class="form-group profile_row">
                                <label style="min-width: 250px;">User Contact:</label>
                                <span><?php e(isCheckVal($order->customer_contact)); ?></span>
                            </div>
                            <div class="form-group profile_row">
                                <label style="min-width: 250px;">Percentage:</label>
                                <span><?php e(isCheckVal($order->percentage)); ?>%</span>
                            </div>
                            <div class="form-group profile_row">
                                <label style="min-width: 250px;">Return Gold:</label>
                                <span><?php e(isCheckVal($order->return_gold)); ?> gm</span>
                            </div>
                            <div class="form-group profile_row">
                                <label style="min-width: 250px;">Return Gold Amount:</label>
                                <span><?php e(isCheckVal($order->return_gold_amt)); ?></span>
                            </div>
                            <div class="form-group profile_row">
                                <label style="min-width: 250px;">Return Silver:</label>
                                <span><?php e(isCheckVal($order->return_silver)); ?> gm</span>
                            </div>
                            <div class="form-group profile_row">
                                <label style="min-width: 250px;">Advance Amount:</label>
                                <span><?php e(isCheckVal($order->advance_amt)); ?></span>
                            </div>
                            <div class="form-group profile_row">
                                <label style="min-width: 250px;">Delivery Date:</label>
                                <span><?php e(isCheckVal($order->delivery_date)); ?></span>
                            </div>
                            <div class="form-group profile_row">
                                <label style="min-width: 250px;">Order Date:</label>
                                <span><?php e(date("F jS, Y h:i A",$order->created)); ?></span>
                            </div>
                            
                            <table class="table table-bordered table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>S.No.</th>
                                        <th>Product Description</th>
                                        <th>Product Image</th>
                                        <th>Comment</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if(isset($attachmentData) && !empty($attachmentData)){ 
									foreach($attachmentData as $key => $product){
								?>
                                	<tr>                                    	
                                        <td>
                                        	<?php e($key+1); ?>
                                        </td>
                                        <td width="30%">
                                        	<span><?php e($product->product_name); ?></span>
                                        </td>
                                        <td>
                                            <?php
												if(!empty($product->product_image)){
												$imgPath = WWW_ROOT.'img/products/'.$product->product_image;
												if(is_file($imgPath) && file_exists(WWW_ROOT.'img/products/'.$product->product_image)){
													e($this->Html->image('products/'.$product->product_image, array('class' => 'img-rounded', 'title'=>$product->product_name, 'alt'=> $product->product_name, 'width' => '100','style' => 'margin: 0px 4px 17px 7px;')));
											?>
                                            	<a href="javascript:void(0);" onclick="showImage('<?php e(trim($product->product_image)) ?>')";>View Image</a>
											<?php } } ?>
                                        </td>
                                        <td width="30%">
                                        	<span><?php e(nl2br($product->comment)); ?></span>
                                        </td>
                                    </tr>
                                <?php } } ?>
                                </tbody>
                                <tbody class="add_more"></tbody>
                         	</table>                                
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Form Elements -->
            <a href="<?php e($this->Url->build(ADMIN_FOLDER.'order-management'.'/'));?>" class="btn btn-info">Back To Listing</a>
        </div>
    </div>
</div>

<script>
	function showImage(imgname){
		var path = SiteUrl+'img/products/'+imgname;
		$('#imageDiv').html('');
		$('#imageDiv').html('<img src="'+path+'" class="img-responsive">');
		$('.mynewmodal').modal('show');
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