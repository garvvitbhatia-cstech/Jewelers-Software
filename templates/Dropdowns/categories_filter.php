<table class="table table-bordered table-hover table-striped">
<thead>
    <tr>
        <th>S.No.</th>
        <th>Title</th>
        <th>ID</th>
        <th>Ordering</th>
        <th style="text-align:center;">Status</th>
        <th>Action</th>
    </tr>
</thead>
<tbody>
    <?php
    if(count($categories) > 0){
        foreach($categories as $key => $category){
        $isExist = 0;
    ?>
        <tr>
            <td width="5%"><?php e($key+1); ?>.</td>
            <td><?php e(ucwords(isCheckVal($category->name)));?></td>                                        
            <td><?php e(ucwords(isCheckVal($category->id)));?></td> 	
            <td width="5%"><input type="text" style="text-align:center;" class="form-control ordering" onchange="saveOrder(<?php e($category->id); ?>,<?php e($category->ordering); ?>,'<?php e($this->encryptData('Categories')); ?>',this.value);" id="ordering" value="<?php e($category->ordering); ?>"/></td>
            <td style="text-align:center;">
                <?php $status = $category->status == 1 ? "<i class='fa fa-check'></i>" : "<i class='fa fa-times'></i>"; ?>
                <?php $class = $category->status == 1 ? "success" : "danger"; ?>
                <a id="statusBtn_<?= $category->id ?>" <?php if($isExist == 0){ ?> onclick="changeStatus('Categories','<?= $this->encryptData($category->id); ?>','<?= $category->status ?>','<?= $category->id; ?>');" <?php } ?> class="btn btn-<?php e($class);?> btn-circle" <?php if($isExist > 0){e('disabled');} ?>><?php e($status);?></a>
                <input type="hidden" id="current_status<?= $category->id ?>" value="<?= $category->status ?>" />
            </td>
            <td width="25%"><a href="<?php e($this->Url->build(ADMIN_FOLDER.'/edit-category/'.base64_encode($this->encryptData($category->id))));?>" title="Edit" class="btn btn-success">Edit</a>
            <a href="javascript:void(0);" onclick="deleteRecord('Categories','<?php e(base64_encode($this->encryptData($category->id))); ?>','0');" title="Delete" class="btn btn-danger">Delete</a>
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
<?php if($categories->count() > 0){ ?>
    <tbody>
        <tr>
            <td align="center" colspan="12">
                <ul class="pagination">
                    <?php
                    $this->Paginator->options(array('update' => '#replaceHtml', 'evalScripts' => true, 'escape' => false, 'url' => array_merge(array('controller' => 'Dropdowns', 'action' => 'categoriesFilter'))));?>
                        <?php echo $this->Paginator->first('First'); ?>
                        <?php echo $this->Paginator->numbers(); ?>
                        <?php echo $this->Paginator->last('Last'); ?>
                    </ul>
                </td>
            </tr>
        </tbody>
    <?php } ?>
</table>

<script>
	$(document).ready(function(e) {
		$('.ordering').filter_input({regex:'[0-9]'});
	});
</script>