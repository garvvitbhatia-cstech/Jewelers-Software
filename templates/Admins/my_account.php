<?php
   #set page meta content   
   $this->assign('title', SITE_TITLE.' :: My Account');   
   $this->assign('meta_robot', 'noindex, nofollow');
?>
<?= $this->Html->css(array('/css/jquery-ui'));?>
<?= $this->Html->script('/js/jquery-ui');?>
<!--  page-wrapper -->
<div id="page-wrapper">
   <div class="row">
      <!-- page header -->
      <div class="col-lg-12">
         <h1 class="page-header">My Account</h1>
      </div>
      <!--end page header -->
   </div>
   <div class="row">
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
                     <input type="hidden" name="old_image" id="old_image" value="<?php e($editData->profile); ?>">
                     <div class="form-group">
                        <label>Unique ID:</label>
                        <input type="text" readonly="readonly" value="<?php e($editData->unique_id); ?>" class="form-control">
                     </div>
                     <div class="row">
                        <div class="col-lg-3">
                           <div class="form-group">
                              <label>User Name:</label>
                              <select name="name_prefix" id="name_prefix" class="form-control">
                                 <option <?php if($editData->name_prefix == 'Mr'){e('selected');} ?> value="Mr">Mr</option>
                                 <option <?php if($editData->name_prefix == 'Miss'){e('selected');} ?> value="Miss">Miss</option>
                                 <option <?php if($editData->name_prefix == 'Mrs'){e('selected');} ?> value="Mrs">Mrs</option>
                                 <option <?php if($editData->name_prefix == 'M/S'){e('selected');} ?> value="M/S">M/S</option>
                              </select>
                              <span id="name_prefixError" class="admin_login_error"></span>
                           </div>
                        </div>
                        <div class="col-lg-9">
                           <div class="form-group">
                              <label>&nbsp;</label>
                              <input type="text" placeholder="Full Name" value="<?php e($editData->name); ?>" id="name" name="name" onkeyup="checkError(this.id);" confirmation="false" class="form-control">
                              <span id="nameError" class="admin_login_error"></span>
                           </div>
                        </div>
                     </div>                     
                     <div class="form-group">
                        <label>User DOB:</label>
                        <input type="text" id="user_dob" name="user_dob" value="<?php e($editData->user_dob); ?>" onkeyup="checkError(this.id);" confirmation="false" class="form-control">
                        <span id="user_dobError" class="admin_login_error"></span>
                     </div>
                     <div class="form-group">
                        <label>Gender:</label>
                        <select name="gender" id="gender" class="form-control">
                           <option <?php if($editData->gender == 'Male'){e('selected');} ?> value="Male">Male</option>
                           <option <?php if($editData->gender == 'Female'){e('selected');} ?> value="Female">Female</option>
                        </select>
                        <span id="careof_prefixError" class="admin_login_error"></span>
                     </div>
                     <div class="form-group">
                        <label>Address:</label>
                        <textarea id="address" name="address" onkeyup="checkError(this.id);" confirmation="false" class="form-control" rows="5"><?php e($editData->address); ?></textarea>
                        <span id="addressError" class="admin_login_error"></span>
                     </div>
                     <div class="form-group">
						<?php $stateList = $this->State->getStateByCountryId(101); ?>
                        <label>State:</label>
                        <select id="state_id" name="state_id" class="form-control" onchange="checkError(this.id); getCityByStateId(this.value);">
                            <?php if(isset($stateList) && !empty($stateList)){ ?>
                                <?php foreach($stateList as $key => $val): ?>
                                    <option <?php if($editData->state_id == $key){e('selected');} ?> value="<?php e($key); ?>"><?php e($val); ?></option>
                                <?php endforeach; ?>
                            <?php } ?>
                        </select>
                        <span id="state_idError" class="admin_login_error"></span>
                    </div>
                     <div class="form-group">
						<?php $districtList = $this->State->getCityByStateId($editData->state_id); ?>
                        <label>City:</label>
                        <select id="city_id" name="city_id" class="form-control" onchange="checkError(this.id);">
                            <?php if(isset($districtList) && !empty($districtList)){ ?>
                                <?php foreach($districtList as $key => $val): ?>
                                    <option <?php if($editData->city_id == $key){e('selected');} ?> value="<?php e($key); ?>"><?php e($val); ?></option>
                                <?php endforeach; ?>
                            <?php } ?>
                        </select>
                        <span id="district_idError" class="admin_login_error"></span>
                    </div>
                     <div class="form-group">
                        <label>Pincode:</label>
                        <input type="tel" id="pincode" name="pincode" class="form-control" value="<?php e($editData->pincode); ?>" onkeyup="checkError(this.id);" maxlength="6">
                        <span id="pincodeError" class="admin_login_error"></span>
                     </div>
                     <div class="form-group">
                        <label>Contact:</label>
                        <input type="tel" id="phone" name="phone" value="<?php e($editData->phone); ?>" class="form-control" onkeyup="checkError(this.id);" maxlength="10">
                        <span id="phoneError" class="admin_login_error"></span>
                     </div>
                     <div class="form-group">
                        <label>Username:</label>
                        <input type="text" readonly="readonly" value="<?php e($this->decryptData($editData->username)); ?>" class="form-control">
                     </div>
                     <div class="form-group">
                        <label>Email:</label>
                        <input type="text" id="email" name="email" class="form-control" value="<?php e($this->decryptData($editData->email)); ?>" onkeyup="checkError(this.id);">
                        <span id="emailError" class="admin_login_error"></span>
                     </div>
                     <div class="form-group">
                        <label>Password:</label>
                        <input type="text" id="password" name="password" class="form-control" value="<?php e($this->decryptData($editData->password)); ?>" onkeyup="checkError(this.id);">
                        <span id="passwordError" class="admin_login_error"></span>
                     </div>
                     <div class="form-group">
                        <label>Profession:</label>
                        <input type="text" id="profession" name="profession" class="form-control" value="<?php e($editData->profession); ?>" onkeyup="checkError(this.id);">
                        <span id="professionError" class="admin_login_error"></span>
                     </div>
                     <div class="form-group">
                        <label>Marital Status:</label>
                        <select id="marital_status" name="marital_status" class="form-control" onchange="checkError(this.id);">
                           <option <?php if($editData->marital_status == 'Single'){e('selected');} ?> value="Single">Single</option>
                           <option <?php if($editData->marital_status == 'Married'){e('selected');} ?> value="Married">Married</option>
                        </select>
                        <span id="marital_statusError" class="admin_login_error"></span>
                     </div>
                     <div class="form-group cropped" id="cropped">
                     	<?php
						if($editData->profile!= ""){
							$imgPath = WWW_ROOT.'img/users/'.$editData->profile;
							if(is_file($imgPath)){
								e($this->Html->image('users/'.$editData->profile, array('title'=>'Profile Image', 'alt'=> 'Profile Image', 'width' => '150')));
							}
						} ?>
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
      </div>
   </div>
