<?php
namespace App\Controller;
use Cake\Core\Configure;
use Cake\Network\Exception\ForbiddenException;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\ORM\TableRegistry;
use Cake\Http\Response;
use Cake\Core\Exception\Exception;

class AgentsManagementController extends AppController{

    #countries page
    public function agents(){
        #check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
        #get content data
        $table = TableRegistry::get(USERS);
        # Conditions...
		$conditions = array();
		$conditions[CONDITIONS] = array(STATUS.' !=' => 3,'type'=>'Sub-Admin');
		$conditions[ORDER] =  array('id' => DESC);
		$conditions[LIMIT] =  PAGE_LIMIT;
        #delete post session data
        $session = $this->request->getSession();
		if($session->check(POSTDATA)){ $session->delete(POSTDATA);}
        #get record data
		$this->paginate = $conditions;
		$agents = $this->paginate($table);
        $this->set(compact('agents'));
    }

	/**************************** pagination page ****************************/
	public function agentsFilter(){
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
        if(isset($postData[FIRST_NAME]) && !empty($postData[FIRST_NAME])){
			$cond[FIRST_NAME] =  array(FIRST_NAME.' '.LIKE => '%'.trim($postData[FIRST_NAME]).'%');
		}
		if(isset($postData[LAST_NAME]) && !empty($postData[LAST_NAME])){
			$cond[LAST_NAME] =  array(LAST_NAME.' '.LIKE => '%'.trim($postData[LAST_NAME]).'%');
		}
		if(isset($postData[EMAIL]) && !empty($postData[EMAIL])){
			$cond[EMAIL] =  array(EMAIL.' '.LIKE => '%'.$this->encryptData(trim($postData[EMAIL])).'%');
		}
		if(isset($postData['username']) && !empty($postData['username'])){
			$cond['username'] =  array('username'.' '.LIKE => '%'.$this->encryptData(trim($postData['username'])).'%');
		}
		if(isset($postData[STATUS]) && !empty($postData[STATUS])){
			$cond[STATUS] =  array(STATUS => $postData[STATUS]);
		}
		$conditions[CONDITIONS] = array(STATUS.' !=' => 3,'type'=>'Sub-Admin');
		$conditions[LIMIT] =  PAGE_LIMIT;
		$conditions[ORDER] =  array('id' => DESC);
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
		$agents = $this->paginate($table);
        $this->set(compact('agents'));
		# Pass all data to render for display...
		$this->render('agentsFilter');
	}

