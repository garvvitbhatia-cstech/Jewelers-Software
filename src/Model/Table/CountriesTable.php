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

class CountriesTable extends Table{
		
	public function validationUpdate($validator){
		$validator
			->notEmptyString('country_name', __('Please enter country name.'))			
			->add('country_name', 'checkUniqueNameUpdate', [
				'rule' => 'isCheckUniqueNameUpdate',
				'message' => 'Country name already exists.',
				'provider' => 'table',
			])			
			->notEmptyString('country_code', __('Please enter country code'))
			->add('country_code', 'checkUniqueCodeUpdate', [
				'rule' => 'isCheckUniqueCodeUpdate',
				'message' => 'Country code already exists.',
				'provider' => 'table',
			])
			->requirePresence('phone_no_format')
			->notEmptyString('phone_no_format', __('Please enter phone number format.'));		
			
		return $validator;
	}
	
	public function validationAdd($validator){
		$validator
			// A list of fields
			->requirePresence('country_name','create')
			->notEmptyString('country_name', __('Please enter country name.'))			
			->add('country_name', 'checkUniqueName', [
				'rule' => 'isCheckUniqueName',
				'message' => 'Country name already exists.',
				'provider' => 'table',
			])
				
			->requirePresence('country_code','create')
			->notEmptyString('country_code', __('Please enter country code'))
			->add('country_code', 'checkUniqueCode', [
				'rule' => 'isCheckUniqueCode',
				'message' => 'Country code already exists.',
				'provider' => 'table',
			])
			
			->requirePresence('phone_no_format','create')
			->notEmptyString('phone_no_format', __('Please enter phone number format.'));
						
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
