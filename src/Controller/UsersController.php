<?php
namespace App\Controller;
use Cake\Core\Configure;
use Cake\Network\Exception\ForbiddenException;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\ORM\TableRegistry;
use Cake\Http\Response;
use Cake\Core\Exception\Exception;

class UsersController extends AppController{

     #Contact-us page
    public function contactUs(){
        #check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
        
        $session = $this->request->getSession();
        #get content data
        $table = TableRegistry::get('Contacts');
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
        $contacts = $this->paginate($table);
        $this->set(compact('contacts'));
    }

    public function contactUsFilter(){
        #check User Auth
        $this->checkValidSession();
        $table = TableRegistry::get('Contacts');
        $session = $this->request->getSession();
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
        if(isset($postData['name']) && !empty($postData['name'])){
            $cond['name'] =  array('name'.' '.LIKE => '%'.trim($postData['name']).'%');
        }
        if(isset($postData['contact']) && !empty($postData['contact'])){
            $cond['contact'] =  array('contact'.' '.LIKE => '%'.trim($postData['contact']).'%');
        }
        if(isset($postData[EMAIL]) && !empty($postData[EMAIL])){
            $cond[EMAIL] =  array(EMAIL.' '.LIKE => '%'.trim($postData[EMAIL]).'%');
        }
        if(isset($postData['read_status']) && !empty($postData['read_status'])){
            $cond['read_status'] =  array('read_status' => $postData['read_status']);
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
        $contacts = $this->paginate($table);
        $this->set(compact('contacts'));
        # Pass all data to render for display...
        $this->render('contactUsFilter');
    }

    public function viewContactUs($viewID = NULL){
        #check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);        
        $table = TableRegistry::get('Contacts');

        $editID = $this->decryptData(base64_decode($viewID));
        $existcontact = $table->find()->where(array(ID => $editID))->first();
        $this->set('contacts',$existcontact);
        $saveData[ID] = $editID;
        $saveData['read_status'] = 1;
        $tableEntity = $table->newEntity($saveData);
        $table->save($tableEntity);
    }
	
    #countries page
    public function index(){
        #check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
        #get content data
        $table = TableRegistry::get(USERS);
        # Conditions...
		$conditions = array();
		$conditions[CONDITIONS] = array(STATUS.' !=' => 3,'type'=>'User');
		$conditions[ORDER] =  array(ID => DESC);
		$conditions[LIMIT] =  PAGE_LIMIT;
        #delete post session data
        $session = $this->request->getSession();
		if($session->check(POSTDATA)){ $session->delete(POSTDATA);}
        #get record data
		$this->paginate = $conditions;
		$users = $this->paginate($table);
        $this->set(compact('users'));
    }

	/**************************** pagination page ****************************/
	public function usersFilter(){
        #check User Auth
        $this->checkValidSession();
		$table = TableRegistry::get(USERS);
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
			$cond['phone'] =  array('phone'.' '.LIKE => '%'.trim($postData['phone']).'%');
		}
		if(isset($postData[EMAIL]) && !empty($postData[EMAIL])){
			$cond[EMAIL] =  array(EMAIL.' '.LIKE => '%'.trim($postData[EMAIL]).'%');
		}
		if(isset($postData[STATUS]) && !empty($postData[STATUS])){
			$cond[STATUS] =  array(STATUS => $postData[STATUS]);
		}
		$conditions[CONDITIONS] = array(STATUS.' !=' => 3,'type'=>'User');
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
		$users = $this->paginate($table);
        $this->set(compact('users'));
		# Pass all data to render for display...
		$this->render('usersFilter');
	}

    public function walletHistory($viewID = NULL){
        #check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);        
        $table = TableRegistry::get(USERS);
        $walletTable = TableRegistry::get('userWallet');
        $userID = $this->decryptData(base64_decode($viewID));
        $existUser = $table->find()->where(array(ID => $userID))->first();
        
        # Conditions...
        $conditions = array();
        $conditions[CONDITIONS] = array('user_id' => $userID);
        $conditions[ORDER] =  array(ID => DESC);
        $conditions[LIMIT] =  PAGE_LIMIT;
        #delete post session data
        $session = $this->request->getSession();
        if($session->check(POSTDATA)){ $session->delete(POSTDATA);}
        #get record data
        $this->paginate = $conditions;
        $all_history = $this->paginate($walletTable);
        $this->set(compact('all_history'));
        $this->set('users',$existUser);
    }

