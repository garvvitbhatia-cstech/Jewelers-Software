    <tr id="remove_<?php e($row); ?>">
        <td><?php e($count); ?></td>
        <td>
        	<input type="hidden" name="tunch[]" id="tunch" class="tunch form-control" value="0"/>
        	<input type="hidden" name="wstg[]" id="wstg" class="wstg form-control" value="0"/>
            <input type="hidden" name="product_id[]" id="product_id" class="product_id"/>
            <input type="tel" name="product_name[]" id="product_name" class="product_name form-control"/>
        </td>
        <td>
            <select name="purity[]" id="purity" class="purity form-control">
                <option value="14 K">14 K</option>
                <option value="18 K">18 K</option>
                <option value="22 K">22 K</option>
                <option value="24 K">24 K</option>
            </select>
        </td>
        <td><input type="text" readonly="readonly" name="huid_code[]" id="huid_code" class="huid_code form-control"/></td>
        <td><input type="text" name="gross_wt[]" id="gross_wt" class="gross_wt form-control"/></td>
        <td><input type="text" name="net_wt[]" id="net_wt<?php e($row);?>" class="net_wt form-control" onblur="setPrice('<?php e($row);?>')"/></td>
        <td>
            <select name="quantity[]" id="quantity<?php e($row);?>" class="quantity form-control" onchange="setPrice('<?php e($row);?>');">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
                <option value="10">10</option>
            </select>
        </td>
        <td><input type="text" name="diam_stone_wgt[]" id="diam_stone_wgt" class="diam_stone_wgt form-control"/></td>
        <td><input type="tel" name="price[]" onblur="setPrice('<?php e($row);?>');" id="price<?php e($row);?>" class="price form-control"/></td>
        
        <td><input type="tel" name="labour[]" onblur="setPrice('<?php e($row);?>');" id="labour<?php e($row);?>" class="labour form-control"/></td>
        <td><input type="text" readonly="readonly" name="total[]" id="total<?php e($row);?>" class="total form-control"/></td>
        <td><button type="button" class="btn btn-danger" onclick="remove(<?php e($row); ?>)">Remove</button></td>
    </tr>


<script type="text/javascript">
	$(document).ready(function(e) {
        $('#customer_contact').filter_input({regex:'[0-9]'});
		$('#date').filter_input({regex:'[0-9-]'});
		$('#qty').filter_input({regex:'[0-9]'});
		$('.price').filter_input({regex:'[0-9.]'});
		$('.quantity').filter_input({regex:'[0-9]'});
		$('.product_name').filter_input({regex:'[0-9]'});
		
	
		var labour = '<?php e($labour); ?>';
		$(function(){ 
			$(".product_name").autocomplete({
			  source: "<?php e($this->Url->build('/ecommerce/searchProduct'.'/'));?>",
			  minLength: 2,
			  dataType: "JSON",
			  select:function(event, ui){
					var qty = ui.item.quantity;
					var total = parseInt(ui.item.price)+parseInt(labour);
					if(qty <= 10 && qty > 0){
						var $html = '';
						for(var i = 1; i <= qty; i++) {
						  $html+= '<option value="'+i+'">'+i+'</option>';
						}
						$(this).parent().next().next().next().next().next().find('.quantity').html($html);
						$(this).parent().next().next().next().next().next().find('.quantity').val(1);
					}
					$(this).parent().find('.product_id').val(ui.item.pid);
					$(this).parent().next().find('.purity').val(ui.item.purity);
					$(this).parent().next().next().find('.huid_code').val(ui.item.huid_code);
					$(this).parent().next().next().next().find('.gross_wt').val(ui.item.gross_weight);
					$(this).parent().next().next().next().next().find('.net_wt').val(ui.item.net_weight);
					$(this).parent().next().next().next().next().next().find('.diam_stone_wgt').val(ui.item.diam_stone_wgt);
					$(this).parent().next().next().next().next().next().next().find('.diam_stone_wgt').val(ui.item.diam_stone_wgt);
					$(this).parent().next().next().next().next().next().next().next().find('.price').val(ui.item.price);
					$(this).parent().next().next().next().next().next().next().next().next().find('.labour').val(labour);
					$(this).parent().next().next().next().next().next().next().next().next().next().find('.total').val(total);
					
					setTotal();
				}
			});
		});
	});
</script>

<?php die; ?>