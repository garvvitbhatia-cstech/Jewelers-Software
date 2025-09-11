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
    if(count($cmsPages) > 0){
        foreach($cmsPages as $key => $cms){
        $isExist = 0;
    ?>
            <tr>
                <td><?php e($key+$pageNo+1); ?>.</td>
                <td><?php e(ucwords(isCheckVal($cms->title)));?></td>
                <td style="text-align:center;">
                    <?php $status = $cms->status == 1 ? "<i class='fa fa-check'></i>" : "<i class='fa fa-times'></i>"; ?>
                    <?php $class = $cms->status == 1 ? "success" : "danger"; ?>
                    <a id="statusBtn_<?= $cms->id ?>" <?php if($isExist == 0){ ?> onclick="changeStatus('Cms','<?= $this->encryptData($cms->id); ?>','<?= $cms->status ?>','<?= $cms->id; ?>');" <?php } ?> class="btn btn-<?php e($class);?> btn-circle" <?php if($isExist > 0){e('disabled');} ?>><?php e($status);?></a>
                    <input type="hidden" id="current_status<?= $cms->id ?>" value="<?= $cms->status ?>" />
                </td>
                <td width="20%"><?php e(date("F jS, Y h:i A",$cms->created)); ?></td>
                <td>
                <a href="<?php e($this->Url->build(ADMIN_FOLDER.'/edit-cms/'.base64_encode($this->encryptData($cms->id))));?>" title="Edit" class="btn btn-success">Edit</a>
                <a href="javascript:void(0);" onclick="deleteRecord('Cms','<?php e(base64_encode($this->encryptData($cms->id))); ?>','0');" title="Delete" class="btn btn-danger">Delete</a>
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
<?php if($cmsPages->count() > 0){ ?>
    <tbody>
        <tr>
            <td align="center" colspan="12">
                <ul class="pagination">
                    <?php
                    $this->Paginator->options(array('update' => '#replaceHtml', 'evalScripts' => true, 'escape' => false, 'url' => array_merge(array('controller' => 'CmsManagement', 'action' => 'cmsFilter'))));?>
                        <?php echo $this->Paginator->first('First'); ?>
                        <?php echo $this->Paginator->numbers(); ?>
                        <?php echo $this->Paginator->last('Last'); ?>
                    </ul>
                </td>
            </tr>
        </tbody>
    <?php } ?>
</table>