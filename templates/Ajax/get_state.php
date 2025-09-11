<option value="">Select State</option>
<?php
if(isset($states) && $states->count() > 0){
	foreach($states as $key => $value):
		echo '<option value="'.$key.'">'.$value.'</option>';
	endforeach;
}
?>