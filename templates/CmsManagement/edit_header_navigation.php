<?php
#set page meta content
$this->assign('title', SITE_TITLE.' :: Edit Header Navigation');
$this->assign('meta_robot', 'noindex, nofollow');
?>
<!--  page-wrapper -->
<div id="page-wrapper">
    <div class="row">
        <!-- page header -->
        <div class="col-lg-12">
            <h1 class="page-header">Edit Header Navigation</h1>
        </div>
        <!--end page header -->
    </div>
    <div class="row">
        <div class="col-lg-12">
            <a href="<?php e($this->Url->build(ADMIN_FOLDER.'header-navigations'.'/'));?>" class="btn btn-info">Back To Listing</a><br />&nbsp;
        </div>
        <div class="col-lg-12">
            <!-- Form Elements -->
            <?php e($this->Flash->render()); ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    Edit header navigation page information
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <?= $this->Form->create(NULL,array('id' => 'addForm', 'type' => 'file', 'inputDefaults' => array('label' => false,'div' => false), 'name' => 'addForm', 'csrfToken' => $this->request->getAttribute('csrfToken')));?>
                            <input type="hidden" name="edit_token" id="edit_token" value="<?php e($this->encryptData($editData->id))?>">
                            <div class="form-group">
                                <label>Parent Category</label>
                                <select name="parent_id" id="parent_id" value="<?php e($editData->parent_id);?>" onkeyup="checkError(this.id);" confirmation="false" class="form-control">
                                <?php e($this->Navigation->getSubCategory($headerNavigationList,$editData->parent_id,$editData->id)); ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Target Window</label>
                                <select id="target_window" name="target_window" onkeyup="checkError(this.id);" confirmation="false" class="form-control">
                                	<option <?php if($editData->target_window == 'Self'){e('selected');} ?> value="Self">Self</option>
                                    <option <?php if($editData->target_window == 'Blank'){e('selected');} ?> value="blank">Blank</option>
                                </select>
                                <span id="target_windowError" class="admin_login_error"></span>
                            </div>
                            <div class="form-group">
                                <label>Page Type</label>
                                <select id="type" name="type" onkeyup="checkError(this.id);" confirmation="false" class="form-control">
                                	<option <?php if($editData->menu_type == 'cms'){e('selected');} ?> value="cms">CMS</option>
                                    <option <?php if($editData->menu_type == 'custom'){e('selected');} ?> value="custom">Custom</option>
                                </select>
                                <span id="typeError" class="admin_login_error"></span>
                            </div>
                            <div id="cmsPageDiv" <?php if($editData->menu_type == 'cms'){ ?> style="display:block;"; <?php } else { ?> style="display:none;"; <?php }  ?>>
                            <div class="form-group">
                                <label>CMS Pages</label>                                
                                <select id="menu_page_id" name="menu_page_id" onchange="checkError(this.id);" confirmation="false" class="form-control">
                                	<option value="">Select Cms Page</option>
                                	<?php if(isset($cmsPageList) && !empty($cmsPageList)){ ?>                                    	
                                    	<?php foreach($cmsPageList as $cmsKey => $cmsVal): ?>
                                        	<option <?php if($editData->menu_page_id == $cmsKey){e('selected');} ?> value="<?php e($cmsKey); ?>"><?php e($cmsVal); ?></option>
                                        <?php endforeach; ?>
                                    <?php } ?>
                                </select>
                                <span id="menu_page_idError" class="admin_login_error"></span>
                            </div>
                            </div>
                            <div id="customPageDiv" <?php if($editData->menu_type == 'custom'){ ?> style="display:block;"; <?php } else { ?> style="display:none;"; <?php }  ?>>
                            <div class="form-group">
                                <label>Title:</label>
                                <input type="text" id="title" name="title" value="<?php e($editData->title); ?>" onkeyup="checkError(this.id);" confirmation="false" class="form-control">
                                <span id="titleError" class="admin_login_error"></span>
                            </div>
                            <div class="form-group">
                                <label>Custom URL:</label>
                                <input type="text" id="url" name="url" onkeyup="checkError(this.id);" value="<?php e($editData->url); ?>" confirmation="false" class="form-control">
                                <span id="urlError" class="admin_login_error"></span>
                            </div>                           
                            <div class="form-group">
                                <label>SEO Title</label>
                                <input type="text" id="seo_title" name="seo_title" value="<?php e($editData->seo_title); ?>" onkeyup="checkError(this.id);" confirmation="false" class="form-control">
                                <span id="seo_titleError" class="admin_login_error"></span>
                            </div>
                            <div class="form-group">
                                <label>SEO Description</label>
                                <textarea id="seo_description" name="seo_description" onkeyup="checkError(this.id);" rows="8" confirmation="false" class="form-control"><?php e($editData->seo_description); ?></textarea>
                                <span id="seo_descriptionError" class="admin_login_error"></span>
                            </div>
                            <div class="form-group">
                                <label>SEO Keywords</label>
                                <textarea id="seo_keyword" name="seo_keyword" onkeyup="checkError(this.id);" rows="8" confirmation="false" class="form-control"><?php e($editData->seo_keyword); ?></textarea> 
                                <span id="seo_keywordError" class="admin_login_error"></span>
                            </div>
                            <div class="form-group">
                                <label>SEO Robots</label>
                                <select id="robot_tags" name="robot_tags" onkeyup="checkError(this.id);" confirmation="false" class="form-control">
                                	<option <?php if($editData->robot_tags == 'index,follow'){e('selected');}; ?> value="index,follow">index,follow</option>
                                    <option <?php if($editData->robot_tags == 'index,nofollow'){e('selected');}; ?> value="index,nofollow">index,nofollow</option>
                                    <option <?php if($editData->robot_tags == 'noindex,follo'){e('selected');}; ?> value="noindex,follow">noindex,follow</option>
                                    <option <?php if($editData->robot_tags == 'noindex,nofollow'){e('selected');}; ?> value="noindex,nofollow">noindex,nofollow</option>
                                </select>
                                <span id="robot_tagsError" class="admin_login_error"></span>
                            </div> 
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
            <a href="<?php e($this->Url->build(ADMIN_FOLDER.'header-navigations'.'/'));?>" class="btn btn-info">Back To Listing</a>
        </div>
    </div>
