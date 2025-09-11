<table class="table table-bordered table-hover table-striped">
   <thead>
      <tr>
         <th>S.No.</th>
         <th>Vehicle Name</th>
         <th style="text-align:center;">Status</th>
         <th>Created</th>
         <th>Action</th>
      </tr>
   </thead>
   <tbody>
      <?php
         if(count($vehicles) > 0){
         
             foreach($vehicles as $key => $user){
                 $isExist = 0;
                 $isExist =$this->Admin->getVehicleExist($user->id);
         ?>
      <tr>
         <td><?php e($key+1); ?>.</td>
         <td><?php e(ucwords(isCheckVal($user->name)));?></td>
         <td style="text-align:center;">
            <?php $status = $user->status == 1 ? "<i class='fa fa-check'></i>" : "<i class='fa fa-times'></i>"; ?>
            <?php $class = $user->status == 1 ? "success" : "danger"; ?>
            <a id="statusBtn_<?= $user->id ?>" <?php if($isExist == 0){ ?> onclick="changeStatus('VehicleTypes','<?= $this->encryptData($user->id); ?>','<?= $user->status ?>','<?= $user->id; ?>');" <?php } ?> class="btn btn-<?php e($class);?> btn-circle" <?php if($isExist > 0){e('disabled');} ?>><?php e($status);?></a>
            <input type="hidden" id="current_status<?= $user->id ?>" value="<?= $user->status ?>" />
         </td>
         <td width="20%"><?php e(date("F jS, Y h:i A",$user->created)); ?></td>
         <td><a href="<?php e($this->Url->build(ADMIN_FOLDER.'/edit-vehicle/'.base64_encode($this->encryptData($user->id))));?>" title="Edit" class="btn btn-success">Edit</a>
            <?php if($isExist == 0) {?>
            <a href="javascript:void(0);" onclick="deleteRecord('VehicleTypes','<?php e(base64_encode($this->encryptData($user->id))); ?>','0');" title="Delete" class="btn btn-danger">Delete</a>
         </td>
         <?php } ?>
      </tr>
      <?php
         }
         
         }else{
         
         ?>
      <tr>
         <td class="text-center" colspan="5">Records are not found.</td>
      </tr>
      <?php } ?>
   </tbody>
   <?php if($vehicles->count() > 0){ ?>
   <tbody>
      <tr>
         <td align="center" colspan="12">
            <ul class="pagination">
               <?php
                  $this->Paginator->options(array('update' => '#replaceHtml', 'evalScripts' => true, 'escape' => false, 'url' => array_merge(array('controller' => 'Users', 'action' => 'vehiclesFilter'))));?>
               <?php echo $this->Paginator->first('First'); ?>
               <?php echo $this->Paginator->numbers(); ?>
               <?php echo $this->Paginator->last('Last'); ?>
            </ul>
         </td>
      </tr>
   </tbody>
   <?php } ?>
</table>