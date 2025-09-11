<?php
#set page meta content
$this->assign('title', SITE_TITLE.' :: Edit Static Content');
$this->assign('meta_robot', 'noindex, nofollow');
?>
<!--  page-wrapper -->
<div id="page-wrapper">
    <div class="row">
        <!-- page header -->
        <div class="col-lg-12">
            <h1 class="page-header">Edit Static Content</h1>
        </div>
        <!--end page header -->
    </div>
    <div class="row">
        <div class="col-lg-12">
            <a href="<?php e($this->Url->build(ADMIN_FOLDER.STATIC_CONTENT_MANAGEMENT_URL.'/'));?>" class="btn btn-info">Back To Listing</a><br />&nbsp;
        </div>
        <div class="col-lg-12">
            <!-- Form Elements -->
            <?php e($this->Flash->render()); ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    Update static content information
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <?= $this->Form->create(NULL,array('id' => 'editForm', 'name' => 'editForm', 'csrfToken' => $this->request->getAttribute('csrfToken')));?>
                            <input type="hidden" name="edit_token" id="edit_token" value="<?php e($this->encryptData($editData->id))?>">
                            <div class="form-group">
                                <label>Title</label>
                                <textarea type="text" value="" id="title" name="title" onkeyup="checkError(this.id);" confirmation="false" class="form-control"style="height:80px;"><?php e($editData->title);?></textarea>
                                <span id="titleError" class="admin_login_error"></span>
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <textarea type="text" value="" id="descriptions" name="descriptions" onkeyup="checkError(this.id);" confirmation="false" class="form-control"style="height:200px;"><?php e($editData->descriptions);?></textarea>
                                <span id="descriptionsError" class="admin_login_error"></span>
                            </div>
                            <div class="form-group">
                                <label>Status</label>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" <?php if($editData->status == 1){e('checked'); }?> value="1" name="status">Active
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
            <a href="<?php e($this->Url->build(ADMIN_FOLDER.STATIC_CONTENT_MANAGEMENT_URL.'/'));?>" class="btn btn-info">Back To Listing</a>
        </div>
    </div>
</div>
<script>
$(document).ready(function(e) {
    window.onload =
    function removeauto(){
        $('#currentkey').val('');
        $('#password').val('');
        $('#confirmpassword').val('');
    }
    var frmSubmitted = 0;
    $('.submitBtn').click(function(){
        var flag = 0;
        if(frmSubmitted == 0){
            if($.trim($('#title').val()) == ""){
                $('#titleError').show().html('Please enter title').slideDown();
                $('#title').focus();
                frmSubmitted = 0;
                flag = 1; return false;
            }
            if($.trim($('#descriptions').val()) == ""){
                $('#descriptionsError').show().html('Please enter description').slideDown();
                $('#descriptions').focus();
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
