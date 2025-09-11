<?php
   #set page meta content
   
   $this->assign('title', SITE_TITLE.' :: Wallet History');
   
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
         <h1 class="page-header"><?= $users->name; ?> Wallet History </h1>
      </div>
      <!--end page header -->
   </div>
   <div class="panel panel-primary">
      <div class="panel-heading">
         <?= $this->Form->create(NULL, array('id' => 'searchForm', 'class' => 'searchForm', 'type' => 'post')) ?>
         <a href="<?php e($this->Url->build(ADMIN_FOLDER.'user-management'.'/'));?>" class="btn btn-info">Back To Listing</a>&nbsp;
         <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
            Search By
            <span class="caret"></span>
            </button>
            <ul class="dropdown-menu pull-right" role="menu">
               <li><a href="javascript:void(0);" onclick="searchoptions('type');">Type</a></li>
            </ul>
         </div>
         <select style="width:200px !important; display:none;"  name="type" id="type" class="form-control filter searchOptions">
            <option value="">Transaction Type</option>
            <option value="Deposit">Deposit</option>
            <option value="Withdrawal">Withdrawal</option>
         </select>
         <input type="hidden" value="<?= $users->id; ?>" name="userID">
         <a style="display:none;" onclick="searchData();" class="btn btn-info searchbuttons" id="searchbuttons">Search</a>
         <a style="display:none;" onclick="resetFilterForm();" class="btn btn-danger searchbuttons">Reset</a>
         <?= $this->Form->end() ?>
         <a style="float:right;" title="Available Balance" class="btn btn-default">Wallet <i class="fa fa-rupee"></i> <?= number_format($users->wallet,2); ?></a>
      </div>
      <input type="hidden" value="<?php e($this->Url->build(ADMIN_FOLDER.'/transaction-history-filter/'));?>" id="paginatUrl">
      <div class="panel-body">
         <div class="row">
            <div class="col-lg-12">
               <div class="table-responsive">
                  <div id="replaceHtml">
                     <table class="table table-bordered table-hover table-striped">
                        <thead>
                           <tr>
                              <th>S.No.</th>
                              <th style="text-align:center;">Transaction Type</th>
                              <th style="text-align:center;"> Amount <i class="fa fa-rupee"></i></th>
                              <th style="text-align:center;">Transaction ID</th>
                              <th style="text-align:center;">Date</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php if(count($all_history) > 0){
                              foreach($all_history as $key => $history){
                              ?>
                           <tr>
                              <td><?php e($key+1); ?>.</td>
                              <td style="text-align:center;"><?php e($history->type); ?></td>
                              <td style="text-align:center;"><?php if($history->type == 'Deposit'){ ?>
                                 <span style="color: green"> <?= number_format($history->amount,2); ?></span> 
                                 <?php  }else{ ?>
                                 <span style="color: red"> <?= number_format($history->amount,2); ?></span>
                                 <?php } ?>
                              </td>
                              <td style="text-align:center;"><?php e($history->payment_id); ?></td>
                              <td width="20%"><?php e(date("F jS, Y h:i A",$history->created)); ?></td>
                           </tr>
                           <?php }
                              }else{ ?>
                           <tr>
                              <td class="text-center" colspan="5">Records are not found.</td>
                           </tr>
                           <?php } ?>
                        </tbody>
                        <?php if($all_history->count() > 0){ ?>
                        <tbody>
                           <tr>
                              <td align="center" colspan="12">
                                 <ul class="pagination">
                                    <?php
                                       $this->Paginator->options(array('update' => '#replaceHtml', 'evalScripts' => true, 'escape' => false, 'url' => array_merge(array('controller' => 'Users', 'action' => 'walletHistoryFilter', $users->id))));?>
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
   
           url: '<?php e($this->Url->build(ADMIN_FOLDER.'/transaction-history-filter/'));?>',
   
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