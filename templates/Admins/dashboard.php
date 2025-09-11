<?php
   #set page meta content   
   $this->assign('title', SITE_TITLE.' :: Admin Dashboard');   
   $this->assign('meta_robot', 'noindex, nofollow');   
?>
<!--  page-wrapper -->
<div id="page-wrapper" class="dashboard-box">
   <div class="row">
      <!-- Page Header -->
      <div class="col-lg-12">
         <h1 class="page-header">Dashboard</h1>
      </div>
      <!--End Page Header -->
   </div>
   <div class="row">
      <!-- Welcome -->
      <div class="col-lg-12">
         <div class="alert alert-info">
            <i class="fa fa-folder-open"></i><b>&nbsp; </b>Global Sections Management <b></b>
         </div>
      </div>
      <!--end  Welcome -->
   </div>
   <div class="row">
      <!--quick dashboard menus section -->
      <div class="col-lg-3 col-sm-4 col-md-4">
         <a href="<?php e($this->Url->build(ADMIN_FOLDER.'my-account/')); ?>" title="My Account">
            <div class="panel panel-primary text-center no-boder">
               <div class="panel-body blue">
                  <i class="fa fa-user fa-3x"></i>
                  <h3>My Account</h3>
               </div>
            </div>
         </a>
      </div>
      <div class="col-lg-3 col-sm-4 col-md-4">
         <a href="<?php e($this->Url->build(ADMIN_FOLDER.'site-configuration/')); ?>" title="Site Configuration">
            <div class="panel panel-primary text-center no-boder">
               <div class="panel-body yellow">
                  <i class="fa fa-wrench fa-3x"></i>
                  <h3>Site Configuration</h3>
               </div>
            </div>
         </a>
      </div>
      <div class="col-lg-3 col-sm-4 col-md-4">
         <a href="<?php e($this->Url->build(ADMIN_FOLDER.'change-password/')); ?>" title="Change Password">
            <div class="panel panel-primary text-center no-boder">
               <div class="panel-body green">
                  <i class="fa fa fa-cog fa-3x"></i>
                  <h3>Change Password</h3>
               </div>
            </div>
         </a>
      </div>
      <?php /*?>
      <div class="col-lg-3 col-sm-4 col-md-4">
         <a href="<?php e($this->Url->build(ADMIN_FOLDER.'static-content-management/')); ?>" title="Static Contents">
            <div class="panel panel-primary text-center no-boder">
               <div class="panel-body red">
                  <i class="fa fa fa-edit fa-3x"></i>
                  <h3>Static Contents</h3>
               </div>
            </div>
         </a>
      </div>
      <?php */?>
   </div>
   <?php /*?>
   <div class="row">
      <!-- Welcome -->
      <div class="col-lg-12">
         <div class="alert alert-info">
            <i class="fa fa-folder-open"></i><b>&nbsp; </b>Content Management System<b></b>
         </div>
      </div>
      <div class="col-lg-3 col-sm-4 col-md-4">
         <a href="<?php e($this->Url->build(ADMIN_FOLDER.'inner-page-management/')); ?>" title="Inner Pages">
            <div class="panel panel-primary text-center no-boder">
               <div class="panel-body green">
                  <i class="fa fa fa-file fa-3x"></i>
                  <h3>Inner Pages</h3>
               </div>
            </div>
         </a>
      </div>
      <div class="col-lg-3 col-sm-4 col-md-4">
         <a href="<?php e($this->Url->build(ADMIN_FOLDER.'cms/')); ?>" title="Cms Pages">
            <div class="panel panel-primary text-center no-boder">
               <div class="panel-body red">
                  <i class="fa fa fa-file fa-3x"></i>
                  <h3>Cms Pages</h3>
               </div>
            </div>
         </a>
      </div>
      <div class="col-lg-3 col-sm-4 col-md-4">
         <a href="<?php e($this->Url->build(ADMIN_FOLDER.'header-navigations/')); ?>" title="Header Navigations">
            <div class="panel panel-primary text-center no-boder">
               <div class="panel-body blue">
                  <i class="fa fa fa-list-ul fa-3x"></i>
                  <h3>Header Navigations</h3>
               </div>
            </div>
         </a>
      </div>
      <div class="col-lg-3 col-sm-4 col-md-4">
         <a href="<?php e($this->Url->build(ADMIN_FOLDER.'footer-navigations/')); ?>" title="Footer Navigations">
            <div class="panel panel-primary text-center no-boder">
               <div class="panel-body yellow">
                  <i class="fa fa fa-list-ul fa-3x"></i>
                  <h3>Footer Navigations</h3>
               </div>
            </div>
         </a>
      </div>
   </div>
   <?php */?>
   <div class="row">
      <!-- Welcome -->
      <div class="col-lg-12">
         <div class="alert alert-info">
            <i class="fa fa-folder-open"></i><b>&nbsp; </b>User Management<b></b>
         </div>
      </div>
      <!--end  Welcome -->
   </div>
   <div class="row">      
      <div class="col-lg-3 col-sm-4 col-md-4">
         <a href="<?php e($this->Url->build(ADMIN_FOLDER.'customer-management/')); ?>" title="Customers">
            <div class="panel panel-primary text-center no-boder">
               <div class="panel-body green">
                  <i class="fa fa-user fa-3x"></i>
                  <h3>Customers</h3>
               </div>
            </div>
         </a>
      </div>
      <!--<div class="col-lg-3 col-sm-4 col-md-4">
         <a href="<?php e($this->Url->build(ADMIN_FOLDER.'user-management/')); ?>" title="Users">
            <div class="panel panel-primary text-center no-boder">
               <div class="panel-body red">
                  <i class="fa fa fa-user fa-3x"></i>
                  <h3>Users</h3>
               </div>
            </div>
         </a>
      </div>-->
      <?php /*?>
      <div class="col-lg-3 col-sm-4 col-md-4">
         <a href="<?php e($this->Url->build(ADMIN_FOLDER.'transporters-management/')); ?>" title="Transporters">
            <div class="panel panel-primary text-center no-boder">
               <div class="panel-body blue">
                  <i class="fa fa-users fa-3x"></i>
                  <h3>Transporters</h3>
               </div>
            </div>
         </a>
      </div>
      <?php */?>          
      <div class="col-lg-3 col-sm-4 col-md-4">
         <a href="<?php e($this->Url->build(ADMIN_FOLDER.'contact-us/')); ?>" title="Contact Us">
            <div class="panel panel-primary text-center no-boder">
               <div class="panel-body yellow">
                  <i class="fa fa fa-phone fa-3x"></i>
                  <h3>Contact Us</h3>
               </div>
            </div>
         </a>
      </div>
      <div class="col-lg-3 col-sm-4 col-md-4">
         <a href="<?php e($this->Url->build(ADMIN_FOLDER.'barcodes/')); ?>" title="Barcodes">
            <div class="panel panel-primary text-center no-boder">
               <div class="panel-body yellow">
                  <i class="fa fa fa-bars fa-3x"></i>
                  <h3>Barcodes</h3>
               </div>
            </div>
         </a>
      </div>
   </div>
   <div class="row">
      <!-- Welcome -->
      <div class="col-lg-12">
         <div class="alert alert-info">
            <i class="fa fa-folder-open"></i><b>&nbsp; </b>Product Management <b></b>
         </div>
      </div>
      <!--end  Welcome -->
   </div>
   <div class="row">
      <div class="col-lg-3 col-sm-4 col-md-4">
         <a href="<?php e($this->Url->build(ADMIN_FOLDER.'categories/')); ?>" title="Category">
            <div class="panel panel-primary text-center no-boder">
               <div class="panel-body yellow">
                  <i class="fa fa fa-list-alt fa-3x"></i>
                  <h3>Category</h3>
               </div>
            </div>
         </a>
      </div>
      <div class="col-lg-3 col-sm-4 col-md-4">
         <a href="<?php e($this->Url->build(ADMIN_FOLDER.'products/')); ?>" title="Products">
            <div class="panel panel-primary text-center no-boder">
               <div class="panel-body blue">
                  <i class="fa fa fa-list-alt fa-3x"></i>
                  <h3>Products</h3>
               </div>
            </div>
         </a>
      </div>
      <div class="col-lg-3 col-sm-4 col-md-4">
         <a href="<?php e($this->Url->build(ADMIN_FOLDER.'agent-management/')); ?>" title="Sub-Admin">
            <div class="panel panel-primary text-center no-boder">
               <div class="panel-body green">
                  <i class="fa fa fa-user fa-3x"></i>
                  <h3>Sub-Admin</h3>
               </div>
            </div>
         </a>
      </div>
      <?php /*?>
      <div class="col-lg-3 col-sm-4 col-md-4">
         <a href="<?php e($this->Url->build(ADMIN_FOLDER.'order-management/')); ?>" title="Orders">
            <div class="panel panel-primary text-center no-boder">
               <div class="panel-body red">
                  <i class="fa fa-list fa-3x"></i>
                  <h3>Orders</h3>
               </div>
            </div>
         </a>
      </div>
      <?php */?>
   </div>
   <div class="row">
      <!-- Welcome -->
      <div class="col-lg-12">
         <div class="alert alert-info">
            <i class="fa fa-folder-open"></i><b>&nbsp; </b>Sales Management <b></b>
         </div>
      </div>
      <!--end  Welcome -->
   </div>
   <div class="row">
      <div class="col-lg-3 col-sm-4 col-md-4">
         <a href="<?php e($this->Url->build(ADMIN_FOLDER.'sales-manager/')); ?>" title="Sales Manager">
            <div class="panel panel-primary text-center no-boder">
               <div class="panel-body green">
                  <i class="fa fa-shopping-cart fa-3x"></i>
                  <h3>Sales Manager</h3>
               </div>
            </div>
         </a>
      </div>
      <div class="col-lg-3 col-sm-4 col-md-4">
         <a href="<?php e($this->Url->build(ADMIN_FOLDER.'order-manager/')); ?>" title="Orders">
            <div class="panel panel-primary text-center no-boder">
               <div class="panel-body green">
                  <i class="fa fa-shopping-cart fa-3x"></i>
                  <h3>Orders</h3>
               </div>
            </div>
         </a>
      </div>
   </div>
   <?php /*?>
   <div class="row">
      <!-- Welcome -->
      <div class="col-lg-12">
         <div class="alert alert-info">
            <i class="fa fa-folder-open"></i><b>&nbsp; </b>Location Management <b></b>
         </div>
      </div>
      <!--end  Welcome -->
   </div>
   <div class="row">
      <div class="col-lg-3 col-sm-4 col-md-4">
         <a href="<?php e($this->Url->build(ADMIN_FOLDER.'country-management/')); ?>" title="Countries">
            <div class="panel panel-primary text-center no-boder">
               <div class="panel-body green">
                  <i class="fa fa fa-globe fa-3x"></i>
                  <h3>Countries</h3>
               </div>
            </div>
         </a>
      </div>
      <div class="col-lg-3 col-sm-4 col-md-4">
         <a href="<?php e($this->Url->build(ADMIN_FOLDER.'state-management/')); ?>" title="States">
            <div class="panel panel-primary text-center no-boder">
               <div class="panel-body yellow">
                  <i class="fa fa fa-globe fa-3x"></i>
                  <h3>States</h3>
               </div>
            </div>
         </a>
      </div>
      <div class="col-lg-3 col-sm-4 col-md-4">
         <a href="<?php e($this->Url->build(ADMIN_FOLDER.'city-management/')); ?>" title="Cities">
            <div class="panel panel-primary text-center no-boder">
               <div class="panel-body blue">
                  <i class="fa fa fa-globe fa-3x"></i>
                  <h3>Cities</h3>
               </div>
            </div>
         </a>
      </div>
      <!--end dashboard menus section -->
   </div>
   <?php */?>
   <?php /*?>
   <div class="row">
      <!-- Welcome -->
      <div class="col-lg-12">
         <div class="alert alert-info">
            <i class="fa fa-folder-open"></i><b>&nbsp; </b>Banner Management <b></b>
         </div>
      </div>
      <!--end  Welcome -->
   </div>
   <div class="row">
      <div class="col-lg-3 col-sm-4 col-md-4">
         <a href="<?php e($this->Url->build(ADMIN_FOLDER.'banner-management/')); ?>" title="Banners">
            <div class="panel panel-primary text-center no-boder">
               <div class="panel-body red">
                  <i class="fa fa-camera-retro fa-3x"></i>
                  <h3>Banners</h3>
               </div>
            </div>
         </a>
      </div>
      <div class="col-lg-3 col-sm-4 col-md-4">
         <a href="<?php e($this->Url->build(ADMIN_FOLDER.'testimonials/')); ?>" title="Testimonials">
            <div class="panel panel-primary text-center no-boder">
               <div class="panel-body yellow">
                  <i class="fa fa fa-user fa-3x"></i>
                  <h3>Testimonials</h3>
               </div>
            </div>
         </a>
      </div>
   </div>
   <?php */?>
   
   <div class="row">
      <!-- Welcome -->
      <div class="col-lg-12">
         <div class="alert alert-info">
            <i class="fa fa-folder-open"></i><b>&nbsp; </b>Report Management<b></b>
         </div>
      </div>
      <!--end  Welcome -->
   </div>
   <div class="row">      
      <div class="col-lg-3 col-sm-4 col-md-4">
         <a href="<?php e($this->Url->build(ADMIN_FOLDER.'sales-item-report/')); ?>" title="Sales Items Report">
            <div class="panel panel-primary text-center no-boder">
               <div class="panel-body green">
                  <i class="fa fa-file fa-3x"></i>
                  <h3>Sales Item Report</h3>
               </div>
            </div>
         </a>
      </div>
      <div class="col-lg-3 col-sm-4 col-md-4">
         <a href="<?php e($this->Url->build(ADMIN_FOLDER.'old-gold-report/')); ?>" title="Old Gold Report">
            <div class="panel panel-primary text-center no-boder">
               <div class="panel-body green">
                  <i class="fa fa-file fa-3x"></i>
                  <h3>Old Gold Report</h3>
               </div>
            </div>
         </a>
      </div>
</div>