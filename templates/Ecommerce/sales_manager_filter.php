<table class="table table-bordered table-hover table-striped">
<thead>
    <tr>
        <th>S.No.</th>
        <th>Customer Details</th>
        <th>Action</th>
    </tr>
</thead>
<tbody>
    <?php
    if(count($invoices) > 0){
        foreach($invoices as $key => $invoice){
        $isExist = 0;
    ?>
        <tr>
            <td width="5%"><?php e($key+1); ?>.</td>
            <td><?php 
                e('<b>Name: </b>'.ucwords(isCheckVal($invoice->customer_name)).'<br>');
                e('<b>Contact: </b>'.ucwords(isCheckVal($invoice->customer_contact)).'<br>');
                e('<b>Invoice: </b>'.ucwords(isCheckVal($invoice->invoice_no)).'<br>');
                e('<b>Date: </b>'.isCheckVal($invoice->date).'<br>');
                date("F jS, Y h:i A",$invoice->created);
            ?></td>
            <td width="30%"><a href="<?php e($this->Url->build(ADMIN_FOLDER.'/view-invoice/'.base64_encode($this->encryptData($invoice->id))));?>" title="View" class="btn btn-primary">View</a>
            <a href="<?php e($this->Url->build(ADMIN_FOLDER.'/edit-invoice/'.base64_encode($this->encryptData($invoice->id))));?>" title="Edit" class="btn btn-success">Edit</a>
            <a href="javascript:void(0);" onclick="deleteRecord('Billings','<?php e(base64_encode($this->encryptData($invoice->id))); ?>','0');" title="Delete" class="btn btn-danger">Delete</a>
            </td>
        </tr>
    <?php
        }
    }else{
    ?>
        <tr>
            <td class="text-center" colspan="10">Records are not found.</td>
        </tr>
    <?php } ?>
</tbody>
<?php if($invoices->count() > 0){ ?>
    <tbody>
        <tr>
            <td align="center" colspan="12">
                <ul class="pagination">
                    <?php
                    $this->Paginator->options(array('update' => '#replaceHtml', 'evalScripts' => true, 'escape' => false, 'url' => array_merge(array('controller' => 'Ecommerce', 'action' => 'salesManagerFilter'))));?>
                        <?php echo $this->Paginator->first('First'); ?>
                        <?php echo $this->Paginator->numbers(); ?>
                        <?php echo $this->Paginator->last('Last'); ?>
                    </ul>
                </td>
            </tr>
        </tbody>
    <?php } ?>
</table>