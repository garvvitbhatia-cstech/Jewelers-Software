<?php
   #set page meta content   
   $this->assign('title', SITE_TITLE.' :: Sales Items Report');   
   $this->assign('meta_robot', 'noindex, nofollow');   
   e($this->Element('/admin/jQuery'));   
?>
<?= $this->Html->css('/css/jquery-ui');?>
<?= $this->Html->script('/js/jquery-ui');?>
<!--  page-wrapper -->
<div id="page-wrapper">
   <div class="row">
      <div id="myProgress" style="display:block;width: 100%;background-color: #ddd;">
         <div id="myBar"></div>
      </div>
      <!-- page header -->
      <div class="col-lg-12">
         <h1 class="page-header">Sales Items Report</h1>
      </div>
      <!--end page header -->
   </div>
   <div class="panel panel-primary">
      <div class="panel-heading">
         <?= $this->Form->create(NULL, array('action' => $this->Url->build('/exports/exportSalesItems'),'id' => 'searchForm', 'class' => 'searchForm', 'type' => 'post')) ?>
         <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
            Search By
            <span class="caret"></span>
            </button>
            <ul class="dropdown-menu pull-right" role="menu">
               <li><a href="javascript:void(0);" onclick="searchoptions('invoice_no');">Invoice ID</a></li>
               <li><a href="javascript:void(0);" onclick="searchoptions('sdate');">Date</a></li>
            </ul>
         </div>
         <input type="text" name="start_date" id="start_date" placeholder="Start Date" autocomplete="off" class="form-control filter searchOptions"  style="width:200px !important; display:none;">
        <input type="text" name="end_date" id="end_date" placeholder="End Date" autocomplete="off" class="form-control filter searchOptions"  style="width:200px !important; display:none;">
         <input name="invoice_no" id="invoice_no" placeholder="Invoice ID" class="form-control filter searchOptions"  style="width:200px !important; display:none;">
         
         <a style="display:none;" onclick="searchData();" class="btn btn-info searchbuttons" id="searchbuttons">Search</a>
         <a style="display:none;" onclick="resetFilterForm();" class="btn btn-danger searchbuttons">Reset</a>
         
         <input style="float: right;" type="submit" name="export" title="Export" class="btn btn-info" id="export" value="Export">
         <?= $this->Form->end(); ?>
      </div>
      <input type="hidden" value="<?php e($this->Url->build(ADMIN_FOLDER.'/sales-item-report-filter/'));?>" id="paginatUrl">
      <div class="panel-body">
         <div class="row">
            <div class="col-lg-12">
               <div class="table-responsive">
                  <div id="replaceHtml">
                     <table class="table table-bordered table-hover table-striped">
                        <thead>
                           <tr>
                              <th>S.No.</th>
                              <th>Customer Detail</th>
                           </tr>
                        </thead>
                        <tbody>
                          <?php
                              if(count($billings) > 0){
							  foreach($billings as $key => $bill){
								  $isExist = 0;
						  ?>
                           <tr>
                              <td width="5%"><?php e($key+1); ?>.</td>
                              <td>
                                 <b>Invoice ID:</b> <?php e(ucwords(isCheckVal($bill->invoice_no)));?><br>       
                                 <b>Name:</b> <?php e(ucwords(isCheckVal($bill->customer_name)));?><br>                                 
                                 <b>Phone:</b> <?php e(ucwords(isCheckVal($bill->customer_contact)));?><br />
                                 <b>Delivery Address:</b> <?php e(isCheckVal($bill->delivery_address));?><br>
                                 <b>Payment Type:</b> <?php e(nl2br($bill->payment_type));?><br>                                                                  
                                 <?php e(date("F jS, Y h:i A",$bill->created)); ?>
                              </td>                        
                               
                           </tr>
                           <?php
                              }                              
                              }else{
                           ?>
                           <tr>
                              <td class="text-center" colspan="3">Records are not found.</td>
                           </tr>
                           <?php } ?>
                        </tbody>
                        <?php if($billings->count() > 0){ ?>
                        <tbody>
                           <tr>
                              <td align="center" colspan="12">
                                 <ul class="pagination">
                                    <?php
                                       $this->Paginator->options(array('update' => '#replaceHtml', 'evalScripts' => true, 'escape' => false, 'url' => array_merge(array('controller' => 'Reports', 'action' => 'salesItemsReportFilter'))));?>
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
$(document).ready(function(e){
	$('#phone').filter_input({regex:'[0-9]'});
    $("#start_date").datepicker({dateFormat: 'dd-mm-yy',changeYear: true, minYear: 2020,changeMonth: true,maxDate: 0});
    $("#end_date").datepicker({dateFormat: 'dd-mm-yy',changeYear: true,minYear: 2020,changeMonth: true, maxDate: 0});
});

function searchoptions(search_options){
   $('.searchOptions').hide().val('');
   $('.searchbuttons').hide();
   if(search_options != ''){
	   if(search_options == 'sdate'){
		$('#start_date').show().focus();
		$('#end_date').show();
	   }else{
		$('#'+search_options).show().focus();
	   }
	   $('.searchbuttons').show();
   }
}
/* search form */
function searchData(){
	$('#searchbuttons').html('Searching...');
	$.ajax({
		type: 'POST',
		url: '<?php e($this->Url->build(ADMIN_FOLDER.'/sales-item-report-filter/'));?>',
		data: $('#searchForm').serialize(),
		success: function(msg){
			$('#replaceHtml').html(msg);
			$('#searchbuttons').html('Search');
			return false;
		},
		error: function(ts){
			$('#searchbuttons').html('Search');
			$('#error500').modal('show');
		}
	});
}   
</script>