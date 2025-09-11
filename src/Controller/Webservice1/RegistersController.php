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
use Cake\Mailer\Mailer;
use Cake\Utility\Security;
//use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Entity;

class RegistersController extends ValidationsController{

	/*public function normalRegisterFirst(){
		header('Content-Type: application/json');
		$postData = $this->request->getData();
		#validations
		$this->userPhoneValidation($postData);
		$this->userRegisteredValidation($postData);
		
		$result = array('response' => 200, 'status' => 'Success','userPhone' => $postData['userPhone']);
		echo json_encode($result); die;
	}*/

	public function selectRegistrationType(){
		header('Content-Type: application/json');
		$postData = $this->request->getData();
		#validations
		$this->registerTypeValidation($postData);
		
		$result = array('response' => 200, 'status' => 'Success', 'registerType' => $postData['registerType']);
		echo json_encode($result); die;
	}

	public function vehicleType(){ 
		header('Content-Type: application/json');
		$table = TableRegistry::get('VehicleTypes');
		$services = $table->find()->where(array('status' => 1))->ORDER(array(NAME => ASC))->all();
		$serviceArray = array();
		foreach($services as $key => $service){
			$serviceArray[$key]['id'] = $service->id;
			$serviceArray[$key]['vehicle_name'] = $service->name;
		}
		$result['vehicleType'] = $serviceArray;
		echo json_encode($result); die;
	}

	public function userRegister(){
		header('Content-Type: application/json');
		$postData = array_map('trim',preg_replace('/\s+/', ' ', $this->request->getData()));
		#validations
		$this->userNameValidation($postData);
		$this->userEmailValidation($postData);
		$this->userPhoneValidation($postData);
		$this->userPasswordValidation($postData);
		$table = TableRegistry::get(USERS);
		$userToken = str_shuffle(md5(time()));
		$otp = rand(1001,9999);
		$saveData[TYPE] = 'User';
		$saveData[NAME] = strtolower($postData['fullname']);
		$saveData[EMAIL] = strtolower($postData['userEmail']);
		$saveData[PASSWORD] = $this->encryptData($postData['userPassword']);
		$saveData[STATUS] = 1;
		$saveData['phone'] = trim($postData['userPhone']);
		$saveData[USER_TOKEN] = $userToken;
		$saveData[OTP] = $otp;
		$saveData[CREATED] = time();
		$saveData[MODIFIED] = time();
		$tableEntity = $table->newEntity($saveData);
		$record = $table->save($tableEntity);
		
			###### send email #######
			$sendEmailTo = 'User';
			$newarray = array(
				'registerType' => $saveData[TYPE],
				NAME => $saveData[NAME],
				EMAIL => $saveData[EMAIL],
				PASSWORD => $postData['userPassword'],
				USER_TOKEN => $userToken,
				OTP => $otp,
				'phone' => $saveData['phone']
			);
				$sendEmail = array(
					'to'=> $saveData[EMAIL],
					'userData' => $newarray,					
					'template_id' => 2,
					'template' => 'app_registration',
					'sendEmailTo' => $sendEmailTo
				);
			
			if($this->SendEmails->sendEmail($sendEmail)){
				$otpEmailStatus = 'Email Sent';			
			}else{
				$otpEmailStatus = EMAIL_NOT_SEND;	
			}
		
		$result = array('response' => 200, 'status' => 'Success','otp' => $otp,'userToken' => $userToken,'otp_email_status' => $otpEmailStatus);
		echo json_encode($result); die;
	}

