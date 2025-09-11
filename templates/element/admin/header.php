<!-- navbar top -->
<nav class="navbar navbar-default navbar-fixed-top" role="navigation" id="navbar">
    <!-- navbar-header -->
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <?php
        #get admin data
        $adminData = $this->Admin->getSettings();
        /*if(isset($adminData->id) && !empty($adminData->logo)){
            $imgPath = WWW_ROOT.'img/logos/'.$adminData->logo;
            if(is_file($imgPath)){
                $imgPath = 'logos/'.$adminData->logo;
                ?>
                <a class="navbar-brand" href="<?php e($this->Url->build(ADMIN_FOLDER));?>">
                <?php
                e($this->Html->image($imgPath, array('title'=>SITE_TITLE, 'alt'=> SITE_TITLE, 'width' => 'auto', 'style' => 'height:65px' )));
                ?>
                </a>
                <?php
            }
        }*/
        ?>
    </div>
    <!-- end navbar-header -->
    <!-- navbar-top-links -->
    <ul class="nav navbar-top-links navbar-right">
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <i class="fa fa-user fa-3x"></i>
            </a>
            <!-- dropdown user-->
            <ul class="dropdown-menu dropdown-user">
                <li><a href="<?php e($this->Url->build(ADMIN_FOLDER.'dashboard/')); ?>"><i class="fa fa-dashboard fa-fw"></i>Dashboard</a>
                </li>
                <li><a href="<?php e($this->Url->build(ADMIN_FOLDER.'my-account/')); ?>"><i class="fa fa-user fa-fw"></i>My Profile</a>
                </li>
                <li><a href="<?php e($this->Url->build(ADMIN_FOLDER.'site-configuration/')); ?>"><i class="fa fa-wrench fa-fw"></i>Site Configuration</a>
                </li>
                <li><a href="<?php e($this->Url->build(ADMIN_FOLDER.'change-password/')); ?>"><i class="fa fa-gear fa-fw"></i>Change Password</a>
                </li>
                <li class="divider"></li>
                <li><a href="javascript:void(0);" data-toggle="modal" data-target="#logoutPopup"><i class="fa fa-sign-out fa-fw"></i>Logout</a>
                </li>
            </ul>
            <!-- end dropdown-user -->
        </li>
        <!-- end main dropdown -->
    </ul>
    <!-- end navbar-top-links -->
</nav>
<!-- end navbar top -->

<script type="text/javascript">
	var SiteUrl = '<?php echo SITEURL; ?>';
</script>