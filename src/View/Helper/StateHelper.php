<?php
namespace App\View\Helper;
use Cake\View\Helper;
use Cake\ORM\TableRegistry;
use Aws\S3\S3Client;
use Cake\Core\Configure;
use Cake\Core\Configure\Engine\PhpConfig;

class StateHelper extends Helper{
	
    #get state data
    public function getStateByCountryId($countryId){
		$table = TableRegistry::get('States');
        return $table->find('list', ['keyField' => 'id','valueField' => 'state'])->where(['country_id' => $countryId, 'status' => 1])->order(['state' => 'asc']);
	}

	#get state data
    public function getCityByStateId($stateId){
		$table = TableRegistry::get('Cities');
        return $table->find('list', ['keyField' => 'id','valueField' => 'city'])->where(['state_id' => $stateId, 'status' => 1])->order(['city' => 'asc']);
	}
	
	#get state name
	public function getStateNameById($stateId){
		if(!empty($stateId)){
			$table = TableRegistry::get('States');
			$stateData = $table->find()->where(['id' => $stateId])->first();
			return $stateData->state;		
		}
	}
	
	#get city name
	public function getCityNameById($cityId){
		if(!empty($cityId)){
			$table = TableRegistry::get('Cities');
			$cityData = $table->find()->where(['id' => $cityId])->first();
			return $cityData->city;		
		}
	}
	#get city data
    public function getCityExist($stateId){
		$table = TableRegistry::get('Cities');
		return $table->find()->where(array('state_id' => $stateId))->count();
	}
}
