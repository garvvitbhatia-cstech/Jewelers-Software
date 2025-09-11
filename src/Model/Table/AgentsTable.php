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

class AgentsTable extends Table{
		
	public function validationUpdate($validator){
		$validator
			->notEmptyString('name', __('Please enter agent name.'))		
			->notEmptyString('email', __('Please enter email address.'))
			->add('email', 'checkUniqueEmailUpdate', [
				'rule' => 'isCheckUniqueEmailUpdate',
				'message' => 'Email address already exists.',
				'provider' => 'table',
			])
			->requirePresence('contact')
			->notEmptyString('contact', __('Please enter phone number.'));			
		return $validator;
	}
	
	public function validationAdd($validator){
		$validator
			->requirePresence('name','create')
			->notEmptyString('name', __('Please enter agent name.'))							
			->requirePresence('email','create')
			->notEmptyString('email', __('Please enter email address.'))
			->add('email', 'checkUniqueEmail', [
				'rule' => 'isCheckUniqueEmail',
				'message' => 'Email address already exists.',
				'provider' => 'table',
			])			
			->requirePresence('contact','create')
			->notEmptyString('contact', __('Please enter phone number.'));			
		return $validator;
	}
	
	public function isCheckUniqueEmail($field){
		$agents = TableRegistry::getTableLocator()->get(AGENTS);
		$agentsData = $agents->find()->where(array('email' => $field))->first();
		if(isset($agentsData->id)){
			return false;	
		}else{
			return true;	
		}
	}
	
	public function isCheckUniqueEmailUpdate($field){
		$agents = TableRegistry::getTableLocator()->get(AGENTS);
		$count = $agents->find()->where(array('email' => $field))->count();
		if($count > 1){
			return false;	
		}else{
			return true;	
		}
	}
}
?>