    public function walletHistoryFilter($viewID = NULL){
        #check User Auth
        $this->checkValidSession();
        $table = TableRegistry::get(USERS);
        $walletTable = TableRegistry::get('userWallet');
        # Conditions...
        $conditions = array();
        $cond = array();
        $postData = $this->request->getData();
        $session = $this->request->getSession();
        
        if(isset($this->request->getParam('pass')[0])){
            $userID = $this->request->getParam('pass')[0]; 
        }else{
            $userID = $postData['userID'];
        }
        $existUser = $table->find()->where(array(ID => $userID))->first();
        if(isset($postData) && count($postData) > 0){
            $session->write(POSTDATA,$postData);
        }
        $postData = $session->read(POSTDATA);
        #set searching conditions
        if(isset($postData['type']) && !empty($postData['type'])){
            $cond['type'] =  array('type' => $postData['type']);
        }
        $conditions[CONDITIONS] = array('user_id' => $userID);
        $conditions[ORDER] =  array(ID => DESC);
        $conditions[LIMIT] =  PAGE_LIMIT;

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
        $all_history = $this->paginate($walletTable);
        $this->set(compact('all_history'));
        $this->set('users',$existUser);
        # Pass all data to render for display...
        $this->render('walletHistoryFilter');
    }
	#edit Static Content
    public function addUser(){
        #check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
        if($this->request->is(['post','put'])){
            #get request data
            $postData = array_map('trim',preg_replace('/\s+/', ' ', $this->request->getData()));
            $table = TableRegistry::get(USERS);
            $getErrors = $table->newEntity($postData,[VALIDATE => 'add']);
            if(!$getErrors->getErrors() && filter_var(strtolower($postData[EMAIL]),FILTER_VALIDATE_EMAIL)){
                try{
					$existUnique = $table->find()->select('unique_id')->where(['unique_id !=' => ''])->order(['id' => 'desc'])->first();
					$uniqueId = 'BRJ100001';
					if(isset($existUnique['unique_id']) && $existUnique['unique_id'] != ''){
						$explodeUnique = explode('J',$existUnique['unique_id']);
						if(isset($explodeUnique[1])){
							$uniqcount = $explodeUnique[1]+1;
							$uniqueId = 'BRJ'.$uniqcount;
						}else{
							$uniqueId = 'BRJ100001';
						}
					}
					if(isset($uniqueId)){
						$saveData['unique_id'] = $uniqueId;	
					}
					if(isset($postData[STATUS])){
						$saveData[STATUS] = $postData[STATUS];
					}
					$saveData[NAME] = ucwords($postData[NAME]);
					$explodeuser = explode('@',$postData['email']);
					if(isset($explodeuser[0])){
						$username = $explodeuser[0];
					}
					$username = $username.mt_rand(111,999);
					if(isset($username)){
						$saveData['username'] = $this->encryptData($username);
					}					
					$explodename = explode(' ',$saveData[NAME]);
					if(isset($explodename[0])){
						$saveData['first_name'] = $explodename[0];		
					}
					if(isset($explodename[1])){
						$saveData['last_name'] = $explodename[1];		
					}
					$saveData[EMAIL] = $this->encryptData(strtolower($postData[EMAIL]));
                    $saveData[USER_TOKEN] = str_shuffle(md5(time()));
                    $saveData[OTP] = rand(1001,9999);
                    $saveData['phone'] = $postData['phone'];
                    if(isset($postData[PROFILE]) && !empty($postData[PROFILE])){
                        $saveData[PROFILE] = $postData[PROFILE];
                    }
					$saveData[PASSWORD] = $this->encryptData($postData[PASSWORD]);
					$saveData[CREATED] = time();
					$saveData[MODIFIED] = time();
                    $tableEntity = $table->newEntity($saveData);
                    $record = $table->save($tableEntity);
                    $this->Flash->set('User has been created successfully.', array(ELEMENT => ALERT_SUCCESS));
                    $this->redirect(ADMIN_FOLDER.'user-management'.'/');
                }catch( \Exception $e){
                    $this->Flash->set(INTERNAL_ERROR, array(ELEMENT => ALERT_ERROR));
                    $this->redirect(ADMIN_FOLDER.'add-user'.'/');
                }
            }else{
                $error = $getErrors->getErrors();
                $this->setErrorMessage($error);
                $this->redirect(ADMIN_FOLDER.'add-user'.'/');
            }
        }
    }

