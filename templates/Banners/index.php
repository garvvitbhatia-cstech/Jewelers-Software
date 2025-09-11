<?php
#set page meta content
$this->assign('title', SITE_TITLE.' :: Banner Management');
$this->assign('meta_robot', 'noindex, nofollow');
?>
<link rel="stylesheet" href="<?php e($this->Url->build('/admin/css/sweet-alert.css'));?>"/>
<script type="text/javascript" src="<?php e($this->Url->build('/admin/js/sweet-alert.min.js'));?>"></script>
<link rel="stylesheet" href="<?php e($this->Url->build('/admin/css/dropzone.css'));?>"/>
<script type="text/javascript" src="<?php e($this->Url->build('/admin/js/dropzone.js'));?>"></script>
<!--  page-wrapper -->
<div id="page-wrapper">
    <div class="row">
        <!-- page header -->
        <div class="col-lg-12">
            <h1 class="page-header">Banner Details</h1>
        </div>
        <!--end page header -->
    </div>
    <div class="row">
        <div class="col-lg-12">
            <a href="<?php e($this->Url->build(ADMIN_FOLDER.'dashboard'.'/'));?>" class="btn btn-info">Back To Listing</a><br />&nbsp;
        </div>
        <div class="col-lg-12">
            <!-- Form Elements -->
            <?php e($this->Flash->render()); ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    Banner information
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-10">
                            <h2>Multiple Image upload using dropzone.js</h2>
                            <div id="my-awesome-dropzone" class="dropzone"></div>
                        </div>
                        
                        <div class="col-lg-10">
                        <hr>
                            <?php if(isset($userProfiles) && !empty($userProfiles)){ ?>
                            	<?php foreach($userProfiles as $key => $val): ?>
									<?php
                                    	if(!empty($val->image_profile)){
										$imgPath = WWW_ROOT.'img/banners/'.$val->image_profile;
										if(is_file($imgPath) && file_exists(WWW_ROOT.'img/banners/'.$val->image_profile)){                                    
										e($this->Html->image('banners/'.$val->image_profile, array('class' => 'img-rounded', 'title'=>$val->image_profile, 'alt'=> $val->image_profile, 'width' => '100','style' => 'margin: 0px 4px 17px 7px;max-width: 100%;height: auto;')));
										?>
										<a class="btn btn-danger" title="Remove Banner Image" href="javascript:void(0);" style="margin: 0px 6px -60px -37px;" aria-label="Delete" onClick="removeBannerImage('<?php e($this->encryptData($val->id)); ?>')"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
								<?php } ?>
                                <?php } endforeach; ?>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>            
            <!-- End Form Elements -->
            <a href="<?php e($this->Url->build(ADMIN_FOLDER.'dashboard'.'/'));?>" class="btn btn-info">Back To Listing</a>
            <button type="button" class="btn btn-success" onClick="window.location.reload();">Submit</button>
        </div>
    </div>
</div>

<script type = "text/javascript" >
    /**************delete banner image*****************/
    function removeBannerImage(rowId) {
        if (rowId != '') {
            swal({
				title: "Do you want to delete this banner image?",
				text: "",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: '#DD6B55',
				cancelButtonText: "No",
				confirmButtonText: 'Yes',
				closeOnConfirm: false,
				closeOnCancel: false
			},
			function(isConfirm) {
				if (isConfirm) {
					swal("Deleted!", "", "success");
					$.ajax({
						type: 'POST',
						url: '<?php e($this->Url->build('/banners/deleteBannerImage'));?>',
						data: {rowId: rowId},
						success: function(msg) {
							window.location.reload();
						},
						error: function(ts) {
							$('#error500').modal('show');
						}
					})
				} else {
					swal("Cancelled", "", "error");
				}
			});
        }
    }


    $('#my-awesome-dropzone').attr('class', 'dropzone');
    var myDropzone = new Dropzone('#my-awesome-dropzone', {
        url: '<?php e($this->Url->build('/banners/uploadFile'));?>',
        clickable: true,
        method: 'POST',
        maxFiles: 50,
        parallelUploads: 50,
        maxFilesize: 20,
        addRemoveLinks: false,
        dictRemoveFile: 'Remove',
        dictCancelUpload: 'Cancel',
        dictCancelUploadConfirmation: 'Confirm cancel?',
        dictDefaultMessage: 'Drop files here to upload',
        dictFallbackMessage: 'Your browser does not support drag n drop file uploads',
        dictFallbackText: 'Please use the fallback form below to upload your files like in the olden days',
        paramName: 'file',
        forceFallback: false,
        createImageThumbnails: true,
        maxThumbnailFilesize: 1,
        acceptedFiles: ".jpeg,.jpg",
        //acceptedFiles: "image/*",
        autoProcessQueue: true,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrfToken"]').attr('content')
        },
        init: function() {
            this.on('thumbnail', function(file) {
                if (file.width < 300 || file.height < 300) {
                    file.rejectDimensions();
                } else {
                    file.acceptDimensions();
                }
            });
        },
        accept: function(file, done) {
            file.acceptDimensions = done;
            file.rejectDimensions = function() {
                done('The image must be at least 300 x 300px')
            };
        }
    });
    
    myDropzone.on("complete", function(file) {
        var status = file.status;
        if (status == 'success') {
    
        }
        console.log(file);
    });
    
    var count = 1;
    myDropzone.on("success", function(file, responseText) {
        var fnamenew = file.name;
        count++;
    });
    
    myDropzone.on("removedfile", function(file) {
        var fname = file.name;
        fname2 = fname.trim().replace(/["~!@#$%^&*\(\)_+=`{}\[\]\|\\:;'<>,.\/?"\- \t\r\n]+/g, '_');    
    });
    
    myDropzone.on("addedfile", function(file) {
    
    }); 
</script>