<option value="">Select weight</option>
<?php
if(isset($weight) && $weight->count() > 0){
	foreach($weight as $key => $value):
		echo '<option value="'.$key.'">'.$value.'</option>';
	endforeach;
}
?>