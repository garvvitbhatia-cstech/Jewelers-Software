<tr id="remove_<?php e($row); ?>">
    <td width="5%"><?php e($count); ?></td>
    <td width="85%"><input type="text" name="product_name[]" id="product_name" class="product_name form-control"/><br />
    	<input type="file" name="product_image[]" id="product_image" accept="image/*" onchange="document.getElementById('previewImg<?php e($row); ?>').src = window.URL.createObjectURL(this.files[0]); $('#previewImg<?php e($row); ?>').show();"/><br />
		<img src="#" id='previewImg<?php e($row); ?>' style="display:none;" width="100px"/><br />
		<textarea cols="27" rows="3" name="comment[]" id="comment" class="form-control"></textarea></td>
    <td><button type="button" class="btn btn-danger" onclick="remove(<?php e($row); ?>)">Remove</button></td>                          
</tr>

<script type="text/javascript">
	$(document).ready(function(e) {
        $('#customer_contact').filter_input({regex:'[0-9]'});
		$('#date').filter_input({regex:'[0-9-]'});
		$('#qty').filter_input({regex:'[0-9]'});
		$('#price').filter_input({regex:'[0-9.]'});
		$('#quantity').filter_input({regex:'[0-9]'});    
	
		$(function(){ 
			$(".product_name").autocomplete({
			  source: "<?php e($this->Url->build('/ecommerce/searchProduct'.'/'));?>",
			  minLength: 2,
			  dataType: "JSON",
			  select:function(event, ui){
				$(this).parent().find('.product_name').val(ui.item.product_name);
			  }
			});
		});
	});
</script>

<?php die; ?>