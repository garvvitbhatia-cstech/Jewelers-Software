<?php
#set page meta content
$this->assign('title', SITE_TITLE.' :: Change Password');
$this->assign('meta_robot', 'noindex, nofollow');
?>
<!--  page-wrapper -->
<div id="page-wrapper">
    <div class="row">
        <!-- page header -->
        <div class="col-lg-12">
            <h1 class="page-header">Change Password</h1>
        </div>
        <!--end page header -->
    </div>
    <div class="row">
        <div class="col-lg-12">
            <!-- Form Elements -->
            <?php e($this->Flash->render()); ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    Change your account password
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <?= $this->Form->create(NULL,array('id' => 'editForm', 'name' => 'editForm', 'csrfToken' => $this->request->getAttribute('csrfToken')));?>
                            <div class="form-group">
                                <label>Old Password</label>
                                <input type="text" value="" id="currentkey" name="currentkey" onkeyup="checkError(this.id);" onblur="checkpassStatus()" confirmation="false" class="form-control" maxlength="40">
                                <span id="currentkeyError" class="admin_login_error"></span>
                            </div>
                            <div class="form-group">
                                <label>New Password</label>
                                <input type="text" value="" id="password" onkeyup="checkError(this.id);" name="password" class="form-control" maxlength="40">
                                <span id="passwordError" class="admin_login_error"></span>
                            </div>
                            <div class="form-group">
                                <label>Confirm New Password</label>
                                <input type="text" value="" id="confirmpassword" onkeyup="checkError(this.id);" name="confirmpassword" class="form-control">
                                <span id="confirmpasswordError" class="admin_login_error"></span>
                            </div>
                            <button type="button" class="btn btn-primary submitBtn" id="submitBtn">Submit</button>
                            <?= $this->Form->end(); ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Form Elements -->
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
            if($.trim($('#currentkey').val()) == ""){
                $('#currentkeyError').show().html('Please enter your current password').slideDown();
                $('#currentkey').focus();
                frmSubmitted = 0;
                flag = 1; return false;
            }
            if($.trim($('#currentkey').attr('confirmation')) == "false"){
                $('#currentkeyError').show().html('Your current password is invalid').slideDown();
                $('#currentkey').focus();
                frmSubmitted = 0;
                flag = 1; return false;
            }
            if($.trim($('#password').val()) == ""){
                $('#passwordError').show().html('Please enter your new password').slideDown();
                $('#password').focus();
                frmSubmitted = 0;
                flag = 1; return false;
            }else{
                var getResponse = CheckStrength($('#password').val());
                if(getResponse == 'TooShort' || getResponse == 'Weak' || getResponse == 'Good') {
                    jQuery('#passwordError').html('Please enter strong password using alphabets, numbers, and special characters').slideDown();
                    $('#password').focus();
                    frmSubmitted = 0;
                    flag = 1; return false;
                }
            }
            if($.trim($('#confirmpassword').val()) == ""){
                $('#confirmpasswordError').html('Please enter confirm password').slideDown();
                $('#confirmpassword').focus();
                frmSubmitted = 0;
                flag = 1; return false;
            }
            if($.trim($('#confirmpassword').val()) != $.trim($('#password').val())){
                $('#confirmpasswordError').html('Password do not match').slideDown();
                $('#confirmpassword').focus();
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
/* check password strength */
function CheckStrength(password){
    //initial strength
    var strength = 0
    //if the password length is less than 6, return message.
    if (password.length < 4) {
        jQuery('#result').removeClass()
        jQuery('#result').addClass('short')
        return 'TooShort'
    }
    //length is ok, lets continue.
    //if length is 8 characters or more, increase strength value
    if (password.length > 7) strength += 1
    //if password contains both lower and uppercase characters, increase strength value
    if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/))  strength += 1
    //if it has numbers and characters, increase strength value
    if (password.match(/([a-zA-Z])/) && password.match(/([0-9])/))  strength += 1
    //if it has one special character, increase strength value
    if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/))  strength += 1
    //if it has two special characters, increase strength value
    if (password.match(/(.*[!,%,&,@,#,$,^,*,?,_,~].*[!,",%,&,@,#,$,^,*,?,_,~])/)) strength += 1
    //now we have calculated strength value, we can return messages
    //if value is less than 2
    if (strength < 2 ) {
        return 'Weak'
    } else if (strength == 2 ) {
        return 'Good'
    } else {
        return 'Strong'
    }
}
function checkpassStatus(){
    var currentkey=$("#currentkey").val();
    var chkid=$("#chkid").val();
    if($.trim($('#currentkey').val()) != ""){
        $.ajax({
            type:'post',
            url:'<?php e( $this->Url->build('/ajax/checkpassword/'));  ?>',// put your real file name
            data:{oldpassword: currentkey,chkid: chkid},
            success:function(msg){
                if(msg != 'Success'){
                    $('#currentkeyError').html('Current password is wrong').slideDown();
                    return false;
                }else{
                    $("#currentkey").attr('confirmation','true');
                }
            }
        });
    }
}
</script>
