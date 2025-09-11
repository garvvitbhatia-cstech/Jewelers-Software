<?php
	#set page meta content   
	$this->assign('title', SITE_TITLE.' :: Site Configuration');   
	$this->assign('meta_robot', 'noindex, nofollow');   
?>
<!--  page-wrapper -->
<div id="page-wrapper">
   <div class="row">
      <!-- page header -->
      <div class="col-lg-12">
         <h1 class="page-header">Site Configuration</h1>
      </div>
      <!--end page header --> 
   </div>
   <div class="row">
      <div class="col-lg-12">
         <!-- Form Elements -->
         <?php e($this->Flash->render()); ?>
         <div class="panel panel-default">
            <div class="panel-heading"> Update Site Configuration Details </div>
            <div class="panel-body">
               <div class="row">
                  <div class="col-lg-12">
                     <?= $this->Form->create(NULL,array('id' => 'editForm', 'name' => 'editForm', 'csrfToken' => $this->request->getAttribute('csrfToken')));?>
                     <div class="form-group">
                        <label>Admin Email Address</label>
                        <input type="text" value="<?= $editData->admin_email; ?>" id="admin_email" onkeyup="checkError(this.id);" name="admin_email" class="form-control" maxlength="40">
                        <span id="admin_emailError" class="admin_login_error"></span> 
                     </div>
                     <div class="form-group">
                        <label>Company Name</label>
                        <input type="text" value="<?= $editData->company_name; ?>" id="company_name" onkeyup="checkError(this.id);" name="company_name" class="form-control" maxlength="40">
                        <span id="company_nameError" class="admin_login_error"></span> 
                     </div>
                     <div class="form-group">
                        <label>Business Address</label>
                        <input type="text" value="<?= $editData->business_address; ?>" id="business_address" onkeyup="checkError(this.id);" name="business_address" class="form-control">
                        <span id="business_addressError" class="admin_login_error"></span>                 
                     </div>
                     <div class="form-group">
                        <label>Company Contact Number</label>
                        <input type="tel" value="<?= $editData->mobile; ?>" id="mobile" onkeyup="checkError(this.id);" name="mobile" class="form-control" maxlength="14">
                        <span id="mobileError" class="admin_login_error"></span> 
                     </div>
                     <div class="form-group">
                        <label>Footer Content</label>
                        <input type="tel" value="<?= $editData->footer_content; ?>" id="footer_content" onkeyup="checkError(this.id);" name="footer_content" class="form-control">
                        <span id="footer_contentError" class="admin_login_error"></span> 
                     </div>
                     <div class="form-group">
                        <label>Labour</label>
                        <input type="text" value="<?= $editData->labour; ?>" id="labour" maxlength="6" placeholder="Labour" onkeyup="checkError(this.id);" name="labour" class="form-control">
                        <span id="labourError" class="admin_login_error"></span> 
                     </div>
                     <div class="form-group">
                        <label>Gold Sale Price (in gram)</label>
                        <input type="text" value="<?= $editData->gold_price; ?>" id="gold_price" maxlength="9" placeholder="Gold Price (in gram)" onkeyup="checkError(this.id);" name="gold_price" class="form-control">
                        <span id="gold_priceError" class="admin_login_error"></span> 
                     </div>
                     <div class="form-group">
                        <label>Silver Sale Price (in gram)</label>
                        <input type="text" value="<?= $editData->silver_price; ?>" id="silver_price" maxlength="9" placeholder="Silver Price (in gram)" onkeyup="checkError(this.id);" name="silver_price" class="form-control">
                        <span id="silver_priceError" class="admin_login_error"></span> 
                     </div>
                     <div class="form-group">
                        <label>Gold Return Price (in gram)</label>
                        <input type="text" value="<?= $editData->gold_price_customer; ?>" id="gold_price_customer" maxlength="9" placeholder="Gold Price Customer (in gram)" onkeyup="checkError(this.id);" name="gold_price_customer" class="form-control">
                        <span id="gold_price_customerError" class="admin_login_error"></span> 
                     </div>
                     <div class="form-group">
                        <label>Silver Return Price (in gram)</label>
                        <input type="text" value="<?= $editData->silver_price_customer; ?>" id="silver_price_customer" maxlength="9" placeholder="Silver Price Custoer (in gram)" onkeyup="checkError(this.id);" name="silver_price_customer" class="form-control">
                        <span id="silver_price_customerError" class="admin_login_error"></span>
                     </div>                     
                     <div class="form-group">
                        <label>GST (%)</label>
                        <select name="gst" id="gst" class="form-control">
                        	<option <?php if($editData->gst == '3'){e('selected');}?> value="3">3</option>
                            <option <?php if($editData->gst == '5'){e('selected');}?> value="5">5</option>
                            <option <?php if($editData->gst == '9'){e('selected');}?> value="9">9</option>
                            <option <?php if($editData->gst == '18'){e('selected');}?> value="18">18</option>
                        </select>
                        <span id="gstError" class="admin_login_error"></span> 
                     </div>
                     <div class="form-group cropped" id="cropped">
					 <?php
                       if($editData->logo != ""){                           
                        $imgPath = WWW_ROOT.'img/logos/'.$editData->logo;                           
                        if(is_file($imgPath)){                           
                            e($this->Html->image('logos/'.$editData->logo, array('title'=>'Profile Image', 'alt'=> 'Profile Image', 'width' => '150' )));      
                        }                           
                       } 
					 ?>
                     </div>
                     <div class="form-group">
                        <label>Choose Site Logo</label>
                        <input type="file" name="logo" id="logo"  accept="image/jpeg, image/png" onchange="checkError(this.id);" class="form-control">
                        <input type="hidden" id="video_profile_extension">
                        <span id="logoError" class="admin_login_error"></span> 
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

