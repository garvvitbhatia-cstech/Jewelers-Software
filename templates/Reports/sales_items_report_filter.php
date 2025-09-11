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