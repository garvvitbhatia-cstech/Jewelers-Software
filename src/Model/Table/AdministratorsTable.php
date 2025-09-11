<?php
// src/Model/Table/UsersTable.php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\Rule\IsUnique;

class AdministratorsTable extends Table{

	public function validationUpdate($validator){
		$validator->notEmptyString('user_name', __('Please enter your full name'));
		return $validator;
	}

	public function validationAdd($validator){
		$validator->notEmptyString('username', __('Please enter your username'))
			->notEmptyString('password', __('Please enter your password'));
		return $validator;
	}

	public function validationChange($validator){
		$validator->notEmptyString('currentkey', __('Please enter your current password'))
			->notEmptyString('password', __('Please enter your new password'))
			->notEmptyString('confirmpassword', __('Please enter your confirm password'));
		return $validator;
	}

	public function validationForgot($validator){
		$validator->notEmptyString('email_address', __('Please enter your email address'));
		return $validator;
	}

}
?>
