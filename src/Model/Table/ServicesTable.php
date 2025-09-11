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

class ServicesTable extends Table{
		
	public function validationUpdate($validator){
		$validator
			->notEmptyString('title', __('Please enter title.'))			
			->add('title', 'checkUniqueNameUpdate', [
				'rule' => 'isCheckUniqueNameUpdate',
				'message' => 'title name already exists.',
				'provider' => 'table',
			]);	
			
		return $validator;
	}
	
	public function validationAdd($validator){
		$validator
			// A list of fields
			->requirePresence('title','create')
			->notEmptyString('title', __('Please enter title.'))			
			->add('title', 'checkUniqueName', [
				'rule' => 'isCheckUniqueName',
				'message' => 'title already exists.',
				'provider' => 'table',
			]);
						
		return $validator;
	}
	
	public function isCheckUniqueName($field){
		$countries = TableRegistry::getTableLocator()->get('Countries');
		$countryData = $countries->find()->where(array('country_name' => $field))->first();
		if(isset($countryData->id)){
			return false;	
		}else{
			return true;	
		}
	}
	
	public function isCheckUniqueCode($field){
		$countries = TableRegistry::getTableLocator()->get('Countries');
		$countryData = $countries->find()->where(array('country_code' => $field))->first();
		if(isset($countryData->id)){
			return false;	
		}else{
			return true;
		}
	}
	
	public function isCheckUniqueNameUpdate($field){
		$countries = TableRegistry::getTableLocator()->get('Countries');
		$country = $countries->find()->where(array('country_name' => $field))->all();
		$firstRec = $country->first();
		if($country->count() > 1){
			return false;	
		}else{
			return true;	
		}
	}
	
	public function isCheckUniqueCodeUpdate($field){
		$countries = TableRegistry::getTableLocator()->get('Countries');
		$count = $countries->find()->where(array('country_code' => $field))->count();
		if($count > 1){
			return false;	
		}else{
			return true;	
		}
	}
}
?>
