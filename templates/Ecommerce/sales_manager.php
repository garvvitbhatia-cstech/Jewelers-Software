<?php
	#set page meta content
	$this->assign('title', SITE_TITLE.' :: Billing Management');
	$this->assign('meta_robot', 'noindex, nofollow');
	e($this->Element('/admin/jQuery'));
?>
<!--  page-wrapper -->
<div id="page-wrapper">
<div class="row">
    <div id="myProgress" style="display:block;width: 100%;background-color: #ddd;">
        <div id="myBar"></div>
    </div>
    <!-- page header -->
    <div class="col-lg-12">
        <h1 class="page-header">Manage Billings</h1>
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
                <li><a href="javascript:void(0);" onclick="searchoptions('invoice_id');">Invoice ID</a></li>
                <li><a href="javascript:void(0);" onclick="searchoptions('customer_name');">Customer Name</a></li>
                <li><a href="javascript:void(0);" onclick="searchoptions('customer_contact');">Customer Contact</a></li>                
            </ul>
        </div>
        <input name="invoice_id" id="invoice_id" placeholder="Invoice ID" class="form-control filter searchOptions"  style="width:200px !important; display:none;">
        <input name="customer_contact" id="customer_contact" placeholder="Customer Contact" class="form-control filter searchOptions"  style="width:200px !important; display:none;">
        <input name="customer_name" id="customer_name" placeholder="Customer Name" class="form-control filter searchOptions"  style="width:200px !important; display:none;">
        <a style="display:none;" onclick="searchData();" class="btn btn-info searchbuttons" id="searchbuttons">Search</a>
        <a style="display:none;" onclick="resetFilterForm();" class="btn btn-danger searchbuttons">Reset</a>
        <?= $this->Form->end() ?>
        <a style="float:right;" href="<?php e($this->Url->build(ADMIN_FOLDER.'/add-invoice/'));?>" title="Add Bill" class="btn btn-default">Add Bill</a>
    </div>
    <input type="hidden" value="<?php e($this->Url->build(ADMIN_FOLDER.'/sales-manager-filter/'));?>" id="paginatUrl">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive">
                    <div id="replaceHtml">
                        <table class="table table-bordered table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Customer Details</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if(count($invoices) > 0){
                                    foreach($invoices as $key => $invoice){
									$isExist = 0;
                                ?>
                                    <tr>
                                        <td width="5%"><?php e($key+1); ?>.</td>
                                        <td><?php 
											e('<b>Name: </b>'.ucwords(isCheckVal($invoice->customer_name)).'<br>');
											e('<b>Contact: </b>'.ucwords(isCheckVal($invoice->customer_contact)).'<br>');
											e('<b>Invoice: </b>'.ucwords(isCheckVal($invoice->invoice_no)).'<br>');
											e('<b>Date: </b>'.isCheckVal($invoice->date).'<br>');
											date("F jS, Y h:i A",$invoice->created);
										?></td>
                                        <td width="30%"><a href="<?php e($this->Url->build(ADMIN_FOLDER.'/view-invoice/'.base64_encode($this->encryptData($invoice->id))));?>" title="View" class="btn btn-primary">View</a>
                                        <a href="<?php e($this->Url->build(ADMIN_FOLDER.'/edit-invoice/'.base64_encode($this->encryptData($invoice->id))));?>" title="Edit" class="btn btn-success">Edit</a>
                                        <a href="javascript:void(0);" onclick="deleteRecord('Billings','<?php e(base64_encode($this->encryptData($invoice->id))); ?>','0');" title="Delete" class="btn btn-danger">Delete</a>
                                        </td>                                            
                                    </tr>
                                <?php
                                    }
                                }else{
                                ?>
                                    <tr>
                                        <td class="text-center" colspan="10">Records are not found.</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                            <?php if($invoices->count() > 0){ ?>
                                <tbody>
                                    <tr>
                                        <td align="center" colspan="12">
                                            <ul class="pagination">
                                                <?php
                                                $this->Paginator->options(array('update' => '#replaceHtml', 'evalScripts' => true, 'escape' => false, 'url' => array_merge(array('controller' => 'Ecommerce', 'action' => 'salesManagerFilter'))));?>
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
    $('.ordering').filter_input({regex:'[0-9]'});
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
		url: '<?php e($this->Url->build(ADMIN_FOLDER.'/sales-manager-filter/'));?>',
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