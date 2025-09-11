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
//use Cake\Auth\DefaultPasswordHasher;

class LoginsController extends ValidationsController{

	public function logout(){
		header('Content-Type: application/json');
		$postData = $this->request->getData();
		#validations
		$this->checkUserToken($postData);
		$table = TableRegistry::get(USERS);
		$userData = $table->find()->where(array(USER_TOKEN => $postData['userToken']))->first();
		$saveData[ID] = $userData->id;
		$saveData[USER_TOKEN] = NULL;
		$tableEntity = $table->newEntity($saveData);
		$record = $table->save($tableEntity);
		$result = array('response' => 200, 'status' => 'Success','dashboard' => false,'msg' => 'Logout successfully');
		echo json_encode($result); die;
	}

	public function normalLogin(){
		header('Content-Type: application/json');
		$postData = array_map('trim',preg_replace('/\s+/', ' ', $this->request->getData()));
		#validations
		$this->userLoginEmailValidation($postData);
		$this->userLoginPasswordValidation($postData);
		$emailAddress = strtolower($postData['userEmail']);
		$password = $this->encryptData($postData['userPassword']);
		$table = TableRegistry::get(USERS);
		$existUser = $table->find()->where(array(EMAIL => $emailAddress, PASSWORD => $password));
		if($existUser->count() > 0){
			$userData = $existUser->first();
			if($userData->status == 1){
				if($userData->otp_verifued == 1){
					$userToken = str_shuffle(md5(time()));
					$saveData[ID] = $userData->id;
					$saveData[USER_TOKEN] = $userToken;
					$saveData[OTP_VERIFIED] = 1;
					$saveData[OTP] = NULL;
					$tableEntity = $table->newEntity($saveData);
					$record = $table->save($tableEntity);
					$result = array('response' => 200, 'status' => 'Success','dashboard' => true,'userToken' => $userToken ,'userEmail' => $emailAddress, 'msg' => 'login successfully');
					echo json_encode($result); die;
				}else{
					$otp = rand(1001,9999);
					$saveData[ID] = $userData->id;
					$saveData[OTP_VERIFIED] = 2;
					$saveData[OTP] = $otp;
					$tableEntity = $table->newEntity($saveData);
					$record = $table->save($tableEntity);
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
					$result = array('response' => 400, 'status' => 'Error','otp' => $otp,'userToken' => $userData->user_token, 'resend_otp_email_status' => $otpEmailStatus, 'msg' => 'otp not verified');
					echo json_encode($result); die;
				}
			}else{
				$result = array('response' => 400,'status' => 'Error','msg' => 'Inactive user');
				echo json_encode($result); die;
			}
		}else{
			$result = array('response' => 400,'status' => 'Error','msg' => 'Incorrect username and password');
			echo json_encode($result); die;
		}
	}

	public function changePassword(){
		header('Content-Type: application/json');
		$postData = array_map('trim',preg_replace('/\s+/', ' ', $this->request->getData()));
		#validations
		$this->checkUserToken($postData);
		$this->checkCurrentPassword($postData);
		$userToken = $postData['userToken'];
		$password = $this->encryptData($postData['currentPassword']);
		$table = TableRegistry::get(USERS);
		$userData = $table->find()->where(array(USER_TOKEN => $userToken, PASSWORD => $password))->first();
		
			$saveData[ID]=$userData['id'];
			$saveData[PASSWORD] = $this->encryptData($postData['newPassword']);
			$tableEntity = $table->newEntity($saveData);
			$record = $table->save($tableEntity);
			$sendEmailTo = 'User';
			$newarray = array(
				'name' => $userData->name,
				'email' => $userData['email'],
				'new_password' => $postData['newPassword']
			);
				$sendEmail = array(
					'to'=> $userData['email'],
					'userData' => $newarray,
					'template_id' => 7,
					'template' => 'app_forgot_password',
					'sendEmailTo' => $sendEmailTo
				);
				if($this->SendEmails->sendEmail($sendEmail)){
					$otpEmailStatus = 'Email Sent';			
				}else{
					$otpEmailStatus = EMAIL_NOT_SEND;	
				}
			$result = array('response' => 200, 'status' => 'Success','dashboard' => true,'new_password' => $postData['newPassword'], 'change_password_status' => $otpEmailStatus );
			echo json_encode($result); die;
		

	}

