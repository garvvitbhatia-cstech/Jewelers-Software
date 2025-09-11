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

class DistancesTable extends Table{
		
	public function validationUpdate($validator){
		$validator
			// A list of fields
			->requirePresence('weight_id','create')
			->notEmptyString('weight_id', __('Please select weight.'))			
			->requirePresence('dist_from','create')
			->notEmptyString('dist_from', __('Please enter distance from.'))			
			->requirePresence('dist_to','create')
			->notEmptyString('dist_to', __('Please enter distance to.'))			
			->requirePresence('price','create')
			->notEmptyString('price', __('Please enter price.'));
						
		return $validator;
	}
	
	public function validationAdd($validator){
		$validator
			// A list of fields
			->requirePresence('weight_id','create')
			->notEmptyString('weight_id', __('Please select weight.'))			
			->requirePresence('dist_from','create')
			->notEmptyString('dist_from', __('Please enter distance from.'))			
			->requirePresence('dist_to','create')
			->notEmptyString('dist_to', __('Please enter distance to.'))			
			->requirePresence('price','create')
			->notEmptyString('price', __('Please enter price.'));
						
		return $validator;
	}	
}
?>
