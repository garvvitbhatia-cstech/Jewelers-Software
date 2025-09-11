<?php
#set page meta content
$this->assign('title', SITE_TITLE.' :: Header Navigation Management');
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
        <h1 class="page-header">Header Navigation Management</h1>
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
                <li><a href="javascript:void(0);" onclick="searchoptions('status');">Action</a></li>
            </ul>
        </div>
        <select style="width:200px !important; display:none;"  name="status" id="status" class="form-control filter searchOptions">
            <option value="">Status</option>
            <option value="1">Active</option>
            <option value="2">Inactive</option>
        </select>
        <a style="display:none;" onclick="searchData();" class="btn btn-info searchbuttons" id="searchbuttons">Search</a>
        <a style="display:none;" onclick="resetFilterForm();" class="btn btn-danger searchbuttons">Reset</a>
        <?= $this->Form->end() ?>
        <a style="float:right;" href="<?php e($this->Url->build(ADMIN_FOLDER.'/add-header-navigation/'));?>" title="Add Service" class="btn btn-default">Add Header Navigation</a>
    </div>
    <input type="hidden" value="<?php e($this->Url->build(ADMIN_FOLDER.'/header-navigation-filter/'));?>" id="paginatUrl">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive">
                    <div id="replaceHtml">
                        <table class="table table-bordered table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Title</th>
                                    <th style="text-align:center;">Status</th>
                                    <th>Created</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                                                        
                            <tbody>
                                <?php 
                                if(count($navigations) > 0){
                                    foreach($navigations as $key => $navigation){
									$isExist = 0;
									$type = $navigation->menu_type;
									$pageTitle = '';
									$root = '';
									if($type == 'custom'){
										$pageTitle = $navigation->title;
									}else{
										$pageTitle = $this->Admin->getTitleByPageId($navigation->menu_page_id);
									}
									if($navigation->parent_id > 0){
										$parentTitle = $this->Navigation->getHeaderPageTitle($navigation->parent_id);
										$root = $parentTitle.' ->';
									}
                                ?>                                
                                        <tr>
                                            <td><?php e($key+1); ?>.</td>
                                            <td><?php e(ucwords($root).ucwords(isCheckVal($pageTitle)));?></td>
                                            <td style="text-align:center;">
                                                <?php $status = $navigation->status == 1 ? "<i class='fa fa-check'></i>" : "<i class='fa fa-times'></i>"; ?>
                                                <?php $class = $navigation->status == 1 ? "success" : "danger"; ?>
                                                <a id="statusBtn_<?= $navigation->id ?>" <?php if($isExist == 0){ ?> onclick="changeStatus('HeaderNavigations','<?= $this->encryptData($navigation->id); ?>','<?= $navigation->status ?>','<?= $navigation->id; ?>');" <?php } ?> class="btn btn-<?php e($class);?> btn-circle" <?php if($isExist > 0){e('disabled');} ?>><?php e($status);?></a>
                                                <input type="hidden" id="current_status<?= $navigation->id ?>" value="<?= $navigation->status ?>" />
                                            </td>
                                            <td width="20%"><?php e(date("F jS, Y h:i A",$navigation->created)); ?></td>
                                            <td>
                                            <a href="<?php e($this->Url->build(ADMIN_FOLDER.'/edit-header-navigation/'.base64_encode($this->encryptData($navigation->id))));?>" title="Edit" class="btn btn-success">Edit</a>
                                            <a href="javascript:void(0);" onclick="deleteRecord('HeaderNavigations','<?php e(base64_encode($this->encryptData($navigation->id))); ?>','0');" title="Delete" class="btn btn-danger">Delete</a>
                                            </td>
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
                            <?php if($navigations->count() > 0){ ?>
                                <tbody>
                                    <tr>
                                        <td align="center" colspan="5">
                                            <ul class="pagination">
                                                <?php
                                                $this->Paginator->options(array('update' => '#replaceHtml', 'evalScripts' => true, 'escape' => false, 'url' => array_merge(array('controller' => 'CmsManagement', 'action' => 'headerNavigationFilter'))));?>
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
		url: '<?php e($this->Url->build(ADMIN_FOLDER.'/header-navigation-filter/'));?>',
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