	public function userUpdateProfileValue(){ 
		header('Content-Type: application/json');
		$postData = $this->request->getData();
		#validations
		$this->checkUserToken($postData);
		$userToken = $postData['userToken'];
		$table = TableRegistry::get(USERS);
		$existUser = $table->find()->where(array(TYPE => 'User',USER_TOKEN => $userToken,STATUS => 1))->all();
		$userArray = array();
		foreach($existUser as $key => $user){
			$userArray[$key]['id'] = $user->id;
			$userArray[$key][TYPE] = $user->type;
			$userArray[$key][NAME] = $user->name;
			$userArray[$key]['email'] = $user->email;
			$userArray[$key]['userPhone'] = $user->phone;
			$userArray[$key]['address'] = $user->address;
			$userArray[$key]['state_id'] = $user->state_id;
			$userArray[$key]['city_id'] = $user->city_id;
			$userArray[$key]['pincode'] = $user->pincode;
			$profile = $user->profile;
			$userArray[$key]['profile'] = SITEURL."img/no-img.png";
			$path = WWW_ROOT."img/users/".$profile;
			if(isset($profile) && !empty($profile) && file_exists($path)) {
				$userArray[$key]['profile'] = SITEURL."img/users/".$profile;
			}
		}
		$result['user_data_updateProfile'] = $userArray;
		echo json_encode($result); die;
	}

	public function transporterUpdateProfileValue(){ 
		header('Content-Type: application/json');
		$postData = $this->request->getData();
		#validations
		$this->checkUserToken($postData);
		$userToken = $postData['userToken'];
		$table = TableRegistry::get(USERS);
		$existUser = $table->find()->where(array(TYPE => 'Transporter', USER_TOKEN => $userToken,STATUS => 1))->all();
		$userArray = array();
		foreach($existUser as $key => $user){
			$userArray[$key]['id'] = $user->id;
			$userArray[$key][TYPE] = $user->type;
			$userArray[$key][NAME] = $user->name;
			$userArray[$key]['email'] = $user->email;
			$userArray[$key]['userPhone'] = $user->phone;
			$userArray[$key]['address'] = $user->address;
			$userArray[$key]['state_id'] = $user->state_id;
			$userArray[$key]['city_id'] = $user->city_id;
			$userArray[$key]['pincode'] = $user->pincode;
			$userArray[$key]['dl_no'] = $user->dl_no;
			$userArray[$key]['rc_no'] = $user->rc_no;
			$userArray[$key]['vehicle_id'] = $user->vehicle_id;
			$profile = $user->profile;
			$userArray[$key]['profile'] = SITEURL."img/no-img.png";
			$path = WWW_ROOT."img/users/".$profile;
			if(isset($profile) && !empty($profile) && file_exists($path)) {
				$userArray[$key]['profile'] = SITEURL."img/users/".$profile;
			}
			$userArray[$key]['dl_image'] = null;
			$dl_path = WWW_ROOT."img/users/dl/".$user->dl_image;
			if(isset($user->dl_image) && !empty($user->dl_image) && file_exists($dl_path)) {
				$userArray[$key]['dl_image'] = SITEURL."img/users/dl/".$user->dl_image;
			}
			$userArray[$key]['rc_image'] = null;
			$rc_path = WWW_ROOT."img/users/rc/".$user->rc_image;
			if(isset($user->rc_image) && !empty($user->rc_image) && file_exists($rc_path)) {
				$userArray[$key]['rc_image'] = SITEURL."img/users/rc/".$user->rc_image;
			}
			$userArray[$key]['fitness_image'] = null;
			$fitness_path = WWW_ROOT."img/users/fitness/".$user->fitness_image;
			if(isset($user->fitness_image) && !empty($user->fitness_image) && file_exists($fitness_path)) {
				$userArray[$key]['fitness_image'] = SITEURL."img/users/fitness/".$user->fitness_image;
			}
		}
		$result['transporter_data_updateProfile'] = $userArray;
		echo json_encode($result); die;
	}

