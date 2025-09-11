<?php
   #set page meta content 
   $this->assign('title', SITE_TITLE.' :: Edit User');   
   $this->assign('meta_robot', 'noindex, nofollow');
?>
<!--  page-wrapper -->
<div id="page-wrapper">
   <div class="row">
      <!-- page header -->
      <div class="col-lg-12">
         <h1 class="page-header">Edit User</h1>
      </div>
      <!--end page header -->
   </div>
   <div class="row">
      <div class="col-lg-12">
         <a href="<?php e($this->Url->build(ADMIN_FOLDER.'user-management'.'/'));?>" class="btn btn-info">Back To Listing</a><br />&nbsp;
      </div>
      <div class="col-lg-12">
         <!-- Form Elements -->
         <?php e($this->Flash->render()); ?>
         <div class="panel panel-default">
            <div class="panel-heading">
               Update user information
            </div>
            <div class="panel-body">
               <div class="row">
                  <div class="col-lg-12">
                     <?= $this->Form->create(NULL,array('id' => 'editForm', 'type' => 'file', 'inputDefaults' => array('label' => false,'div' => false), 'name' => 'editForm', 'csrfToken' => $this->request->getAttribute('csrfToken')));?>
                     <input type="hidden" name="edit_token" id="edit_token" value="<?php e($this->encryptData($editData->id))?>">
                     <div class="form-group">
                        <label>Unique ID:<span class="mandatory_field">*</span></label> 
                        <input type="text" value="<?php e($editData->unique_id);?>" readonly="readonly" class="form-control">
                     </div>
                     <div class="form-group">
                        <label>Full Name:<span class="mandatory_field">*</span></label> 
                        <input type="text" id="name" name="name" value="<?php e($editData->name);?>" onkeyup="checkError(this.id);" confirmation="false" class="form-control">
                        <span id="nameError" class="admin_login_error"></span>
                     </div>
                     <div class="form-group">
                        <label>Phone No:<span class="mandatory_field">*</span></label> 
                        <input type="text" id="phone" name="phone" maxlength="10" value="<?php e($editData->phone);?>" onkeyup="checkError(this.id);" confirmation="false" class="form-control">
                        <span id="phoneError" class="admin_login_error"></span>
                     </div>
                     <div class="form-group">
                        <label>Username:<span class="mandatory_field">*</span></label> 
                        <input type="text" value="<?php e($this->decryptData($editData->username));?>" readonly="readonly" class="form-control">
                     </div>
                     <div class="form-group">
                        <label>Email:<span class="mandatory_field">*</span></label> 
                        <input type="text" id="email" name="email" value="<?php e($this->decryptData($editData->email));?>" onkeyup="checkError(this.id);" confirmation="false" class="form-control">
                        <span id="emailError" class="admin_login_error"></span>
                     </div>
                     <div class="form-group">
                        <label>Password:<span class="mandatory_field">*</span></label> 
                        <input type="text" id="password" name="password" onkeyup="checkError(this.id);" value="<?php e($this->decryptData($editData->password));?>" confirmation="false" class="form-control">
                        <span id="passwordError" class="admin_login_error"></span>
                     </div>                     
                     <div class="form-group">
                        <label>State:<span class="mandatory_field"></span></label>
                        <select name="state_id" id="state_id" onchange="getCityName(this.value); checkError(this.id);" confirmation="false" class="form-control">
                           <option value="">Select State</option>
                           <?php   if(isset($stateList) && !empty($stateList)){ ?>
                           <?php foreach($stateList as $key => $val): ?>
                           <option <?php if($editData->state_id == $key){e('selected');} ?> value="<?php e($key); ?>"><?php e($val); ?></option>
                           <?php endforeach; ?>
                           <?php } ?>
                        </select>
                        <span id="state_idError" class="admin_login_error"></span> 
                     </div>
                     <div class="form-group">
                        <label>City:<span class="mandatory_field"></span></label>
                        <?php $cityList = $this->State->getCityByStateId($editData->state_id); ?>
                        <select name="city_id" id="city_id" onchange="checkError(this.id);" confirmation="false" class="form-control">
                           <?php foreach($cityList as $key => $val): ?>
                           <option <?php if($editData->city_id == $key){e('selected');} ?> value="<?php e($key); ?>"><?php e($val); ?></option>
                           <?php endforeach; ?>
                        </select>
                        <span id="city_idError" class="admin_login_error"></span> 
                     </div>
                     <div class="form-group">
                        <label>Address:</label>
                        <input type="text" id="address" name="address" onkeyup="checkError(this.id);" value="<?php e($editData->address);?>" confirmation="false" class="form-control">
                        <span id="addressError" class="admin_login_error"></span>
                     </div>                     
                     <div class="form-group">
                        <label>Pincode:</label>
                        <input type="text" id="pincode" maxlength="6" minlength="6" name="pincode" onkeyup="checkError(this.id);" value="<?php e($editData->pincode);?>" confirmation="false" class="form-control">
                        <span id="addressError" class="admin_login_error"></span>
                     </div>
                     <div class="form-group cropped" id="cropped">
                        <?php
                           if(!empty($editData->profile)){                      
                               $imgPath = WWW_ROOT.'img/users/'.$editData->profile;                           
                               if(is_file($imgPath)){                           
                                   e($this->Html->image('users/'.$editData->profile, array('title'=>$editData->name, 'alt'=> $editData->name, 'width' => '100' )));                           		}                           
                           }
						?>
                     </div>
                     <div class="form-group">
                        <label>User Profile</label>
                        <input type="hidden" id="profile" name="profile"/>
                        <input type="file" id="profile_image"  accept="image/jpeg, image/png" onchange="checkError(this.id);" class="form-control" />
                        <input type="hidden" id="profile_image_extension">                                
                        <span id="profile_imageError" class="admin_login_error"></span>
                     </div>
                     <div class="form-group">
                        <label>Status</label>
                        <div class="checkbox">
                           <label>
                           <input type="checkbox" <?php if($editData->status == 1){e('checked'); }?> value="1" name="status">Active
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
         <a href="<?php e($this->Url->build(ADMIN_FOLDER.'user-management'.'/'));?>" class="btn btn-info">Back To Listing</a>
      </div>
   </div>
