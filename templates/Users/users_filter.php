<table class="table table-bordered table-hover table-striped">
<thead>
   <tr>
      <th>S.No.</th>
      <th>User Details</th>
      <th>Profile</th>
      <th style="text-align:center;">Status</th>
      <th>Action</th>
   </tr>
</thead>
<tbody>
  <?php
      if(count($users) > 0){
      foreach($users as $key => $user){
          $isExist = 0;
          $isExist =$this->Admin->getUserExist($user->id);
          $state = $this->State->getStateNameById($user->state_id);
          $city = $this->State->getCityNameById($user->city_id);
  ?>
  <tr>
      <td width="5%"><?php e($key+1); ?>.</td>
      <td>
         <b>Name:</b> <?php e(ucwords(isCheckVal($user->name)));?><br>
         <b>Email:</b> <?php e(isCheckVal($this->decryptData($user->email)));?><br>
         <b>Phone:</b> <?php e(ucwords(isCheckVal($user->phone)));?><br>
         <b>Password:</b> <?php e(ucwords(isCheckVal($this->decryptData($user->password))));?><br />
         <?php e(date("F jS, Y h:i A",$user->created)); ?><br />
         
         <b>Address:</b> <?php e(ucwords(isCheckVal($user->address)));?><br>
         <b>State:</b> <?php e(ucwords(isCheckVal($state)));?><br>
         <b>City:</b> <?php e(ucwords(isCheckVal($city)));?><br>
         <b>Pincode:</b> <?php e(ucwords(isCheckVal($user->pincode)));?>
      </td>
      <td><?php 
         if($user->profile != "" && file_exists(WWW_ROOT.'img/users/'.$user->profile)){                               
            e($this->Html->image('users/'.$user->profile, array('width'=>'90px','title'=>ucwords($user->name),'alt'=>ucwords($user->name))));
         }else{                                 
            e(isCheckVal());                                 
         }                                 
         ?></td>
      <td style="text-align:center;" width="5%">
         <?php $status = $user->status == 1 ? "<i class='fa fa-check'></i>" : "<i class='fa fa-times'></i>"; ?>
         <?php $class = $user->status == 1 ? "success" : "danger"; ?>
         <a id="statusBtn_<?= $user->id ?>" onclick="changeStatus('Users','<?= $this->encryptData($user->id); ?>','<?= $user->status ?>','<?= $user->id; ?>');" class="btn btn-<?php e($class);?> btn-circle"><?php e($status);?></a>
         <input type="hidden" id="current_status<?= $user->id ?>" value="<?= $user->status ?>" />
      </td>
      <td width="20%"><a href="<?php e($this->Url->build(ADMIN_FOLDER.'/edit-user/'.base64_encode($this->encryptData($user->id))));?>" title="Edit" class="btn btn-success">Edit</a>
         <!--<a href="<?php e($this->Url->build(ADMIN_FOLDER.'/transaction-history/'.base64_encode($this->encryptData($user->id))));?>" title="Wallet History" class="btn btn-info">Wallet History</a>-->
         <a href="javascript:void(0);" onclick="deleteRecord('Users','<?php e(base64_encode($this->encryptData($user->id))); ?>','0');" title="Delete" class="btn btn-danger">Delete</a>
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
<?php if($users->count() > 0){ ?>
<tbody>
   <tr>
      <td align="center" colspan="12">
         <ul class="pagination">
            <?php
               $this->Paginator->options(array('update' => '#replaceHtml', 'evalScripts' => true, 'escape' => false, 'url' => array_merge(array('controller' => 'Users', 'action' => 'usersFilter'))));?>
            <?php echo $this->Paginator->first('First'); ?>
            <?php echo $this->Paginator->numbers(); ?>
            <?php echo $this->Paginator->last('Last'); ?>
         </ul>
      </td>
   </tr>
</tbody>
<?php } ?>
</table>