 <table class="table table-bordered table-hover table-striped">
    <thead>
        <tr>
            <th>S.No.</th>
            <th>Weight Type</th>
            <th>Weight</th>
            <th style="text-align:center;">Status</th>
            <th>Created</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if(count($weights) > 0){
            foreach($weights as $key => $weight){
            $isExist = 0;
            $isExist =$this->Weight->getWeightExist($weight->id);
            $weightType = $this->Weight->getWeightTypeById($weight->weight_type_id);

        ?>
                <tr>
                    <td><?php e($key+1); ?>.</td>
                    <td><?php e(isCheckVal($weightType));?></td>
                    <td><?php e(isCheckVal($weight->weight));?></td>
                    
                    <td style="text-align:center;">
                        <?php $status = $weight->status == 1 ? "<i class='fa fa-check'></i>" : "<i class='fa fa-times'></i>"; ?>
                        <?php $class = $weight->status == 1 ? "success" : "danger"; ?>
                        <a id="statusBtn_<?= $weight->id ?>" <?php if($isExist == 0){ ?> onclick="changeStatus('Weights','<?= $this->encryptData($weight->id); ?>','<?= $weight->status ?>','<?= $weight->id; ?>');" <?php } ?> class="btn btn-<?php e($class);?> btn-circle" <?php if($isExist > 0){e('disabled');} ?>><?php e($status);?></a>
                        <input type="hidden" id="current_status<?= $weight->id ?>" value="<?= $weight->status ?>" />
                    </td>
                    <td width="20%"><?php e(date("F jS, Y h:i A",$weight->created)); ?></td>
                    <td><a href="<?php e($this->Url->build(ADMIN_FOLDER.'/edit-weight/'.base64_encode($this->encryptData($weight->id))));?>" title="Edit" class="btn btn-success">Edit</a>
                    <?php if($isExist == 0) {?>
                    <a href="javascript:void(0);" onclick="deleteRecord('Weights','<?php e(base64_encode($this->encryptData($weight->id))); ?>','0');" title="Delete" class="btn btn-danger">Delete</a>
                <?php } ?>
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
    <?php if($weights->count() > 0){ ?>
        <tbody>
            <tr>
                <td align="center" colspan="12">
                    <ul class="pagination">
                        <?php
                        $this->Paginator->options(array('update' => '#replaceHtml', 'evalScripts' => true, 'escape' => false, 'url' => array_merge(array('controller' => 'Weights', 'action' => 'weightFilter'))));?>
                            <?php echo $this->Paginator->first('First'); ?>
                            <?php echo $this->Paginator->numbers(); ?>
                            <?php echo $this->Paginator->last('Last'); ?>
                        </ul>
                    </td>
                </tr>
            </tbody>
        <?php } ?>
    </table>