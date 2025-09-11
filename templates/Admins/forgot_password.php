<?php
#set page meta content
$this->assign('title', SITE_TITLE.' :: Admin Login');
$this->assign('meta_robot', 'noindex, nofollow');
?>
<div class="container">
    <div class="row">
        <div class="login">
            <div class="col-md-8 text-center logo-margin  ">
                <?php
                #get admin data
                $adminData = $this->Admin->getSettings();
                if(isset($adminData->id) && !empty($adminData->logo)){
                    $imgPath = WWW_ROOT.'img/logos/'.$adminData->logo;
                    if(is_file($imgPath)){
                        $imgPath = 'logos/'.$adminData->logo;
                        e($this->Html->image($imgPath, array('title'=>SITE_TITLE, 'alt'=> SITE_TITLE, 'width' => '200' )));
                    }
                }
                ?>
            </div>
            <div class="col-md-12">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Retrieve Your Password</h3>
                    </div>
                    <div class="panel-body" style="min-height:200px;">
                        <?php e($this->Flash->render()); ?>
                        <?= $this->Form->create(NULL,array('id' => 'editForm', 'name' => 'adminLoginForm', 'csrfToken' => $this->request->getAttribute('csrfToken')));?>
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Email Address" id="email_address" name="email_address" type="text" autofocus onkeyup="checkError(this.id);" maxlength="40" value="">
                                    <span id="email_addressError" class="admin_login_error"></span>
                                </div>
                                <!-- Change this to a button or input when using this as a form -->
                                <a href="javascript:void(0);" id="forgot_password_btn" class="btn btn-lg btn-success" >Submit</a>
                                <a href="<?php e($this->Url->build(ADMIN_FOLDER));?>" class="btn btn-lg btn-info pull-right" >Back To Login</a>
                            </fieldset>
                        <?= $this->Form->end();?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function(e) {
    var frmSubmitted = 0;
    $('#forgot_password_btn').click(function(){
        var flag = 0;
        if(frmSubmitted == 0){
            if($.trim($('#email_address').val()) == ""){
                $('#email_addressError').show().html('Please enter your email address').slideDown();
                $('#email_address').focus();
                frmSubmitted = 0;
                flag = 1; return false;
            }else if(jQuery.trim(jQuery("#email_address").val()).length > 40){
                jQuery('#email_addressError').html('Plaese enter your valid email address').fadeIn();
                flag = 1;
                formSubmit = 0;
                return false;
            }else{
            	var filter = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            	if(!filter.test($('#email_address').val())){
            		$('#email_addressError').html('Plaese enter your valid email address').slideDown();
            		$('#email_address').focus();
            		frmSubmitted = 0;
            		flag = 1; return false;
            	}
             }
            if(flag == 0){
                $('#forgot_password_btn').html('Processing...');
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
