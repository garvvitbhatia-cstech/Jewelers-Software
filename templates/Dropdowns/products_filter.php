<table class="table table-bordered table-hover table-striped">
<thead>
    <tr>
        <th>S.No.</th>
        <th>Product Details</th>
        <th class="text-center">Barcode</th>
        <th style="text-align:center;">Status</th>
        <th>Action</th>
    </tr>
</thead>
<tbody>
    <?php
    if(count($products) > 0){
        foreach($products as $key => $product){
        $isExist = 0;
        $rand = mt_rand();
        $categoryName = $this->Ecommerce->getCategoryNameById($product->category_id);
    ?>
        <tr>
            <td width="5%"><?php e($key+1); ?>.</td>
            <td>
                <b>Product Name: </b><?php e(ucwords(isCheckVal($product->product_name)));?><br />
                <b>Category: </b><?php e(ucwords(isCheckVal($categoryName)));?><br />
                <b>Unique Code: </b><?php e(ucwords(isCheckVal($product->unique_code)));?><br />
                <?php e(date("F jS, Y h:i A",$product->created)); ?>
            </td>
            <td class="text-center">
                <script type="text/javascript">
                    $(function(){
                        var settings = {
                            barWidth: 2,
                            barHeight: 50,
                            moduleSize: 5,
                            showHRI: true,
                            addQuietZone: true,
                            marginHRI: 5,
                            bgColor: "#FFFFFF",
                            color: "#000000",
                            fontSize: 10,
                            output: "css",
                            posX: 0,
                            posY: 0
                        };
                        
                        var code = '<?php e($product->unique_code); ?>';
                        var randomno = <?php e($rand); ?>;
                        $("#barcodeTarget_"+randomno).barcode(
                            code, // Value barcode (dependent on the type of barcode)
                            "code128", // type (string)
                            settings
                        );	
                    });
                </script>                                                
                <div class="barcodeTarget text-center" id="barcodeTarget_<?php e($rand); ?>"></div>
            </td>
            <td style="text-align:center;" width="5%">
                <?php $status = $product->status == 1 ? "<i class='fa fa-check'></i>" : "<i class='fa fa-times'></i>"; ?>
                <?php $class = $product->status == 1 ? "success" : "danger"; ?>
                <a id="statusBtn_<?= $product->id ?>" <?php if($isExist == 0){ ?> onclick="changeStatus('Products','<?= $this->encryptData($product->id); ?>','<?= $product->status ?>','<?= $product->id; ?>');" <?php } ?> class="btn btn-<?php e($class);?> btn-circle" <?php if($isExist > 0){e('disabled');} ?>><?php e($status);?></a>
                <input type="hidden" id="current_status<?= $product->id ?>" value="<?= $product->status ?>" />
            </td>
            <td width="25%"><a href="<?php e($this->Url->build(ADMIN_FOLDER.'/edit-product/'.base64_encode($this->encryptData($product->id))));?>" title="Edit" class="btn btn-success">Edit</a>
            <a href="javascript:void(0);" onclick="deleteRecord('Products','<?php e(base64_encode($this->encryptData($product->id))); ?>','0');" title="Delete" class="btn btn-danger">Delete</a>
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
<?php if($products->count() > 0){ ?>
    <tbody>
        <tr>
            <td align="center" colspan="12">
                <ul class="pagination">
                    <?php
                    $this->Paginator->options(array('update' => '#replaceHtml', 'evalScripts' => true, 'escape' => false, 'url' => array_merge(array('controller' => 'Dropdowns', 'action' => 'productsFilter'))));?>
                        <?php echo $this->Paginator->first('First'); ?>
                        <?php echo $this->Paginator->numbers(); ?>
                        <?php echo $this->Paginator->last('Last'); ?>
                    </ul>
                </td>
            </tr>
        </tbody>
    <?php } ?>
</table>