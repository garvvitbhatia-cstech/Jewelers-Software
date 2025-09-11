<table class="table table-bordered table-hover table-striped">
<thead>
    <tr>
        <th>S.No.</th>
        <th>Product Details</th>
        <th style="text-align:center;">Quantity</th>
    </tr>
</thead>
<tbody>
    <?php
    if(count($products) > 0){
        foreach($products as $key => $product){
        $isExist = 0;
        $categoryName = $this->Ecommerce->getCategoryNameById($product->category_id);
    ?>
        <tr>
            <input type="hidden" name="unique_code" value="<?php e($product->id);?>"/>
            <td width="5%"><?php e($key+1); ?>.</td>
            <td>
                <b>Product Name: </b><?php e(ucwords(isCheckVal($product->product_name)));?><br />
                <b>Category: </b><?php e(ucwords(isCheckVal($categoryName)));?>
           </td>
            <td style="text-align:center;" width="5%">
                <input type="tel" name="quantity" class="form-control quantity" value="0" maxlength="3"/>
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
                    $this->Paginator->options(array('update' => '#replaceHtml', 'evalScripts' => true, 'escape' => false, 'url' => array_merge(array('controller' => 'Barcode', 'action' => 'barcodesFilter'))));?>
                        <?php echo $this->Paginator->first('First'); ?>
                        <?php echo $this->Paginator->numbers(); ?>
                        <?php echo $this->Paginator->last('Last'); ?>
                    </ul>
                </td>
            </tr>
        </tbody>
    <?php } ?>
</table>

<script>
	$(document).ready(function(e) {
		$('.quantity').filter_input({regex:'[0-9]'});
	});
</script>