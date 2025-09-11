<?php
#set page meta content
$this->assign('title', SITE_TITLE.' :: Permissions');
$this->assign('meta_robot', 'noindex, nofollow');
e($this->Element('/admin/jQuery'));
?>
<style>
	.checkbox_permission{width: 21px;height: 21px;}
</style>
<!--  page-wrapper -->
<div id="page-wrapper">
<div class="row">
    <div id="myProgress" style="display:block;width: 100%;background-color: #ddd;">
        <div id="myBar"></div>
    </div>
    <!-- page header -->
    <div class="col-lg-12">
        <h1 class="page-header">Permissions</h1>
    </div>    
    <!--end page header -->
</div>
<?php e($this->Flash->render()); ?>
<div class="panel panel-primary">
    <div class="panel-heading">
        <div class="btn-group">
        	<a href="<?php e($this->Url->build(ADMIN_FOLDER.'agent-management'.'/'));?>" class="btn btn-info">Back To Listing</a> 
        </div>
    </div>
    <input type="hidden" value="<?php e($this->Url->build(ADMIN_FOLDER.'/agents-filter/'));?>" id="paginatUrl">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive">
                    <div id="replaceHtml">
                        <table class="table table-bordered table-hover table-striped">
                            <thead>
                                <tr>
                                    <th colspan="2" style="text-align:center;">Select All</th>
                                    <th style="text-align:center;"><input class="checkbox_permission" type="checkbox" id="checkAllList" name="checkAllList[]" /></th>
                                    <th style="text-align:center;"><input class="checkbox_permission" type="checkbox" id="checkAllAdd" name="checkAllAdd[]" /></th>
                                    <th style="text-align:center;"><input class="checkbox_permission" type="checkbox" id="checkAllEdit" name="checkAllEdit[]" /></th>
                                    <th style="text-align:center;"><input class="checkbox_permission" type="checkbox" id="checkAllView" name="checkAllView[]" /></th>
                                    <th style="text-align:center;"><input class="checkbox_permission" type="checkbox" id="checkAllDelete" name="checkAllDelete[]" /></th>
                                </tr>
                            </thead>
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Tables</th>
                                    <th style="text-align:center;">List</th>
                                    <th style="text-align:center;">Add</th>
                                    <th style="text-align:center;">Edit</th>
                                    <th style="text-align:center;">View</th>
                                    <th style="text-align:center;">Delete</th>
                                </tr>
                            </thead>
                                                        
                            <tbody>
                            	<?= $this->Form->create(NULL,array('id' => 'editForm', 'inputDefaults' => array('label' => false,'div' => false), 'name' => 'editForm', 'csrfToken' => $this->request->getAttribute('csrfToken')));?>
                                <input type="hidden" name="administrators_id" value="<?php e(base64_encode($this->encryptData($administrators_id))); ?>"/>
                                <?php
                                if(count($tables) > 0){
                                    foreach($tables as $key => $table){
										$permissionId = $list = $add = $edit = $view = $remove = 0;
										$permission = $this->Admin->getPermission($administrators_id,$table);
										if(isset($permission->id)){
											$permissionId = $permission->id;
											$list = $permission->list;
											$add = $permission->addon;
											$edit = $permission->edit;
											$view = $permission->view;
											$remove = $permission->remove;																			
										}
                                ?>
                                    <tr> 
                                    	<input type="hidden" name="data[permission][<?php e($table) ?>]" value="<?php e($permissionId) ?>"/>
                                        <td width="5%"><?php e($key+1); ?>.</td>
                                        <td><?php e($table); ?></td>
                                        <td style="text-align:center;">
                                            <input type="hidden" name="data[list][<?php e($table) ?>]" value="2" />
                                            <input class="checkbox_permission list" type="checkbox" id="list" name="data[list][<?php e($table) ?>]" <?php if($list == 1){e('checked');} ?> value="1" />
                                        </td>
                                        <td style="text-align:center;">
                                        	<input type="hidden" name="data[add][<?php e($table) ?>]" value="2" />
                                            <input class="checkbox_permission add" type="checkbox" id="add" name="data[add][<?php e($table) ?>]" <?php if($add == 1){e('checked');} ?> value="1" />        	</td>                                        
                                        <td style="text-align:center;">
                                        	<input type="hidden" name="data[edit][<?php e($table) ?>]" value="2" />
                                        	<input class="checkbox_permission edit" type="checkbox" id="edit" name="data[edit][<?php e($table) ?>]" <?php if($edit == 1){e('checked');} ?> value="1" />
                                        </td>
                                        <td style="text-align:center;">
                                        	<input type="hidden" name="data[view][<?php e($table) ?>]" value="2" />
                                        	<input class="checkbox_permission view" type="checkbox" id="view" name="data[view][<?php e($table) ?>]" <?php if($view == 1){e('checked');} ?> value="1" />
                                       	</td>
                                        <td style="text-align:center;">
                                        	<input type="hidden" name="data[delete][<?php e($table) ?>]" value="2" />
                                        	<input class="checkbox_permission delete" type="checkbox" id="delete" name="data[delete][<?php e($table) ?>]" <?php if($remove == 1){e('checked');} ?> value="1" />
                                       	</td>
                                    </tr>
                                <?php
                                    }									
                                }else{
                                ?>
                                <tr>
                                    <td class="text-center" colspan="7">Records are not found.</td>
                                </tr>
                                <?php } ?>
                            </tbody>
                            </table>
                            <button type="button" class="btn btn-primary submitBtn" id="submitBtn">Submit</button>
                        	<?= $this->Form->end(); ?>
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

	$(document).on('click','#submitBtn',function(){
		$('#editForm').submit();
	});

	$('#checkAllList').click(function(){   
    	$('.list').prop('checked', this.checked);
		if($(this).prop("checked") == false){
			$('.add').prop('checked', false);
			$('.edit').prop('checked', false);
			$('.view').prop('checked', false);
			$('.delete').prop('checked', false);
		}
 	});
	$('#checkAllAdd').click(function () {    
    	$('.add').prop('checked', this.checked);    
 	});
	$('#checkAllEdit').click(function () {    
    	$('.edit').prop('checked', this.checked);    
 	});
	$('#checkAllView').click(function () {    
    	$('.view').prop('checked', this.checked);    
 	});
	$('#checkAllDelete').click(function () {    
    	$('.delete').prop('checked', this.checked);    
 	});
 
</script>