	#edit Country
    public function editUser($editID = NULL){
        #check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
        if($this->request->is(['post','put'])){
            #get request data
            $postData = $this->request->getData();
            $table = TableRegistry::get(USERS);
            $getErrors = $table->newEntity($postData,[VALIDATE => 'update']);
            $editID = base64_encode($postData[EDIT_TOKEN]);			
            if(!$getErrors->getErrors() && filter_var(strtolower($postData['email']),FILTER_VALIDATE_EMAIL)){
                try{
                    $saveData[ID] = $this->decryptData($postData[EDIT_TOKEN]);
					$saveData[NAME] = ucwords($postData[NAME]);
					$explodename = explode(' ',$saveData[NAME]);
					if(isset($explodename[0])){
						$saveData['first_name'] = $explodename[0];		
					}
					if(isset($explodename[1])){
						$saveData['last_name'] = $explodename[1];		
					}
					$saveData['phone'] = ucwords($postData['phone']);
					$saveData[EMAIL] = $this->encryptData(strtolower($postData[EMAIL]));
					$saveData[PASSWORD] = $this->encryptData($postData[PASSWORD]);
					if(!empty($postData[PROFILE]) && isset($postData[PROFILE])){
                        $saveData[PROFILE] = $postData[PROFILE];
                    }
                    if(!empty($postData['address']) && isset($postData['address'])){
                        $saveData['address'] = $postData['address'];
                    }
                    if(!empty($postData['state_id']) && isset($postData['state_id'])){
                        $saveData['state_id'] = $postData['state_id'];
                    }
                    if(!empty($postData['city_id']) && isset($postData['city_id'])){
                        $saveData['city_id'] = $postData['city_id'];
                    }
                    if(!empty($postData['pincode']) && isset($postData['pincode'])){
                        $saveData['pincode'] = $postData['pincode'];
                    }
					if(isset($postData[STATUS])){
						$saveData[STATUS] = $postData[STATUS];
					}else{
						$saveData[STATUS] = 2;
					}
					$saveData[MODIFIED] = time();
                    $tableEntity = $table->newEntity($saveData);
                    $table->save($tableEntity);
                    $this->Flash->set('User information has been updated successfully.', array(ELEMENT => ALERT_SUCCESS));
                    $this->redirect(ADMIN_FOLDER.'edit-user'.'/'.$editID);
                }catch( \Exception $e){
                    $this->Flash->set(INTERNAL_ERROR, array(ELEMENT => ALERT_ERROR));
                    $this->redirect(ADMIN_FOLDER.'edit-user'.'/'.$editID);
                }
            }else{
                $error = $getErrors->getErrors();
                $this->setErrorMessage($error);
				$this->set('error',$error);
                $this->redirect(ADMIN_FOLDER.'edit-user'.'/'.$editID);
            }
        }
        if(!empty($editID)){
            #decrypt request ID
            $editID = $this->decryptData(base64_decode($editID));
            #get row data
            $table = TableRegistry::get(USERS);
            $editData = $table->find()->where(array(ID => $editID))->first();
            if(isset($editData->id) && !empty($editData->id)){
                $this->set(compact('editData'));
                $this->set('stateList',$this->getStateList());
            }else{
                $this->redirect(ADMIN_FOLDER.'edit-user'.'/');
            }
        }else{
            $this->redirect(ADMIN_FOLDER.'edit-user'.'/');
        }
    }

