<?php
	#set page meta content
	$this->assign('title', SITE_TITLE.' :: View Customer Details');
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
            <h1 class="page-header">Customer Details</h1>
        </div>
        <!--end page header -->
    </div>
    <div class="row">
        <div class="col-lg-12">
            <a href="<?php e($this->Url->build(ADMIN_FOLDER.'customer-management'.'/'));?>" class="btn btn-info">Back To Listing</a><br />&nbsp;
        </div>
        <div class="col-lg-12">
            <!-- Form Elements -->
            <?php e($this->Flash->render()); 
				$customer = $orders[0];
			?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    View Customer Details
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group profile_row">
                                <label style="min-width: 250px;">Unique Id:</label>
                                <span><?php e(isCheckVal($customer->unique_id)); ?></span>
                            </div>
                            <div class="form-group profile_row">
                                <label style="min-width: 250px;">Customer Name:</label>
                                <span><?php e(isCheckVal($customer->name)); ?></span>
                            </div>
                            <div class="form-group profile_row">
                                <label style="min-width: 250px;">Customer Email:</label>
                                <span><?php e(isCheckVal($customer->email)); ?></span>
                            </div>
                            <div class="form-group profile_row">
                                <label style="min-width: 250px;">Customer Contact:</label>
                                <span><?php e(isCheckVal($customer->contact)); ?></span>
                            </div>
                            <div class="form-group profile_row">
                                <label style="min-width: 250px;">Customer Address:</label>
                                <span><?php e(isCheckVal($customer->address)); ?></span>
                            </div>
                            
                            
                            <?php if(isset($customer->orders) && count($customer->orders) > 0){ ?>
                            <h3>कच्ची रसीद</h3>
                            <div class="table-responsive">
                             	<table class="table table-bordered table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>S.No.</th>
                                        <th>Details</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach($customer->orders as $key => $order){ ?>
                                <tr>                                    	
                                	<td width="5%"><?php e($key+1); ?></td>
                                	<td>
										<b>Invoice: </b><?php e(isCheckVal($order->invoice_id)); ?><br />
                                        <b>Advance: </b><?php e(isCheckVal(number_format($order->advance_amt,2))); ?><br />
                                        <b>Delivery Date: </b><?php e(isCheckVal($order->delivery_date)); ?><br />
                                    </td>
                                    <td width="15%"><a href="<?php e($this->Url->build(ADMIN_FOLDER.'/view-order/'.base64_encode($this->encryptData($order->id))));?>" title="View Order" class="btn btn-primary">View Order</a></td>
                                </tr>
                                <?php } ?>
                                </tbody>
                                </table>	
                            </div>
                            <?php } ?>
                            
                            <?php if(isset($billings) && $billings->count() > 0){ ?>
                            <h3>Order Billing</h3>
                            <div class="table-responsive">
                             	<table class="table table-bordered table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>S.No.</th>
                                        <th>Details</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach($billings as $key => $bill){ ?>
                                
                                <tr>
                                	<td width="5%"><?php e($key+1); ?></td>
                                	<td>
										<b>Invoice: </b><?php e(isCheckVal($bill->invoice_no)); ?><br />
                                        <b>Amount: </b><?php e(number_format($bill->amount,2)); ?><br />
                                        <b>Order Date: </b><?php e(isCheckVal($bill->date)); ?><br />
                                    </td>
                                    <td width="15%"><a href="<?php e($this->Url->build(ADMIN_FOLDER.'/view-invoice/'.base64_encode($this->encryptData($bill->id))));?>" title="View Invoice" class="btn btn-primary">View Invoice</a></td>
                                </tr>
                                <?php } ?>
                                </tbody>
                                </table>	
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Form Elements -->
            <a href="<?php e($this->Url->build(ADMIN_FOLDER.'customer-management'.'/'));?>" class="btn btn-info">Back To Listing</a>
        </div>
    </div>
</div>