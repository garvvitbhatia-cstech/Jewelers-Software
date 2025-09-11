<?php
#set page meta content
$this->assign('title', SITE_TITLE.' :: Edit Country');
$this->assign('meta_robot', 'noindex, nofollow');
?>
<!--  page-wrapper -->
<div id="page-wrapper">
    <div class="row">
        <!-- page header -->
        <div class="col-lg-12">
            <h1 class="page-header">Edit Country Details</h1>
        </div>
        <!--end page header -->
    </div>
    <div class="row">
        <div class="col-lg-12">
            <a href="<?php e($this->Url->build(ADMIN_FOLDER.'country-management'.'/'));?>" class="btn btn-info">Back To Listing</a><br />&nbsp;
        </div>
        <div class="col-lg-12">
            <!-- Form Elements -->
            <?php e($this->Flash->render()); ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    Update country information
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <?= $this->Form->create(NULL,array('id' => 'editForm', 'type' => 'file', 'inputDefaults' => array('label' => false,'div' => false), 'name' => 'editForm', 'csrfToken' => $this->request->getAttribute('csrfToken')));?>
                            <input type="hidden" name="edit_token" id="edit_token" value="<?php e($this->encryptData($editData->id))?>">                            
                            <input type="hidden" name="old_image" id="old_image" value="<?php e($editData->flag_image); ?>">
                            <div class="form-group">
                                <label>Country Name</label>
                                <input type="text" id="country_name" name="country_name" value="<?php e($editData->country_name);?>" onkeyup="checkError(this.id);" confirmation="false" class="form-control">
                                <span id="country_nameError" class="admin_login_error"></span>
                            </div>
                            <div class="form-group">
                                <label>Country Code</label>
                                <input type="text" id="country_code" name="country_code" value="<?php e($editData->country_code);?>" onkeyup="checkError(this.id);" confirmation="false" class="form-control">
                                <span id="country_codeError" class="admin_login_error"></span>
                            </div>
                            <div class="form-group">
                                <label>Phone Number Format:</label>
                                <input type="text" id="phone_no_format" maxlength="15" placeholder="(###) ###-###" value="<?php e($editData->phone_no_format); ?>" name="phone_no_format" onkeyup="checkError(this.id);" confirmation="false" class="form-control">
                                <span id="phone_no_formatError" class="admin_login_error"></span>
                            </div>
                            <?php 
								$count = 0; 
								if(!empty($editData->zipcode_format)){
									$explode = explode(',',$editData->zipcode_format);
									$count = count($explode);
								}
							?>
                            <div class="form-group">
                            	<?php if($count < 5){ ?>
                            	<div class="row appendRowDiv">
                                	<div class="col-md-10">
                                    <label>Zip Code Format:</label>
                                    <input type="text" id="zipcode_format0" maxlength="10" name="zipcode_format[]" onkeyup="checkError(this.id);" confirmation="false" class="form-control">
                                    <span id="zipcode_format0Error" class="admin_login_error"></span>
                                    </div>
                                    <label>&nbsp;</label>
                                    <div class="col-md-2">
                                    	<button type="button" class="btn btn-info" onclick="addMoreZipCodeUpdate()">Add More</button>
                                    </div>
                                </div>
                                <?php } if(!empty($editData->zipcode_format)){ ?>
                                	<?php 
										$explode = explode(',',$editData->zipcode_format);
										foreach($explode as $key => $zipformat):
									?>
                                    <div class="row appendRowDiv">
                                        <div class="col-md-10">
                                        <label>Zip Code Format:</label>
                                        <input type="text" id="zipcode_format<?php e($key+mt_rand()); ?>" maxlength="10" value="<?php e($zipformat); ?>" name="zipcode_format[]" onkeyup="checkError(this.id);" confirmation="false" class="form-control">
                                        </div>
                                        <label>&nbsp;</label>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-danger" onclick="removeZipCode('<?php e($zipformat); ?>','<?php e($this->encryptData($editData->id)); ?>');">Remove</button>
                                        </div>
                                    </div>
									<?php endforeach; ?>
                                <?php } ?>
                                <div id="appendZipCode"></div>
                            </div>
                            <div class="form-group cropped" id="cropped">
                                <?php
                                if($editData->flag_image != ""){
                                    $imgPath = WWW_ROOT.'img/countries/'.$editData->flag_image;
                                    if(is_file($imgPath)){
                                        e($this->Html->image('countries/'.$editData->flag_image, array('title'=>$editData->country_name, 'alt'=> $editData->country_name, 'width' => '70' )));
                                    }
                                } ?>
                            </div>
                            <div class="form-group">
                                <label>Country Flag</label>
                                <input type="hidden" id="country_flag" name="country_flag"/>
                                <input type="file" id="flag_image"  accept="image/jpeg, image/png" onchange="checkError(this.id);" class="form-control" />
                                <input type="hidden" id="country_image_extension">                                
                                <span id="flag_imageError" class="admin_login_error"></span>
                            </div>
                            <?php 
								$isExist = $this->Country->getStateExist($editData->id);
								if($isExist > 0){
							?>
                            <input type="hidden" name="status" value="1"/>
                            <?php }else{ ?>
                            <div class="form-group">
                                <label>Status</label>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" <?php if($editData->status == 1){e('checked'); }?> value="1" name="status">Active
                                    </label>
                                </div>
                            </div>
                            <?php } ?>
                            <button type="button" class="btn btn-primary submitBtn" id="submitBtn">Submit</button>
                            <?= $this->Form->end(); ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Form Elements -->
            <a href="<?php e($this->Url->build(ADMIN_FOLDER.'country-management'.'/'));?>" class="btn btn-info">Back To Listing</a>
        </div>
    </div>