    public function transporters(){
        #check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
        #get content data
        $table = TableRegistry::get(USERS);
        # Conditions...
        $conditions = array();
        $conditions[CONDITIONS] = array(STATUS.' !=' => 3,'type'=>'Transporter');
        $conditions[ORDER] =  array('verify_status' => DESC);
        $conditions[LIMIT] =  PAGE_LIMIT;
        #delete post session data
        $session = $this->request->getSession();
        if($session->check(POSTDATA)){ $session->delete(POSTDATA);}
        #get record data
        $this->paginate = $conditions;
        $users = $this->paginate($table);
        $this->set(compact('users'));
    }

    /**************************** pagination page ****************************/
    public function transportersFilter(){
        #check User Auth
        $this->checkValidSession();
        $table = TableRegistry::get(USERS);
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
            $cond['phone'] =  array('phone'.' '.LIKE => '%'.trim($postData['phone']).'%');
        }
        if(isset($postData[EMAIL]) && !empty($postData[EMAIL])){
            $cond[EMAIL] =  array(EMAIL.' '.LIKE => '%'.trim($postData[EMAIL]).'%');
        }
        if(isset($postData[STATUS]) && !empty($postData[STATUS])){
            $cond[STATUS] =  array('verify_status' => $postData[STATUS]);
        }
        $conditions[CONDITIONS] = array(STATUS.' !=' => 3,'type'=>'Transporter');
        $conditions[LIMIT] =  PAGE_LIMIT;
        $conditions[ORDER] =  array('verify_status' => DESC);
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
        $users = $this->paginate($table);
        $this->set(compact('users'));
        # Pass all data to render for display...
        $this->render('transportersFilter');
    }

    #edit Static Content
    public function addTransporter(){
        #check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
        if($this->request->is(['post','put'])){
            #get request data
            $postData = array_map('trim',preg_replace('/\s+/', ' ', $this->request->getData()));
            $table = TableRegistry::get(USERS);
            //pr($postData); die;
            $getErrors = $table->newEntity($postData,[VALIDATE => 'add']);
            if(isset($postData[EMAIL]) && !empty($postData[EMAIL])){
                $existUser = $table->find()->where(array(EMAIL => $postData[EMAIL]))->all();
                if($existUser->count() > 0){
                    $this->Flash->set('Transporter email already exists.', array(ELEMENT => ALERT_ERROR));
                    $this->redirect(ADMIN_FOLDER.'add-transporter/');
                }
                if(!filter_var(strtolower($postData[EMAIL]),FILTER_VALIDATE_EMAIL)){
                    $this->Flash->set('Please enter valid email address.', array(ELEMENT => ALERT_ERROR));
                    $this->redirect(ADMIN_FOLDER.'add-transporter/');
                }
            }
            if($existUser->count() == 0 && filter_var(strtolower($postData[EMAIL]),FILTER_VALIDATE_EMAIL)){
                try{
                    if(isset($postData[STATUS])){
                        $saveData[STATUS] = $postData[STATUS];
                    }
                    $saveData[TYPE] = 'Transporter';
                    $saveData[NAME] = ucwords($postData[NAME]);
                    $saveData[EMAIL] = strtolower($postData[EMAIL]);
                    $saveData[USER_TOKEN] = str_shuffle(md5(time()));
                    $saveData[OTP] = rand(1001,9999);
                    $saveData['phone'] = $postData['phone'];
                    $saveData['vehicle_id'] = $postData['vehicle_id'];
                    $saveData['dl_no'] = strtoupper($postData['dl_no']);
                    $saveData['rc_no'] = strtoupper($postData['rc_no']);
                    if(isset($postData[PROFILE]) && !empty($postData[PROFILE])){
                        $saveData[PROFILE] = $postData[PROFILE];
                    }
                    $saveData[PASSWORD] = $this->encryptData($postData[PASSWORD]);
                    $saveData[CREATED] = time();
                    $saveData[MODIFIED] = time();
                    $tableEntity = $table->newEntity($saveData);
                    $record = $table->save($tableEntity);
                    $this->Flash->set('Transporter has been created successfully.', array(ELEMENT => ALERT_SUCCESS));
                    $this->redirect(ADMIN_FOLDER.'edit-transporter'.'/'.base64_encode($this->encryptData($record->id)));
                }catch( \Exception $e){
                    $this->Flash->set(INTERNAL_ERROR, array(ELEMENT => ALERT_ERROR));
                    $this->redirect(ADMIN_FOLDER.'add-transporter'.'/');
                }
            }else{
                $error = $getErrors->getErrors();
                $this->setErrorMessage($error);
                $this->redirect(ADMIN_FOLDER.'add-transporter'.'/');
            }
        }
        $this->set('vehicleTypeList',$this->getVehicleTypes());
    }

    #edit Country
    public function editTransporter($editID = NULL){
        #check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
        if($this->request->is(['post','put'])){
            #get request data
            $postData = $this->request->getData();
            $table = TableRegistry::get(USERS);
            //$getErrors = $table->newEntity($postData,[VALIDATE => 'update']);
            $editID = base64_encode($postData[EDIT_TOKEN]);
            $record = $table->find()->where(array('id' => $this->decryptData($postData[EDIT_TOKEN])))->first();
            if(isset($postData[EMAIL]) && !empty($postData[EMAIL])){                
                $existCountryName = $table->find()->where(array(EMAIL => strtolower($postData[EMAIL]),'id !=' => $this->decryptData($postData[EDIT_TOKEN])))->all();
                if($existCountryName->count() > 0){
                    if($existCountryName->count() > 0){
                        $this->Flash->set('Email address already exists.', array(ELEMENT => ALERT_ERROR));
                    }
                    $this->redirect(ADMIN_FOLDER.'edit-transporter'.'/'.$editID);
                }
                if(!filter_var(strtolower($postData[EMAIL]),FILTER_VALIDATE_EMAIL)){
                    $this->Flash->set('Please enter valid email address.', array(ELEMENT => ALERT_ERROR));
                    $this->redirect(ADMIN_FOLDER.'edit-transporter'.'/'.$editID);
                }
            }
            if($existCountryName->count() == 0 && filter_var(strtolower($postData['email']),FILTER_VALIDATE_EMAIL)){
                try{
                    $saveData[ID] = $this->decryptData($postData[EDIT_TOKEN]);
                    $saveData[NAME] = ucwords($postData[NAME]);
                    $saveData['phone'] = ucwords($postData['phone']);
                    $saveData[EMAIL] = strtolower($postData[EMAIL]);
                    $saveData['vehicle_id'] = $postData['vehicle_id'];
                    $saveData['dl_no'] = strtoupper($postData['dl_no']);
                    $saveData['rc_no'] = strtoupper($postData['rc_no']);
                    $saveData[PASSWORD] = $this->encryptData($postData[PASSWORD]);
                    if(isset($_FILES['rc_image']['name']) && !empty($_FILES['rc_image']['name']) && $postData['rc_no']){
                         $saveData['verify_status'] = 2;
                    }
                    if(!empty($postData[PROFILE]) && isset($postData[PROFILE])){
                        $saveData[PROFILE] = $postData[PROFILE];
                    }
                    if(!empty($postData['address']) && isset($postData['address'])){
                        $saveData['address'] = $postData['address'];
                    }
                    if(!empty($postData['state_id']) && isset($postData['state_id'])){
                        $saveData['state_id'] = $postData['state_id'];
                    }
                    if(!empty($postData['city_id']) && isset($postData['city_id'])){
                        $saveData['city_id'] = $postData['city_id'];
                    }
                    if(!empty($postData['pincode']) && isset($postData['pincode'])){
                        $saveData['pincode'] = $postData['pincode'];
                    }

                    if(isset($_FILES['dl_image']['name']) && !empty($_FILES['dl_image']['name'])){
                        $target_dir = WWW_ROOT.'img/users/dl/';
                        if(!empty($record['dl_image']) && file_exists($target_dir.$record['dl_image'])){
                            unlink(WWW_ROOT.'img/users/dl/'.$record['dl_image']);
                        }
                        $dl_image_file = $_FILES['dl_image']['name'];
                        $ext = pathinfo($_FILES["dl_image"]["name"], PATHINFO_EXTENSION);
                        $newImage = time().rand(11,99).'.'.$ext;
                        move_uploaded_file($_FILES["dl_image"]["tmp_name"], $target_dir.$newImage);
                        $saveData['dl_image'] = $newImage;
                    }
                    if(isset($_FILES['rc_image']['name']) && !empty($_FILES['rc_image']['name'])){
                        $target_dir = WWW_ROOT.'img/users/rc/';
                        if(!empty($record['rc_image']) && file_exists($target_dir.$record['rc_image'])){
                            unlink(WWW_ROOT.'img/users/rc/'.$record['rc_image']);
                        }
                        $dl_image_file = $_FILES['rc_image']['name'];
                        $ext = pathinfo($_FILES["rc_image"]["name"], PATHINFO_EXTENSION);
                        $newImage = time().rand(11,99).'.'.$ext;
                        move_uploaded_file($_FILES["rc_image"]["tmp_name"], $target_dir.$newImage);
                        $saveData['rc_image'] = $newImage;
                    }
                    if(isset($_FILES['fitness_image']['name']) && !empty($_FILES['fitness_image']['name'])){
                        $target_dir = WWW_ROOT.'img/users/fitness/';
                       if(!empty($record['fitness_image']) && file_exists($target_dir.$record['fitness_image'])){
                             unlink(WWW_ROOT.'img/users/fitness/'.$record['fitness_image']);
                        }
                        $dl_image_file = $_FILES['fitness_image']['name'];
                        $ext = pathinfo($_FILES["fitness_image"]["name"], PATHINFO_EXTENSION);
                        $newImage = time().rand(11,99).'.'.$ext;
                        move_uploaded_file($_FILES["fitness_image"]["tmp_name"], $target_dir.$newImage);
                        $saveData['fitness_image'] = $newImage;
                    }

                    if(isset($postData[STATUS])){
                        $saveData[STATUS] = $postData[STATUS];
                    }else{
                        $saveData[STATUS] = 2;
                    }
                    $saveData[MODIFIED] = time();
                    $tableEntity = $table->newEntity($saveData);
                    $table->save($tableEntity);
                    $this->Flash->set('Transporter information has been updated successfully.', array(ELEMENT => ALERT_SUCCESS));
                    $this->redirect(ADMIN_FOLDER.'edit-transporter'.'/'.$editID);
                }catch( \Exception $e){
                    $this->Flash->set(INTERNAL_ERROR, array(ELEMENT => ALERT_ERROR));
                    $this->redirect(ADMIN_FOLDER.'edit-transporter'.'/'.$editID);
                }
            }else{
                $error = $getErrors->getErrors();
                $this->setErrorMessage($error);
                $this->set('error',$error);
                $this->redirect(ADMIN_FOLDER.'edit-transporter'.'/'.$editID);
            }
        }
        if(!empty($editID)){
            #decrypt request ID
            $editID = $this->decryptData(base64_decode($editID));
            #get row data
            $table = TableRegistry::get(USERS);
            $editData = $table->find()->where(array(ID => $editID))->first();
            if(isset($editData->id) && !empty($editData->id)){
                $this->set(compact('editData'));
                $this->set('vehicleTypeList',$this->getVehicleTypes());
                $this->set('stateList',$this->getStateList());
            }else{
                $this->redirect(ADMIN_FOLDER.'edit-transporter'.'/');
            }
        }else{
            $this->redirect(ADMIN_FOLDER.'edit-transporter'.'/');
        }
    }
    #setErrorMessage
    function setErrorMessage($error){		
		if(isset($error['phone']['uniquePhone']) && !empty($error['phone']['uniquePhone'])){
            $this->Flash->set($error['phone']['uniquePhone'], array(ELEMENT => ALERT_ERROR));
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