<table class="table table-bordered table-hover table-striped">
    <thead>
       <tr>
          <th>S.No.</th>
          <th>Item Details</th>
       </tr>
    </thead>
    <tbody>
      <?php
            if($orders->count() > 0){
            foreach($orders as $key => $item){
            $isExist = 0;
      ?>
       <tr>
          <td width="5%"><?php e($key+1); ?>.</td>
          <td>
             <b>Return Gold (gm):</b> <?php e(ucwords(isCheckVal($item->return_gold)));?><br />
             <b>Percentage:</b> <?php e(isCheckVal($item->percentage));?><br />
             <b>Gold Amount:</b> <?php e(isCheckVal($item->total));?>
          </td>                        
           
       </tr>
       <?php
          }                              
          }else{
       ?>
       <tr>
          <td class="text-center" colspan="3">Records are not found.</td>
       </tr>
       <?php } ?>
    </tbody>
 </table>