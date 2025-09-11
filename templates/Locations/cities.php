<?php
#set page meta content
$this->assign('title', SITE_TITLE.' :: City Management');
$this->assign('meta_robot', 'noindex, nofollow');
e($this->Element('/admin/jQuery'));
?>
<!--  page-wrapper -->
<div id="page-wrapper">
<div class="row">
    <!-- page header -->
    <div class="col-lg-12">
        <h1 class="page-header">City Management</h1>
    </div>
    <!--end page header -->
</div>
<div class="panel panel-primary">
    <div class="panel-heading">
        <i class="fa fa-edit fa-fw"></i>City List
        <?= $this->Form->create(NULL, array('id' => 'searchForm', 'class' => 'searchForm', 'type' => 'post')) ?>
        <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                Search By
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu pull-right" role="menu">
                <li><a href="javascript:void(0);" onclick="searchoptions('country_id');">Country Name</a></li>
                <li><a href="javascript:void(0);" onclick="searchoptions('state_id');">State Name</a></li>
                <li><a href="javascript:void(0);" onclick="searchoptions('city');">City Name</a></li>
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
        <select name="state_id" id="state_id" placeholder="State Name" class="form-control filter searchOptions"  style="width:200px !important; display:none;">
        	<option value="">State</option>
        	<?php if(isset($stateList) && !empty($stateList)){ ?>
            	<?php foreach($stateList as $skey => $sval): ?>
                	<option value="<?php e($skey); ?>"><?php e($sval); ?></option>
                <?php endforeach; ?>            	
            <?php } ?>
        </select>        
        <input name="city" id="city" placeholder="City" class="form-control filter searchOptions"  style="width:200px !important; display:none;">
        <select style="width:200px !important; display:none;"  name="status" id="status" class="form-control filter searchOptions">
            <option value="">Status</option>
            <option value="1">Active</option>
            <option value="2">Inactive</option>
        </select>
        <a style="display:none;" onclick="searchData();" class="btn btn-info searchbuttons" id="searchbuttons">Search</a>
        <a style="display:none;" onclick="resetFilterForm();" class="btn btn-danger searchbuttons">Reset</a>
        <?= $this->Form->end() ?>
        <a style="float:right;" href="<?php e($this->Url->build(ADMIN_FOLDER.'/add-city/'));?>" title="Add Country" class="btn btn-default">Add City</a>
    </div>
    <input type="hidden" value="<?php e($this->Url->build(ADMIN_FOLDER.'/cities-filter/'));?>" id="paginatUrl">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive">
                    <div id="replaceHtml">
                        <table class="table table-bordered table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>City Name</th>                                  
                                    <th>Country Name</th>
                                    <th>State Name</th>
                                    <th style="text-align:center;">Status</th>
                                    <th>Created</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if(count($cities) > 0){
                                    foreach($cities as $key => $city){
									$stateName = $this->State->getStateNameById($city->state_id);
                                ?>
                                    <tr>
                                        <td><?php e($key+1); ?>.</td>
                                        <td><?php e(isCheckVal($city->city));?></td>
                                        <td><?php e(ucwords(isCheckVal($city->country->country_name)));?></td>
                                        <td><?php e(isCheckVal($stateName));?></td>
                                        <td style="text-align:center;">
                                            <?php $status = $city->status == 1 ? "<i class='fa fa-check'></i>" : "<i class='fa fa-times'></i>"; ?>
                                            <?php $class = $city->status == 1 ? "success" : "danger"; ?>
                                            <a id="statusBtn_<?= $city->id ?>" onclick="changeStatus('Cities','<?= $this->encryptData($city->id); ?>','<?= $city->status ?>','<?= $city->id; ?>');" class="btn btn-<?php e($class);?> btn-circle"><?php e($status);?></a>
                                            <input type="hidden" id="current_status<?= $city->id ?>" value="<?= $city->status ?>" />
                                        </td>
                                        <td width="20%"><?php e(date("F jS, Y h:i A",$city->created)); ?></td>
                                        <td><a href="<?php e($this->Url->build(ADMIN_FOLDER.'/edit-city/'.base64_encode($this->encryptData($city->id))));?>" title="Edit" class="btn btn-success">Edit</a></td>
                                    </tr>
                                <?php }
                                }else{
                                ?>
                                    <tr>
                                        <td class="text-center" colspan="6">Records are not found.</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                            <?php if($cities->count() > 0){ ?>
                                <tbody>
                                    <tr>
                                        <td align="center" colspan="12">
                                            <ul class="pagination">
                                                <?php
                                                $this->Paginator->options(array('update' => '#replaceHtml', 'evalScripts' => true, 'escape' => false, 'url' => array_merge(array('controller' => 'Locations', 'action' => 'citiesFilter'))));?>
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
		url: '<?php e($this->Url->build(ADMIN_FOLDER.'/cities-filter/'));?>',
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