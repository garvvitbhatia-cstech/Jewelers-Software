<?php
#set page meta content
$this->assign('title', SITE_TITLE.' :: Product Management');
$this->assign('meta_robot', 'noindex, nofollow');
e($this->Element('/admin/jQuery'));
?>
<script type="text/javascript" src="<?php e($this->Url->build('/admin/js/jquery-barcode.js'));?>"></script> 
<!--  page-wrapper -->
<div id="page-wrapper">
<div class="row">
    <div id="myProgress" style="display:block;width: 100%;background-color: #ddd;">
        <div id="myBar"></div>
    </div>
    <!-- page header -->
    <div class="col-lg-12">
        <h1 class="page-header">Product Management</h1>
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
        <select style="width:200px !important; display:none;" name="category_id" id="category_id" class="form-control filter searchOptions">
            <option value="">Select Category</option>
			<?php if(isset($categoryList) && !empty($categoryList)){?>
                <?php foreach($categoryList as $key => $val): ?>
                    <option value="<?php e($key); ?>"><?php e($val); ?></option>
                <?php endforeach; ?>
            <?php } ?>
        </select>
        <input name="product_name" id="product_name" placeholder="Product Name" class="form-control filter searchOptions"  style="width:200px !important; display:none;">
        <select style="width:200px !important; display:none;"  name="status" id="status" class="form-control filter searchOptions">
            <option value="">Status</option>
            <option value="1">Active</option>
            <option value="2">Inactive</option>
        </select>
        <a style="display:none;" onclick="searchData();" class="btn btn-info searchbuttons" id="searchbuttons">Search</a>
        <a style="display:none;" onclick="resetFilterForm();" class="btn btn-danger searchbuttons">Reset</a>
        <?= $this->Form->end() ?>
        <a style="float:right;margin-left:5px;" href="<?php e($this->Url->build(ADMIN_FOLDER.'/add-product/'));?>" title="Add Product" class="btn btn-default">Add Product</a>
        <input style="float:right;margin-left:5px;" type="button" onclick="uploadBulkProducts()" name="import" title="Upload Products" class="btn btn-info" id="import" value="Upload Products">
        <a href="<?php e($this->Url->build('/img/csv/upload_bulk_product.csv'));?>"><button type="button" class="btn btn-warning" style="float:right;">Download Product CSV</button></a>
    </div>
    <input type="hidden" value="<?php e($this->Url->build(ADMIN_FOLDER.'/products-filter/'));?>" id="paginatUrl">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive">
                    <div id="replaceHtml">
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
		url: '<?php e($this->Url->build(ADMIN_FOLDER.'/products-filter/'));?>',
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

function uploadBulkProducts(){
	$('#uploadBulkProductsModal').modal('show');
}
</script>

<div class="modal fade" id="uploadBulkProductsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <?= $this->Form->create(NULL,array('action' => $this->Url->build('/exports/uploadBulkProducts/'), 'id' => 'addForm', 'type' => 'file', 'inputDefaults' => array('label' => false,'div' => false), 'name' => 'addForm', 'csrfToken' => $this->request->getAttribute('csrfToken')));?>
            <div class="modal-header">     
            	<h5 class="modal-title" id="exampleModalLabel" style="color: #fff;">UPLOAD BULK PRODUCTS</h5>            
            </div>
            <div class="modal-body">
            	<input type="file" name="file" id="file"/>
            </div>
            <div class="modal-footer">
            	<p style="text-align:left;"><label>Required Fields </label><span style="color:#F00">*</span><br />
                Category ID<span style="color:#F00">*</span>,
                Product Name<span style="color:#F00">*</span>,
                Purity<span style="color:#F00">*</span>
                </p>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Upload Products</button>
            </div>
      	<?= $this->Form->end() ?>
    </div>
  </div>
</div>