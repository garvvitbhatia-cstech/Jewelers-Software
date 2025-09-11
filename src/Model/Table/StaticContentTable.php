<?php
// src/Model/Table/UsersTable.php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\Rule\IsUnique;

class StaticContentTable extends Table{
	public function validationUpdate($validator){
		$validator->notEmptyString('title', __('Please enter title'))
		->notEmptyString('descriptions', __('Please enter description'));
		return $validator;
	}
}
?>
