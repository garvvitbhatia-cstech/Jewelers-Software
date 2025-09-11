<?php
#set page meta content
$this->assign('title', SITE_TITLE.' :: Static Content Management');
$this->assign('meta_robot', 'noindex, nofollow');
e($this->Element('/admin/jQuery'));
?>
<!--  page-wrapper -->
<div id="page-wrapper">
<div class="row">
    <!-- page header -->
    <div class="col-lg-12">
        <h1 class="page-header">Static Content Management</h1>
    </div>
    <!--end page header -->
</div>
<div class="panel panel-primary">
    <div class="panel-heading">
        <i class="fa fa-edit fa-fw"></i>Static Content List
        <?= $this->Form->create(NULL, array('id' => 'searchForm', 'class' => 'searchForm', 'type' => 'post')) ?>
        <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                Search By
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu pull-right" role="menu">
                <li><a href="javascript:void(0);" onclick="searchoptions('section_name');">Section Name</a></li>
                <li><a href="javascript:void(0);" onclick="searchoptions('title');">Title</a></li>
                <li><a href="javascript:void(0);" onclick="searchoptions('status');">Action</a></li>
            </ul>
        </div>
        <input name="section_name" id="section_name" placeholder="Section Name" class="form-control filter searchOptions"  style="width:200px !important; display:none;">
        <input name="title" id="title" placeholder="Title" class="form-control filter searchOptions"  style="width:200px !important; display:none;">
        <select style="width:200px !important; display:none;"  name="status" id="status" class="form-control filter searchOptions">
            <option value="">Status</option>
            <option value="1">Active</option>
            <option value="2">Inactive</option>
        </select>
        <a style="display:none;" onclick="searchData();" class="btn btn-info searchbuttons" id="searchbuttons">Search</a>
        <a style="display:none;" onclick="resetFilterForm();" class="btn btn-danger searchbuttons">Reset</a>
        <?= $this->Form->end() ?>
    </div>
    <input type="hidden" value="<?php e($this->Url->build(ADMIN_FOLDER.'/static-content-filter/'));?>" id="paginatUrl">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive">
                    <div id="replaceHtml">
                        <table class="table table-bordered table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Section Name</th>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if(count($records) > 0){
                                    foreach($records as $key => $record){
                                        ?>
                                        <tr>
                                            <td><?php e($key+1); ?>.</td>
                                            <td><?php e(ucwords(isCheckVal($record->section_name)));?></td>
                                            <td width="40%"><?php e(nl2br(isCheckVal($record->title)));?></td>
                                            <td>
                                                <?php $status = $record->status == 1 ? "<i class='fa fa-check'></i>" : "<i class='fa fa-times'></i>"; ?>
                                                <?php $class = $record->status == 1 ? "success" : "danger"; ?>
                                                <a id="statusBtn_<?= $record->id ?>" onclick="changeStatus('StaticContent','<?= $this->encryptData($record->id); ?>','<?= $record->status ?>','<?= $record->id; ?>');" class="btn btn-<?php e($class);?> btn-circle"><?php e($status);?></a>
                                                <input type="hidden" id="current_status<?= $record->id ?>" value="<?= $record->status ?>" />
                                            </td>
                                            <td><a href="<?php e($this->Url->build(ADMIN_FOLDER.'/edit-static-content/'.base64_encode($this->encryptData($record->id))));?>" title="Edit" class="btn btn-success">Edit</a></td>
                                        </tr>
                                        <?php
                                    }
                                }else{
                                    ?>
                                    <tr>
                                        <td class="text-center" colspan="10">Records are not found.</td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                            <?php if($records->count() > 0){ ?>
                                <tbody>
                                    <tr>
                                        <td align="center" colspan="12">
                                            <ul class="pagination">
                                                <?php
                                                $this->Paginator->options(
                                                    array('update' => '#replaceHtml', 'evalScripts' => true, 'escape' => false, 'url' => array_merge(array('controller' => 'StaticContentManagement', 'action' => 'staticContentFilter'))));?>
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
<script>
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
		url: '<?php e($this->Url->build(ADMIN_FOLDER.'/static-content-filter/'));?>',
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
