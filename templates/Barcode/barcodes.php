<?php
#set page meta content
$this->assign('title', SITE_TITLE.' :: Product Barcodes');
$this->assign('meta_robot', 'noindex, nofollow');
e($this->Element('/admin/jQuery'));
?>
<!--  page-wrapper -->
<div id="page-wrapper">
<div class="row">
    <!-- page header -->
    <div class="col-lg-12">
        <h1 class="page-header">Product Barcodes</h1>
    </div>
    <!--end page header -->
</div>
<div class="panel panel-primary">
    <div class="panel-heading">
        <?= $this->Form->create(NULL, array('id' => 'searchForm', 'class' => 'searchForm', 'type' => 'post')) ?>
        <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                Search By
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu pull-right" role="menu">
            	<li><a href="javascript:void(0);" onclick="searchoptions('category_id');">Category</a></li>
                <li><a href="javascript:void(0);" onclick="searchoptions('product_name');">Product Name</a></li>
                <li><a href="javascript:void(0);" onclick="searchoptions('status');">Action</a></li>
            </ul>
        </div>
        <input type="text" name="product_name" id="product_name" placeholder="Product Name" class="form-control filter searchOptions"  style="width:200px !important; display:none;">
        <select style="width:200px !important; display:none;" name="category_id" id="category_id" class="form-control filter searchOptions">
            <option value="">Select Category</option>
			<?php if(isset($categoryList) && !empty($categoryList)){?>
                <?php foreach($categoryList as $key => $val): ?>
                    <option value="<?php e($key); ?>"><?php e($val); ?></option>
                <?php endforeach; ?>
            <?php } ?>
        </select>
        <select style="width:200px !important; display:none;"  name="status" id="status" class="form-control filter searchOptions">
            <option value="">Status</option>
            <option value="1">Active</option>
            <option value="2">Inactive</option>
        </select>
        <a style="display:none;" onclick="searchData();" class="btn btn-info searchbuttons" id="searchbuttons">Search</a>
        <a style="display:none;" onclick="resetFilterForm();" class="btn btn-danger searchbuttons">Reset</a>
        <?= $this->Form->end() ?>
        <a style="float:right;" href="javascript:void(0)" onclick="generateBarcode();" title="Generate Barcode" class="btn btn-default">Generate Barcode</a>
    </div>
    <input type="hidden" value="<?php e($this->Url->build(ADMIN_FOLDER.'/barcodes-filter/'));?>" id="paginatUrl">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive">
                    <div id="replaceHtml">
                    	<?= $this->Form->create(NULL, array('id' => 'barcodeForm', 'url' => ADMIN_FOLDER.'/generate-barcode/', 'class' => 'searchForm', 'type' => 'post')) ?>
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
                                    	<input type="hidden" name="unique_code[]" value="<?php e($product->id);?>"/>
                                        <td width="5%"><?php e($key+1); ?>.</td>
                                        <td>
                                            <b>Product Name: </b><?php e(ucwords(isCheckVal($product->product_name)));?><br />
                                            <b>Category: </b><?php e(ucwords(isCheckVal($categoryName)));?>
                                       </td>
                                        <td style="text-align:center;" width="5%">
                                            <input type="tel" name="quantity[]" class="form-control quantity" value="0" maxlength="3"/>
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
                            <?= $this->Form->end(); ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.panel-body -->
    </div>
</div>
<script type="text/javascript">
function generateBarcode(){
	$('#barcodeForm').submit();
}
$(document).ready(function(e){
    $('.quantity').filter_input({regex:'[0-9]'});
});
function searchoptions(search_options){
    $('.searchOptions').hide().val('');
    $('.searchbuttons').hide();
    if(search_options != ''){
        $('#'+search_options).show().focus();
        $('.searchbuttons').show();
    }
}
/* search form */
function searchData(){
	$('#searchbuttons').html('Searching...');
	$.ajax({
		type: 'POST',
		url: '<?php e($this->Url->build(ADMIN_FOLDER.'/barcodes-filter/'));?>',
		data: $('#searchForm').serialize(),
		success: function(msg){
			$('#replaceHtml').html(msg);
			$('#searchbuttons').html('Search');
			return false;
		},error: function(ts){
            $('#searchbuttons').html('Search');
            $('#error500').modal('show');
        }
	});
}
</script>