</div>
<script type="text/javascript">
function getCityName(stateId) {
	if (stateId != '') {
		$.ajax({
			type: 'POST',
			url: '<?php e($this->Url->build('/ajax/getCity/'));?>',
			data: {stateId: stateId},
			success: function (response){
				$('#city_id').html(response);
			}
		});
		return false;
	}
}
$(document).ready(function (e) {
	$('#phone').filter_input({regex: '[0-9]'});
	$('#pincode').filter_input({regex: '[0-9]'});
	var frmSubmitted = 0;
	$('.submitBtn').click(function(){
		var flag = 0;
		if(frmSubmitted == 0){
			if ($.trim($('#name').val()) == ""){
				$('#nameError').show().html('Please enter your fullname.').slideDown();
				$('#name').focus();
				frmSubmitted = 0;
				flag = 1;
				return false;
			}
			if ($.trim($('#email').val()) == ""){
				$('#emailError').show().html('Please enter your email address.').slideDown();
				$('#email').focus();
				frmSubmitted = 0;
				flag = 1;
				return false;
			} else if ($.trim($('#email').val()) != ""){
				var filter = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
				if (!filter.test($('#email').val())){
					$('#emailError').show().html('Please enter valid email address.').slideDown();
					$('#email').focus();
					frmSubmitted = 0;
					flag = 1;
					return false;
				}
			}
			if ($.trim($('#phone').val()) == ""){
				$('#phoneError').show().html('Please enter your phone number.').slideDown();
				$('#phone').focus();
				frmSubmitted = 0;
				flag = 1;
				return false;
			}
			var password = $.trim($('#password').val());
			if (password == ""){
				$('#passwordError').show().html('Please enter password.').slideDown();
				$('#password').focus();
				frmSubmitted = 0;
				flag = 1;
				return false;
			} else if (password.length < 6){
				$('#passwordError').show().html('Your password must be 6 to 15 characters').slideDown();
				$('#password').focus();
				frmSubmitted = 0;
				flag = 1;
				return false;
			} else if ($.trim($('#password').val()) != ""){
				var filterStPass = /(?=^.{6,15}$)(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*()_+}{":;'?/>.<,])(?!.*\s).*$/;
				if (!filterStPass.test($('#password').val())){
					$('#passwordError').show().html('Password should include at least one upper case letter, one number, and one special character.').slideDown();
					$('#password').focus();
					frmSubmitted = 0;
					flag = 1;
					return false;
				}
			}
			if (flag == 0){
				$('.submitBtn').html('Processing...');
				$('#editForm').submit();
				frmSubmitted = 1;
				return true;
			}
		} else {
			return false;
		}
	});
}); 
</script>
<?= $this->Html->css('/js/cropper/cropper');?>
<?= $this->Html->script('/js/cropper/cropper');?>
<!-----Small Banner------>
<script type = "text/javascript">
var fld_data = '<?php e($this->encryptData('users')); ?>';
var setURL = '<?php e($this->Url->build('/images/save_image'));?>';
var setSaveURL = '<?php e($this->Url->build('/ajax/save_site_logo_image/'));?>';
var oldImage = '<?php e($editData->profile);?>';

window.addEventListener('DOMContentLoaded', function (){
	var avatar = document.getElementById('cropped');
	var image = document.getElementById('image');
	var input = document.getElementById('profile_image');
	var actions = document.getElementById('minactions');
	var $modal = $('#myLogoModal');
	var cropper;
	input.addEventListener('change', function (e){
		var fileName = $('#profile_image').val();
		var element = '#profile_image';
		var errorElement = '#profile_imageError';
		var ext = fileName.substring(fileName.lastIndexOf('.') + 1);
		$('#profile_image_extension').val(ext);
		if (ext == "jpg" || ext == "jpeg" || ext == "JPG" || ext == "JPEG" || ext == "png" || ext == "PNG"){
			var files = e.target.files;
			var done = function (url){
				input.value = '';
				image.src = url;
				$modal.modal('show');
			};
			var reader;
			var file;
			var url;
			if (files && files.length > 0){
				file = files[0];
				if (URL){
					done(URL.createObjectURL(file));
				} else if (FileReader){
					reader = new FileReader();
					reader.onload = function (e){
						done(reader.result);
					};
					reader.readAsDataURL(file);
				}
			}
		} else {
			$(element).val(null);
			$(errorElement).fadeIn().html('Please upload jpg or png image');
			$(element).focus();
			return false;
		}
	});
	$modal.on('shown.bs.modal', function (){
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
			zoom: function (e){
				console.log(e.type, e.detail.ratio);
			}
		});
	}).on('hidden.bs.modal', function (){
		cropper.destroy();
		cropper = null;
	});
	// Methods
	actions.querySelector('.docs-buttons').onclick = function (event){
		var e = event || window.event;
		var target = e.target || e.srcElement;
		var cropped;
		var result;
		var input;
		var data;
		if (!cropper){
			return;
		}
		while (target !== this){
			if (target.getAttribute('data-method')){
				break;
			}
			target = target.parentNode;
		}
		if (target === this || target.disabled || target.className.indexOf('disabled') > -1){
			return;
		}
		data = {
			method: target.getAttribute('data-method'),
			target: target.getAttribute('data-target'),
			option: target.getAttribute('data-option') || undefined,
			secondOption: target.getAttribute('data-second-option') || undefined
		};
		cropped = cropper.cropped;
		if (data.method){
			if (typeof data.target !== 'undefined'){
				input = document.querySelector(data.target);
				if (!target.hasAttribute('data-option') && data.target && input){
					try {
						data.option = JSON.parse(input.value);
					} catch (e){
						console.log(e.message);
					}
				}
			}
			switch (data.method){
				case 'rotate':
					if (cropped && options.viewMode > 0){
						cropper.clear();
					}
					break;
				case 'getCroppedCanvas':
					try {
						data.option = JSON.parse(data.option);
					} catch (e){
						console.log(e.message);
					}
					if (uploadedImageType === 'image/jpeg' || uploadedImageType === 'image/png'){
						if (!data.option){
							data.option = {};
						}
						data.option.fillColor = '#fff';
					}
					break;
			}
			result = cropper[data.method](data.option, data.secondOption);
			switch (data.method){
				case 'rotate':
					if (cropped && options.viewMode > 0){
						cropper.crop();
					}
					break;
				case 'scaleX':
				case 'scaleY':
					target.setAttribute('data-option', -data.option);
					break;
				case 'getCroppedCanvas':
					if (result){
						// Bootstrap's Modal
						$('#getCroppedCanvasModal').modal().find('.modal-body').html(result);
						if (!download.disabled){
							download.download = uploadedImageName;
							download.href = result.toDataURL(uploadedImageType);
						}
					}
					break;
				case 'destroy':
					cropper = null;
					if (uploadedImageURL){
						URL.revokeObjectURL(uploadedImageURL);
						uploadedImageURL = '';
						image.src = originalImageURL;
					}
					break;
			}
			if (typeof result === 'object' && result !== cropper && input){
				try {
					input.value = JSON.stringify(result);
				} catch (e){
					console.log(e.message);
				}
			}
		}
	};
	document.getElementById('crop').addEventListener('click', function (){
		var initialAvatarURL;
		var canvas;
		$modal.modal('hide');
		if (cropper){
			canvas = cropper.getCroppedCanvas({
				width: 300,
				height: 300
			});
			initialAvatarURL = avatar.src;
			avatar.src = canvas.toDataURL();
			var srcOriginal = canvas.toDataURL();
			$('.cropped').show();
			$('#cropped').html('<img src="' + srcOriginal + '" width="100">');
			$('#profile_image').val(null);
			uploadProfileImg(srcOriginal);
		}
	});
});

function uploadProfileImg(crop_img){
	var ext = $('#profile_image_extension').val();
	$.ajax({
		type: 'POST',
		url: setURL,
		data: {
			fld_data: fld_data,
			crop_img: crop_img,
			ext: ext,
			oldImage: oldImage
		},
		success: function (img){
			if (img != ''){
				var obj = JSON.parse(img);
				if (typeof obj['filename'] != 'undefined' && obj['filename'] != 'InvalidUser'){
					$('#profile').val(obj['filename']);
				} else {
					$('#profile_imageError').show().html('Something want to wrong, Please try again after sometime.');
				}
			} else {
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
            <h1>User Profile (300 x 300)</h1>
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