	public function userUpdateProfile(){
		header('Content-Type: application/json');
		$postData = array_map('trim',preg_replace('/\s+/', ' ', $this->request->getData()));
		#validations
		$this->checkUserToken($postData);
		$this->userNameValidation($postData);
		$this->userPhoneValidation($postData);
		$userToken = $postData['userToken'];
		$table = TableRegistry::get(USERS);
		$existUser = $table->find()->where(array(USER_TOKEN => $userToken, STATUS => 1));
		if($existUser->count() > 0){
			$userData = $existUser->first();
			$saveData[ID]=$userData['id'];
			$saveData['phone'] = trim($postData['userPhone']);
			$saveData[NAME] = $postData['fullname'];
			if(isset($postData['profile']) && !empty($postData['profile'])){
				$target_dir = WWW_ROOT."img/users/";
				if(isset($userData->profile) && !empty($userData->profile) && file_exists($target_dir.$userData->profile)) {
					unlink($target_dir.$userData->profile);
				}
				$decoded_file = base64_decode($postData['profile']); 
			   	$mime_type = finfo_buffer(finfo_open(), $decoded_file, FILEINFO_MIME_TYPE);
			    $extension = $this->mime2ext($mime_type);
			    if($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png'){
		    		$file = time().'.'. $extension;
				    $file_dir = $target_dir . $file;
				    file_put_contents($file_dir, $decoded_file);
				    $saveData['profile'] = $file;
		    	}
			}
			if(isset($postData['address']) && !empty($postData['address'])){
				$saveData['address'] = $postData['address'];
			}
			if(!empty($postData['state_id'])){
				$this->checkStateValidation($postData);
				$saveData['state_id'] = $postData['state_id'];
			}
			if(!empty($postData['city_id'])){
				$this->checkCityValidation($postData);
				$saveData['city_id'] = $postData['city_id'];
			}
			if(!empty($postData['pincode'])){
				$this->userPincodeValidation($postData);
				$saveData['pincode'] = $postData['pincode'];
			}
			
			$tableEntity = $table->newEntity($saveData);
			$record = $table->save($tableEntity);
			$result = array('response' => 200, 'status' => 'Success','dashboard' => true, 'msg' => 'profile updated successfully');
			echo json_encode($result); die;
		}
	}

	public function transporterUpdateProfile(){
		header('Content-Type: application/json');
		$postData = array_map('trim',preg_replace('/\s+/', ' ', $this->request->getData()));
		#validations
		$this->checkUserToken($postData);
		$this->userNameValidation($postData);
		$this->userVehicleIDValidation($postData);
		$this->userDLValidation($postData);
		$this->userRCValidation($postData);
		$this->userPhoneValidation($postData);
		$userToken = $postData['userToken'];
		$table = TableRegistry::get(USERS);
		$existUser = $table->find()->where(array(USER_TOKEN => $userToken, STATUS => 1));
		if($existUser->count() > 0){
			$userData = $existUser->first();
			$saveData[ID]=$userData['id'];
			$saveData['phone'] = trim($postData['userPhone']);
			$saveData[NAME] = $postData['fullname'];
			$saveData['vehicle_id'] = $postData['vehicle_id'];
			$saveData['dl_no'] = strtoupper($postData['dl_no']);
			$saveData['rc_no'] = strtoupper($postData['rc_no']);
			if(!empty($postData['rc_no']) && !empty($postData['rc_image'])){
				$saveData['verify_status'] = 2;
			}
			if(isset($postData['profile']) && !empty($postData['profile'])){
				$target_dir = WWW_ROOT."img/users/";
				if(isset($userData->profile) && !empty($userData->profile) && file_exists($target_dir.$userData->profile)) {
					unlink($target_dir.$userData->profile);
				}
				$decoded_file = base64_decode($postData['profile']); 
			   	$mime_type = finfo_buffer(finfo_open(), $decoded_file, FILEINFO_MIME_TYPE);
			    $extension = $this->mime2ext($mime_type);
			    if($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png'){
		    		$file = mt_rand(11,99).time().mt_rand(11,99). '.' . $extension;
				    $file_dir = $target_dir . $file;
				    file_put_contents($file_dir, $decoded_file);
				    $saveData['profile'] = $file;
		    	}
			}
			if(isset($postData['dl_image']) && !empty($postData['dl_image'])){
				$this->userDLImageValidation($postData);
				$target_dir = WWW_ROOT."img/users/dl/";
				if(isset($userData->dl_image) && !empty($userData->dl_image) && file_exists($target_dir.$userData->dl_image)) {
					unlink($target_dir.$userData->dl_image);
				}
				$decoded_file = base64_decode($postData['dl_image']); 
			   	$mime_type = finfo_buffer(finfo_open(), $decoded_file, FILEINFO_MIME_TYPE);
			    $extension = $this->mime2ext($mime_type);
			    if($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png'){
		    		$file = mt_rand(11,99).time().mt_rand(11,99). '.' . $extension;
				    $file_dir = $target_dir . $file;
				    file_put_contents($file_dir, $decoded_file);
				    $saveData['dl_image'] = $file;
		    	}
			}
			if(isset($postData['rc_image']) && !empty($postData['rc_image'])){
				$this->userRCImageValidation($postData);
				$target_dir = WWW_ROOT."img/users/rc/";
				if(isset($userData->rc_image) && !empty($userData->rc_image) && file_exists($target_dir.$userData->rc_image)) {
					unlink($target_dir.$userData->rc_image);
				}
				$decoded_file = base64_decode($postData['rc_image']); 
			   	$mime_type = finfo_buffer(finfo_open(), $decoded_file, FILEINFO_MIME_TYPE);
			    $extension = $this->mime2ext($mime_type);
			    if($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png'){
		    		$file = mt_rand(11,99).time().mt_rand(11,99). '.' . $extension;
				    $file_dir = $target_dir . $file;
				    file_put_contents($file_dir, $decoded_file);
				    $saveData['rc_image'] = $file;
		    	}
			}
			if(isset($postData['fitness_image']) && !empty($postData['fitness_image'])){
				$this->userFitnessImageValidation($postData);
				$target_dir = WWW_ROOT."img/users/fitness/";
				if(isset($userData->fitness_image) && !empty($userData->fitness_image) && file_exists($target_dir.$userData->fitness_image)) {
					unlink($target_dir.$userData->fitness_image);
				}
				$decoded_file = base64_decode($postData['fitness_image']); 
			   	$mime_type = finfo_buffer(finfo_open(), $decoded_file, FILEINFO_MIME_TYPE);
			    $extension = $this->mime2ext($mime_type);
			    if($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png'){
		    		$file = mt_rand(11,99).time().mt_rand(11,99). '.' . $extension;
				    $file_dir = $target_dir . $file;
				    file_put_contents($file_dir, $decoded_file);
				    $saveData['fitness_image'] = $file;
		    	}
			}
			if(isset($postData['address']) && !empty($postData['address'])){
				$saveData['address'] = $postData['address'];
			}
			if(!empty($postData['state_id'])){
				$this->checkStateValidation($postData);
				$saveData['state_id'] = $postData['state_id'];
			}
			if(!empty($postData['city_id'])){
				$this->checkCityValidation($postData);
				$saveData['city_id'] = $postData['city_id'];
			}
			if(!empty($postData['pincode'])){
				$this->userPincodeValidation($postData);
				$saveData['pincode'] = $postData['pincode'];
			}
			
			$tableEntity = $table->newEntity($saveData);
			$record = $table->save($tableEntity);
			$result = array('response' => 200, 'status' => 'Success','dashboard' => true, 'msg' => 'profile updated successfully');
			echo json_encode($result); die;
		}
	}

