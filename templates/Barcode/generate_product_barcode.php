<?php
#set page meta content
$this->assign('title', SITE_TITLE.' :: Product Barcodes');
$this->assign('meta_robot', 'noindex, nofollow');
e($this->Element('/admin/jQuery'));
?>
<script type="text/javascript" src="<?php e($this->Url->build('/admin/js/jquery-barcode.js'));?>"></script> 
<!--  page-wrapper -->
<div id="page-wrapper">
<div class="row">
    <!-- page header -->
    <div class="col-lg-12">
    </div>
    <!--end page header -->
</div>
<div class="panel panel-primary">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive">
                    <div id="replaceHtml">
                        <table class="table table-bordered table-hover">                            
                            <tbody>
                                <?php
                                if(count($data) > 0){
                                    foreach($data['unique_code'] as $key => $product){
										$detail = $this->Ecommerce->getItemDetails($product);
										$quantity = $data['quantity'][$key];
										if($quantity > 0){
                                ?>
                                	<tr>
                                    	<td colspan="2"><b><?php e($detail->product_name); ?></b></td>
                                    </tr>
                                    <tr>
                                        <th>#</th>
                                        <th>Product Details</th>
                                    </tr>
                                    <?php									
										for($i=1;$i<=$quantity;$i++){
											$rand = mt_rand();										
									?>                                    	
                                        <tr>
                                        	<td>
                                            	<script type="text/javascript">
													$(function(){
														var settings = {
															barWidth: 2,
															barHeight: 50,
															moduleSize: 5,
															showHRI: true,
															addQuietZone: true,
															marginHRI: 5,
															bgColor: "#FFFFFF",
															color: "#000000",
															fontSize: 10,
															output: "css",
															posX: 0,
															posY: 0
														};
														
														var code = '<?php e($detail->unique_code); ?>';
														var randomno = <?php e($rand); ?>;
														$("#barcodeTarget_"+randomno).barcode(
															code, // Value barcode (dependent on the type of barcode)
															"code128", // type (string)
															settings
														);	
													});
                                                </script>                                                
                                                <div class="barcodeTarget" id="barcodeTarget_<?php e($rand); ?>"></div>
                                            </td>
                                            <td>
                                            	<b><?php e($detail->product_name); ?></b><br />
                                            	<b>Gross Weight: </b><?php e($detail->gross_weight); ?> gm<br />
                                                <b>Net Weight: </b><?php e($detail->net_weight); ?> gm                                           
                                            </td>
                                        </tr>
                                    <?php }  ?>                                   
                                <?php
                                    } }
                                }else{
                                ?>
                                <tr>
                                    <td class="text-center" colspan="6">Records are not found.</td>
                                </tr>
                                <?php } ?>
                            </tbody>                            
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.panel-body -->
    </div>
</div>
<script type="text/javascript">
$(document).ready(function(e){
    window.print();
});
</script>