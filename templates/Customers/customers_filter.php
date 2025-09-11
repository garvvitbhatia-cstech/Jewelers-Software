<table class="table table-bordered table-hover table-striped">
<thead>
   <tr>
      <th>S.No.</th>
      <th>Customer Detail</th>
      <th style="text-align:center;">Status</th>
      <th>Action</th>
   </tr>
</thead>
<tbody>
  <?php
      if(count($customers) > 0){
      foreach($customers as $key => $user){
          $isExist = 0;
  ?>
   <tr>
      <td width="5%"><?php e($key+1); ?>.</td>
      <td>
         <b>Name:</b> <?php e(ucwords(isCheckVal($user->name)));?><br>
         <b>Email:</b> <?php e(isCheckVal($user->email));?><br>
         <b>Phone:</b> <?php e(ucwords(isCheckVal($user->contact)));?><br />
         <b>Address:</b> <?php e(nl2br($user->address));?><br>                                                                  
         <?php e(date("F jS, Y h:i A",$user->created)); ?>
      </td>
      <td style="text-align:center;" width="5%">
         <?php $status = $user->status == 1 ? "<i class='fa fa-check'></i>" : "<i class='fa fa-times'></i>"; ?>
         <?php $class = $user->status == 1 ? "success" : "danger"; ?>
         <a id="statusBtn_<?= $user->id ?>" onclick="changeStatus('Customers','<?= $this->encryptData($user->id); ?>','<?= $user->status ?>','<?= $user->id; ?>');" class="btn btn-<?php e($class);?> btn-circle"><?php e($status);?></a>
         <input type="hidden" id="current_status<?= $user->id ?>" value="<?= $user->status ?>" />
      </td>
       <td width="30%">
       <a href="<?php e($this->Url->build(ADMIN_FOLDER.'/view-customer/'.base64_encode($this->encryptData($user->id))));?>" title="View" class="btn btn-primary">View</a>
       <a href="<?php e($this->Url->build(ADMIN_FOLDER.'/edit-customer/'.base64_encode($this->encryptData($user->id))));?>" title="Edit" class="btn btn-success">Edit</a>
         <a href="javascript:void(0);" onclick="deleteRecord('Customers','<?php e(base64_encode($this->encryptData($user->id))); ?>','0');" title="Delete" class="btn btn-danger">Delete</a>
      </td>
   </tr>
   <?php
      }                              
      }else{
   ?>
   <tr>
      <td class="text-center" colspan="8">Records are not found.</td>
   </tr>
   <?php } ?>
</tbody>
<?php if($customers->count() > 0){ ?>
<tbody>
   <tr>
      <td align="center" colspan="12">
         <ul class="pagination">
            <?php
               $this->Paginator->options(array('update' => '#replaceHtml', 'evalScripts' => true, 'escape' => false, 'url' => array_merge(array('controller' => 'Customers', 'action' => 'customersFilter'))));?>
            <?php echo $this->Paginator->first('First'); ?>
            <?php echo $this->Paginator->numbers(); ?>
            <?php echo $this->Paginator->last('Last'); ?>
         </ul>
      </td>
   </tr>
</tbody>
<?php } ?>
</table>