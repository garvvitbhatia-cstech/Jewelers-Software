<?php
namespace App\View\Helper;
use Cake\View\Helper;
use Cake\ORM\TableRegistry;
use Aws\S3\S3Client;
use Cake\Core\Configure;
use Cake\Core\Configure\Engine\PhpConfig;

class AdminHelper extends Helper{

	#get admin profile data
    public function getData($adminID = NULL){
		$Table = TableRegistry::get(ADMINISTRATORS);
        return $Table->find()->where(array(ID => $adminID))->first();
	}

    #get site configuration data
    public function getSettings(){
		$Table = TableRegistry::get(SETTINGS);
        return $Table->find()->where(array(ID => 1))->first();
	}	
	
	public function getServiceNameById($serviceId=NULL){
		if($serviceId != ''){
			$Table = TableRegistry::get(SERVICES);
			$data = $Table->find()->where(array(ID => $serviceId))->first();
			return $data->title;
		}else{
			return '';
		}
	}
	
	public function getAgentNameById($agentId=NULL){
		if($agentId != ''){
			$Table = TableRegistry::get(USERS);
			$data = $Table->find()->where(array(ID => $agentId))->first();
			return $data->first_name.' '.$data->last_name;
		}else{
			return '';
		}
	}	
	
	public function getTitleByPageId($pageId=NULL){
		if($pageId != ''){
			$Table = TableRegistry::get('Cms');
			$data = $Table->find()->where(array(ID => $pageId))->first();
			return $data->title;
		}else{
			return '';
		}
	}
	
	public function getPermission($adminId=NULL,$table=NULL){
		if($adminId != '' && $table != ''){
			$Table = TableRegistry::get('UserPermissions');
			return $Table->find()->where(array('administrators_id' => $adminId, 'name' => $table))->first();			
		}else{
			return NULL;	
		}
	}
	
	/*public function getWeightById($weightId=NULL){
		if($weightId != ''){
			$Table = TableRegistry::get(WEIGHTS);
			$data = $Table->find()->where(array(ID => $weightId))->first();
			return $data->weight;
		}else{
			return '';
		}
	}
	public function getWeightTypeById($weightId=NULL){
		if($weightId != ''){
			$Table = TableRegistry::get(WEIGHTS);
			$data = $Table->find()->where(array(ID => $weightId))->first();
			return $data->weight_type;
		}else{
			return '';
		}
	}*/
	/*public function getVehicleNameById($vehicleId=NULL){
		if($vehicleId != ''){
			$Table = TableRegistry::get('VehicleTypes');
			$data = $Table->find()->where(array(ID => $vehicleId))->first();
			return $data->name;
		}else{
			return '';
		}
	}*/

	/*public function getUserExist($userId){
		$table = TableRegistry::get('Distances');
		return $table->find()->where(array('weight_id' => $userId))->count();
	}

	public function getVehicleExist($vehicleId){
		$table = TableRegistry::get('Users');
		return $table->find()->where(array('vehicle_id' => $vehicleId))->count();
	}*/

}
