<?php
   #set page meta content   
   $this->assign('title', SITE_TITLE.' :: User Management');   
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
         <h1 class="page-header">User Management</h1>
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
               <li><a href="javascript:void(0);" onclick="searchoptions('name');">Name</a></li>
               <li><a href="javascript:void(0);" onclick="searchoptions('phone');">Phone No</a></li>
               <li><a href="javascript:void(0);" onclick="searchoptions('email');">Email</a></li>
               <li><a href="javascript:void(0);" onclick="searchoptions('status');">Action</a></li>
            </ul>
         </div>
         <input name="name" id="name" placeholder="Full Name" class="form-control filter searchOptions"  style="width:200px !important; display:none;">
         <input name="phone" id="phone" placeholder="Phone Number" maxlength="10" class="form-control filter searchOptions"  style="width:200px !important; display:none;">
         <input name="email" id="email" placeholder="Email" class="form-control filter searchOptions"  style="width:200px !important; display:none;">
         <select style="width:200px !important; display:none;"  name="status" id="status" class="form-control filter searchOptions">
            <option value="">Status</option>
            <option value="1">Active</option>
            <option value="2">Inactive</option>
         </select>
         <a style="display:none;" onclick="searchData();" class="btn btn-info searchbuttons" id="searchbuttons">Search</a>
         <a style="display:none;" onclick="resetFilterForm();" class="btn btn-danger searchbuttons">Reset</a>
         <?= $this->Form->end(); ?>
         <a style="float:right;" href="<?php e($this->Url->build(ADMIN_FOLDER.'/add-user/'));?>" title="Add User" class="btn btn-default">Add User</a>
      </div>
      <input type="hidden" value="<?php e($this->Url->build(ADMIN_FOLDER.'/users-filter/'));?>" id="paginatUrl">
      <div class="panel-body">
         <div class="row">
            <div class="col-lg-12">
               <div class="table-responsive">
                  <div id="replaceHtml">
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
});

function searchoptions(search_options) {
	$('.searchOptions').hide().val('');
	$('.searchbuttons').hide();
	if (search_options != '') {
		$('#' + search_options).show().focus();
		$('.searchbuttons').show();
	}
}
/* search form */
function searchData() {
	$('#searchbuttons').html('Searching...');
	$.ajax({
		type: 'POST',
		url: '<?php e($this->Url->build(ADMIN_FOLDER.'/users-filter/'));?>',
		data: $('#searchForm').serialize(),
		success: function (msg) {
			$('#replaceHtml').html(msg);
			$('#searchbuttons').html('Search');
			return false;
		},
		error: function (ts) {
			$('#searchbuttons').html('Search');
			$('#error500').modal('show');
		}
	});
}   
</script>