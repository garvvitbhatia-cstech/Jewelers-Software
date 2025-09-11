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