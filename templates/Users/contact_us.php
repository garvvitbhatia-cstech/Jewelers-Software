<?php
   #set page meta content   
   $this->assign('title', SITE_TITLE.' :: Contact Us Management');   
   $this->assign('meta_robot', 'noindex, nofollow');   
   e($this->Element('/admin/jQuery'));   
?>
<!--  page-wrapper -->
<div id="page-wrapper">
   <div class="row">
      <div id="myProgress" style="display:block;width: 100%;background-color: #ddd;">
         <div id="myBar"></div>
      </div>
      <!-- page header -->
      <div class="col-lg-12">
         <h1 class="page-header">Contact Us Management</h1>
      </div>
      <!--end page header -->
   </div>
   <div class="panel panel-primary">
      <div class="panel-heading">
         <?= $this->Form->create(NULL, array('id' => 'searchForm', 'class' => 'searchForm', 'type' => 'post')) ?>
         <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
            Search By
            <span class="caret"></span>
            </button>
            <ul class="dropdown-menu pull-right" role="menu">
               <?php 
                  $session = $this->request->getSession();
                  $sessionType = $session->read('Auth.admin.type');
                  if($sessionType == 'SuperAdmin'){ 
                  ?>
               <li><a href="javascript:void(0);" onclick="searchoptions('type');">Franchise/SubAdmin</a></li>
               <?php } ?>
               <li><a href="javascript:void(0);" onclick="searchoptions('name');">Name</a></li>
               <li><a href="javascript:void(0);" onclick="searchoptions('email');">Email</a></li>
               <li><a href="javascript:void(0);" onclick="searchoptions('contact');">Contact No</a></li>
               <li><a href="javascript:void(0);" onclick="searchoptions('read_status');">Action</a></li>
            </ul>
         </div>
         <select name="franchise" id="franchise"  class="form-control filter searchOptions"  style="width:200px !important; display:none;" onchange="getAdminByType(this.value);">
            <option value="">Select One</option>
            <option value="FranchiseAdmin">Franchise</option>
            <option value="SubAdmin">SubAdmin</option>
         </select>
         <select style="width:200px !important; display:none;"  name="franchisename" id="franchisename" class="form-control filter searchOptions">
            <option value="">Select Name</option>
         </select>
         <input name="name" id="name" placeholder="Name" class="form-control filter searchOptions"  style="width:200px !important; display:none;">
         <input name="contact" id="contact" placeholder="contact No" class="form-control filter searchOptions"  style="width:200px !important; display:none;">
         <input name="email" id="email" placeholder="Email" class="form-control filter searchOptions"  style="width:200px !important; display:none;">
         <select style="width:200px !important; display:none;"  name="read_status" id="read_status" class="form-control filter searchOptions">
            <option value="">Status</option>
            <option value="1">Read</option>
            <option value="2">Unread</option>
         </select>
         <a style="display:none;" onclick="searchData();" class="btn btn-info searchbuttons" id="searchbuttons">Search</a>
         <a style="display:none;" onclick="resetFilterForm();" class="btn btn-danger searchbuttons">Reset</a>
         <?= $this->Form->end() ?>
      </div>
      <input type="hidden" value="<?php e($this->Url->build(ADMIN_FOLDER.'/contact-us-filter/'));?>" id="paginatUrl">
      <div class="panel-body">
         <div class="row">
            <div class="col-lg-12">
               <div class="table-responsive">
                  <div id="replaceHtml">
                     <table class="table table-bordered table-hover table-striped">
                        <thead>
                           <tr>
                              <th>S.No.</th>
                              <th>User Details</th>
                              <th>Action</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php
                              if(count($contacts) > 0){                              
                                foreach($contacts as $key => $contact){                              
                                $readStatus = '';
								if($contact->read_status == 1){
									$readStatus = "color: #4285F4 ";
                              	}
                           ?>
                           <tr>
                              <td width="5%"><?php e($key+1); ?>.</td>
                               <td>
                              	<b>Name: </b><?php e(ucwords(isCheckVal($contact->name)));?><br />
                                <b>Email: </b><?php e(isCheckVal($contact->email));?><br />
                                <b>Contact: </b><?php e(isCheckVal($contact->contact));?><br />
                                <?php e(date("F jS, Y h:i A",$contact->created)); ?>
                              </td>                              
                              <td width="30%" style="<?php e($readStatus) ?>">
                                 <a href="<?php e($this->Url->build(ADMIN_FOLDER.'/view-contact-us/'.base64_encode($this->encryptData($contact->id))));?>" title="View" class="btn btn-primary">View</a>
                                 <a href="javascript:void(0);" onclick="deleteRecord('Contacts','<?php e(base64_encode($this->encryptData($contact->id))); ?>','0');" title="Delete" class="btn btn-danger">Delete</a>
                              </td>
                           </tr>
                           <?php
                              }
                              
                              }else{
                              
                              ?>
                           <tr>
                              <td class="text-center" colspan="7">Records are not found.</td>
                           </tr>
                           <?php } ?>
                        </tbody>
                        <?php if(count($contacts) > 0){  ?>
                        <tbody>
                           <tr>
                              <td align="center" colspan="12">
                                 <ul class="pagination">
                                    <?php
                                       $this->Paginator->options(array('update' => '#replaceHtml', 'evalScripts' => true, 'escape' => false, 'url' => array_merge(array('controller' => 'Contacts', 'action' => 'contactUsFilter'))));?>
                                    <?php echo $this->Paginator->first('First'); ?>
                                    <?php echo $this->Paginator->numbers(); ?>
                                    <?php echo $this->Paginator->last('Last'); ?>
                                 </ul>
                              </td>
                           </tr>
                        </tbody>
                        <?php } ?>
                     </table>
                  </div>
               </div>
            </div>
         </div>
         <!-- /.row -->
      </div>
      <!-- /.panel-body -->
   </div>
</div>
<script type="text/javascript">
   $(document).ready(function(e) {   
       $('.contact').filter_input({regex:'[0-9]'});   
   });   
   
   
   function searchoptions(search_options){   
       $('.searchOptions').hide().val('');   
       $('.searchbuttons').hide();   
       if(search_options != ''){   
           if(search_options == 'type'){
               $('#franchise').show().focus();
               $('#franchisename').show();
           }else{
            $('#'+search_options).show().focus();
           }   
           $('.searchbuttons').show();   
       }   
   }
   
   function getAdminByType(adminType){
         
           if(adminType != '' ){
               $.ajax({
                   type: 'POST',
                   url: '<?php e($this->Url->build('/ajax/getAdminByType/'));?>',
                   data: {adminType:adminType},
                   success: function(response){
                       $('#franchisename').html(response);
                   }
               }); 
               return false;
           }
       }
   
   /* search form */
   
   function searchData(){
   	$('#searchbuttons').html('Searching...');   
   	$.ajax({   
   		type: 'POST',   
   		url: '<?php e($this->Url->build(ADMIN_FOLDER.'/contact-us-filter/'));?>',   
   		data: $('#searchForm').serialize(),   
   		success: function(msg){   
   			$('#replaceHtml').html(msg);   
   			$('#searchbuttons').html('Search');   
   			return false;   
   		},error: function(ts){   
		   $('#searchbuttons').html('Search');   
		   $('#error500').modal('show');   
	   }   
   	});   
   }   
</script>