	public function forgotPassword(){
		header('Content-Type: application/json');
		$postData = array_map('trim',preg_replace('/\s+/', ' ', $this->request->getData()));
		#validations
		$this->userLoginEmailValidation($postData);
		$userEmail = $postData['userEmail'];
		$table = TableRegistry::get(USERS);
		$existUser = $table->find()->where(array('email' => $userEmail,'status' => 1));
		if($existUser->count() > 0){
			$userData = $existUser->first();
			$saveData[ID]=$userData['id'];
			$str = 'AQcv@36';
			$tmpPassword= str_shuffle($str);
			$saveData[PASSWORD] = $this->encryptData($tmpPassword);
			$tableEntity = $table->newEntity($saveData);
			$record = $table->save($tableEntity);
			$sendEmailTo = 'User';
			$newarray = array(
				'name' => $userData->name,
				'email' => $userData['email'],
				'temp_password' => $tmpPassword
			);
				$sendEmail = array(
					'to'=> $userData['email'],
					'userData' => $newarray,
					'template_id' => 6,
					'template' => 'app_forgot_password',
					'sendEmailTo' => $sendEmailTo
				);
				if($this->SendEmails->sendEmail($sendEmail)){
					$otpEmailStatus = 'Email Sent';			
				}else{
					$otpEmailStatus = EMAIL_NOT_SEND;	
				}
			$result = array('response' => 200, 'status' => 'Success','dashboard' => true,'tempPassword' => $tmpPassword, 'userEmail' => $userEmail, 'forgot_password_status' => $otpEmailStatus );
			echo json_encode($result); die;
		}

	}

