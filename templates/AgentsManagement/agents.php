<?php
#set page meta content
$this->assign('title', SITE_TITLE.' :: Sub Admin Management');
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
        <h1 class="page-header">Sub Admin Management</h1>
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
                <li><a href="javascript:void(0);" onclick="searchoptions('first_name');">First Name</a></li>
                <li><a href="javascript:void(0);" onclick="searchoptions('last_name');">Last Name</a></li>
                <li><a href="javascript:void(0);" onclick="searchoptions('username');">Username</a></li>
                <li><a href="javascript:void(0);" onclick="searchoptions('email');">Email</a></li>                
                <li><a href="javascript:void(0);" onclick="searchoptions('status');">Action</a></li>
            </ul>
        </div>
        <input name="username" id="username" placeholder="Username" class="form-control filter searchOptions"  style="width:200px !important; display:none;">
        <input name="first_name" id="first_name" placeholder="First Name" class="form-control filter searchOptions"  style="width:200px !important; display:none;">
        <input name="last_name" id="last_name" placeholder="Last Name" class="form-control filter searchOptions"  style="width:200px !important; display:none;">
        <input name="email" id="email" placeholder="Email" class="form-control filter searchOptions"  style="width:200px !important; display:none;">
        <input name="contact" id="contact" placeholder="Phone Number" class="form-control filter searchOptions"  style="width:200px !important; display:none;">
        <select style="width:200px !important; display:none;"  name="status" id="status" class="form-control filter searchOptions">
            <option value="">Status</option>
            <option value="1">Active</option>
            <option value="2">Inactive</option>
        </select>
        <a style="display:none;" onclick="searchData();" class="btn btn-info searchbuttons" id="searchbuttons">Search</a>
        <a style="display:none;" onclick="resetFilterForm();" class="btn btn-danger searchbuttons">Reset</a>
        <?= $this->Form->end() ?>
        <a style="float:right;" href="<?php e($this->Url->build(ADMIN_FOLDER.'/add-agent/'));?>" title="Add Sub Admin" class="btn btn-default">Add Sub-Admin</a>
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
                                    <th>S.No.</th>
                                    <th>User Details</th>
                                    <th style="text-align:center;">Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                                                        
                            <tbody>
                                <?php
                                if(count($agents) > 0){
                                    foreach($agents as $key => $agent){
									$isExist = 0;
                                ?>
                                        <tr>
                                            <td width="5%"><?php e($key+1); ?>.</td>
                                            <td>
												<b>Name: </b><?php e(ucwords(isCheckVal($agent->first_name.' '.$agent->last_name)));?><br />                
                                                <b>Email: </b><?php e(isCheckVal($this->decryptData($agent->email)));?><br />
                                                <b>Username: </b><?php e(isCheckVal($this->decryptData($agent->username)));?><br />
                                                <b>Password: </b><?php e(isCheckVal($this->decryptData($agent->password)));?><br />
                                                <?php e(date("F jS, Y h:i A",$agent->created)); ?>
                                            </td>
                                            <td style="text-align:center;" width="5%">
                                                <?php $status = $agent->status == 1 ? "<i class='fa fa-check'></i>" : "<i class='fa fa-times'></i>"; ?>
                                                <?php $class = $agent->status == 1 ? "success" : "danger"; ?>
                                                <a id="statusBtn_<?= $agent->id ?>" <?php if($isExist == 0){ ?> onclick="changeStatus('Users','<?= $this->encryptData($agent->id); ?>','<?= $agent->status ?>','<?= $agent->id; ?>');" <?php } ?> class="btn btn-<?php e($class);?> btn-circle" <?php if($isExist > 0){e('disabled');} ?>><?php e($status);?></a>
                                                <input type="hidden" id="current_status<?= $agent->id ?>" value="<?= $agent->status ?>" />
                                            </td>
                                            <td width="25%">
                                            <a href="<?php e($this->Url->build(ADMIN_FOLDER.'/edit-agent/'.base64_encode($this->encryptData($agent->id))));?>" title="Edit" class="btn btn-success">Edit</a>
                                            <a href="javascript:void(0);" onclick="deleteRecord('Users','<?php e(base64_encode($this->encryptData($agent->id))); ?>','0');" title="Delete" class="btn btn-danger">Delete</a>  
                                            <a href="<?php e($this->Url->build(ADMIN_FOLDER.'/permissions/'.base64_encode($this->encryptData($agent->id))));?>" title="Permissions" class="btn btn-info">Permissions</a>                                         
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
                            <?php if($agents->count() > 0){ ?>
                                <tbody>
                                    <tr>
                                        <td align="center" colspan="12">
                                            <ul class="pagination">
                                                <?php
                                                $this->Paginator->options(array('update' => '#replaceHtml', 'evalScripts' => true, 'escape' => false, 'url' => array_merge(array('controller' => 'AgentsManagement', 'action' => 'agentsFilter'))));?>
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
	$('#contact').filter_input({regex:'[0-9+ -_]'});
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
		url: '<?php e($this->Url->build(ADMIN_FOLDER.'/agents-filter/'));?>',
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