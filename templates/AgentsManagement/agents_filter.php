<table class="table table-bordered table-hover table-striped">
<thead>
    <tr>
        <th>S.No.</th>
        <th>User Details</th>
        <th style="text-align:center;">Status</th>
        <th>Action</th>
    </tr>
</thead>
                            
<tbody>
    <?php
    if(count($agents) > 0){
        foreach($agents as $key => $agent){
        $isExist = 0;
    ?>
            <tr>
                <td width="5%"><?php e($key+1); ?>.</td>
                <td>
                    <b>Name: </b><?php e(ucwords(isCheckVal($agent->first_name.' '.$agent->last_name)));?><br />                
                    <b>Email: </b><?php e(isCheckVal($this->decryptData($agent->email)));?><br />
                    <b>Username: </b><?php e(isCheckVal($this->decryptData($agent->username)));?><br />
                    <b>Password: </b><?php e(isCheckVal($this->decryptData($agent->password)));?><br />
                    <?php e(date("F jS, Y h:i A",$agent->created)); ?>
                </td>
                <td style="text-align:center;" width="5%">
                    <?php $status = $agent->status == 1 ? "<i class='fa fa-check'></i>" : "<i class='fa fa-times'></i>"; ?>
                    <?php $class = $agent->status == 1 ? "success" : "danger"; ?>
                    <a id="statusBtn_<?= $agent->id ?>" <?php if($isExist == 0){ ?> onclick="changeStatus('Users','<?= $this->encryptData($agent->id); ?>','<?= $agent->status ?>','<?= $agent->id; ?>');" <?php } ?> class="btn btn-<?php e($class);?> btn-circle" <?php if($isExist > 0){e('disabled');} ?>><?php e($status);?></a>
                    <input type="hidden" id="current_status<?= $agent->id ?>" value="<?= $agent->status ?>" />
                </td>
                <td width="25%">
                <a href="<?php e($this->Url->build(ADMIN_FOLDER.'/edit-agent/'.base64_encode($this->encryptData($agent->id))));?>" title="Edit" class="btn btn-success">Edit</a>
                <a href="javascript:void(0);" onclick="deleteRecord('Users','<?php e(base64_encode($this->encryptData($agent->id))); ?>','0');" title="Delete" class="btn btn-danger">Delete</a>  
                <a href="<?php e($this->Url->build(ADMIN_FOLDER.'/permissions/'.base64_encode($this->encryptData($agent->id))));?>" title="Permissions" class="btn btn-info">Permissions</a>                                         
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
<?php if($agents->count() > 0){ ?>
    <tbody>
        <tr>
            <td align="center" colspan="12">
                <ul class="pagination">
                    <?php
                    $this->Paginator->options(array('update' => '#replaceHtml', 'evalScripts' => true, 'escape' => false, 'url' => array_merge(array('controller' => 'AgentsManagement', 'action' => 'agentsFilter'))));?>
                        <?php echo $this->Paginator->first('First'); ?>
                        <?php echo $this->Paginator->numbers(); ?>
                        <?php echo $this->Paginator->last('Last'); ?>
                    </ul>
                </td>
            </tr>
        </tbody>
    <?php } ?>
</table>