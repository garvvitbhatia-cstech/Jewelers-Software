

<?php
   #set page meta content
   
   $this->assign('title', SITE_TITLE.' :: Add Transporter');
   
   $this->assign('meta_robot', 'noindex, nofollow');
   
   ?>
<!--  page-wrapper -->
<div id="page-wrapper">
   <div class="row">
      <!-- page header -->
      <div class="col-lg-12">
         <h1 class="page-header">Add Transporter</h1>
      </div>
      <!--end page header -->
   </div>
   <div class="row">
      <div class="col-lg-12">
         <a href="<?php e($this->Url->build(ADMIN_FOLDER.'transporters-management'.'/'));?>" class="btn btn-info">Back To Listing</a><br />&nbsp;
      </div>
      <div class="col-lg-12">
         <!-- Form Elements -->
         <?php e($this->Flash->render()); ?>
         <div class="panel panel-default">
            <div class="panel-heading">
               Add Transporter information
            </div>
            <div class="panel-body">
               <div class="row">
                  <div class="col-lg-12">
                     <?= $this->Form->create(NULL,array('id' => 'addForm', 'type' => 'file', 'inputDefaults' => array('label' => false,'div' => false), 'name' => 'addForm', 'csrfToken' => $this->request->getAttribute('csrfToken')));?>
                     <div class="form-group">
                        <label>Full Name:<span class="mandatory_field">*</span></label> 
                        <input type="text" id="name" name="name" onkeyup="checkError(this.id);" confirmation="false" class="form-control">
                        <span id="nameError" class="admin_login_error"></span>
                     </div>
                     <div class="form-group">
                        <label>Email:<span class="mandatory_field">*</span></label> 
                        <input type="text" id="email" name="email" onkeyup="checkError(this.id);" confirmation="false" class="form-control">
                        <span id="emailError" class="admin_login_error"></span>
                     </div>
                     <div class="form-group">
                        <label>Phone No:<span class="mandatory_field">*</span></label> 
                        <input type="text" id="phone" name="phone" onkeyup="checkError(this.id);" confirmation="false" class="form-control" maxlength="10">
                        <span id="phoneError" class="admin_login_error"></span>
                     </div>
                     <div class="form-group">
                        <label>Password:<span class="mandatory_field">*</span></label> 
                        <input type="text" id="password" name="password" onkeyup="checkError(this.id);" confirmation="false" class="form-control">
                        <span id="passwordError" class="admin_login_error"></span>
                     </div>
                     <div class="form-group">
                        <label>Vehicle Type:<span class="mandatory_field">*</span></label> 
                        <select id="vehicle_id" name="vehicle_id" class="form-control" onchange="(this.value); checkError(this.id);">
                           <option value="">Select Vehicle Type</option>
                           <?php if(isset($vehicleTypeList) && !empty($vehicleTypeList)){ ?>
                           <?php foreach($vehicleTypeList as $key => $val): ?>
                           <option value="<?php e($key); ?>"><?php e($val); ?></option>
                           <?php endforeach; ?>
                           <?php } ?>
                        </select>
                        <span id="vehicle_idError" class="admin_login_error"></span>
                     </div>
                     <div class="form-group">
                        <label>Driving Licence No:<span class="mandatory_field">*</span></label> 
                        <input type="text" id="dl_no" name="dl_no" onkeyup="checkError(this.id);" confirmation="false" class="form-control" style="text-transform: uppercase;">
                        <span id="dl_noError" class="admin_login_error"></span>
                     </div>
                     <div class="form-group">
                        <label>RC No:<span class="mandatory_field">*</span></label> 
                        <input type="text" id="rc_no" name="rc_no" onkeyup="checkError(this.id);" confirmation="false" class="form-control" style="text-transform: uppercase;">
                        <span id="rc_noError" class="admin_login_error"></span>
                     </div>
                     <div class="form-group cropped" id="cropped"></div>
                     <div class="form-group">
                        <label>User Profile</label>
                        <input type="hidden" id="profile" name="profile"/>
                        <input type="file" id="profile_image"  accept="image/jpeg, image/png" onchange="checkError(this.id);" class="form-control" />
                        <input type="hidden" id="country_image_extension">                                
                        <span id="profile_imageError" class="admin_login_error"></span>
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
         <a href="<?php e($this->Url->build(ADMIN_FOLDER.'transporters-management'.'/'));?>" class="btn btn-info">Back To Listing</a>
      </div>
   </div>
