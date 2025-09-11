<?php
namespace App\Controller;
use Cake\Core\Configure;
use Cake\Network\Exception\ForbiddenException;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\ORM\TableRegistry;
use Cake\Http\Response;
use Cake\Core\Exception\Exception;

class CustomersController extends AppController{

    #countries page
    public function index(){
        #check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
        #get content data
        $table = TableRegistry::get('Customers');
        # Conditions...
		$conditions = array();
		$conditions[CONDITIONS] = array(STATUS.' !=' => 3);
		$conditions[ORDER] =  array(ID => DESC);
		$conditions[LIMIT] =  PAGE_LIMIT;
        #delete post session data
        $session = $this->request->getSession();
		if($session->check(POSTDATA)){ $session->delete(POSTDATA);}
        #get record data
		$this->paginate = $conditions;
		$customers = $this->paginate($table);
        $this->set(compact('customers'));
    }

	/**************************** pagination page ****************************/
	public function customersFilter(){
        #check User Auth
        $this->checkValidSession();
		$table = TableRegistry::get('Customers');
		# Conditions...
		$conditions = array();
		$cond = array();
		$postData = $this->request->getData();
		$session = $this->request->getSession();
        #set post data in session
		if(isset($postData) && count($postData) > 0){
			$session->write(POSTDATA,$postData);
		}
		$postData = $session->read(POSTDATA);
        #set searching conditions
        if(isset($postData[NAME]) && !empty($postData[NAME])){
			$cond[NAME] =  array(NAME.' '.LIKE => '%'.trim($postData[NAME]).'%');
		}
		if(isset($postData['phone']) && !empty($postData['phone'])){
			$cond['contact'] =  array('contact'.' '.LIKE => '%'.trim($postData['phone']).'%');
		}
		if(isset($postData[EMAIL]) && !empty($postData[EMAIL])){
			$cond[EMAIL] =  array(EMAIL.' '.LIKE => '%'.trim($postData[EMAIL]).'%');
		}
		if(isset($postData[STATUS]) && !empty($postData[STATUS])){
			$cond[STATUS] =  array(STATUS => $postData[STATUS]);
		}
		$conditions[CONDITIONS] = array(STATUS.' !=' => 3);
		$conditions[LIMIT] =  PAGE_LIMIT;
		$conditions[ORDER] =  array(ID => DESC);
        #set next page number
		$pageNo = 0;
		if(isset($_REQUEST[PAGE])){
			$pageNo = $_GET[PAGE]-1;
		}
		$pageNo = $pageNo*$conditions[LIMIT];
		$this->set(PAGENO,$pageNo);
		$i = 0;
		foreach($cond as $value){
			$conditions[CONDITIONS][$i] = $value;
			$i++;
		}
		# Set data...
        $this->paginate = $conditions;
		$customers = $this->paginate($table);
        $this->set(compact('customers'));
		# Pass all data to render for display...
		$this->render('customersFilter');
	}

	#edit Static Content
    public function addCustomer(){
        #check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
        if($this->request->is(['post','put'])){
            #get request data
            $postData = array_map('trim',preg_replace('/\s+/', ' ', $this->request->getData()));
            $table = TableRegistry::get('Customers');
            $getErrors = $table->newEntity($postData,[VALIDATE => 'add']);
            if(!$getErrors->getErrors() && filter_var(strtolower($postData[EMAIL]),FILTER_VALIDATE_EMAIL)){
                try{
					$existUnique = $table->find()->select('unique_id')->where(['unique_id !=' => ''])->order(['id' => 'desc'])->first();
					$uniqueId = 'BRJC101';
					if(isset($existUnique['unique_id']) && $existUnique['unique_id'] != ''){
						$explodeUnique = explode('C',$existUnique['unique_id']);
						if(isset($explodeUnique[1])){
							$uniqcount = $explodeUnique[1]+1;
							$uniqueId = 'BRJC'.$uniqcount;
						}else{
							$uniqueId = 'BRJC101';
						}
					}
					if(isset($uniqueId)){
						$saveData['unique_id'] = $uniqueId;	
					}
					if(isset($postData[STATUS])){
						$saveData[STATUS] = $postData[STATUS];
					}
					$saveData[NAME] = ucwords($postData[NAME]);					
					$saveData[EMAIL] = strtolower($postData[EMAIL]);
                    $saveData['contact'] = $postData['contact'];
					$saveData['address'] = $postData['address'];
					$saveData[CREATED] = time();
					$saveData[MODIFIED] = time();
                    $tableEntity = $table->newEntity($saveData);
                    $record = $table->save($tableEntity);
                    $this->Flash->set('Customer has been created successfully.', array(ELEMENT => ALERT_SUCCESS));
                    $this->redirect(ADMIN_FOLDER.'customer-management'.'/');
                }catch( \Exception $e){
                    $this->Flash->set(INTERNAL_ERROR, array(ELEMENT => ALERT_ERROR));
                    $this->redirect(ADMIN_FOLDER.'add-customer'.'/');
                }
            }else{
                $error = $getErrors->getErrors();
                $this->setErrorMessage($error);
                $this->redirect(ADMIN_FOLDER.'add-customer'.'/');
            }
        }
    }

