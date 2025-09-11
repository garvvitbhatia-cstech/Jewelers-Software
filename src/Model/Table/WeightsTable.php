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

class WeightsTable extends Table{
		
	public function validationUpdate($validator){
		$validator
			->requirePresence('weight_type_id','create')
			->notEmptyString('weight_id', __('Please select weight type.'))	
			->requirePresence('weight','create')
			->notEmptyString('weight', __('Please enter weight.'))	;		
		return $validator;
	}
	
	public function validationAdd($validator){
		$validator
			// A list of fields
			->requirePresence('weight_type_id','create')
			->notEmptyString('weight_type_id', __('Please select weight type.'))	
			->requirePresence('weight','create')
			->notEmptyString('weight', __('Please enter weight.'))			
			/*->add('weight', 'checkUniqueName', [
				'rule' => 'isCheckUniqueName',
				'message' => 'weight already exists.',
				'provider' => 'table',
			])*/;
						
		return $validator;
	}
	
	public function isCheckUniqueName($field){
		$weights = TableRegistry::getTableLocator()->get(WEIGHTS);
		$weightData = $weights->find()->where(array('weight' => $field))->first();
		if(isset($weightData->id)){
			return false;	
		}else{
			return true;	
		}
	}
	 

	public function isCheckUniqueNameUpdate($field,$id=NULL){
		if(isset($id['data']['id']) && !empty($id['data']['edit_token'])){
			$weightData = $this->find()->where(array('weight' => $field, 'id != ' => $id['data']['id']))->first();
			if(isset($weightData->id)){
				return false;
			}else{
				return true;	
			}	
		}
	}
	
}
?>
