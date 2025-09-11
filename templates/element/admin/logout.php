<div class="modal fade fancyPopup" id="logoutPopup" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title text-center">Logout</h1>
        </div>
        <div class="modal-body" style="padding:15px 30px !important;">
         <div class="rowField_col">
         	<div class="row">
            	<div class="col-sm-12">
                	<div class="help_from" style="padding:15px;">
                    <p>Are you sure you want to log out?</p>
                    </div>
		       </div>
            </div>
         </div>         
         <div class="row twoButton text-center">
         	<div class="col-sm-12">
             <button type="button" class="btn btn-outline btn-success" onclick="location.href='<?php e($this->Url->build(ADMIN_FOLDER.'logout/'));?>'">Yes</button>
             <button type="button" data-dismiss="modal" class="btn btn-outline btn-warning">No</button>
            </div>
         </div>         
         </div>
      </div>
       </div>
  </div>