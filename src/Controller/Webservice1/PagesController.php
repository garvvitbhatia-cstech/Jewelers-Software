<?php 
namespace App\Controller\Webservice1;
use Cake\Core\Configure;
use Cake\Network\Exception\ForbiddenException;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\ORM\TableRegistry;
use Cake\Http\Response;
use Cake\Core\Exception\Exception;
use App\Controller\AppController;
use Cake\Utility\Security;

class PagesController extends ValidationsController{

	public function banners(){
   		header('Content-Type: application/json');
   		$postData = array_map('trim',preg_replace('/\s+/', ' ', $this->request->getData()));
   		$this->checkUserToken($postData);
   		$table = TableRegistry::get('Banners');
   		$banners = $table->find()->where(array('status' => 1))->ORDER(array(ID => ASC))->all();
   		$bannersArray = array();
   		foreach($banners as $key => $banner){
   			$banner_path = WWW_ROOT . "img/banners/" . $banner->image_profile;
   			if(isset($banner->image_profile) && !empty($banner->image_profile) && file_exists($banner_path)){
   				$bannersArray[$key]['id'] = $banner->id;
   				$bannersArray[$key]['banner_image'] = SITEURL."img/banners/".$banner->image_profile;
   			}
   		}
         $result['homepage_banners'] = $bannersArray;
   		echo json_encode($result); die;
   	}

	public function weightTypes(){
		header('Content-Type: application/json');
		$postData = array_map('trim',preg_replace('/\s+/', ' ', $this->request->getData()));
		#validations
		$this->checkUserToken($postData);
		$userToken = $postData['userToken'];
		$table = TableRegistry::get('WeightTypes');
		$datas = $table->find()->where(array('status' => 1))->ORDER(array(ORDERING => ASC))->all();
		$weightTypeArray = array();
		foreach($datas as $key => $data){
			$weightTypeArray[$key]['id'] = $data->id;
			$weightTypeArray[$key]['weight_type'] = $data->type;
		}
		$result['weightTypes'] = $weightTypeArray;
		echo json_encode($result); die;
	}

	public function selectWeight(){
		header('Content-Type: application/json');
		$postData = array_map('trim',preg_replace('/\s+/', ' ', $this->request->getData()));
		#validations 
		$this->checkUserToken($postData);
		$this->checkWeightType($postData);
		$userToken = $postData['userToken'];
		$weightTypeID = $postData['weight_type_id'];
		$table = TableRegistry::get('WeightTypes');
		$weightType = $table->find()->where(array('status' => 1, ID => $weightTypeID))->first();
		$WeightTable = TableRegistry::get('Weights');
		$datas = $WeightTable->find()->where(array('status' => 1, 'weight_type_id' => $weightTypeID))->ORDER(array('weight' => ASC))->all();
		$weightArray = array();
		if($datas->count() >0){
			foreach($datas as $key => $data){
				$weightArray[$key]['id'] = $data->id;
				$weightArray[$key]['weight_type'] = $weightType['type'];
				$weightArray[$key]['weight'] = $data->weight;
			}
			$result['weights'] = $weightArray;
			echo json_encode($result); die;
		}else{
			$result['weights'] = null;
			echo json_encode($result); die;
		}
	}

	public function distanceCharge(){
		header('Content-Type: application/json');
		$postData = array_map('trim',preg_replace('/\s+/', ' ', $this->request->getData()));
		#validations 
		$this->checkUserToken($postData);
		$this->userVehicleValidation($postData);
		$this->checkWeightType($postData);
		$this->checkWeight($postData);
		$this->checkDistance($postData);
		$userToken = $postData['userToken'];
		$weightTypeID = $postData['weight_type_id'];
		$weightID = $postData['weight_id'];
		$distance = $postData['distance'];
		$table = TableRegistry::get('WeightTypes');
		$WeightTable = TableRegistry::get('Weights');
		$distanceTable = TableRegistry::get('Distances');
		$weightType = $table->find()->where(array('status' => 1, ID => $weightTypeID))->first();
		$weight = $WeightTable->find()->where(array('status' => 1, ID => $weightID))->first();

		$data = $distanceTable->find()->where(array('status' => 1, 'weight_type_id' => $weightTypeID, 'weight_id' => $weightID, 'dist_from <=' => $distance, 'dist_to >=' => $distance ))->first();
		$priceArray = array();
		
		$priceArray['id'] = $data->id;
		$priceArray['weight_type'] = $weightType['type'];
		$priceArray['weight'] = $weight->weight;
		$priceArray['distance'] = $distance;
		$priceArray['charge'] = $data->price;
	
		$result['distance_charge'] = $priceArray;
		echo json_encode($result); die;
		
	}


	/*validations*/

	public function checkWeightType($data){
   		if(!isset($data['weight_type_id'])){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'weight_type_id param does not exist');
   			echo json_encode($result); die;
   		}
   		if($data['weight_type_id'] == ""){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'weight_type_id cannot blank');
   			echo json_encode($result); die;
   		}
   		if(!is_numeric($data['weight_type_id'])){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'weight_type_id have numeric format');
   			echo json_encode($result); die;
   		}
   
   		$table = TableRegistry::get('WeightTypes');
   		$existRecord = $table->find()->where(array('status' => 1, ID => $data['weight_type_id']))->count();
   		if($existRecord == 0){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'InValid weight_type_id');
   			echo json_encode($result); die;
   		}
   	}

   	public function checkWeight($data){
   		if(!isset($data['weight_id'])){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'weight_id param does not exist');
   			echo json_encode($result); die;
   		}
   		if($data['weight_id'] == ""){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'weight_id cannot blank');
   			echo json_encode($result); die;
   		}
   		if(!is_numeric($data['weight_id'])){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'weight_id have numeric format');
   			echo json_encode($result); die;
   		}
   
   		$table = TableRegistry::get('Weights');
   		$existRecord = $table->find()->where(array('status' => 1, ID => $data['weight_id']))->count();
   		if($existRecord == 0){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'InValid weight_id');
   			echo json_encode($result); die;
   		}
   	}

   	public function checkDistance($data){
   		if(!isset($data['distance'])){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'distance param does not exist');
   			echo json_encode($result); die;
   		}
   		if($data['distance'] == ""){
   			$result = array('distance' => 400,'status' => 'Error','msg' => 'distance cannot blank');
   			echo json_encode($result); die;
   		}
   		
   	}

   	public function userVehicleValidation($data){
   		if(!isset($data['vehicle_id'])){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'vehicle_id param does not exist');
   			echo json_encode($result); die;
   		}
   		if($data['vehicle_id'] == ""){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'vehicle_id cannot blank');
   			echo json_encode($result); die;
   		}
   		if(!is_numeric($data['vehicle_id'])){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'vehicle_id is numeric value');
   			echo json_encode($result); die;
   		}
   		$table = TableRegistry::get('VehicleTypes');
   		$existUser = $table->find()->where(array(STATUS => 1,'id' => $data['vehicle_id']))->count();
   		if($existUser == 0){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'vehicle_id not valid');
   			echo json_encode($result); die;
   		}
   	}

   	
}