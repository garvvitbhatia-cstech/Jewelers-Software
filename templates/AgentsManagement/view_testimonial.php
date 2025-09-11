<?php
#set page meta content
$this->assign('title', SITE_TITLE.' :: View Testimonial');
$this->assign('meta_robot', 'noindex, nofollow');
?>
<style>
	.profile_row{background: #f5f5f5;padding: 8px;margin-bottom: 10px;border:1px groove;}
</style>
<!--  page-wrapper -->
<div id="page-wrapper">
    <div class="row">
        <!-- page header -->
        <div class="col-lg-12">
            <h1 class="page-header">Testimonial Details</h1>
        </div>
        <!--end page header -->
    </div>
    <div class="row">
        <div class="col-lg-12">
            <a href="<?php e($this->Url->build(ADMIN_FOLDER.'testimonials'.'/'));?>" class="btn btn-info">Back To Listing</a><br />&nbsp;
        </div>
        <div class="col-lg-12">
            <!-- Form Elements -->
            <?php e($this->Flash->render()); ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    View testimonial
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-6">
                             <div class="form-group profile_row">
                                <label style="min-width: 250px;">User Name:</label>
                                <span><?php e(isCheckVal($viewData->username)); ?></span>
                            </div>                          
                             <div class="form-group profile_row">
                                <label style="min-width: 250px;">Testimonial:</label>
                                <span><?php e(nl2br(isCheckVal($viewData->testimonial))); ?></span>
                            </div>
                            <div class="form-group profile_row">
                                <label style="min-width: 250px;">Profile:</label>
                                <span>
									<?php
                                    if($viewData->profile != ""){
                                        $imgPath = WWW_ROOT.'img/testimonials/'.$viewData->profile;
                                        if(is_file($imgPath) && file_exists($imgPath)){
                                            e($this->Html->image('testimonials/'.$viewData->profile, array('title'=>$viewData->username, 'alt'=> $viewData->username, 'width' => '100' )));
                                        }else{
                                            e(isCheckVal());
                                        }
                                    }else{
                                        e(isCheckVal());
                                    } ?>
                                </span>
                            </div>
                            <div class="form-group profile_row">
                                <label style="min-width: 250px;">Date:</label>
                                <span><?php e(date("F jS, Y h:i A",$viewData->created)); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Form Elements -->
            <a href="<?php e($this->Url->build(ADMIN_FOLDER.'testimonials'.'/'));?>" class="btn btn-info">Back To Listing</a>
        </div>
    </div>
</div>