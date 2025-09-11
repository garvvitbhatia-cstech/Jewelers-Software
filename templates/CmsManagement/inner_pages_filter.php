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
    if(count($innerPages) > 0){
        foreach($innerPages as $key => $innerPage){
        $isExist = 0;
    ?>
            <tr>
                <td><?php e($key+$pageNo+1); ?>.</td>
                <td><?php e(ucwords(isCheckVal($innerPage->title)));?></td>
                <td style="text-align:center;">
                    <?php $status = $innerPage->status == 1 ? "<i class='fa fa-check'></i>" : "<i class='fa fa-times'></i>"; ?>
                    <?php $class = $innerPage->status == 1 ? "success" : "danger"; ?>
                    <a id="statusBtn_<?= $innerPage->id ?>" <?php if($isExist == 0){ ?> onclick="changeStatus('InnerPages','<?= $this->encryptData($innerPage->id); ?>','<?= $innerPage->status ?>','<?= $innerPage->id; ?>');" <?php } ?> class="btn btn-<?php e($class);?> btn-circle" <?php if($isExist > 0){e('disabled');} ?>><?php e($status);?></a>
                    <input type="hidden" id="current_status<?= $innerPage->id ?>" value="<?= $innerPage->status ?>" />
                </td>
                <td width="20%"><?php e(date("F jS, Y h:i A",$innerPage->created)); ?></td>
                <td>
                <a href="<?php e($this->Url->build(ADMIN_FOLDER.'/edit-inner-page/'.base64_encode($this->encryptData($innerPage->id))));?>" title="Edit" class="btn btn-success">Edit</a></td>
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
<?php if($innerPages->count() > 0){ ?>
    <tbody>
        <tr>
            <td align="center" colspan="12">
                <ul class="pagination">
                    <?php
                    $this->Paginator->options(array('update' => '#replaceHtml', 'evalScripts' => true, 'escape' => false, 'url' => array_merge(array('controller' => 'CmsManagement', 'action' => 'innerPagesFilter'))));?>
                        <?php echo $this->Paginator->first('First'); ?>
                        <?php echo $this->Paginator->numbers(); ?>
                        <?php echo $this->Paginator->last('Last'); ?>
                    </ul>
                </td>
            </tr>
        </tbody>
    <?php } ?>
</table>