<?php
	#set page meta content
	$this->assign('title', SITE_TITLE.' :: Order Management');
	$this->assign('meta_robot', 'noindex, nofollow');
	e($this->Element('/admin/jQuery'));
?>
<!--  page-wrapper -->
<div id="page-wrapper">
<div class="row">
    <!-- page header -->
    <div class="col-lg-12">
        <h1 class="page-header">Order Management</h1>
    </div>
    <!--end page header -->
</div>
<div class="panel panel-primary">
    <div class="panel-heading">
        <?= $this->Form->create(NULL, array('id' => 'searchForm', 'class' => 'searchForm', 'type' => 'post')) ?>
        <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                Search By
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu pull-right" role="menu">                
                <li><a href="javascript:void(0);" onclick="searchoptions('customer_name');">Customer Name</a></li>
                <li><a href="javascript:void(0);" onclick="searchoptions('customer_contact');">Customer Contact</a></li>
                <li><a href="javascript:void(0);" onclick="searchoptions('delivery_date');">Delivery Date</a></li>
                <li><a href="javascript:void(0);" onclick="searchoptions('status');">Status</a></li>
            </ul>
        </div>
        <select name="delivery_date" id="delivery_date" placeholder="Delivery Date" class="form-control filter searchOptions" style="width:200px !important; display:none;">
        	<option value="">Delivery Date</option>
            <option value="Ascending">Ascending</option>
            <option value="Descending">Descending</option>
        </select>
        <select name="status" id="status" placeholder="Status" class="form-control filter searchOptions" style="width:200px !important; display:none;">
        	<option value="">Status</option>
            <option value="2">Pending</option>
            <option value="1">Completed</option>
        </select>
        <input type="text" name="customer_name" id="customer_name" placeholder="Customer Name" class="form-control filter searchOptions"  style="width:200px !important; display:none;">
        <input type="text" name="customer_contact" id="customer_contact" placeholder="Customer Contact" class="form-control filter searchOptions"  style="width:200px !important; display:none;">
        <a style="display:none;" onclick="searchData();" class="btn btn-info searchbuttons" id="searchbuttons">Search</a>
        <a style="display:none;" onclick="resetFilterForm();" class="btn btn-danger searchbuttons">Reset</a>
        <?= $this->Form->end() ?>
        <a style="float:right;" href="<?php e($this->Url->build(ADMIN_FOLDER.'add-order'.'/'));?>" class="btn btn-default">Add Order</a>
    </div>
    <input type="hidden" value="<?php e($this->Url->build(ADMIN_FOLDER.'/products-filter/'));?>" id="paginatUrl">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive">
                    <div id="replaceHtml">
                        <table class="table table-bordered table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>S.No.</th>                                    
                                    <th>Details</th>
                                    <th style="text-align:center;">Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if(count($orders) > 0){
                                    foreach($orders as $key => $order){
                                ?>
                                    <tr>
                                        <td width="5%"><?php e($key+1); ?>.</td>
                                        <td>
											<b>Name: </b><?php e(ucwords($order->customer_name)); ?><br />
                                            <b>Contact: </b><?php e(isCheckVal($order->customer_contact)); ?><br />
                                            <b>Address: </b><?php e(nl2br(isCheckVal($order->customer_address))); ?><br />
                                            <b>Approx. Delivery Date: </b><?php e(isCheckVal($order->delivery_date)); ?><br />
                                            <?php e(date("F jS, Y h:i A",$order->created)); ?>
                                        </td>
                                        <td style="text-align:center;" width="5%">
											<?php $status = $order->status == 1 ? "Completed" : "Pending"; ?>
                                            <?php $class = $order->status == 1 ? "success" : "danger"; ?>
                                            <a id="statusBtn_<?= $order->id ?>" onclick="changeStatus('Orders','<?= $this->encryptData($order->id); ?>','<?= $order->status ?>','<?= $order->id; ?>');" class="btn btn-<?php e($class);?>"><?php e($status);?></a>
                                            <input type="hidden" id="current_status<?= $order->id ?>" value="<?= $order->status ?>" />
                                        </td>                                       
                                        <td width="35%">
                                        <a href="<?php e($this->Url->build(ADMIN_FOLDER.'/view-order/'.base64_encode($this->encryptData($order->id))));?>" title="View" class="btn btn-primary">View</a>
                                        <a href="<?php e($this->Url->build(ADMIN_FOLDER.'/edit-order/'.base64_encode($this->encryptData($order->id))));?>" title="Edit" class="btn btn-success">Edit</a>
                                        <a href="javascript:void(0);" onclick="deleteRecord('Orders','<?php e(base64_encode($this->encryptData($order->id))); ?>','0');" title="Delete" class="btn btn-danger">Delete</a>
                                        </td>
                                    </tr>
                                <?php }
                                	}else{
                                ?>
                                    <tr>
                                        <td class="text-center" colspan="6">Records are not found.</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                            <?php if($orders->count() > 0){ ?>
                            <tbody>
                            	<tr>
                                	<td align="center" colspan="6">
                                    <ul class="pagination">
                                    <?php
                                    $this->Paginator->options(array('update' => '#replaceHtml', 'evalScripts' => true, 'escape' => false, 'url' => array_merge(array('controller' => 'Ecommerce', 'action' => 'ordersFilter'))));?>
                                        <?php echo $this->Paginator->first('First'); ?>
                                        <?php echo $this->Paginator->numbers(); ?>
                                        <?php echo $this->Paginator->last('Last'); ?>
                                    </ul>
                                    </td>
                                </tr>
                            </tbody>
                            <?php } ?>
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
$(document).ready(function(e) {
    $('#customer_contact').filter_input({regex:'[0-9]'});
});
function searchoptions(search_options){
    $('.searchOptions').hide().val('');
    $('.searchbuttons').hide();
    if(search_options != ''){
        $('#'+search_options).show().focus();
        $('.searchbuttons').show();
    }
}
/* search form */
function searchData(){
	$('#searchbuttons').html('Searching...');
	$.ajax({
		type: 'POST',
		url: '<?php e($this->Url->build(ADMIN_FOLDER.'/orders-filter/'));?>',
		data: $('#searchForm').serialize(),
		success: function(msg){
			$('#replaceHtml').html(msg);
			$('#searchbuttons').html('Search');
			return false;
		},error: function(ts){
            $('#searchbuttons').html('Search');
            $('#error500').modal('show');
        }
	});
}
</script>