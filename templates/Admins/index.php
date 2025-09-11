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
                /*if(isset($adminData->id) && !empty($adminData->logo)){
                    $imgPath = WWW_ROOT.'img/logos/'.$adminData->logo;
                    if(is_file($imgPath)){
                        $imgPath = 'logos/'.$adminData->logo;
                        e($this->Html->image($imgPath, array('title'=>SITE_TITLE, 'alt'=> SITE_TITLE, 'width' => '200' )));
                    }
                }*/
				$imgPath = 'logos/logo-gold.png';
				e($this->Html->image($imgPath, array('title'=>SITE_TITLE, 'alt'=> SITE_TITLE, 'width' => '247' )));
                ?>
            </div>
            <div class="col-md-12">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Sign In</h3>
                    </div>
                    <div class="panel-body">
                        <div id="errorBox"></div>
                        <?= $this->Form->create(NULL,array('id' => 'adminLoginForm', 'name' => 'adminLoginForm', 'csrfToken' => $this->request->getAttribute('csrfToken')));?>
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Username" id="username" name="username" type="username" autofocus onkeyup="removeError('username');" maxlength="40" value="<?= $cookieUserName?>">
                                    <span id="usernameError" class="admin_login_error"></span>
                                </div>
                                <div class="form-group" style="position:relative;">
                                    <input class="form-control" placeholder="Password" id="password" name="password" type="password" onkeyup="removeError('password');" maxlength="40" value="<?= $cookiePassword?>">
                                    <em style="position:absolute;right:10px;top:10px;cursor:pointer;float:right;margin-top:0px;" onclick="showConfirmPassword();" id="passEye2" class="fa fa-eye"></em>
                                    <span id="passwordError" class="admin_login_error"></span>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input name="remember" id="remember" type="checkbox" value="Remember Me" <?php e(!empty($cookieRemember)?'checked="checked"':'')?>>Remember Me
                                    </label>
                                    <p class="pull-right"><a href="<?php e($this->Url->build(ADMIN_FOLDER.'/forgot-password/'));?>">Forgot Password?</a> </p>
                                </div>
                                <!-- Change this to a button or input when using this as a form -->
                                <a href="javascript:void(0);" class="btn btn-lg btn-success btn-block" id="loginSubmit">Login to your account</a>
                            </fieldset>
                        <?= $this->Form->end();?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
var loginRequest = '<?= $this->Url->build('/admins/index');?>';
var blockAccountLink = '<?= $this->Url->build('/block-account/');?>';
var nextPageUrl = '<?= $this->Url->build(ADMIN_FOLDER.'dashboard/');?>';
<?php
$session = $this->request->getSession();
if($session->check('nextPageUrl')){
?>
nextPageUrl = '<?php e($session->read('nextPageUrl'));?>';
<?php } ?>
</script>
<?= $this->Html->script('/admin/js/custom_login');?>
