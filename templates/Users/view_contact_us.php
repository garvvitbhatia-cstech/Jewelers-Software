<?php
#set page meta content
$this->assign('title', SITE_TITLE.' :: View Contact Us');
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
            <h1 class="page-header">Contact Us Details</h1>
        </div>
        <!--end page header -->
    </div>
    <div class="row">
        <div class="col-lg-12">
            <a href="<?php e($this->Url->build(ADMIN_FOLDER.'contact-us'.'/'));?>" class="btn btn-info">Back To Listing</a><br />&nbsp;
        </div>
        <div class="col-lg-12">
            <!-- Form Elements -->
            <?php e($this->Flash->render()); ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    View Contact Us 
                </div>
                
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                           
                             <div class="form-group profile_row">
                                <label style="min-width: 250px;"> Name:</label>
                                <span><?php e($contacts->name); ?></span>
                            </div>
                             
                            <div class="form-group profile_row">
                                <label style="min-width: 250px;">Email:</label>
                                <span><?php e($contacts->email); ?></span>
                            </div>

                            <div class="form-group profile_row">
                                <label style="min-width: 250px;">Contact No:</label>
                                <span><?php  e($contacts->contact); ?> </span>
                            </div>
                           
                           <div class="form-group profile_row">
                                <label style="min-width: 250px;">Subject:</label>
                                <span><?php if($contacts->subject !=''){ e($contacts->subject); } else {  ?> <span style="color: red">Not Available</span> <?php } ?></span>
                            </div>

                             <div class="form-group profile_row">
                                <label style="min-width: 250px;">Message:</label>
                                <?php if($contacts->description ==''){ ?>
                                 <span style="color: red">Not Available</span> 
                             <?php } else { ?>
                                <br>
                                <span><?php e(nl2br($contacts->description)); ?> </span>
                                <?php } ?>
                            </div>
                             <div class="form-group profile_row">
                                <label style="min-width: 250px;">Date:</label>
                                <span><?php e(date("F jS, Y h:i A",$contacts->created)); ?></span>
                            </div>

                            
                            
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Form Elements -->
            <a href="<?php e($this->Url->build(ADMIN_FOLDER.'contact-us'.'/'));?>" class="btn btn-info">Back To Listing</a>
        </div>
    </div>
</div>