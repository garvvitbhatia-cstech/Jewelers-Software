<!-- navbar side -->

<nav class="navbar-default navbar-static-side" role="navigation">

    <!-- sidebar-collapse -->

    <div class="sidebar-collapse">

        <!-- side-menu -->

        <ul class="nav" id="side-menu">

            <li>

                <?php

                #get admin data

                $session = $this->request->getSession();

                $adminData = $this->Admin->getData($session->read(AUTHADMINID));

                if(isset($adminData->id)){

                ?>

                    <!-- user image section-->

                    <div class="user-section">

    

                            <?php

                            $imgPath = 'user.jpg';

                            if($adminData->profile_image != ""){

                                $imgPath = WWW_ROOT.'img/profile_image/'.$adminData->profile_image;

                                if(is_file($imgPath)){

                                    $imgPath = 'profile_image/'.$adminData->profile_image;

                                }

                            }

                            e($this->Html->image($imgPath, array('title'=>'Profile Image', 'alt'=> 'Profile Image','width' => 118)));

                            ?>

                     

                           <?php /*?> <div><?php e($this->decryptData($adminData->firstname)); ?></div><?php */?>

                       

                    </div>

                    <?php

                }

                ?>

                <!--end user image section-->

            </li>

            <li class="selected">

                <a href="<?php e($this->Url->build(ADMIN_FOLDER));?>"><i class="fa fa-dashboard fa-fw"></i>Dashboard</a>

            </li>

            <li>

                <a href="<?php e($this->Url->build(ADMIN_FOLDER.'my-account/')); ?>"><i class="fa fa-user fa-fw"></i>My Account</a>

            </li>

            <li>

                <a href="<?php e($this->Url->build(ADMIN_FOLDER.'site-configuration/')); ?>"><i class="fa fa-wrench fa-fw"></i>Site Configuration</a>

            </li>

            <li>

                <a href="<?php e($this->Url->build(ADMIN_FOLDER.'change-password/')); ?>"><i class="fa fa-cog fa-fw"></i>Change Password</a>

            </li>

            <li>

                <a href="javascript:void(0);" data-toggle="modal" data-target="#logoutPopup"><i class="fa fa-lock fa-fw"></i>Logout</a>

            </li>

        </ul>

        <!-- end side-menu -->

    </div>

    <!-- end sidebar-collapse -->

</nav>

<!-- end navbar side -->





<div class="modal fade fancyPopup" id="errorMsgPopup" role="dialog" data-keyboard="false" data-backdrop="static">

   <div class="modal-dialog">

      <div class="modal-content">

         <div class="modal-header">

            <h1 class="modal-title text-center">Alert!</h1>

         </div>

         <div class="modal-body" style="padding:15px 30px !important;">

            <div class="rowField_col">

               <div class="row">

                  <div class="col-sm-12">

                     <div class="help_from" style="padding:15px;">

                        <p id="errMsgId"></p>

                     </div>

                  </div>

               </div>

            </div>

            <div class="row twoButton text-center">

               <div class="col-sm-12">

                  <button type="button" data-dismiss="modal" class="btn btn-outline btn-warning">Ok</button>

               </div>

            </div>

         </div>

      </div>

   </div>

</div>

<script type="text/javascript">
	var SiteUrl = '<?php echo SITEURL; ?>';
</script>