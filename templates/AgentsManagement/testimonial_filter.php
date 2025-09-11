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
                <td><?php e($key+$pageNo+1); ?>.</td>
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