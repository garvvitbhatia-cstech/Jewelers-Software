<?php
#set page meta content
$this->assign('title', SITE_TITLE.' :: Distance Management');
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
        <h1 class="page-header">Distance Management</h1>
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
                <li><a href="javascript:void(0);" onclick="searchoptions('weight_type_id');">Weight Type</a></li>
                <li><a href="javascript:void(0);" onclick="searchoptions('weight');">Weight</a></li>
                <li><a href="javascript:void(0);" onclick="searchoptions('distance');">Distance</a></li>
                <li><a href="javascript:void(0);" onclick="searchoptions('price');">Price</a></li>
                <li><a href="javascript:void(0);" onclick="searchoptions('status');">Action</a></li>
            </ul>
        </div>
        <select name="weight_type_id" id="weight_type_id"  class="form-control filter searchOptions"  style="width:200px !important; display:none;" onchange="getWeightByType(this.value); checkError(this.id);">
            <option value="">Select Weight Type</option>
            <?php if(isset($weightType) && !empty($weightType)){ ?>               
                <?php foreach($weightType as $key => $val): ?>
                    <option value="<?php e($key); ?>"><?php e($val); ?></option>
                <?php endforeach; ?>                
            <?php } ?>
        </select>
        <select style="width:200px !important; display:none;"  name="weight_id" id="weight_id" class="form-control filter searchOptions">
            <option value="">Select Weight</option>
            
        </select>
         <input name="dist_from" id="dist_from" placeholder="From" class="form-control filter searchOptions"  style="width:200px !important; display:none;">
         <input name="dist_to" id="dist_to" placeholder="To" class="form-control filter searchOptions"  style="width:200px !important; display:none;">
        
        <input name="price" id="price" placeholder="Price" class="form-control filter searchOptions"  style="width:200px !important; display:none;">
        <select style="width:200px !important; display:none;"  name="status" id="status" class="form-control filter searchOptions">
            <option value="">Status</option>
            <option value="1">Active</option>
            <option value="2">Inactive</option>
        </select>
        <a style="display:none;" onclick="searchData();" class="btn btn-info searchbuttons" id="searchbuttons">Search</a>
        <a style="display:none;" onclick="resetFilterForm();" class="btn btn-danger searchbuttons">Reset</a>
        <?= $this->Form->end() ?>
        <a style="float:right;" href="<?php e($this->Url->build(ADMIN_FOLDER.'/add-distance/'));?>" title="Add Distance" class="btn btn-default">Add Distance</a>
    </div>
    <input type="hidden" value="<?php e($this->Url->build(ADMIN_FOLDER.'/distance-filter/'));?>" id="paginatUrl">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive">
                    <div id="replaceHtml">
                        <table class="table table-bordered table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Weight Type</th>
                                    <th>Weight</th>
                                    <th>Distance From (KM)</th>
                                    <th>Distance To (KM)</th>
                                    <th>Price (<i class="fa fa-inr"></i>)</th>
                                    <th style="text-align:center;">Status</th>
                                    <th>Created</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if(count($distances) > 0){
                                    foreach($distances as $key => $distance){
									$isExist = 0;
									$weight = $this->Admin->getWeightById($distance->weight_id);
                                    $weightType = $this->Weight->getWeightTypeById($distance->weight_type_id);
                                  
                                ?>
                                        <tr>
                                            <td><?php e($key+1); ?>.</td>
                                            <td><?php e(ucwords(isCheckVal($weightType)));?></td>
                                            <td><?php e(trim(isCheckVal($weight))); ?></td>
                                            <td><?php e(ucwords(isCheckVal($distance->dist_from)));?></td>
                                            <td><?php e(ucwords(isCheckVal($distance->dist_to)));?></td>
                                            <td><?php e(ucwords(isCheckVal($distance->price)));?></td>
                                            <td style="text-align:center;">
                                                <?php $status = $distance->status == 1 ? "<i class='fa fa-check'></i>" : "<i class='fa fa-times'></i>"; ?>
                                                <?php $class = $distance->status == 1 ? "success" : "danger"; ?>
                                                <a id="statusBtn_<?= $distance->id ?>" <?php if($isExist == 0){ ?> onclick="changeStatus('Distances','<?= $this->encryptData($distance->id); ?>','<?= $distance->status ?>','<?= $distance->id; ?>');" <?php } ?> class="btn btn-<?php e($class);?> btn-circle" <?php if($isExist > 0){e('disabled');} ?>><?php e($status);?></a>
                                                <input type="hidden" id="current_status<?= $distance->id ?>" value="<?= $distance->status ?>" />
                                            </td>
                                            <td width="20%"><?php e(date("F jS, Y h:i A",$distance->created)); ?></td>
                                            <td><a href="<?php e($this->Url->build(ADMIN_FOLDER.'/edit-distance/'.base64_encode($this->encryptData($distance->id))));?>" title="Edit" class="btn btn-success">Edit</a>
                                            <a href="javascript:void(0);" onclick="deleteRecord('Distances','<?php e(base64_encode($this->encryptData($distance->id))); ?>','0');" title="Delete" class="btn btn-danger">Delete</a>
                                            </td>
                                            
                                        </tr>
                                <?php
                                    }
                                }else{
                                ?>
                                    <tr>
                                        <td class="text-center" colspan="9">Records are not found.</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                            <?php if($distances->count() > 0){ ?>
                                <tbody>
                                    <tr>
                                        <td align="center" colspan="12">
                                            <ul class="pagination">
                                                <?php
                                                $this->Paginator->options(array('update' => '#replaceHtml', 'evalScripts' => true, 'escape' => false, 'url' => array_merge(array('controller' => 'Weights', 'action' => 'distanceFilter'))));?>
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
    $('#price').filter_input({regex:'[0-9]'});
    $('#dist_from').filter_input({regex:'[0-9]'});
    $('#dist_to').filter_input({regex:'[0-9]'});

function searchoptions(search_options){
    $('.searchOptions').hide().val('');
    $('.searchbuttons').hide();
    if(search_options != ''){
        if(search_options == 'distance'){
            $('#dist_from').show().focus();
            $('#dist_to').show();
        }
        if(search_options == 'weight'){
            $('#weight_type_id').show().focus();
            $('#weight_id').show();
        }
        else{
        $('#'+search_options).show().focus();
    }
        $('.searchbuttons').show();
    }
}

 function getWeightByType(weightId){
       
        if(weightId != '' ){
            $.ajax({
                type: 'POST',
                url: '<?php e($this->Url->build('/ajax/getWeight/'));?>',
                data: {weightId:weightId},
                success: function(response){
                    $('#weight_id').html(response);
                }
            }); 
            return false;
        }
    }
/* search form */
function searchData(){
	$('#searchbuttons').html('Searching...');
	$.ajax({
		type: 'POST',
		url: '<?php e($this->Url->build(ADMIN_FOLDER.'/distance-filter/'));?>',
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