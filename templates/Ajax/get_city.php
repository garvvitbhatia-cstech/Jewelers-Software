<option value="">Select City</option>

<?php

if(isset($cities) && $cities->count() > 0){

	foreach($cities as $key => $value):

		echo '<option value="'.$key.'">'.$value.'</option>';

	endforeach;

}

?>