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