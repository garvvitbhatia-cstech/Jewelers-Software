<?php
#set page meta content
$this->assign('title', SITE_TITLE.' :: Edit Cms Page');
$this->assign('meta_robot', 'noindex, nofollow');
?>
<!--  page-wrapper -->
<div id="page-wrapper">
    <div class="row">
        <!-- page header -->
        <div class="col-lg-12">
            <h1 class="page-header">Edit Cms Page</h1>
        </div>
        <!--end page header -->
    </div>
    <div class="row">
        <div class="col-lg-12">
            <a href="<?php e($this->Url->build(ADMIN_FOLDER.'cms'.'/'));?>" class="btn btn-info">Back To Listing</a><br />&nbsp;
        </div>
        <div class="col-lg-12">
            <!-- Form Elements -->
            <?php e($this->Flash->render()); ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    Update cms page information
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <?= $this->Form->create(NULL,array('id' => 'editForm', 'type' => 'file', 'inputDefaults' => array('label' => false,'div' => false), 'name' => 'editForm', 'csrfToken' => $this->request->getAttribute('csrfToken')));?>
                            <input type="hidden" name="edit_token" id="edit_token" value="<?php e($this->encryptData($editData->id))?>"> 
                            <div class="form-group">
                                <label>Title:</label>
                                <input type="text" id="title" name="title" onkeyup="checkError(this.id);" value="<?php e($editData->title); ?>" confirmation="false" class="form-control">
                                <span id="titleError" class="admin_login_error"></span>
                            </div>
                             <div class="form-group">
                                <label>Description:</label>
                                <textarea id="description" name="description" onkeyup="checkError(this.id);" rows="10" confirmation="false" class="form-control"><?php e($editData->description); ?></textarea>
                                <span id="descriptionError" class="admin_login_error"></span>
                            </div>                            
                            <div class="form-group">
                                <label>Seo Title</label>
                                <input type="text" id="seo_title" name="seo_title" onkeyup="checkError(this.id);" value="<?php e($editData->seo_title); ?>" confirmation="false" class="form-control">
                                <span id="seo_titleError" class="admin_login_error"></span>
                            </div>
                            <div class="form-group">
                                <label>Seo Description</label>
                                <textarea id="seo_description" name="seo_description" onkeyup="checkError(this.id);" rows="10" confirmation="false" class="form-control"><?php e($editData->seo_description); ?></textarea>
                                <span id="edit_descriptionError" class="admin_login_error"></span>
                            </div>
                            <div class="form-group">
                                <label>Seo Keywords</label>
                                <input type="text" id="seo_keyword" name="seo_keyword" onkeyup="checkError(this.id);" value="<?php e($editData->seo_keyword); ?>" confirmation="false" class="form-control">
                                <span id="seo_keywordError" class="admin_login_error"></span>
                            </div>
                            <div class="form-group">
                                <label>Seo Robots</label>
                                <select id="robot_tags" name="robot_tags" onkeyup="checkError(this.id);" value="<?php e($editData->robot_tags); ?>" confirmation="false" class="form-control">
                                	<option <?php if($editData->robot_tags == 'index,follow'){e('selected');} ?> value="index,follow">index,follow</option>
                                    <option <?php if($editData->robot_tags == 'index,nofollow'){e('selected');} ?> value="index,nofollow">index,nofollow</option>
                                    <option <?php if($editData->robot_tags == 'noindex,follow'){e('selected');} ?> value="noindex,follow">noindex,follow</option>
                                    <option <?php if($editData->robot_tags == 'noindex,nofollow'){e('selected');} ?> value="noindex,nofollow">noindex,nofollow</option>
                                </select>
                                <span id="robot_tagsError" class="admin_login_error"></span>
                            </div>                           
                            <div class="form-group">
                                <label>Status</label>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" checked="checked" value="1" name="status">Active
                                    </label>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary submitBtn" id="submitBtn">Submit</button>
                            <?= $this->Form->end(); ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Form Elements -->
            <a href="<?php e($this->Url->build(ADMIN_FOLDER.'inner-page-management'.'/'));?>" class="btn btn-info">Back To Listing</a>
        </div>
    </div>
</div>

<script type="text/javascript">  
$(document).ready(function(e) {
    var frmSubmitted = 0;
    $('.submitBtn').click(function(){
        var flag = 0;
        if(frmSubmitted == 0){
           if($.trim($('#title').val()) == ""){
                $('#titleError').show().html('Please enter title.').slideDown();
                $('#title').focus();
                frmSubmitted = 0;
                flag = 1; return false;
            }
            if(flag == 0){
                $('.submitBtn').html('Processing...');
                $('#editForm').submit();
                frmSubmitted = 1;
                return true;
            }
        }else{
            return false;
        }
    });
});
</script>