	public function selectState(){ 
		header('Content-Type: application/json');
		$postData = $this->request->getData();
		#validations
		$this->checkUserToken($postData);
		$table = TableRegistry::get(STATES);
		$states = $table->find()->where(array(STATUS => 1, COUNTRY_ID => 101))->ORDER(array(STATE => ASC))->all();
		$stateArray = array();
		foreach($states as $key => $state){
			$stateArray[$key]['id'] = $state->id;
			$stateArray[$key]['state_name'] = $state->state;
		}
		$result['states'] = $stateArray;
		echo json_encode($result); die;
	}

	public function selectCity(){ 
		header('Content-Type: application/json');
		$postData = $this->request->getData();
		#validations
		$this->checkUserToken($postData);
		$this->checkStateValidation($postData);
		$state_id = $postData['state_id'];
		$table = TableRegistry::get(CITIES);
		$stateTable = TableRegistry::get(STATES);
		$states = $stateTable->find()->where(array('status' => 1, COUNTRY_ID => 101, ID => $state_id))->first();
		$cities = $table->find()->where(array('status' => 1, COUNTRY_ID => 101, 'state_id' => $state_id))->ORDER(array(CITY => ASC))->all();
		$cityArray = array();
		foreach($cities as $key => $city){
			$cityArray[$key]['id'] = $city->id;
			$cityArray[$key]['state_name'] = $states->state;
			$cityArray[$key]['city_name'] = $city->city;
		}
		$result['cities'] = $cityArray;
		echo json_encode($result); die;
	}


######### Validations ################################
	public function userVehicleIDValidation($data){
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

   	public function checkCurrentPassword($data){
   		if(!isset($data['currentPassword'])){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'currentPassword param does not exist');
   			echo json_encode($result); die;
   		}
   		if($data['currentPassword'] == ""){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'currentPassword cannot blank');
   			echo json_encode($result); die;
   		}
   		$table = TableRegistry::get(USERS);
   		$existUser = $table->find()->where(array(PASSWORD => $this->encryptData($data['currentPassword']), 'status' => 1, USER_TOKEN => $data['userToken']))->all();
   		if($existUser->count() == 0){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'InValid currentPassword');
   			echo json_encode($result); die;
   		}
   		if(!isset($data['newPassword'])){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'newPassword param does not exist');
   			echo json_encode($result); die;
   		}
   		if($data['newPassword'] == ""){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'newPassword cannot blank');
   			echo json_encode($result); die;
   		}
   		if(strlen($data['newPassword']) < 5){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'New password length should be greather then 6');
   			echo json_encode($result); die;
   		}
   		if(strlen($data['newPassword']) >= 6){
   			$uppercase = preg_match('@[A-Z]@', $data['newPassword']);
   			$lowercase = preg_match('@[a-z]@', $data['newPassword']);
   			$number    = preg_match('@[0-9]@', $data['newPassword']);	
   			$specialChars = preg_match('@[^\w]@', $data['newPassword']);	
   			if(!$uppercase || !$lowercase || !$number || !$specialChars) {
   				$result = array('response' => 400,'status' => 'Error','msg' => 'Password should include at least one upper case letter, one lower case letter, one number, and one special character');
   				echo json_encode($result); die;
   			}
   		}
   		if(!isset($data['confirmPassword'])){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'confirmPassword param does not exist');
   			echo json_encode($result); die;
   		}
   		if($data['confirmPassword'] == ""){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'confirmPassword cannot blank');
   			echo json_encode($result); die;
   		}
   		if($data['newPassword'] != $data['confirmPassword']){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'Confirm Password cannot match');
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
      if($existUser > 1){
        $result = array('response' => 400,'status' => 'Error','msg' => 'vehicle rc_no already register');
        echo json_encode($result); die;
      }
    }
    

   	public function userFitnessImageValidation($data){
   		if(!isset($data['fitness_image'])){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'fitness_image param does not exist');
   			echo json_encode($result); die;
   		}
   		if($data['fitness_image'] == ""){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'fitness_image cannot blank');
   			echo json_encode($result); die;
   		}
   	}

   	public function userDLImageValidation($data){
   		if(!isset($data['dl_image'])){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'dl_image param does not exist');
   			echo json_encode($result); die;
   		}
   		if($data['dl_image'] == ""){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'dl_image cannot blank');
   			echo json_encode($result); die;
   		}
   	}

   	public function userRCImageValidation($data){
   		if(!isset($data['rc_image'])){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'rc_image param does not exist');
   			echo json_encode($result); die;
   		}
   		if($data['rc_image'] == ""){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'rc_image cannot blank');
   			echo json_encode($result); die;
   		}
   	}

}
?>