</div>
<script type="text/javascript">
   function removeProfileImg(file){
   
   	if(file != ''){
   
   		$.ajax({
   
   			type: 'POST',
   
   			url: '<?php e($this->Url->build('/users/deleteUserImg'))?>',
   
   			data: {profile:file},
   
   			success: function(img){
   
   				$('#cropped').html('');
   
   				$('.cropped').hide();
   
   			}
   
   		});
   
   		return false;
   
   	}
   
   }
   
   $(document).ready(function(e){		
   
   $('#phone').filter_input({regex:'[0-9]'});
   
   
      var frmSubmitted = 0;
   
      $('.submitBtn').click(function(){
   
          var flag = 0;
   
          if(frmSubmitted == 0){
   
              if($.trim($('#name').val()) == ""){
   
                  $('#nameError').show().html('Please enter your fullname.').slideDown();
   
                  $('#name').focus();
   
                  frmSubmitted = 0;
   
                  flag = 1; return false;
   
              }
   
   
   		if($.trim($('#email').val()) == ""){
   
                  $('#emailError').show().html('Please enter your email address.').slideDown();
   
                  $('#email').focus();
   
                  frmSubmitted = 0;
   
                  flag = 1; return false;
   
              }else if($.trim($('#email').val()) != ""){
   
   			var filter = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
   
   			if(!filter.test($('#email').val())){
   
   				$('#emailError').show().html('Please enter valid email address.').slideDown();
   
   				$('#email').focus();
   
   				frmSubmitted = 0;
   
   				flag = 1; return false;					
   
   			}	
   
   		}
   		
              if($.trim($('#phone').val()) == ""){
   
                  $('#phoneError').show().html('Please enter your phone number.').slideDown();
   
                  $('#phone').focus();
   
                  frmSubmitted = 0;
   
                  flag = 1; return false;
   
              }
   
              var password = $.trim($('#password').val());
              if(password == ""){
                  $('#passwordError').show().html('Please enter password.').slideDown();
                  $('#password').focus();
                  frmSubmitted = 0;
                  flag = 1; return false;
   
                  }else if(password.length < 6){
                         $('#passwordError').show().html('Your password must be 6 to 15 characters').slideDown();
                          $('#password').focus();
                          frmSubmitted = 0;
                          flag = 1; return false;                 
                   
                  } else if($.trim($('#password').val()) != ""){
                   var filterStPass = /(?=^.{6,15}$)(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&amp;*()_+}{&quot;:;'?/&gt;.&lt;,])(?!.*\s).*$/;
                  if(!filterStPass.test($('#password').val())){
                      $('#passwordError').show().html('Password should include at least one upper case letter, one number, and one special character.').slideDown();
                      $('#password').focus();
                      frmSubmitted = 0;
                      flag = 1; return false;                 
                  }   
              }
   
              if($.trim($('#vehicle_id').val()) == ""){
   
                  $('#vehicle_idError').show().html('Please select your vehicle type.').slideDown();
   
                  $('#vehicle_id').focus();
   
                  frmSubmitted = 0;
   
                  flag = 1; return false;
   
              }
   
              if($.trim($('#dl_no').val()) == ""){
   
                  $('#dl_noError').show().html('Please enter your driving licence number.').slideDown();
   
                  $('#dl_no').focus();
   
                  frmSubmitted = 0;
   
                  flag = 1; return false;
   
              }
              if($.trim($('#rc_no').val()) == ""){
   
                  $('#rc_noError').show().html('Please enter your rc number.').slideDown();
   
                  $('#rc_no').focus();
   
                  frmSubmitted = 0;
   
                  flag = 1; return false;
   
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
   
</script>
<?= $this->Html->css('/js/cropper/cropper');?>
<?= $this->Html->script('/js/cropper/cropper');?>
<!-----Small Banner------>
<script type="text/javascript">
   var fld_data ='<?php e($this->encryptData('users')); ?>';
   
   var setURL ='<?php e($this->Url->build('/images/save_image'));?>';
   
   var setSaveURL ='<?php e($this->Url->build('/ajax/save_site_logo_image/'));?>';
   
   var oldImage ='';
   
   window.addEventListener('DOMContentLoaded', function () {
   
       var avatar = document.getElementById('cropped');
   
       var image = document.getElementById('image');
   
       var input = document.getElementById('profile_image');
   
       var actions = document.getElementById('minactions');
   
       var $modal = $('#myLogoModal');
   
       var cropper;
   
       input.addEventListener('change', function (e) {
   
           var fileName = $('#profile_image').val();
   
           var element  = '#profile_image';
   
           var errorElement  = '#profile_imageError';
   
           var ext = fileName.substring(fileName.lastIndexOf('.') + 1);
   
           $('#country_image_extension').val(ext);
   
           if(ext == "jpg" || ext == "jpeg" || ext == "JPG" || ext == "JPEG" || ext == "png" || ext == "PNG"){
   
   
   
               var files = e.target.files;
   
               var done = function (url) {
   
                   input.value = '';
   
                   image.src = url;
   
                   $modal.modal('show');
   
               };
   
   
   
               var reader;
   
               var file;
   
               var url;
   
   
   
               if (files && files.length > 0) {
   
                   file = files[0];
   
   
   
                   if (URL) {
   
                       done(URL.createObjectURL(file));
   
                   } else if (FileReader) {
   
                       reader = new FileReader();
   
                       reader.onload = function (e) {
   
                           done(reader.result);
   
                       };
   
                       reader.readAsDataURL(file);
   
                   }
   
               }
   
           }else{
   
               $(element).val(null);
   
               $(errorElement).fadeIn().html('Please upload jpg or png image');
   
               $(element).focus();
   
               return false;
   
           }
   
       });
   
   
   
       $modal.on('shown.bs.modal', function () {
   
           cropper = new Cropper(image, {
   
               dragMode: 'move',
   
               aspectRatio: 300 / 300,
   
               autoCropArea: 0.65,
   
               restore: false,
   
               guides: false,
   
               center: false,
   
               highlight: false,
   
               cropBoxMovable: false,
   
               cropBoxResizable: false,
   
               toggleDragModeOnDblclick: false,
   
               zoom: function (e) {
   
                   console.log(e.type, e.detail.ratio);
   
               }
   
           });
   
       }).on('hidden.bs.modal', function () {
   
           cropper.destroy();
   
           cropper = null;
   
       });
   
   
   
       // Methods
   
       actions.querySelector('.docs-buttons').onclick = function (event) {
   
           var e = event || window.event;
   
           var target = e.target || e.srcElement;
   
           var cropped;
   
           var result;
   
           var input;
   
           var data;
   
   
   
           if (!cropper) {
   
               return;
   
           }
   
   
   
           while (target !== this) {
   
               if (target.getAttribute('data-method')) {
   
                   break;
   
               }
   
               target = target.parentNode;
   
           }
   
   
   
           if (target === this || target.disabled || target.className.indexOf('disabled') > -1) {
   
               return;
   
           }
   
   
   
           data = {
   
               method: target.getAttribute('data-method'),
   
               target: target.getAttribute('data-target'),
   
               option: target.getAttribute('data-option') || undefined,
   
               secondOption: target.getAttribute('data-second-option') || undefined
   
           };
   
   
   
           cropped = cropper.cropped;
   
           if (data.method) {
   
               if (typeof data.target !== 'undefined') {
   
                   input = document.querySelector(data.target);
   
   
   
                   if (!target.hasAttribute('data-option') && data.target && input) {
   
                       try {
   
                           data.option = JSON.parse(input.value);
   
                       } catch (e) {
   
                           console.log(e.message);
   
                       }
   
                   }
   
               }
   
               switch (data.method) {
   
                   case 'rotate':
   
                   if (cropped && options.viewMode > 0) {
   
                       cropper.clear();
   
                   }
   
                   break;
   
                   case 'getCroppedCanvas':
   
                   try {
   
                       data.option = JSON.parse(data.option);
   
                   } catch (e) {
   
                       console.log(e.message);
   
                   }
   
                   if (uploadedImageType === 'image/jpeg' || uploadedImageType === 'image/png') {
   
                       if (!data.option) {
   
                           data.option = {};
   
                       }
   
                       data.option.fillColor = '#fff';
   
                   }
   
                   break;
   
               }
   
   
   
               result = cropper[data.method](data.option, data.secondOption);
   
               switch (data.method) {
   
                   case 'rotate':
   
                   if (cropped && options.viewMode > 0) {
   
                       cropper.crop();
   
                   }
   
                   break;
   
                   case 'scaleX':
   
                   case 'scaleY':
   
                   target.setAttribute('data-option', -data.option);
   
                   break;
   
   
   
                   case 'getCroppedCanvas':
   
                   if (result) {
   
                       // Bootstrap's Modal
   
                       $('#getCroppedCanvasModal').modal().find('.modal-body').html(result);
   
                       if (!download.disabled) {
   
                           download.download = uploadedImageName;
   
                           download.href = result.toDataURL(uploadedImageType);
   
                       }
   
                   }
   
                   break;
   
   
   
                   case 'destroy':
   
                   cropper = null;
   
                   if (uploadedImageURL) {
   
                       URL.revokeObjectURL(uploadedImageURL);
   
                       uploadedImageURL = '';
   
                       image.src = originalImageURL;
   
                   }
   
                   break;
   
               }
   
   
   
               if (typeof result === 'object' && result !== cropper && input) {
   
                   try {
   
                       input.value = JSON.stringify(result);
   
                   } catch (e) {
   
                       console.log(e.message);
   
                   }
   
               }
   
           }
   
       };
   
   
   
       document.getElementById('crop').addEventListener('click', function () {
   
           var initialAvatarURL;
   
           var canvas;
   
           $modal.modal('hide');
   
           if (cropper) {
   
               canvas = cropper.getCroppedCanvas({
   
                   width: 300,
   
                   height: 300
   
               });
   
               initialAvatarURL = avatar.src;
   
               avatar.src = canvas.toDataURL();
   
               var srcOriginal = canvas.toDataURL();
   
               $('.cropped').show();
   
               $('#cropped').html('<img src="'+srcOriginal+'" width="100"><a class="btn btn-danger" title="Remove Profile Image" href="javascript:void(0);" onclick="" style="margin-top: 65px;" id="removeProfileId" aria-label="Delete"><i class="fa fa-trash-o" aria-hidden="true"></i></a>');
   
               $('#profile_image').val(null);
   
               uploadProfileImg(srcOriginal);
   
           }
   
       });
   
   });
   
   function uploadProfileImg(crop_img){
   
       var ext = $('#country_image_extension').val();
   
       $.ajax({
   
           type: 'POST',
   
           url: setURL,
   
           data: {fld_data:fld_data,crop_img:crop_img,ext:ext,oldImage:oldImage},
   
           success: function(img){
   
               if(img != ''){
   
                   var obj = JSON.parse(img);
   
                   if(typeof obj['filename'] != 'undefined' && obj['filename'] != 'InvalidUser'){
   
   					$('#profile').val(obj['filename']);
   
   					$('#removeProfileId').attr('onclick',"removeProfileImg('"+obj['filename']+"');");
   
                   }else{
   
                       $('#profile_imageError').show().html('Something want to wrong, Please try again after sometime.');
   
                   }
   
               }else{
   
                   $('#profile_imageError').show().html('Something want to wrong, Please try again after sometime.');
   
               }
   
               return false;
   
           }
   
       });
   
   }
   
</script>
<div id="myLogoModal" class="modal fade cropperPopUp" data-keyboard="false" data-backdrop="static" role="dialog">
   <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h1>User Profeile (300 x 300)</h1>
         </div>
         <div class="modal-body">
            <div  style="width:100%">
               <div class="img-container">
                  <div id="main_image"><img id="image" src=""></div>
                  <div id="cropped-original" style="display:none;"></div>
               </div>
            </div>
            <div class="modal-footer">
               <div id="minactions">
                  <div class="docs-buttons text-center">
                     <div class="btn-group">
                        <button type="button" class="btn btn-primary" data-method="zoom" data-option="0.1" title="Zoom In">
                        <span class="docs-tooltip" data-toggle="tooltip" title="" data-original-title="cropper.zoom(0.1)">
                        <span class="fa fa-search-plus">+</span>
                        </span>
                        </button>
                        <button type="button" class="btn btn-primary" data-method="zoom" data-option="-0.1" title="Zoom Out">
                        <span class="docs-tooltip" data-toggle="tooltip" title="" data-original-title="cropper.zoom(-0.1)">
                        <span class="fa fa-search-minus">-</span>
                        </span>
                        </button>
                        <button type="button" class="btn btn-primary" id="crop">Crop</button>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

