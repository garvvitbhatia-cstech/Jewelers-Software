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