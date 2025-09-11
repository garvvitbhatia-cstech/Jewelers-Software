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