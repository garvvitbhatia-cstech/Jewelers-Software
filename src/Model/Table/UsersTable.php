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

class UsersTable extends Table{
	
	public function validationAdd($validator){
		$validator
			->requirePresence('name','create')
			->notEmptyString('name', __('Please enter your fullname.'))
							
			->requirePresence('phone','create')
			->notEmptyString('phone', __('Please enter your phone number.'))	
			
			->add('phone', 'uniquePhone', [
				'rule' => ['validateUnique'],
				'message' => 'Phone number already exists.',
				'provider' => 'table',
			])
			
			->requirePresence('email','create')
			->notEmptyString('email', __('Please enter your email address.'))
			
			->add('email', 'uniqueEmail', [
				'rule' => ['isCheckUniqueEmail'],
				'message' => 'Email already exists.',
				'provider' => 'table',
			])
			
			->requirePresence('password','password')
			->notEmptyString('password', __('Please insert user password.'));
		return $validator;
	}
	
	public function validationUpdate($validator){
		$validator
			->requirePresence('name','create')
			->notEmptyString('name', __('Please enter your fullname.'))			
							
			->requirePresence('phone','create')
			->notEmptyString('phone', __('Please enter your phone number.'))
			
			->add('phone', 'uniquePhone', [
				'rule' => ['isCheckUniquePhone'],
				'message' => 'Phone number already exists.',
				'provider' => 'table',
			])	
			
			->requirePresence('email','create')
			->notEmptyString('email', __('Please enter your email address.'))
			
			->add('email', 'uniqueEmail', [
				'rule' => ['isCheckUniqueEmail'],
				'message' => 'Email already exists.',
				'provider' => 'table',
			]);
		return $validator;
	}
	
	public function validationLogin($validator){
		$validator->notEmptyString('username', __('Please enter your username'))
			->notEmptyString('password', __('Please enter your password'));
		return $validator;
	}
	
	public function validationChange($validator){
		$validator->notEmptyString('currentkey', __('Please enter your current password'))
			->notEmptyString('password', __('Please enter your new password'))
			->notEmptyString('confirmpassword', __('Please enter your confirm password'));
		return $validator;
	}

	public function validationForgot($validator){
		$validator->notEmptyString('email_address', __('Please enter your email address'));
		return $validator;
	}
	
	public function validationAccountUpdate($validator){
		$validator->notEmptyString('user_name', __('Please enter your user name'))
		->notEmptyString('password', __('Please enter your email'))
		->add('email', 'uniqueEmail', [
			'rule' => ['isCheckUniqueEmail'],
			'message' => 'Email address already exists.',
			'provider' => 'table',
		]);
		return $validator;
	}
	
	public function isCheckUniqueEmail($field,$id=NULL){
		if(isset($id['data']['edit_token']) && !empty($id['data']['edit_token'])){
			$data = $this->find()->where(array('email' => $this->encryptData($field), 'id != ' => $this->decryptData($id['data']['edit_token'])))->first();
			if(isset($data->id)){
				return false;
			}else{
				return true;
			}
		}else{
			$data = $this->find()->where(array('email' => $this->encryptData($field)))->first();
			if(isset($data->id)){
				return false;	
			}else{
				return true;
			}
		}
	}
	
	public function isCheckUniquePhone($field,$id=NULL){
		if(isset($id['data']['edit_token']) && !empty($id['data']['edit_token'])){
			$data = $this->find()->where(array('phone' => $field, 'id != ' => $this->decryptData($id['data']['edit_token'])))->first();
			if(isset($data->id)){
				return false;
			}else{
				return true;	
			}	
		}else{
			$data = $this->find()->where(array('phone' => $field))->first();
			if(isset($data->id)){
				return false;	
			}else{
				return true;	
			}
		}
	}
		
	public function encryptData($value = NULL){
        if(!empty($value)){
            $value = trim(preg_replace('/\s+/', ' ', $value));
            //date_default_timezone_set('UTC');
            $encryptionMethod = "AES-256-CBC";
            $secret = "MYSECURITYSS12020PKSEncryption19";  //must be 32 char length
            $iv = substr($secret, 0, 16);
            $encryptedText = openssl_encrypt($value, $encryptionMethod, $secret,0,$iv);
            $result = "";
            if($encryptedText != ""){
                $result = trim($encryptedText);
            }
            return $result;
        }else{
            return $value;
        }
    }
	
	#decryptData
    public function decryptData($value = NULL){
        if(!empty($value)){
            //date_default_timezone_set('UTC');
            $encryptionMethod = "AES-256-CBC";
            $secret = "MYSECURITYSS12020PKSEncryption19";  //must be 32 char length
            $iv = substr($secret, 0, 16);
            $decryptedText = openssl_decrypt($value, $encryptionMethod, $secret,0,$iv);
            $result = "";
            if($decryptedText != ""){
                $result = trim($decryptedText);
            }
            return $result;
        }else{
            return $value;
        }
    }
	
}
?>