</div>

<script type="text/javascript">  
$(document).ready(function(e) {
	$('#title').filter_input({regex:'[a-z A-Z]'});
   
    var frmSubmitted = 0;
    $('.submitBtn').click(function(){
        var flag = 0;
        if(frmSubmitted == 0){
           if($.trim($('#type').val()) == "cms"){
			   	if($.trim($('#menu_page_id').val()) == ""){
					$('#menu_page_idError').show().html('Please select cms page.').slideDown();
					$('#menu_page_id').focus();
					frmSubmitted = 0;
					flag = 1; return false;   
				}               
            }
			if($.trim($('#type').val()) == "custom"){
			   	if($.trim($('#title').val()) == ""){
					$('#titleError').show().html('Please enter page title.').slideDown();
					$('#title').focus();
					frmSubmitted = 0;
					flag = 1; return false;   
				}
				if($.trim($('#url').val()) == ""){
					$('#urlError').show().html('Please enter page url.').slideDown();
					$('#url').focus();
					frmSubmitted = 0;
					flag = 1; return false;   
				}else{
					url_validate = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
					if(!url_validate.test($.trim($('#url').val()))){
					   $('#urlError').show().html('Please enter valid url.').slideDown();
						$('#url').focus();
						frmSubmitted = 0;
						flag = 1; return false;  
					}	
				}
				if($.trim($('#seo_title').val()) == ""){
					$('#seo_titleError').show().html('Please enter seo title.').slideDown();
					$('#seo_title').focus();
					frmSubmitted = 0;
					flag = 1; return false;   
				}
				if($.trim($('#seo_description').val()) == ""){
					$('#seo_descriptionError').show().html('Please enter seo description.').slideDown();
					$('#seo_description').focus();
					frmSubmitted = 0;
					flag = 1; return false;   
				}
				if($.trim($('#seo_keyword').val()) == ""){
					$('#seo_keywordError').show().html('Please enter seo keyword.').slideDown();
					$('#seo_keyword').focus();
					frmSubmitted = 0;
					flag = 1; return false;   
				}				              
            }
            if(flag == 0){
                $('.submitBtn').html('Processing...');
                $('#addForm').submit();
                frmSubmitted = 1;
                return true;
            }
        }else{
            return false;
        }
    });
});

$('#type').on('change',function(){	
	if(this.value == 'custom'){
		$('#customPageDiv').css('display','block');
		$('#cmsPageDiv').css('display','none');
	}else{
		$('#customPageDiv').css('display','none');
		$('#cmsPageDiv').css('display','block');
	}
});
</script>