	#edit Country
    public function editCustomer($editID = NULL){
        #check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
        if($this->request->is(['post','put'])){
            #get request data
            $postData = $this->request->getData();
            $table = TableRegistry::get('Customers');
            $getErrors = $table->newEntity($postData,[VALIDATE => 'update']);
            $editID = base64_encode($postData[EDIT_TOKEN]);			
            if(!$getErrors->getErrors() && filter_var(strtolower($postData['email']),FILTER_VALIDATE_EMAIL)){
                try{
                    $saveData[ID] = $this->decryptData($postData[EDIT_TOKEN]);
					$saveData[NAME] = ucwords($postData[NAME]);					
					$saveData['contact'] = ucwords($postData['contact']);
					$saveData[EMAIL] = strtolower($postData[EMAIL]);
					if(!empty($postData[PROFILE]) && isset($postData[PROFILE])){
                        $saveData[PROFILE] = $postData[PROFILE];
                    }
                    if(!empty($postData['address']) && isset($postData['address'])){
                        $saveData['address'] = $postData['address'];
                    }
					if(isset($postData[STATUS])){
						$saveData[STATUS] = $postData[STATUS];
					}else{
						$saveData[STATUS] = 2;
					}
					$saveData[MODIFIED] = time();
                    $tableEntity = $table->newEntity($saveData);
                    $table->save($tableEntity);
                    $this->Flash->set('Customer information has been updated successfully.', array(ELEMENT => ALERT_SUCCESS));
                    $this->redirect(ADMIN_FOLDER.'edit-customer'.'/'.$editID);
                }catch( \Exception $e){
                    $this->Flash->set(INTERNAL_ERROR, array(ELEMENT => ALERT_ERROR));
                    $this->redirect(ADMIN_FOLDER.'edit-customer'.'/'.$editID);
                }
            }else{
                $error = $getErrors->getErrors();
                $this->setErrorMessage($error);
				$this->set('error',$error);
                $this->redirect(ADMIN_FOLDER.'edit-customer'.'/'.$editID);
            }
        }
        if(!empty($editID)){
            #decrypt request ID
            $editID = $this->decryptData(base64_decode($editID));
            #get row data
            $table = TableRegistry::get('Customers');
            $editData = $table->find()->where(array(ID => $editID))->first();
            if(isset($editData->id) && !empty($editData->id)){
                $this->set(compact('editData'));
            }else{
                $this->redirect(ADMIN_FOLDER.'add-customer'.'/');
            }
        }else{
            $this->redirect(ADMIN_FOLDER.'add-customer'.'/');
        }
    }
	public function viewCustomer($viewID = NULL){
		#check User Auth
		$this->checkValidSession();
		$this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
		
		$table = TableRegistry::get('Customers');
		$billTbl = TableRegistry::get('Billings');
		$viewID = $this->decryptData(base64_decode($viewID));
		$countCustomer = $table->find()->where(array(ID => $viewID))->count();
		if($countCustomer > 0){			
			$table->hasMany('Orders', [
				'foreignKey' => ['user_id'],
			]);
			$orders = $table->find('all', array(
				'contain' => array('Orders'),
				'order' => array('id' => 'DESC')
			))->toArray();
						
			$billings = $billTbl->find()->where(array('customer_id' => $viewID))->all();
			$this->set(compact('orders','billings'));
		}else{
			$this->redirect(ADMIN_FOLDER.'customers'.'/');
		}
	}	
    #setErrorMessage
    function setErrorMessage($error){
		if(isset($error['contact']['uniquePhone']) && !empty($error['contact']['uniquePhone'])){
            $this->Flash->set($error['contact']['uniquePhone'], array(ELEMENT => ALERT_ERROR));
        }
		if(isset($error['email']['uniqueEmail']) && !empty($error['email']['uniqueEmail'])){
            $this->Flash->set($error['email']['uniqueEmail'], array(ELEMENT => ALERT_ERROR));
        }
		if(isset($error[NAME][CHECK_EMPTY]) && !empty($error[NAME][CHECK_EMPTY])){
            $this->Flash->set($error[NAME][CHECK_EMPTY], array(ELEMENT => ALERT_ERROR));
        }
		if(isset($error['phone'][CHECK_EMPTY]) && !empty($error['phone'][CHECK_EMPTY])){
            $this->Flash->set($error['phone'][CHECK_EMPTY], array(ELEMENT => ALERT_ERROR));
        }
		if(isset($error[EMAIL][CHECK_EMPTY]) && !empty($error[EMAIL][CHECK_EMPTY])){
            $this->Flash->set($error[EMAIL][CHECK_EMPTY], array(ELEMENT => ALERT_ERROR));
        }
		if(isset($error[PROFILE][CHECK_EMPTY]) && !empty($error[PROFILE][CHECK_EMPTY])){
            $this->Flash->set($error[PROFILE][CHECK_EMPTY], array(ELEMENT => ALERT_ERROR));
        }
        return true;
    }

    #checkLoginSession
    function checkValidSession(){
        $session = $this->request->getSession();
		$nextPageUrl = $_SERVER["REQUEST_URI"];
		$session->write('nextPageUrl',$nextPageUrl);
        if(!$session->check(AUTHADMINID)){
            return $this->redirect(ADMIN_FOLDER);
        }
    }

    #checkLoginSession
    function checkLoginSession(){
        $session = $this->request->getSession();
        if(!empty($session->read(AUTHADMINID))){
            return $this->redirect(ADMIN_FOLDER.'dashboard/');
        }
    }

	function deleteUserImg(){
		if($this->request->is('Ajax')){
			$postData = $this->request->getData();
			if(file_exists(WWW_ROOT.'img/users/'.$postData['profile'])){
				unlink(WWW_ROOT.'img/users/'.$postData['profile']);
			}
		}
		exit;
	}

    function getVehicleTypes(){
        $table = TableRegistry::get('VehicleTypes');
         return $table->find('list', ['keyField' => 'id','valueField' => 'name'])->where(['status' => 1])->order(['name' => 'asc']);
    }

    function getStateList(){
        $countryTbl = TableRegistry::get(STATES);
        return $countryTbl->find('list', ['keyField' => 'id','valueField' => 'state'])->where(['country_id' => 101])->order(['state' => 'asc']);
        
    }

}
?>