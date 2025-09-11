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

class WeightTypesTable extends Table{
		
	public function validationUpdate($validator){
		$validator
			// A list of fields
			->requirePresence('type','create')
			->notEmptyString('type', __('Please enter weight type.'));			
						
		return $validator;
	}
	
	public function validationAdd($validator){
		$validator
			// A list of fields
			->requirePresence('type','create')
			->notEmptyString('type', __('Please enter weight type.'));			
						
		return $validator;
	}	
}
?>
