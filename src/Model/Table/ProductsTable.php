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

class ProductsTable extends Table{
		
	public function validationUpdate($validator){
		$validator
			// A list of fields
			->requirePresence('product_name','create')
			->notEmptyString('product_name', __('Please enter product name.'))
			->add('product_name', 'checkUniqueName', [
				'rule' => 'isCheckUniqueName',
				'message' => 'Product name already exists.',
				'provider' => 'table',
			]);
						
		return $validator;
	}
	
	public function validationAdd($validator){
		$validator
			// A list of fields
			->requirePresence('product_name','create')
			->notEmptyString('product_name', __('Please enter product name.'))
			->add('product_name', 'checkUniqueName', [
				'rule' => 'isCheckUniqueName',
				'message' => 'Product name already exists.',
				'provider' => 'table',
			]);
						
		return $validator;
	}
	
	public function isCheckUniqueName($field,$id=NULL){
		if(isset($id['data']['edit_token']) && !empty($id['data']['edit_token'])){
			$data = $this->find()->where(array('product_name' => $field, 'id != ' => $this->decryptData($id['data']['edit_token'])))->first();
			if(isset($data->id)){
				return false;
			}else{
				return true;
			}
		}else{
			$data = $this->find()->where(array('product_name' => $field))->first();
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