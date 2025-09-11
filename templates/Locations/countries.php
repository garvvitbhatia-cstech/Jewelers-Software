<?php
#set page meta content
$this->assign('title', SITE_TITLE.' :: Country Management');
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
        <h1 class="page-header">Country Management</h1>
    </div>    
    <!--end page header -->
</div>
<div class="panel panel-primary">
    <div class="panel-heading">
        <i class="fa fa-edit fa-fw"></i>Country List
        <?= $this->Form->create(NULL, array('id' => 'searchForm', 'class' => 'searchForm', 'type' => 'post')) ?>
        <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                Search By
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu pull-right" role="menu">
                <li><a href="javascript:void(0);" onclick="searchoptions('country_name');">Country Name</a></li>
                <li><a href="javascript:void(0);" onclick="searchoptions('country_code');">Country Code</a></li>
                <li><a href="javascript:void(0);" onclick="searchoptions('status');">Action</a></li>
            </ul>
        </div>
        <input name="country_name" id="country_name" placeholder="Country Name" class="form-control filter searchOptions"  style="width:200px !important; display:none;">
        <input name="country_code" id="country_code" placeholder="Country Code" class="form-control filter searchOptions"  style="width:200px !important; display:none;">
        <select style="width:200px !important; display:none;"  name="status" id="status" class="form-control filter searchOptions">
            <option value="">Status</option>
            <option value="1">Active</option>
            <option value="2">Inactive</option>
        </select>
        <a style="display:none;" onclick="searchData();" class="btn btn-info searchbuttons" id="searchbuttons">Search</a>
        <a style="display:none;" onclick="resetFilterForm();" class="btn btn-danger searchbuttons">Reset</a>
        <?= $this->Form->end() ?>
        <a style="float:right;" href="<?php e($this->Url->build(ADMIN_FOLDER.'/add-country/'));?>" title="Add Country" class="btn btn-default">Add Country</a>
    </div>
    <input type="hidden" value="<?php e($this->Url->build(ADMIN_FOLDER.'/countries-filter/'));?>" id="paginatUrl">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive">
                    <div id="replaceHtml">
                        <table class="table table-bordered table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Country Name</th>
                                    <th>Country Code</th>
                                    <th>Ordering</th>
                                    <th style="text-align:center;">Status</th>
                                    <th>Created</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if(count($countries) > 0){
                                    foreach($countries as $key => $country){
									$isExist = 0;
									$isExist = $this->Country->getStateExist($country->id);
                                ?>
                                        <tr>
                                            <td><?php e($key+1); ?>.</td>
                                            <td><?php e(ucwords(isCheckVal($country->country_name)));?></td>
                                            <td><?php e(isCheckVal($country->country_code));?></td>
                                            <td width="5%"><input type="text" style="text-align:center;" class="form-control ordering" onchange="saveOrder(<?php e($country->id); ?>,<?php e($country->ordering); ?>,'<?php e($this->encryptData('Countries')); ?>',this.value);" id="ordering" value="<?php e($country->ordering); ?>"/></td>
                                            <td style="text-align:center;">
                                                <?php $status = $country->status == 1 ? "<i class='fa fa-check'></i>" : "<i class='fa fa-times'></i>"; ?>
                                                <?php $class = $country->status == 1 ? "success" : "danger"; ?>
                                                <a id="statusBtn_<?= $country->id ?>" <?php if($isExist == 0){ ?> onclick="changeStatus('Countries','<?= $this->encryptData($country->id); ?>','<?= $country->status ?>','<?= $country->id; ?>');" <?php } ?> class="btn btn-<?php e($class);?> btn-circle" <?php if($isExist > 0){e('disabled');} ?>><?php e($status);?></a>
                                                <input type="hidden" id="current_status<?= $country->id ?>" value="<?= $country->status ?>" />
                                            </td>
                                            <td width="20%"><?php e(date("F jS, Y h:i A",$country->created)); ?></td>
                                            <td><a href="<?php e($this->Url->build(ADMIN_FOLDER.'/edit-country/'.base64_encode($this->encryptData($country->id))));?>" title="Edit" class="btn btn-success">Edit</a></td>
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
                            <?php if($countries->count() > 0){ ?>
                                <tbody>
                                    <tr>
                                        <td align="center" colspan="12">
                                            <ul class="pagination">
                                                <?php
                                                $this->Paginator->options(array('update' => '#replaceHtml', 'evalScripts' => true, 'escape' => false, 'url' => array_merge(array('controller' => 'Locations', 'action' => 'countriesFilter'))));?>
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
$(document).ready(function(e) {
    $('.ordering').filter_input({regex:'[0-9]'});
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
		url: '<?php e($this->Url->build(ADMIN_FOLDER.'/countries-filter/'));?>',
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