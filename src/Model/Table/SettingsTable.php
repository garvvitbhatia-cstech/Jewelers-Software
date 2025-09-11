<?php
// src/Model/Table/UsersTable.php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\Rule\IsUnique;

class SettingsTable extends Table{

	public function validationUpdate($validator){
		$validator->notEmptyString('admin_email', __('Please enter admin email address'))
		->notEmptyString('company_name', __('Please enter company name'));
		return $validator;
	}

}
?>
