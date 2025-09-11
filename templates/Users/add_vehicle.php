<?php

#set page meta content

$this->assign('title', SITE_TITLE.' :: Add Vehicle');

$this->assign('meta_robot', 'noindex, nofollow');

?>

<!--  page-wrapper -->

<div id="page-wrapper">

    <div class="row">

        <!-- page header -->

        <div class="col-lg-12">

            <h1 class="page-header">Add Vehicle</h1>

        </div>

        <!--end page header -->

    </div>

    <div class="row">

        <div class="col-lg-12">

            <a href="<?php e($this->Url->build(ADMIN_FOLDER.'vehicles-management'.'/'));?>" class="btn btn-info">Back To Listing</a><br />&nbsp;

        </div>

        <div class="col-lg-12">

            <!-- Form Elements -->

            <?php e($this->Flash->render()); ?>

            <div class="panel panel-default">

                <div class="panel-heading">

                    Add vehicle information

                </div>

                <div class="panel-body">

                    <div class="row">

                        <div class="col-lg-6">

                            <?= $this->Form->create(NULL,array('id' => 'addForm', 'type' => 'file', 'inputDefaults' => array('label' => false,'div' => false), 'name' => 'addForm', 'csrfToken' => $this->request->getAttribute('csrfToken')));?>

                            <div class="form-group">

                                <label>Vehicle Name:</label>

                                <input type="text" id="name" name="name" onkeyup="checkError(this.id);" confirmation="false" class="form-control">

                                <span id="nameError" class="admin_login_error"></span>

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

            <a href="<?php e($this->Url->build(ADMIN_FOLDER.'vehicles-management'.'/'));?>" class="btn btn-info">Back To Listing</a>

        </div>

    </div>

</div>



<script type="text/javascript">


$(document).ready(function(e){		

	//$('#phone').filter_input({regex:'[0-9]'});

	
    var frmSubmitted = 0;

    $('.submitBtn').click(function(){

        var flag = 0;

        if(frmSubmitted == 0){

            if($.trim($('#name').val()) == ""){

                $('#nameError').show().html('Please enter vehicle name.').slideDown();

                $('#name').focus();

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

