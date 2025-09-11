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
    if(count($navigations) > 0){
        foreach($navigations as $key => $navigation){
        $isExist = 0;
        $type = $navigation->menu_type;
        $pageTitle = '';
        $root = '';
        if($type == 'custom'){
            $pageTitle = $navigation->title;
        }else{
            $pageTitle = $this->Admin->getTitleByPageId($navigation->menu_page_id);
        }
        if($navigation->parent_id > 0){
            $parentTitle = $this->Navigation->getHeaderPageTitle($navigation->parent_id);
            $root = $parentTitle.' ->';
        }
    ?>                                
            <tr>
                <td><?php e($key+$pageNo+1); ?>.</td>
                <td><?php e(ucwords($root).ucwords(isCheckVal($pageTitle)));?></td>
                <td style="text-align:center;">
                    <?php $status = $navigation->status == 1 ? "<i class='fa fa-check'></i>" : "<i class='fa fa-times'></i>"; ?>
                    <?php $class = $navigation->status == 1 ? "success" : "danger"; ?>
                    <a id="statusBtn_<?= $navigation->id ?>" <?php if($isExist == 0){ ?> onclick="changeStatus('HeaderNavigations','<?= $this->encryptData($navigation->id); ?>','<?= $navigation->status ?>','<?= $navigation->id; ?>');" <?php } ?> class="btn btn-<?php e($class);?> btn-circle" <?php if($isExist > 0){e('disabled');} ?>><?php e($status);?></a>
                    <input type="hidden" id="current_status<?= $navigation->id ?>" value="<?= $navigation->status ?>" />
                </td>
                <td width="20%"><?php e(date("F jS, Y h:i A",$navigation->created)); ?></td>
                <td>
                <a href="<?php e($this->Url->build(ADMIN_FOLDER.'/edit-header-navigation/'.base64_encode($this->encryptData($navigation->id))));?>" title="Edit" class="btn btn-success">Edit</a>
                <a href="javascript:void(0);" onclick="deleteRecord('HeaderNavigations','<?php e(base64_encode($this->encryptData($navigation->id))); ?>','0');" title="Delete" class="btn btn-danger">Delete</a>
                </td>
            </tr>
    <?php
        }
    }else{
    ?>
        <tr>
            <td class="text-center" colspan="5">Records are not found.</td>
        </tr>
    <?php } ?>
</tbody>
<?php if($navigations->count() > 0){ ?>
    <tbody>
        <tr>
            <td align="center" colspan="5">
                <ul class="pagination">
                    <?php
                    $this->Paginator->options(array('update' => '#replaceHtml', 'evalScripts' => true, 'escape' => false, 'url' => array_merge(array('controller' => 'CmsManagement', 'action' => 'headerNavigationFilter'))));?>
                        <?php echo $this->Paginator->first('First'); ?>
                        <?php echo $this->Paginator->numbers(); ?>
                        <?php echo $this->Paginator->last('Last'); ?>
                    </ul>
                </td>
            </tr>
        </tbody>
    <?php } ?>
</table>