	public function transporterRegister(){
		header('Content-Type: application/json');
		$postData = array_map('trim',preg_replace('/\s+/', ' ', $this->request->getData()));
		#validations
		$this->userNameValidation($postData);
		$this->userEmailValidation($postData);
		$this->userPhoneValidation($postData);
		$this->userVehicleValidation($postData);
		$this->userDLValidation($postData);
		$this->userRCValidation($postData);
		$this->userPasswordValidation($postData);
		$table = TableRegistry::get(USERS);
		$userToken = str_shuffle(md5(time()));
		$otp = rand(1001,9999);
		$saveData[TYPE] = 'Transporter';
		$saveData[NAME] = strtolower($postData['fullname']);
		$saveData[EMAIL] = strtolower($postData['userEmail']);
		$saveData['vehicle_id'] = $postData['vehicle_id'];
		$saveData['dl_no'] = strtoupper($postData['dl_no']);
		$saveData['rc_no'] = strtoupper($postData['rc_no']);
		$saveData[PASSWORD] = $this->encryptData($postData['userPassword']);
		$saveData[STATUS] = 1;
		$saveData['phone'] = trim($postData['userPhone']);
		$saveData[USER_TOKEN] = $userToken;
		$saveData[OTP] = $otp;
		$saveData[CREATED] = time();
		$saveData[MODIFIED] = time();
		$tableEntity = $table->newEntity($saveData);
		$record = $table->save($tableEntity);
		
			###### send email #######
			$sendEmailTo = 'User';
			$newarray = array(
				'registerType' => $saveData[TYPE],
				NAME => $saveData[NAME],
				EMAIL => $saveData[EMAIL],
				PASSWORD => $postData['userPassword'],
				USER_TOKEN => $userToken,
				OTP => $otp,
				'dl_no' => $postData['dl_no'],
				'rc_no' => $postData['rc_no'],
				'phone' => $saveData['phone']
			);
				$sendEmail = array(
					'to'=> $saveData[EMAIL],
					'userData' => $newarray,					
					'template_id' => 2,
					'template' => 'app_registration',
					'sendEmailTo' => $sendEmailTo
				);
			
			if($this->SendEmails->sendEmail($sendEmail)){
				$otpEmailStatus = 'Email Sent';			
			}else{
				$otpEmailStatus = EMAIL_NOT_SEND;	
			}
		
		$result = array('response' => 200, 'status' => 'Success','otp' => $otp,'userToken' => $userToken,'otp_email_status' => $otpEmailStatus);
		echo json_encode($result); die;
	}

	
	public function resendOtp(){
		header('Content-Type: application/json');
		$postData = $this->request->getData();
		#validations
		$this->checkUserToken($postData);
		$otp = rand(1001,9999);
		$table = TableRegistry::get(USERS);
		$userData = $table->find()->where(array(USER_TOKEN => $postData['userToken']))->first();
		$saveData[ID] = $userData->id;
		$saveData[OTP_VERIFIED] = 2;
		$saveData[OTP] = $otp;
		$tableEntity = $table->newEntity($saveData);
		$record = $table->save($tableEntity);
		#send email otp
		$sendEmailTo = 'User';
		$newarray = array(
			'name' => $userData->name,
			'email' => $userData->email,
			'otp' => $otp
		);
			$sendEmail = array(
				'to'=> $userData->email,
				'userData' => $newarray,
				'template_id' => 5,
				'template' => 'resend_otp',
				'sendEmailTo' => $sendEmailTo
			);
			if($this->SendEmails->sendEmail($sendEmail)){
				$otpEmailStatus = 'Email Sent';			
			}else{
				$otpEmailStatus = EMAIL_NOT_SEND;	
			}
		$result = array('response' => 200, 'status' => 'Success','otp' => $otp,'userToken' => $postData['userToken'],'otp_email_status' => $otpEmailStatus);
		echo json_encode($result); die;
	}
	public function checkOtp(){
		header('Content-Type: application/json');
		$postData = $this->request->getData();
		#validations
		$this->checkUserToken($postData);
		$this->otpValidation($postData);
		$table = TableRegistry::get(USERS);
		$userData = $table->find()->where(array(USER_TOKEN => $postData['userToken'], OTP_VERIFIED => 2))->first();
		$saveData[ID] = $userData->id;
		$saveData[OTP_VERIFIED] = 1;
		$saveData[OTP] = NULL;
		$tableEntity = $table->newEntity($saveData);
		$record = $table->save($tableEntity);
		$result = array('response' => 200, 'status' => 'Success','dashboard' => true,'userToken' => $postData['userToken']);
		echo json_encode($result); die;
	}

	/*validations*/

	public function otpValidation($data){
   		if(!isset($data['otp'])){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'otp param does not exist');
   			echo json_encode($result); die;
   		}
   		if($data['otp'] == ""){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'otp cannot blank');
   			echo json_encode($result); die;
   		}
   		if(!is_numeric($data['otp'])){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'otp is numeric value');
   			echo json_encode($result); die;
   		}
   		if(strlen($data['otp']) != 4){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'Invalid otp');
   			echo json_encode($result); die;
   		}
   		$table = TableRegistry::get(USERS);
   		$existUser = $table->find()->where(array(OTP => $data['otp'],USER_TOKEN => $data['userToken']))->count();
   		if($existUser == 0){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'Incorrect otp');
   			echo json_encode($result); die;
   		}
   	}

   	public function userRCValidation($data){
      if(!isset($data['rc_no'])){
        $result = array('response' => 400,'status' => 'Error','msg' => 'rc_no param does not exist');
        echo json_encode($result); die;
      }
      if($data['rc_no'] == ""){
        $result = array('response' => 400,'status' => 'Error','msg' => 'rc_no cannot blank');
        echo json_encode($result); die;
      }
      $table = TableRegistry::get(USERS);
      $existUser = $table->find()->where(array('rc_no' => $data['rc_no']))->count();
      if($existUser > 0){
        $result = array('response' => 400,'status' => 'Error','msg' => 'vehicle rc_no already register');
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

   	public function registerTypeValidation($data){
   		if(!isset($data['registerType'])){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'registerType param does not exist');
   			echo json_encode($result); die;
   		}
   		if($data['registerType'] == ""){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'registerType cannot blank');
   			echo json_encode($result); die;
   		}
   		if(!in_array($data['registerType'],['User','Transporter'])){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'registerType like Transporter/User ');
   			echo json_encode($result); die;
   		}
   	}

   	/*public function serviceIDValidation($data){
   		if(!isset($data['serviceID'])){
            $result = array('response' => 400,'status' => 'Error','msg' => 'serviceID param does not exist');
            echo json_encode($result); die;
   	     }
   	    if($data['serviceID'] == ""){
   	        $result = array('response' => 400,'status' => 'Error','msg' => 'serviceID cannot blank');
   	        echo json_encode($result); die;
   	    }
    }*/

    public function userPasswordValidation($data){
   		if(!isset($data['userPassword'])){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'userPassword param does not exist');
   			echo json_encode($result); die;
   		}
   		if($data['userPassword'] == ""){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'Password cannot blank');
   			echo json_encode($result); die;
   		}
   		if(strlen($data['userPassword']) < 5){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'Password length should be greather then 6');
   			echo json_encode($result); die;
   		}
   		if(strlen($data['userPassword']) >= 6){
   			$uppercase = preg_match('@[A-Z]@', $data['userPassword']);
   			$lowercase = preg_match('@[a-z]@', $data['userPassword']);
   			$number    = preg_match('@[0-9]@', $data['userPassword']);	
   			$specialChars = preg_match('@[^\w]@', $data['userPassword']);	
   			if(!$uppercase || !$lowercase || !$number || !$specialChars) {
   				$result = array('response' => 400,'status' => 'Error','msg' => 'Password should include at least one upper case letter, one lower case letter, one number and one special character');
   				echo json_encode($result); die;
   			}
   		}
   	}

}
?>