</div>

<script type="text/javascript">
	function addMoreZipCodeUpdate(){
		var x = $('.appendRowDiv').length;
		var max_fields = 5; //maximum input boxes allowed
		var wrapper   	= $("#appendZipCode"); //Fields wrapper
		if(x < max_fields){ //max input box allowed			
			$(wrapper).append('<div class="row appendRowDiv"><div class="col-md-10"><label>Zip Code Format:</label><input type="text" value="" maxlength="10" name="zipcode_format[]" class="form-control zipcode_format"></div><label>&nbsp;</label><div class="col-md-2"><button type="button" class="btn btn-danger remove_field">Remove</button></div></div>'); //add input box
			$('.zipcode_format').filter_input({regex:'[# ]'});
			x++; //text box increment
		}else{			
			$('#errMsgId').html('You can add maximum '+max_fields+' zipcode format.');
			$('#errorMsgPopup').modal('show');
		}
	}
	$(wrapper).on("click",".remove_field", function(e){ //user click on remove text
		 e.preventDefault();
		 $(this).closest('.appendRowDiv').remove();
		 x--;
	});
	
	function removeZipCode(row,editId){
		if(editId != '' && row != ''){			
			$('#zipcodeDeleteId').attr('onclick','deleteZipCode("'+row+'","'+editId+'")');
			$('#removeZipCodePopup').modal('show');
		}
	}
	
	function deleteZipCode(row,editId){
		if(editId != '' && row != ''){
			var removeZipcodeUrl = '<?php e($this->Url->build('/locations/removeZipcode'));?>';
			$.ajax({
				type: 'POST',
				url: removeZipcodeUrl,
				data: {value:row,editId:editId},
				success: function(msg){
					window.location.reload(true);
				}
			});
		}else{
			$('#errMsgId').html('Something went wrong.');
			$('#errorMsgPopup').modal('show');	
		}
	}
	
$(document).ready(function(e) {
	$('#zipcode_format0').filter_input({regex:'[# ]'});
	$('#phone_no_format').filter_input({regex:'[()# -]'});
   
    var frmSubmitted = 0;
    $('.submitBtn').click(function(){
        var flag = 0;
        if(frmSubmitted == 0){
            if($.trim($('#country_name').val()) == ""){
                $('#country_nameError').show().html('Please enter country name.').slideDown();
                $('#country_name').focus();
                frmSubmitted = 0;
                flag = 1; return false;
            }
            if($.trim($('#country_code').val()) == ""){
                $('#country_codeError').show().html('Please enter country code.').slideDown();
                $('#country_code').focus();
                frmSubmitted = 0;
                flag = 1; return false;
            }
			if($.trim($('#phone_no_format').val()) == ""){
                $('#phone_no_formatError').show().html('Please enter phone number format.').slideDown();
                $('#phone_no_format').focus();
                frmSubmitted = 0;
                flag = 1; return false;
            }
			<?php if(empty($editData->zipcode_format)){ ?>
			if($.trim($('#zipcode_format0').val()) == ""){
                $('#zipcode_format0Error').show().html('Please enter zip code format.').slideDown();
                $('#zipcode_format0').focus();
                frmSubmitted = 0;
                flag = 1; return false;
            }
			<?php } ?>
			
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
<script type="text/javascript">
var fld_data ='<?php e($this->encryptData('countries')); ?>';
var setURL ='<?php e($this->Url->build('/images/save_image'));?>';
var setSaveURL ='<?php e($this->Url->build('/ajax/save_site_logo_image/'));?>';
var oldImage ='<?php e($this->encryptData($editData->flag_image));?>';
window.addEventListener('DOMContentLoaded', function () {
    var avatar = document.getElementById('cropped');
    var image = document.getElementById('image');
    var input = document.getElementById('flag_image');
    var actions = document.getElementById('minactions');
    var $modal = $('#myLogoModal');
    var cropper;
    input.addEventListener('change', function (e) {
        var fileName = $('#flag_image').val();
        var element  = '#flag_image';
        var errorElement  = '#flag_imageError';
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
            aspectRatio: 70 / 90,
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
                width: 70,
                height: 90
            });
            initialAvatarURL = avatar.src;
            avatar.src = canvas.toDataURL();
            var srcOriginal = canvas.toDataURL();
            $('.cropped').show();
            $('#cropped').html('<img src="'+srcOriginal+'" width="70">');
            $('#flag_image').val(null);
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
					$('#country_flag').val(obj['filename']);
                }else{
                    $('#flag_imageError').show().html('Something want to wrong, Please try again after sometime.');
                }
            }else{
                $('#flag_imageError').show().html('Something want to wrong, Please try again after sometime.');
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
            <h1>Flag Image (70 x 90)</h1>
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
  
<div class="modal fade fancyPopup" id="removeZipCodePopup" role="dialog" data-keyboard="false" data-backdrop="static">
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
                <p>Are you sure want to delete this zipcode?</p>
                </div>
           </div>
        </div>
     </div>         
     <div class="row twoButton text-center">
        <div class="col-sm-12">
        	<button type="button" id="zipcodeDeleteId" data-dismiss="modal" onclick="" class="btn btn-outline btn-success">Yes</button>
         	<button type="button" data-dismiss="modal" class="btn btn-outline btn-warning">No</button>
        </div>
     </div>         
     </div>
  </div>
   </div>
</div>