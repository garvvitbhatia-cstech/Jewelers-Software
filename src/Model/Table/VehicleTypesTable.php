<?php
// src/Model/Table/UsersTable.php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Utility\Text;
// the EventInterface class
use Cake\Event\EventInterface;
use Cake\ORM\Rule\IsUnique;
use Cake\ORM\TableRegistry;

class VehicleTypesTable extends Table{
	
	public function validationAdd($validator){
		$validator
			->requirePresence('name','create')
			->notEmptyString('name', __('Please enter vehicle name.'));			
				
		return $validator;
	}
	
	public function validationUpdate($validator){
		$validator
			->requirePresence('name','create')
			->notEmptyString('name', __('Please enter vehicle name.'));		
			
		return $validator;
	}
	
}
?>