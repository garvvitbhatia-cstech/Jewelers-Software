<?php
#set page meta content
$this->assign('title', SITE_TITLE.' :: Testimonials');
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
        <h1 class="page-header">Testimonials</h1>
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
        <a style="float:right;" href="<?php e($this->Url->build(ADMIN_FOLDER.'/add-testimonial/'));?>" title="Add Agent" class="btn btn-default">Add Testimonial</a>
    </div>
    <input type="hidden" value="<?php e($this->Url->build(ADMIN_FOLDER.'/testimonials-filter/'));?>" id="paginatUrl">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12">
            <?php e($this->Flash->render()); ?>
                <div class="table-responsive">
                    <div id="replaceHtml">
                        <table class="table table-bordered table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>User Name</th>
                                    <th style="text-align:center;">Status</th>
                                    <th>Created</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                                                        
                            <tbody>
                                <?php
                                if(count($testimonials) > 0){
                                    foreach($testimonials as $key => $testimonial){
									$isExist = 0;
                                ?>
                                        <tr>
                                            <td><?php e($key+1); ?>.</td>
                                            <td><?php e(ucwords(isCheckVal($testimonial->username)));?></td>
                                            <td style="text-align:center;">
                                                <?php $status = $testimonial->status == 1 ? "<i class='fa fa-check'></i>" : "<i class='fa fa-times'></i>"; ?>
                                                <?php $class = $testimonial->status == 1 ? "success" : "danger"; ?>
                                                <a id="statusBtn_<?= $testimonial->id ?>" <?php if($isExist == 0){ ?> onclick="changeStatus('Testimonials','<?= $this->encryptData($testimonial->id); ?>','<?= $testimonial->status ?>','<?= $testimonial->id; ?>');" <?php } ?> class="btn btn-<?php e($class);?> btn-circle" <?php if($isExist > 0){e('disabled');} ?>><?php e($status);?></a>
                                                <input type="hidden" id="current_status<?= $testimonial->id ?>" value="<?= $testimonial->status ?>" />
                                            </td>
                                            <td width="20%"><?php e(date("F jS, Y h:i A",$testimonial->created)); ?></td>
                                            <td>
                                            <a href="<?php e($this->Url->build(ADMIN_FOLDER.'/view-testimonial/'.base64_encode($this->encryptData($testimonial->id))));?>" title="View" class="btn btn-primary">View</a>
                                            <a href="<?php e($this->Url->build(ADMIN_FOLDER.'/edit-testimonial/'.base64_encode($this->encryptData($testimonial->id))));?>" title="View" class="btn btn-success">Edit</a>
                                            <a href="javascript:void(0);" onclick="deleteRecord('Testimonials','<?php e(base64_encode($this->encryptData($testimonial->id))); ?>','0');" title="Delete" class="btn btn-danger">Delete</a></td>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                }else{
                                ?>
                                    <tr>
                                        <td class="text-center" colspan="6">Records are not found.</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                            <?php if($testimonials->count() > 0){ ?>
                                <tbody>
                                    <tr>
                                        <td align="center" colspan="6">
                                            <ul class="pagination">
                                                <?php
                                                $this->Paginator->options(array('update' => '#replaceHtml', 'evalScripts' => true, 'escape' => false, 'url' => array_merge(array('controller' => 'AgentsManagement', 'action' => 'testimonialsFilter'))));?>
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
		url: '<?php e($this->Url->build(ADMIN_FOLDER.'/testimonials-filter/'));?>',
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