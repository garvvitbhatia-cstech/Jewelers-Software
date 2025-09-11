<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta http-equiv="content-type" content="text/html; charset=UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Print Invoice</title>
      	<style>
		.profile_row{background: #f5f5f5;padding: 8px;margin-bottom: 10px;border:1px groove;}
		</style>
   </head>
   <div>
         <script type="text/javascript">
            //<![CDATA[
            window.print();window.onfocus = function() { window.close(); }//]]>
         </script>
         <div>
   <body> 
<!--  page-wrapper -->
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
        <!-- Form Elements -->
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">                            
                            <div class="table-responsive">
                            <table class="table" border=1 cellspacing=0 cellpadding=1 width="100%">
                            	<tr>
                                	<td><label>Invoice Id</label></td>
                                    <td><span><?php e(isCheckVal($order->invoice_no)); ?></span></td>
                                </tr>
                                <tr>
                                	<td><label>Custromer Name</label></td>
                                    <td><span><?php e(isCheckVal($order->customer_name)); ?></span></td>
                                </tr>
                                <tr>
                                	<td><label>Customer Contact</label></td>
                                    <td><span><?php e(isCheckVal($order->customer_contact)); ?></span></td>
                                </tr>
                                <tr>
                                	<td><label>Customer Address</label></td>
                                    <td><span><?php e(isCheckVal($order->customer_address)); ?></span></span></td>
                                </tr>
                                <tr>
                                	<td><label>Delivery Address</label></td>
                                    <td><span><?php e(isCheckVal($order->delivery_address)); ?></span></td>
                                </tr>
                                <tr>
                                	<td><label>Date</label></td>
                                    <td><span><?php e($order->date); ?></span></td>
                                </tr>
                            </table>
                            <table class="table" border=1 cellspacing=0 cellpadding=1 width="100%">
                                <thead>
                                    <tr>
                                        <th>S.No.</th>
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
                                        <td>
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
                                            <?php
                                                if($product->banner != ""){
                                                    $imgPath = WWW_ROOT.'img/banners/'.$product->banner;
                                                    if(is_file($imgPath)){
                                                        print $this->Html->image('banners/'.$product->banner, array('title'=>'', 'alt'=> '', 'width' => '90' ));
                                                    }
                                                } 
                                            ?>
                                        </td>
                                    </tr>
                                <?php } } ?>
                                    <tr>
                                    <td></td><td></td><td></td><td></td><td></td>                                    
                                    <td></td><td></td><td></td><td></td><td></td><td></td>
                                    <td colspan="2" id="totalDiscount"><strong>&nbsp;₹<?php e(isCheckVal(number_format($grossTotal,2))); ?></strong></td>
                                    </tr>
                                    <tr>
                                    <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                                    <td style="border-right:1px solid #000;"><strong>GST (<?php e(isCheckVal($gst)); ?>%)</strong></td>
                                    <td colspan="2" id="totalDiscount"><strong>&nbsp;₹<?php e(isCheckVal(number_format($order->gst,2))); ?></strong></td>
                                    </tr>                                    
                                    <?php if($advance > 0){ ?>
                                    <tr>
                                    <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                                    <td style="border-right:1px solid #000;"><strong>Advance</strong></td>
                                    <td colspan="2" id="totalDiscount"><strong>&nbsp;- ₹<?php e(isCheckVal(number_format($advance,2))); ?></strong></td>
                                    </tr>
                                    <?php } ?>
                                    <?php if($discount > 0){ ?>
                                    <tr>
                                    <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                                    <td style="border-right:1px solid #000;"><strong>Discount</strong></td>
                                    <td colspan="2" id="totalDiscount"><strong>&nbsp;- ₹<?php e(isCheckVal(number_format($discount,2))); ?></strong></td>
                                    </tr>
                                    <?php } ?>
                                    <?php if($order->return_jewellery > 0){ ?>
                                    <tr>
                                    <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                                    <td style="border-right:1px solid #000;"><strong>Return Jewellery</strong></td>
                                    <td colspan="2" id="totalDiscount"><strong>- ₹<?php e(isCheckVal(number_format($order->return_jewellery,2))); ?></strong></td>
                                    </tr>
                                    <?php } ?>
                                    <tr>
                                    <td></td><td colspan="6"><b>Rs. <?php e($this->Ecommerce->convertInWords($order->amount)); ?></b></td><td></td><td></td><td></td>
                                    <td style="border-right:1px solid #000;"><strong>Closing Balance</strong></td>
                                    <td colspan="2" id="totalDiscount"><strong>&nbsp;₹<?php e(isCheckVal(number_format($order->amount,2))); ?></strong></td>
                                    </tr>
                                </tbody>                                
                            </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>