	#edit Static Content
    public function addAgent(){
        #check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
        if($this->request->is(['post','put'])){
            #get request data
            $postData = $this->request->getData();
            $table = TableRegistry::get(USERS);			
			if(isset($postData[EMAIL]) && !empty($postData[EMAIL])){
				$existEmail = $table->find()->where(array('email' => $this->encryptData(trim($postData[EMAIL]))))->all();
				if($existEmail->count() > 0){
					if($existEmail->count() > 0){
						$this->Flash->set('Email already exists.', array(ELEMENT => ALERT_ERROR));
					}
					$this->redirect(ADMIN_FOLDER.'add-agent/');
				}
			}
            if(filter_var(strtolower($postData[EMAIL]),FILTER_VALIDATE_EMAIL) && $existEmail->count() == 0){
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
					$explodeuser = explode('@',$postData['email']);
					if(isset($explodeuser[0])){
						$username = $explodeuser[0];
					}					
					if(isset($username)){
						$username = $username.mt_rand(111,999);
						$saveData['username'] = $this->encryptData($username);
					}					
                    $saveData[FIRST_NAME] = ucwords($postData[FIRST_NAME]);
					$saveData[LAST_NAME] = ucwords($postData[LAST_NAME]);
					$saveData['name'] = $saveData[FIRST_NAME].' '.$saveData[LAST_NAME];
                    $saveData[EMAIL] = $this->encryptData(strtolower($postData[EMAIL]));
					$saveData['type'] = 'Sub-Admin';
					$saveData[USER_TOKEN] = str_shuffle(md5(time()));
					$saveData[PASSWORD] = $this->encryptData($postData[PASSWORD]);
					if(isset($postData[STATUS])){
						$saveData[STATUS] = $postData[STATUS];
					}
					$saveData[CREATED] = time();
					$saveData[MODIFIED] = time();
					$tableEntity = $table->newEntity(array_map('trim',preg_replace('/\s+/', ' ', $saveData)));
                    $record = $table->save($tableEntity);
					###### send email #######
					$agentData = $saveData;
					$sendEmailTo = 'User';
					$sendEmail = array(
						'to'=> $saveData[EMAIL], 
						'userData' => $postData,
						'template_id' => 3,
						'template' => 'agent_registration',
						'sendEmailTo' => $sendEmailTo
					);
					$this->SendEmails->sendEmail($sendEmail);
					###########################
					$this->Flash->set('Sub Admin has been created successfully.', array(ELEMENT => ALERT_SUCCESS));
					$this->redirect(ADMIN_FOLDER.'agent-management'.'/');
															
                }catch( \Exception $e){
                    $this->Flash->set(INTERNAL_ERROR, array(ELEMENT => ALERT_ERROR));
                    $this->redirect(ADMIN_FOLDER.'add-agent'.'/');
                }
            }else{
                $this->redirect(ADMIN_FOLDER.'add-agent'.'/');
            }
        }
    }

	#edit Country
    public function editAgent($editID = NULL){
        #check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
        if($this->request->is(['post','put'])){
            #get request data
            $postData = $this->request->getData();
            $table = TableRegistry::get(USERS);
            $editID = base64_encode($postData[EDIT_TOKEN]);
			if(isset($postData[EMAIL]) && !empty($postData[EMAIL])){
				$existEmail = $table->find()->where(array('email' => $this->encryptData(trim($postData[EMAIL])),ID.' !=' => $this->decryptData($postData[EDIT_TOKEN])))->all();
				if($existEmail->count() > 0){
					if($existEmail->count() > 0){
						$this->Flash->set('Email already exists.', array(ELEMENT => ALERT_ERROR));
					}
					$this->redirect(ADMIN_FOLDER.'edit-agent'.'/'.$editID);
				}
			}
            if(filter_var(strtolower($postData[EMAIL]),FILTER_VALIDATE_EMAIL) && $existEmail->count() == 0){
                try{
					$saveData[ID] = $this->decryptData($postData[EDIT_TOKEN]);
                    $saveData[FIRST_NAME] = ucwords($postData[FIRST_NAME]);
					$saveData[LAST_NAME] = ucwords($postData[LAST_NAME]);
					$saveData['name'] = $saveData[FIRST_NAME].' '.$saveData[LAST_NAME];
                    $saveData[EMAIL] = $this->encryptData(strtolower($postData[EMAIL]));
					$saveData[PASSWORD] = $this->encryptData($postData[PASSWORD]);
					if(isset($postData[STATUS])){
						$saveData[STATUS] = $postData[STATUS];
					}else{
						$saveData[STATUS] = 2;
					}
					$saveData[MODIFIED] = time();
                    $tableEntity = $table->newEntity(array_map('trim',preg_replace('/\s+/', ' ', $saveData)));
                    $table->save($tableEntity);
                    $this->Flash->set('Sub Admin information has been updated successfully.', array(ELEMENT => ALERT_SUCCESS));
                    $this->redirect(ADMIN_FOLDER.'edit-agent'.'/'.$editID);
                }catch( \Exception $e){
                    $this->Flash->set(INTERNAL_ERROR, array(ELEMENT => ALERT_ERROR));
                    $this->redirect(ADMIN_FOLDER.'edit-agent'.'/'.$editID);
                }
            }else{
                $this->redirect(ADMIN_FOLDER.'edit-agent'.'/'.$editID);
            }
        }
        if(!empty($editID)){
            #decrypt request ID
            $editID = $this->decryptData(base64_decode($editID));
            #get row data
            $table = TableRegistry::get(USERS);
            $editData = $table->find()->where(array(ID => $editID))->first();
            if(isset($editData->id) && !empty($editData->id)){
                $this->set(compact(EDITDATA));
            }else{
                $this->redirect(ADMIN_FOLDER.'edit-agent'.'/');
            }
        }else{
            $this->redirect(ADMIN_FOLDER.'edit-agent'.'/');
        }
    }
	
	public function permissions($view){
		#check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
		$permissionTbl = TableRegistry::get('UserPermissions');
		if($this->request->is(['post','put'])){
			$postData = $this->request->getData();			
			if(!empty($postData['administrators_id'])){
				$administrators_id = $this->decryptData(base64_decode($postData['administrators_id']));
				foreach($postData['data']['list'] as $key => $value){
					if(isset($postData['data']['permission']) && !empty($postData['data']['permission'][$key])){
						$saveData['id'] = trim($postData['data']['permission'][$key]);	
					}
					$saveData['administrators_id'] = trim($administrators_id);
					$saveData['name'] = trim($key);
					$saveData['list'] = trim($postData['data']['list'][$key]);
					$saveData['addon'] = trim($postData['data']['add'][$key]);
					$saveData['edit'] = trim($postData['data']['edit'][$key]);
					$saveData['view'] = trim($postData['data']['view'][$key]);
					$saveData['remove'] = trim($postData['data']['delete'][$key]);
					
					$tableEntity = $permissionTbl->newEntity($saveData);
                    $permissionTbl->save($tableEntity);					
				}
				$this->Flash->set('User permissions updated successfully.', array(ELEMENT => ALERT_SUCCESS));
				$this->redirect(ADMIN_FOLDER.'permissions'.'/'.$postData['administrators_id']);	
			}			
		}
		
		if(!empty($view)){
            #decrypt request ID
            $view = $this->decryptData(base64_decode($view));
            #get row data
            $table = TableRegistry::get(USERS);
            $editData = $table->find()->where(array(ID => $view))->first();
            if(isset($editData->id) && !empty($editData->id)){
				$tables = array('Customers','Contact-Us','Category','Products','Sales-Manager','Orders');
				$administrators_id = $editData->id;
				$this->set(compact('tables','administrators_id'));
            }else{
                $this->redirect(ADMIN_FOLDER.'agent-management'.'/');
            }
        }else{
            $this->redirect(ADMIN_FOLDER.'agent-management'.'/');
        }		
	}
	
	#countries page
    public function testimonials(){
        #check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
        #get content data
        $table = TableRegistry::get('Testimonials');
        # Conditions...
		$conditions = array();
		$conditions[CONDITIONS] = array(STATUS.' !=' => 3);
		$conditions[ORDER] =  array('id' => ASC);
		$conditions[LIMIT] =  PAGE_LIMIT;
        #delete post session data
        $session = $this->request->getSession();
		if($session->check(POSTDATA)){ $session->delete(POSTDATA);}
        #get record data
		$this->paginate = $conditions;
		$testimonials = $this->paginate($table);
        $this->set(compact('testimonials'));
    }

	/**************************** pagination page ****************************/
	public function testimonialsFilter(){
        #check User Auth
        $this->checkValidSession();
		$table = TableRegistry::get('Testimonials');
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
		if(isset($postData[STATUS]) && !empty($postData[STATUS])){
			$cond[STATUS] =  array(STATUS => $postData[STATUS]);
		}
		$conditions[CONDITIONS] = array(STATUS.' !=' => 3);
		$conditions[LIMIT] =  PAGE_LIMIT;
		$conditions[ORDER] =  array('id' => DESC);
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
		$testimonials = $this->paginate($table);
        $this->set(compact('testimonials'));
		# Pass all data to render for display...
		$this->render('testimonialFilter');
	}

	#edit Static Content
    public function addTestimonial(){
        #check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
        if($this->request->is(['post','put'])){
            #get request data
            $postData = $this->request->getData();
            $table = TableRegistry::get('Testimonials');
            $getErrors = $table->newEntity($postData,[VALIDATE => 'addtestimonial']);
            if(!$getErrors->getErrors()){
                try{					
					$saveData['username'] = trim($postData['username']);
					$saveData[PROFILE] = $postData[PROFILE];
					$saveData['testimonial'] = trim($postData['testimonial']);
					if(isset($postData[STATUS])){
						$saveData[STATUS] = $postData[STATUS];
					}
					$saveData[CREATED] = time();
					$saveData[MODIFIED] = time();
					$tableEntity = $table->newEntity($saveData);
                    $record = $table->save($tableEntity);
                    $this->Flash->set('Testimonial has been created successfully.', array(ELEMENT => ALERT_SUCCESS));
					$this->redirect(ADMIN_FOLDER.'testimonials'.'/');
                }catch( \Exception $e){
                    $this->Flash->set(INTERNAL_ERROR, array(ELEMENT => ALERT_ERROR));
                    $this->redirect(ADMIN_FOLDER.'add-testimonial'.'/');
                }
            }else{
                $error = $getErrors->getErrors();
                $this->setErrorMessage($error);
                $this->redirect(ADMIN_FOLDER.'add-testimonial'.'/');
            }
        }
    }
	
	#edit Country
    public function editTestimonial($editID = NULL){
        #check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
        if($this->request->is(['post','put'])){
            #get request data
            $postData = $this->request->getData();
            $table = TableRegistry::get('Testimonials');
            $getErrors = $table->newEntity($postData,[VALIDATE => 'addtestimonial']);
            $editID = base64_encode($postData[EDIT_TOKEN]);
            if(!$getErrors->getErrors()){
                try{
					$saveData[ID] = $this->decryptData($postData[EDIT_TOKEN]);
                    $saveData['username'] = trim($postData['username']);
					$saveData['testimonial'] = trim($postData['testimonial']);
					if(!empty($postData[PROFILE])){
						$saveData[PROFILE] = $postData[PROFILE];
					}else{
						$saveData[PROFILE] = $postData['old_image'];
					}
					if(isset($postData[STATUS])){
						$saveData[STATUS] = $postData[STATUS];
					}else{
						$saveData[STATUS] = 2;
					}
					$saveData[MODIFIED] = time();
                    $tableEntity = $table->newEntity($saveData);
                    $table->save($tableEntity);
                    $this->Flash->set('Testimonial information has been updated successfully.', array(ELEMENT => ALERT_SUCCESS));
                    $this->redirect(ADMIN_FOLDER.'edit-testimonial'.'/'.$editID);
                }catch( \Exception $e){
                    $this->Flash->set(INTERNAL_ERROR, array(ELEMENT => ALERT_ERROR));
                    $this->redirect(ADMIN_FOLDER.'edit-testimonial'.'/'.$editID);
                }
            }else{
                $error = $getErrors->getErrors();
                $this->setErrorMessage($error);
				$this->set(ALERT_ERROR,$error);
                $this->redirect(ADMIN_FOLDER.'edit-testimonial'.'/'.$editID);
            }
        }
        if(!empty($editID)){
            #decrypt request ID
            $editID = $this->decryptData(base64_decode($editID));
            #get row data
            $table = TableRegistry::get('Testimonials');
            $editData = $table->find()->where(array(ID => $editID))->first();
            if(isset($editData->id) && !empty($editData->id)){
                $this->set(compact(EDITDATA));
            }else{
                $this->redirect(ADMIN_FOLDER.'edit-testimonial'.'/');
            }
        }else{
            $this->redirect(ADMIN_FOLDER.'edit-testimonial'.'/');
        }
    }
	
	public function viewTestimonial($viewId=NULL){
		#check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
		$viewId = $this->decryptData(base64_decode($viewId));
		$table = TableRegistry::get('Testimonials');
		$viewData = $table->find()->where(array(ID => $viewId))->first();
		if(isset($viewData->id) && !empty($viewData->id)){
			$this->set(compact('viewData'));
		}else{
			$this->redirect(ADMIN_FOLDER.'testimonials/');
		}
	}
	
    #setErrorMessage
    function setErrorMessage($error){
        if(isset($error[FIRST_NAME][CHECK_EMPTY]) && !empty($error[FIRST_NAME][CHECK_EMPTY])){
            $this->Flash->set($error[FIRST_NAME][CHECK_EMPTY], array(ELEMENT => ALERT_ERROR));
        }
		if(isset($error[LAST_NAME][CHECK_EMPTY]) && !empty($error[LAST_NAME][CHECK_EMPTY])){
            $this->Flash->set($error[LAST_NAME][CHECK_EMPTY], array(ELEMENT => ALERT_ERROR));
        }
        if(isset($error[EMAIL][CHECK_EMPTY]) && !empty($error[EMAIL][CHECK_EMPTY])){
            $this->Flash->set($error[EMAIL][CHECK_EMPTY], array(ELEMENT => ALERT_ERROR));
        }
		if(isset($error[EMAIL]['checkUniqueEmail']) && !empty($error[EMAIL]['checkUniqueEmail'])){
            $this->Flash->set($error[EMAIL]['checkUniqueEmail'], array(ELEMENT => ALERT_ERROR));
        }
		if(isset($error[EMAIL]['checkUniqueEmailUpdate']) && !empty($error[EMAIL]['checkUniqueEmailUpdate'])){
            $this->Flash->set($error[EMAIL]['checkUniqueEmailUpdate'], array(ELEMENT => ALERT_ERROR));
        }
		if(isset($error[PASSWORD][CHECK_EMPTY]) && !empty($error[PASSWORD][CHECK_EMPTY])){
            $this->Flash->set($error[PASSWORD][CHECK_EMPTY], array(ELEMENT => ALERT_ERROR));
        }
		if(isset($error[CONTACT][CHECK_EMPTY]) && !empty($error[CONTACT][CHECK_EMPTY])){
            $this->Flash->set($error[CONTACT][CHECK_EMPTY], array(ELEMENT => ALERT_ERROR));
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

}
?>