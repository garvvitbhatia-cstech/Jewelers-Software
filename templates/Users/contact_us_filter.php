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