<?php
#set page meta content
$this->assign('title', SITE_TITLE.' :: State Management');
$this->assign('meta_robot', 'noindex, nofollow');
e($this->Element('/admin/jQuery'));
?>
<!--  page-wrapper -->
<div id="page-wrapper">
<div class="row">
    <!-- page header -->
    <div class="col-lg-12">
        <h1 class="page-header">State Management</h1>
    </div>
    <!--end page header -->
</div>
<div class="panel panel-primary">
    <div class="panel-heading">
        <i class="fa fa-edit fa-fw"></i>State List
        <?= $this->Form->create(NULL, array('id' => 'searchForm', 'class' => 'searchForm', 'type' => 'post')) ?>
        <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                Search By
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu pull-right" role="menu">
                <li><a href="javascript:void(0);" onclick="searchoptions('country_id');">Country Name</a></li>
                <li><a href="javascript:void(0);" onclick="searchoptions('state');">State Name</a></li>
                <li><a href="javascript:void(0);" onclick="searchoptions('status');">Action</a></li>
            </ul>
        </div>
        <select name="country_id" id="country_id" placeholder="Country Name" class="form-control filter searchOptions"  style="width:200px !important; display:none;">
        	<option value="">Country</option>
        	<?php if(isset($countryList) && !empty($countryList)){ ?>            	
            	<?php foreach($countryList as $key => $val): ?>
                	<option value="<?php e($key); ?>"><?php e($val); ?></option>
                <?php endforeach; ?>            	
            <?php } ?>
        </select>
        <input name="state" id="state" placeholder="State" class="form-control filter searchOptions"  style="width:200px !important; display:none;">
        <select style="width:200px !important; display:none;"  name="status" id="status" class="form-control filter searchOptions">
            <option value="">Status</option>
            <option value="1">Active</option>
            <option value="2">Inactive</option>
        </select>
        <a style="display:none;" onclick="searchData();" class="btn btn-info searchbuttons" id="searchbuttons">Search</a>
        <a style="display:none;" onclick="resetFilterForm();" class="btn btn-danger searchbuttons">Reset</a>
        <?= $this->Form->end() ?>
        <a style="float:right;" href="<?php e($this->Url->build(ADMIN_FOLDER.'/add-state/'));?>" title="Add Country" class="btn btn-default">Add State</a>
    </div>
    <input type="hidden" value="<?php e($this->Url->build(ADMIN_FOLDER.'/states-filter/'));?>" id="paginatUrl">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive">
                    <div id="replaceHtml">
                        <table class="table table-bordered table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>S.No.</th>                                    
                                    <th>State Name</th>
                                    <th>Country Name</th>
                                    <th style="text-align:center;">Status</th>
                                    <th>Created</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if(count($states) > 0){
                                    foreach($states as $key => $state){
									$isExistCity = $this->State->getCityExist($state->id);
                                ?>
                                    <tr>
                                        <td><?php e($key+1); ?>.</td>
                                        <td><?php e(isCheckVal($state->state));?></td>
                                        <td><?php e(ucwords(isCheckVal($state->country->country_name)));?></td>
                                        <td style="text-align:center;">
                                            <?php $status = $state->status == 1 ? "<i class='fa fa-check'></i>" : "<i class='fa fa-times'></i>"; ?>
                                            <?php $class = $state->status == 1 ? "success" : "danger"; ?>
                                            <a id="statusBtn_<?= $state->id ?>" <?php if($isExistCity == 0){ ?> onclick="changeStatus('States','<?= $this->encryptData($state->id); ?>','<?= $state->status ?>','<?= $state->id; ?>');" <?php } ?> class="btn btn-<?php e($class);?> btn-circle" <?php if($isExistCity > 0){e('disabled');} ?>><?php e($status);?></a>
                                            <input type="hidden" id="current_status<?= $state->id ?>" value="<?= $state->status ?>" />
                                        </td>
                                        <td width="20%"><?php e(date("F jS, Y h:i A",$state->created)); ?></td>
                                        <td><a href="<?php e($this->Url->build(ADMIN_FOLDER.'/edit-state/'.base64_encode($this->encryptData($state->id))));?>" title="Edit" class="btn btn-success">Edit</a></td>
                                    </tr>
                                <?php }
                                }else{
                                ?>
                                    <tr>
                                        <td class="text-center" colspan="6">Records are not found.</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                            <?php if($states->count() > 0){ ?>
                                <tbody>
                                    <tr>
                                        <td align="center" colspan="12">
                                            <ul class="pagination">
                                                <?php
                                                $this->Paginator->options(array('update' => '#replaceHtml', 'evalScripts' => true, 'escape' => false, 'url' => array_merge(array('controller' => 'Locations', 'action' => 'statesFilter'))));?>
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
		url: '<?php e($this->Url->build(ADMIN_FOLDER.'/states-filter/'));?>',
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