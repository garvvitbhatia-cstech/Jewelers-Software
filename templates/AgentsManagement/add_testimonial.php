<?php
#set page meta content
$this->assign('title', SITE_TITLE.' :: Add Testimonial');
$this->assign('meta_robot', 'noindex, nofollow');
?>
<!--  page-wrapper -->
<div id="page-wrapper">
    <div class="row">
        <!-- page header -->
        <div class="col-lg-12">
            <h1 class="page-header">Add Testimonial Details</h1>
        </div>
        <!--end page header -->
    </div>
    <div class="row">
        <div class="col-lg-12">
            <a href="<?php e($this->Url->build(ADMIN_FOLDER.'testimonials'.'/'));?>" class="btn btn-info">Back To Listing</a><br />&nbsp;
        </div>
        <div class="col-lg-12">
            <!-- Form Elements -->
            <?php e($this->Flash->render()); ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    Add testimonial information
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <?= $this->Form->create(NULL,array('id' => 'addForm', 'type' => 'file', 'inputDefaults' => array('label' => false,'div' => false), 'name' => 'addForm', 'csrfToken' => $this->request->getAttribute('csrfToken')));?>
                            <div class="form-group">
                                <label>User Name:</label>
                                <input type="text" id="username" name="username" onkeyup="checkError(this.id);" confirmation="false" class="form-control">
                                <span id="usernameError" class="admin_login_error"></span>
                            </div>
                            <div class="form-group">
                                <label>Testimonial:</label>
                                <textarea id="testimonial" name="testimonial" onkeyup="checkError(this.id);" rows="8" confirmation="false" class="form-control"></textarea>
                                <span id="testimonialError" class="admin_login_error"></span>
                            </div>
                            <div class="form-group cropped" id="cropped"></div>
                            <div class="form-group">
                                <label>Profile Image</label>
                                <input type="hidden" id="profile" name="profile"/>
                                <input type="file" id="profile_image"  accept="image/jpeg" onchange="checkError(this.id);" class="form-control" />
                                <input type="hidden" id="profile_image_extension">                                
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
            <a href="<?php e($this->Url->build(ADMIN_FOLDER.'testimonials'.'/'));?>" class="btn btn-info">Back To Listing</a>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function(e){   	
	$('#name').filter_input({regex:'[A-Za-z- ]'});
	
    var frmSubmitted = 0;
    $('.submitBtn').click(function(){
        var flag = 0;
        if(frmSubmitted == 0){
            if($.trim($('#username').val()) == ""){
                $('#usernameError').show().html('Please enter user name.').slideDown();
                $('#username').focus();
                frmSubmitted = 0;
                flag = 1; return false;
            }
			if($.trim($('#testimonial').val()) == ""){
                $('#testimonialError').show().html('Please enter testimonial.').slideDown();
                $('#testimonial').focus();
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
var fld_data ='<?php e($this->encryptData('testimonials')); ?>';
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
        $('#profile_image_extension').val(ext);
        if(ext == "jpg" || ext == "jpeg" || ext == "JPG" || ext == "JPEG"){

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
            $(errorElement).fadeIn().html('Please upload jpg image.');
            $(element).focus();
            return false;
        }
    });

    $modal.on('shown.bs.modal', function () {
        cropper = new Cropper(image, {
            dragMode: 'move',
            aspectRatio: 200 / 200,
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
                width: 200,
                height: 200
            });
            initialAvatarURL = avatar.src;
            avatar.src = canvas.toDataURL();
            var srcOriginal = canvas.toDataURL();
            $('.cropped').show();
            $('#cropped').html('<img src="'+srcOriginal+'" width="100">');
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
        data: {fld_data:fld_data,crop_img:crop_img,ext:ext,oldImage:oldImage},
        success: function(img){
            if(img != ''){
                var obj = JSON.parse(img);
                if(typeof obj['filename'] != 'undefined' && obj['filename'] != 'InvalidUser'){
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
            <h1>Profile Image (200 x 200)</h1>
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