</div>
<script type="text/javascript">   
function getCityByStateId(stateId) {
	if (stateId != '' && $.isNumeric(stateId)) {
		$.ajax({
			type: 'POST',
			url: '<?php e($this->Url->build('/ajax/getCity/'));?>',
			data: {stateId: stateId},
			success: function (response) {
				$('#city_id').html(response);
			}
		});
		return false;
	}
}

$(document).ready(function(e){
	
	$("#user_dob").datepicker({
		dateFormat: 'dd-mm-yy',
		changeYear: true,
		changeMonth: true,
		maxDate: 0,
		yearRange: '1960:c',
	});
	$("#marriage_dob").datepicker({
		dateFormat: 'dd-mm-yy',
		changeYear: true,
		changeMonth: true,
		maxDate: 0,
		yearRange: '1960:c',
	});

	$('#phone').filter_input({regex: '[0-9]'});
	$('#pincode').filter_input({regex: '[0-9]'});
	$('#mobile').filter_input({regex: '[0-9]'});

	var frmSubmitted = 0;
	$('.submitBtn').click(function(){
		var flag = 0;
		if (frmSubmitted == 0){
			if($.trim($('#name').val()) == "") {
				$('#nameError').show().html('Please enter your fullname.').slideDown();
				$('#name').focus();
				frmSubmitted = 0;
				flag = 1;
				return false;
			}

			if($.trim($('#email').val()) == ""){
				$('#emailError').show().html('Please enter your email address.').slideDown();
				$('#email').focus();
				frmSubmitted = 0;
				flag = 1;
				return false;

			}else if ($.trim($('#email').val()) != ""){
				var filter = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
				if(!filter.test($('#email').val())){
					$('#emailError').show().html('Please enter valid email address.').slideDown();
					$('#email').focus();
					frmSubmitted = 0;
					flag = 1;
					return false;
				}
			}

			var password = $.trim($('#password').val());
			if(password == ""){
				$('#passwordError').show().html('Please enter password.').slideDown();
				$('#password').focus();
				frmSubmitted = 0;
				flag = 1;
				return false;

			}else if(password.length < 6){
				$('#passwordError').show().html('Your password must be 6 to 15 characters').slideDown();
				$('#password').focus();
				frmSubmitted = 0;
				flag = 1;
				return false;

			}else if($.trim($('#password').val()) != "") {
				var filterStPass = /(?=^.{6,15}$)(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*()_+}{":;'?/>.<,])(?!.*\s).*$/;
				if(!filterStPass.test($('#password').val())) {
					$('#passwordError').show().html('Password should include at least one upper case letter, one number, and one special character.').slideDown();
					$('#password').focus();
					frmSubmitted = 0;
					flag = 1;
					return false;
				}
			}

			if(flag == 0){
				$('.submitBtn').html('Processing...');
				$('#editForm').submit();
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
<script type = "text/javascript">
var fld_data = '<?php e($this->encryptData('users ')); ?>';
var setURL = '<?php e($this->Url->build('/images/save_image'));?>';
var setSaveURL = '<?php e($this->Url->build('/ajax/save_site_logo_image/'));?>';
var oldImage = '<?php e($editData->profile);?>';

window.addEventListener('DOMContentLoaded', function(){
	var avatar = document.getElementById('cropped');
	var image = document.getElementById('image');
	var input = document.getElementById('profile_image');
	var actions = document.getElementById('minactions');
	var $modal = $('#myLogoModal');
	var cropper;
	input.addEventListener('change', function(e){
		var fileName = $('#profile_image').val();
		var element = '#profile_image';
		var errorElement = '#profile_imageError';
		var ext = fileName.substring(fileName.lastIndexOf('.') + 1);
		$('#profile_image_extension').val(ext);
		if(ext == "jpg" || ext == "jpeg" || ext == "JPG" || ext == "JPEG" || ext == "png" || ext == "PNG") {
			var files = e.target.files;
			var done = function (url){
				input.value = '';
				image.src = url;
				$modal.modal('show');
			};
			var reader;
			var file;
			var url;
			if(files && files.length > 0) {
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
	$modal.on('shown.bs.modal', function(){
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
	}).on('hidden.bs.modal', function(){
		cropper.destroy();
		cropper = null;
	});
	// Methods
	actions.querySelector('.docs-buttons').onclick = function(event){
		var e = event || window.event;
		var target = e.target || e.srcElement;
		var cropped;
		var result;
		var input;
		var data;
		if(!cropper){
			return;
		}
		while(target !== this){
			if(target.getAttribute('data-method')){
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
		if(data.method){
			if(typeof data.target !== 'undefined'){
				input = document.querySelector(data.target);
				if (!target.hasAttribute('data-option') && data.target && input){
					try{
						data.option = JSON.parse(input.value);
					}catch(e){
						console.log(e.message);
					}
				}
			}
			switch (data.method){
				case 'rotate':
					if(cropped && options.viewMode > 0){
						cropper.clear();
					}
					break;
				case 'getCroppedCanvas':
					try{
						data.option = JSON.parse(data.option);
					}catch(e){
						console.log(e.message);
					}
					if(uploadedImageType === 'image/jpeg' || uploadedImageType === 'image/png'){
						if (!data.option){
							data.option = {};
						}
						data.option.fillColor = '#fff';
					}
					break;
			}
			result = cropper[data.method](data.option, data.secondOption);
			switch(data.method){
				case 'rotate':
					if(cropped && options.viewMode > 0){
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
			if(typeof result === 'object' && result !== cropper && input){
				try{
					input.value = JSON.stringify(result);
				}catch(e){
					console.log(e.message);
				}
			}
		}
	};
	document.getElementById('crop').addEventListener('click', function(){
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
			if(img != ''){
				var obj = JSON.parse(img);
				if (typeof obj['filename'] != 'undefined' && obj['filename'] != 'InvalidUser') {
					$('#profile').val(obj['filename']);
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