<?= $this->Html->css('/js/cropper/cropper');?>
<?= $this->Html->script('/js/cropper/cropper');?>
<script>
$(document).ready(function(e){
	$('#mobile').filter_input({regex:'[0-9()-]'});
	$('#labour').filter_input({regex:'[0-9.]'});
	$('#gold_price').filter_input({regex:'[0-9.]'});
	$('#silver_price').filter_input({regex:'[0-9.]'});
	
	var frmSubmitted = 0;
	$('.submitBtn').click(function(){
		var flag = 0;
		if(frmSubmitted == 0){
			if($.trim($('#admin_email').val()) == ""){
				$('#admin_emailError').show().html('Please enter admin email address').slideDown();
				$('#admin_email').focus();
				frmSubmitted = 0;
				flag = 1;
				return false;
			}else if(jQuery.trim(jQuery("#admin_email").val()).length > 40){
				jQuery('#admin_emailError').html('Plaese enter valid email address').fadeIn();
				flag = 1;
				formSubmit = 0;
				return false;
			}else{
				var filter = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
				if(!filter.test($('#admin_email').val())){
					$('#admin_emailError').html('Invalid email address').slideDown();
					$('#admin_email').focus();
					frmSubmitted = 0;
					flag = 1;
					return false;
				}
			}
			if($.trim($('#company_name').val()) == ""){
				$('#company_nameError').show().html('Please enter company name').slideDown();
				$('#company_name').focus();
				frmSubmitted = 0;
				flag = 1;
				return false;
			}else if(jQuery.trim(jQuery("#company_name").val()).length > 40){
				jQuery('#company_nameError').html('Plaese enter valid company name').fadeIn();
				flag = 1;
				formSubmit = 0;
				return false;
			}
			if($.trim($('#mobile').val()) != ""){
				if(jQuery.trim(jQuery("#mobile").val()).length > 14){
					jQuery('#mobileError').html('Plaese enter valid contact number').fadeIn();
					flag = 1;
					formSubmit = 0;
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

<!-----Small Banner------> 
<script>
var fld_data = '<?php e($this->encryptData('logos')); ?>';
var setURL = '<?php e($this->Url->build('/images/save_image '));?>';
var setSaveURL = '<?php e($this->Url->build('/ajax/save_site_logo_image/'));?>';
var oldImage = '<?php e($this->encryptData($editData->logo));?>';

window.addEventListener('DOMContentLoaded', function(){
	var avatar = document.getElementById('cropped');
	var image = document.getElementById('image');
	var input = document.getElementById('logo');
	var actions = document.getElementById('minactions');
	var $modal = $('#myLogoModal');
	var cropper;
	input.addEventListener('change', function(e){
		var fileName = $('#logo').val();
		var element = '#logo';
		var errorElement = '#logoError';
		var ext = fileName.substring(fileName.lastIndexOf('.') + 1);
		$('#video_profile_extension').val(ext);
		if(ext == "jpg" || ext == "jpeg" || ext == "JPG" || ext == "JPEG" || ext == "png" || ext == "PNG"){
			var files = e.target.files;
			var done = function(url){
				input.value = '';
				image.src = url;
				$modal.modal('show');
			};
			var reader;
			var file;
			var url;
			if(files && files.length > 0){
				file = files[0];
				if(URL){
					done(URL.createObjectURL(file));
				}else if(FileReader){
					reader = new FileReader();
					reader.onload = function(e){
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
			aspectRatio: 200 / 150,
			autoCropArea: 0.65,
			restore: false,
			guides: false,
			center: false,
			highlight: false,
			cropBoxMovable: false,
			cropBoxResizable: false,
			toggleDragModeOnDblclick: false,
			zoom: function(e){
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
		if(target === this || target.disabled || target.className.indexOf('disabled') > -1){
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
				if(!target.hasAttribute('data-option') && data.target && input){
					try {
						data.option = JSON.parse(input.value);
					} catch(e){
						console.log(e.message);
					}
				}
			}
			switch(data.method){
				case 'rotate':
					if(cropped && options.viewMode > 0){
						cropper.clear();
					}
					break;
				case 'getCroppedCanvas':
					try {
						data.option = JSON.parse(data.option);
					} catch(e){
						console.log(e.message);
					}
					if(uploadedImageType === 'image/jpeg' || uploadedImageType === 'image/png'){
						if(!data.option){
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
					if(result){
						// Bootstrap's Modal
						$('#getCroppedCanvasModal').modal().find('.modal-body').html(result);
						if(!download.disabled){
							download.download = uploadedImageName;
							download.href = result.toDataURL(uploadedImageType);
						}
					}
					break;
				case 'destroy':
					cropper = null;
					if(uploadedImageURL){
						URL.revokeObjectURL(uploadedImageURL);
						uploadedImageURL = '';
						image.src = originalImageURL;
					}
					break;
			}
			if(typeof result === 'object' && result !== cropper && input){
				try {
					input.value = JSON.stringify(result);
				} catch(e){
					console.log(e.message);
				}
			}
		}
	};
	document.getElementById('crop').addEventListener('click', function(){
		var initialAvatarURL;
		var canvas;
		$modal.modal('hide');
		if(cropper){
			canvas = cropper.getCroppedCanvas({
				width: 200,
				height: 150
			});
			initialAvatarURL = avatar.src;
			avatar.src = canvas.toDataURL();
			var srcOriginal = canvas.toDataURL();
			$('.cropped').show();
			$('#cropped').html('<img src="' + srcOriginal + '" width="150">');
			$('#logo').val(null);
			uploadProfileImg(srcOriginal);
		}
	});
});

function uploadProfileImg(crop_img){
	var ext = $('#video_profile_extension').val();
	$.ajax({
		type: 'POST',
		url: setURL,
		data: { fld_data: fld_data, crop_img: crop_img, ext: ext, oldImage: oldImage },
		success: function(img){
			if(img != ''){
				var obj = JSON.parse(img);
				if(typeof obj['filename'] != 'undefined' && obj['filename'] != 'InvalidUser'){
					saveProfileImage(obj['filename']);
				}else{
					$('#logoError').show().html('Something want to wrong, Please try again after sometime.');
				}
			}else{
				$('#logoError').show().html('Something want to wrong, Please try again after sometime.');
			}
			return false;
		}
	});
}

function saveProfileImage(filename){
	$.ajax({
		type: 'POST',
		url: setSaveURL,
		data: { filename: filename },
		success: function(msg){
			if(msg == 'Error'){
				$('#video-logoError-error').html('Something want to wrong, Please try again after sometime.');
			}
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
        <h1>Country Flag Image (200 x 150)</h1>
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
                <button type="button" class="btn btn-primary" data-method="zoom" data-option="0.1" title="Zoom In"> <span class="docs-tooltip" data-toggle="tooltip" title="" data-original-title="cropper.zoom(0.1)"> <span class="fa fa-search-plus">+</span> </span> </button>
                <button type="button" class="btn btn-primary" data-method="zoom" data-option="-0.1" title="Zoom Out"> <span class="docs-tooltip" data-toggle="tooltip" title="" data-original-title="cropper.zoom(-0.1)"> <span class="fa fa-search-minus">-</span> </span> </button>
                <button type="button" class="btn btn-primary" id="crop">Crop</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
