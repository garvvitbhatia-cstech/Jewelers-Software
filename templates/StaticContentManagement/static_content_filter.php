<table class="table table-bordered table-hover table-striped">
    <thead>
        <tr>
            <th>S.No.</th>
            <th>Section Name</th>
            <th>Title</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if(count($records) > 0){
            foreach($records as $key => $record){
                ?>
                <tr>
                    <td><?php e($key+$pageNo+1); ?>.</td>
                    <td><?php e(ucwords(isCheckVal($record->section_name)));?></td>
                    <td width="40%"><?php e(nl2br(($record->title)));?></td>
                    <td>
                        <?php $status = $record->status == 1 ? "<i class='fa fa-check'></i>" : "<i class='fa fa-times'></i>"; ?>
                        <?php $class = $record->status == 1 ? "success" : "danger"; ?>
                        <a id="statusBtn_<?= $record->id ?>" onclick="changeStatus('StaticContent','<?= $this->encryptData($record->id); ?>','<?= $record->status ?>','<?= $record->id; ?>');" class="btn btn-<?php e($class);?> btn-circle"><?php e($status);?></a>
                        <input type="hidden" id="current_status<?= $record->id ?>" value="<?= $record->status ?>" />
                    </td>
                    <td><a href="<?php e($this->Url->build(ADMIN_FOLDER.'/edit-static-content/'.base64_encode($this->encryptData($record->id))));?>" title="Edit" class="btn btn-success">Edit</a></td>
                </tr>
                <?php
            }
        }else{
            ?>
            <tr>
                <td class="text-center" colspan="10">Records are not found.</td>
            </tr>
            <?php
        }
        ?>
    </tbody>
    <?php if($records->count() > 0){ ?>
        <tbody>
            <tr>
                <td align="center" colspan="12">
                    <ul class="pagination">
                        <?php
                        $this->Paginator->options(
                            array('update' => '#replaceHtml', 'evalScripts' => true, 'escape' => false, 'url' => array_merge(array('controller' => 'StaticContentManagement', 'action' => 'staticContentFilter'))));?>
                            <?php echo $this->Paginator->first('First'); ?>
                            <?php echo $this->Paginator->numbers(); ?>
                            <?php echo $this->Paginator->last('Last'); ?>
                        </ul>
                    </td>
                </tr>
            </tbody>
        <?php } ?>
    </table>
