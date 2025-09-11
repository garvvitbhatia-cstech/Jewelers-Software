<table class="table table-bordered table-hover table-striped">
<thead>
    <tr>
        <th>S.No.</th>
        <th>Country Name</th>
        <th>Country Code</th>
        <th>Ordering</th>
        <th style="text-align:center;">Status</th>
        <th>Created</th>
        <th>Action</th>
    </tr>
</thead>
<tbody>
    <?php
    if(count($countries) > 0){
        foreach($countries as $key => $country){
        $isExist = 0;
        $isExist = $this->Country->getStateExist($country->id);
    ?>
            <tr>
                <td><?php e($key+1); ?>.</td>
                <td><?php e(ucwords(isCheckVal($country->country_name)));?></td>
                <td><?php e(isCheckVal($country->country_code));?></td>
                <td width="5%"><input type="text" style="text-align:center;" class="form-control ordering" onchange="saveOrder(<?php e($country->id); ?>,<?php e($country->ordering); ?>,'<?php e($this->encryptData('Countries')); ?>',this.value);" id="ordering" value="<?php e($country->ordering); ?>"/></td>
                <td style="text-align:center;">
                    <?php $status = $country->status == 1 ? "<i class='fa fa-check'></i>" : "<i class='fa fa-times'></i>"; ?>
                    <?php $class = $country->status == 1 ? "success" : "danger"; ?>
                    <a id="statusBtn_<?= $country->id ?>" <?php if($isExist == 0){ ?> onclick="changeStatus('Countries','<?= $this->encryptData($country->id); ?>','<?= $country->status ?>','<?= $country->id; ?>');" <?php } ?> class="btn btn-<?php e($class);?> btn-circle" <?php if($isExist > 0){e('disabled');} ?>><?php e($status);?></a>
                    <input type="hidden" id="current_status<?= $country->id ?>" value="<?= $country->status ?>" />
                </td>
                <td width="20%"><?php e(date("F jS, Y h:i A",$country->created)); ?></td>
                <td><a href="<?php e($this->Url->build(ADMIN_FOLDER.'/edit-country/'.base64_encode($this->encryptData($country->id))));?>" title="Edit" class="btn btn-success">Edit</a></td>
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
<?php if($countries->count() > 0){ ?>
    <tbody>
        <tr>
            <td align="center" colspan="12">
                <ul class="pagination">
                    <?php
                    $this->Paginator->options(array('update' => '#replaceHtml', 'evalScripts' => true, 'escape' => false, 'url' => array_merge(array('controller' => 'Locations', 'action' => 'countriesFilter'))));?>
                        <?php echo $this->Paginator->first('First'); ?>
                        <?php echo $this->Paginator->numbers(); ?>
                        <?php echo $this->Paginator->last('Last'); ?>
                    </ul>
                </td>
            </tr>
        </tbody>
    <?php } ?>
</table>