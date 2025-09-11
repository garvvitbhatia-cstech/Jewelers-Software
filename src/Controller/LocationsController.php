<?php
namespace App\Controller;
use Cake\Core\Configure;
use Cake\Network\Exception\ForbiddenException;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\ORM\TableRegistry;
use Cake\Http\Response;
use Cake\Core\Exception\Exception;

class LocationsController extends AppController{

    #countries page
    public function countries(){
        #check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
        #get content data
        $table = TableRegistry::get(COUNTRIES);
        # Conditions...
		$conditions = array();
		$conditions[CONDITIONS] = array(STATUS.' !=' => 3);
		$conditions[ORDER] =  array(ORDERING => ASC);
		$conditions[LIMIT] =  PAGE_LIMIT;
        #delete post session data
        $session = $this->request->getSession();
		if($session->check(POSTDATA)){ $session->delete(POSTDATA);}
        #get record data
		$this->paginate = $conditions;
		$countries = $this->paginate($table);
        $this->set(compact('countries'));
    }

	/**************************** pagination page ****************************/
	public function countriesFilter(){
        #check User Auth
        $this->checkValidSession();
		$table = TableRegistry::get(COUNTRIES);
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
        if(isset($postData[COUNTRYNAME]) && !empty($postData[COUNTRYNAME])){
			$cond[COUNTRYNAME] =  array(COUNTRYNAME.' '.LIKE => '%'.trim($postData[COUNTRYNAME]).'%');
		}
		if(isset($postData[COUNTRYCODE]) && !empty($postData[COUNTRYCODE])){
			$cond[COUNTRYCODE] =  array(COUNTRYCODE.' '.LIKE => '%'.trim($postData[COUNTRYCODE]).'%');
		}
		if(isset($postData[STATUS]) && !empty($postData[STATUS])){
			$cond[STATUS] =  array(STATUS => $postData[STATUS]);
		}
		$conditions[CONDITIONS] = array(STATUS.' !=' => 3);
		$conditions[LIMIT] =  PAGE_LIMIT;
		$conditions[ORDER] =  array(ORDERING => ASC);
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
		$countries = $this->paginate($table);
        $this->set(compact('countries'));
		# Pass all data to render for display...
		$this->render('countriesFilter');
	}

	#edit Static Content
    public function addCountry(){
        #check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
        if($this->request->is(['post','put'])){
            #get request data
            $postData = $this->request->getData();
            $table = TableRegistry::get(COUNTRIES);
            $getErrors = $table->newEntity($postData,[VALIDATE => 'add']);
			$zipCodeFilterData = array_filter($postData[ZIPCODEFORMAT]);
			if(empty($zipCodeFilterData)){
				$this->Flash->set('Please enter zipcode format.', array(ELEMENT => ALERT_ERROR));
				$this->redirect(ADMIN_FOLDER.'edit-country'.'/'.$editID);
			}
            if(!$getErrors->getErrors() && !empty($zipCodeFilterData)){
                try{
					$query = $table->find();
					$findOrder= $query->select(array('max_order' => $query->func()->max(ORDERING)))->first();
					if($findOrder->max_order == ''){
						$saveData[ORDERING] = 1;
					}else{
						$saveData[ORDERING] = ($findOrder->max_order+1);
					}
                    $saveData[COUNTRYNAME] = ucwords($postData[COUNTRYNAME]);
                    $saveData[COUNTRYCODE] = strtoupper($postData[COUNTRYCODE]);
					$saveData[PHONENOFORMAT] = $postData[PHONENOFORMAT];
					$saveData[FLAG_IMAGE] = $postData[COUNTRYFLAG];
					if(!empty($postData[ZIPCODEFORMAT])){
						$saveData[ZIPCODEFORMAT] = implode(',',$zipCodeFilterData);
					}
					if(isset($postData[STATUS])){
						$saveData[STATUS] = $postData[STATUS];
					}
					$saveData[CREATED] = time();
					$saveData[MODIFIED] = time();
					$tableEntity = $table->newEntity(array_map('trim',preg_replace('/\s+/', ' ', $saveData)));
                    $record = $table->save($tableEntity);
                    $this->Flash->set('Country has been created successfully.', array(ELEMENT => ALERT_SUCCESS));
                    $this->redirect(ADMIN_FOLDER.'edit-country'.'/'.base64_encode($this->encryptData($record->id)));
                }catch( \Exception $e){
                    $this->Flash->set(INTERNAL_ERROR, array(ELEMENT => ALERT_ERROR));
                    $this->redirect(ADMIN_FOLDER.'add-country'.'/');
                }
            }else{
                $error = $getErrors->getErrors();
                $this->setErrorMessage($error);
                $this->redirect(ADMIN_FOLDER.'add-country'.'/');
            }
        }
    }

	#edit Country
    public function editCountry($editID = NULL){
        #check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
        if($this->request->is(['post','put'])){
            #get request data
            $postData = $this->request->getData();
            $table = TableRegistry::get(COUNTRIES);
            $getErrors = $table->newEntity($postData,[VALIDATE => UPDATE]);
            $editID = base64_encode($postData[EDIT_TOKEN]);
			$zipCodeFilterData = array_filter($postData['zipcode_format']);
			if(isset($postData[COUNTRYNAME]) && !empty($postData[COUNTRYNAME]) && isset($postData[COUNTRYCODE]) && !empty($postData[COUNTRYCODE])){
				$existCountryName = $table->find()->where(array(COUNTRYNAME => ucwords(trim($postData[COUNTRYNAME])),ID.' !=' => $this->decryptData($postData[EDIT_TOKEN])))->all();
				$existCountryCode = $table->find()->where(array(COUNTRYCODE => strtoupper(trim($postData[COUNTRYCODE])),ID.' !=' => $this->decryptData($postData[EDIT_TOKEN])))->all();
				if($existCountryName->count() > 0 || $existCountryCode->count() > 0){
					if($existCountryName->count() > 0){
						$this->Flash->set('Country name already exists.', array(ELEMENT => ALERT_ERROR));
					}
					if($existCountryCode->count() > 0){
						$this->Flash->set('Country code already exists.', array(ELEMENT => ALERT_ERROR));
					}
					$this->redirect(ADMIN_FOLDER.'edit-country'.'/'.$editID);
				}
			}
			if(empty($zipCodeFilterData)){
				$this->Flash->set('Please enter zipcode format.', array(ELEMENT => ALERT_ERROR));
				$this->redirect(ADMIN_FOLDER.'edit-country'.'/'.$editID);
			}
            if(!$getErrors->getErrors() && $existCountryName->count() == 0 && $existCountryCode->count() == 0 && !empty($zipCodeFilterData)){
                try{
                    $saveData[ID] = $this->decryptData($postData[EDIT_TOKEN]);
                    $saveData[COUNTRYNAME] = ucwords($postData[COUNTRYNAME]);
                    $saveData[COUNTRYCODE] = strtoupper($postData[COUNTRYCODE]);
					if(!empty($postData['country_flag'])){
						$saveData[FLAG_IMAGE] = $postData['country_flag'];
					}else{
						$saveData[FLAG_IMAGE] = $postData['old_image'];
					}
					$saveData[PHONENOFORMAT] = $postData[PHONENOFORMAT];
					if(!empty($postData[ZIPCODEFORMAT])){
						$saveData[ZIPCODEFORMAT] = implode(',',$zipCodeFilterData);
					}
					if(isset($postData[STATUS])){
						$saveData[STATUS] = $postData[STATUS];
					}else{
						$saveData[STATUS] = 2;
					}
					$saveData[MODIFIED] = time();
                    $tableEntity = $table->newEntity(array_map('trim',preg_replace('/\s+/', ' ', $saveData)));
                    $table->save($tableEntity);
                    $this->Flash->set('Country information has been updated successfully.', array(ELEMENT => ALERT_SUCCESS));
                    $this->redirect(ADMIN_FOLDER.'edit-country'.'/'.$editID);
                }catch( \Exception $e){
                    $this->Flash->set(INTERNAL_ERROR, array(ELEMENT => ALERT_ERROR));
                    $this->redirect(ADMIN_FOLDER.'edit-country'.'/'.$editID);
                }
            }else{
                $error = $getErrors->getErrors();
                $this->setErrorMessage($error);
				$this->set(ALERT_ERROR,$error);
                $this->redirect(ADMIN_FOLDER.'edit-country'.'/'.$editID);
            }
        }
        if(!empty($editID)){
            #decrypt request ID
            $editID = $this->decryptData(base64_decode($editID));
            #get row data
            $table = TableRegistry::get(COUNTRIES);
            $editData = $table->find()->where(array(ID => $editID))->first();
            if(isset($editData->id) && !empty($editData->id)){
                $this->set(compact(EDITDATA));
            }else{
                $this->redirect(ADMIN_FOLDER.'edit-country'.'/');
            }
        }else{
            $this->redirect(ADMIN_FOLDER.'edit-country'.'/');
        }
    }

	#states page
    public function states(){
        #check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
        #get content data
        $table = TableRegistry::get(STATES);
        # Conditions...
		$conditions = array();
		$conditions[CONDITIONS] = array(STATES.'.'.STATUS.' !=' => 3);
		$conditions[ORDER] =  array(COUNTRY_ID => ASC);
		$conditions[LIMIT] =  PAGE_LIMIT;
        #delete post session data
        $session = $this->request->getSession();
		if($session->check(POSTDATA)){ $session->delete(POSTDATA);}
        #get record data
		$this->paginate = $conditions;
		$query = $table->find('all')->contain([COUNTRIES]);
		$states = $this->paginate($query);
        $this->set(compact('states'));
		$this->set(COUNTRYLIST,$this->getCountryList());
    }

	/**************************** pagination page ****************************/
	public function statesFilter(){
        #check User Auth
        $this->checkValidSession();
		$table = TableRegistry::get(STATES);
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
        if(isset($postData[COUNTRY_ID]) && !empty($postData[COUNTRY_ID])){
			$cond['States.country_id'] =  array(COUNTRY_ID => $postData[COUNTRY_ID]);
		}
		if(isset($postData[STATE]) && !empty($postData[STATE])){
			$cond['States.state'] =  array(STATE.' '.LIKE => '%'.trim($postData[STATE]).'%');
		}
		if(isset($postData[STATUS]) && !empty($postData[STATUS])){
			$cond['States.status'] =  array('States.status' => $postData[STATUS]);
		}
		$conditions[CONDITIONS] = array('States.status !=' => 3);
		$conditions[ORDER] =  array(COUNTRY_ID => ASC);
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
		$query = $table->find('all')->contain(['Countries']);
		$states = $this->paginate($query);
        $this->set(compact('states'));
		# Pass all data to render for display...
		$this->render('statesFilter');
	}

	#edit Static Content
    public function addState(){
        #check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
        if($this->request->is(['post','put'])){
            #get request data
			$postData = array_map('trim',preg_replace('/\s+/', ' ', $this->request->getData()));
            $table = TableRegistry::get(STATES);
            $getErrors = $table->newEntity($postData,[VALIDATE => 'add']);
			if(isset($postData[COUNTRY_ID]) && !empty($postData[STATE])){
				$existStateName = $table->find()->where(array(COUNTRY_ID => $postData[COUNTRY_ID],STATE => ucwords($postData[STATE])))->all();
				if($existStateName->count() > 0){
					$this->Flash->set('State name already exists.', array(ELEMENT => ALERT_ERROR));
					$this->redirect(ADMIN_FOLDER.'add-state/');
				}
			}
            if(!$getErrors->getErrors() && $existStateName->count() == 0){
                try{
					$saveData[COUNTRY_ID] = $postData[COUNTRY_ID];
					$saveData[STATE] = ucwords($postData[STATE]);
					$saveData[ABBREVIATION] = strtoupper($postData[ABBREVIATION]);
					if(isset($postData[STATUS])){
						$saveData[STATUS] = $postData[STATUS];
					}
					$saveData[CREATED] = time();
					$saveData[MODIFIED] = time();
					$tableEntity = $table->newEntity(array_map('trim',preg_replace('/\s+/', ' ', $saveData)));
                    $record = $table->save($tableEntity);
                    $this->Flash->set('State has been created successfully.', array(ELEMENT => ALERT_SUCCESS));
                    $this->redirect(ADMIN_FOLDER.'edit-state'.'/'.base64_encode($this->encryptData($record->id)));
                }catch( \Exception $e){
                    $this->Flash->set(INTERNAL_ERROR, array(ELEMENT => ALERT_ERROR));
                    $this->redirect(ADMIN_FOLDER.'add-state'.'/');
                }
            }else{
                $error = $getErrors->getErrors();
                $this->setErrorMessage($error);
                $this->redirect(ADMIN_FOLDER.'add-state'.'/');
            }
        }
		$this->set(COUNTRYLIST,$this->getCountryList());
    }

	#edit Country
    public function editState($editID = NULL){
        #check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
        if($this->request->is(['post','put'])){
            #get request data
            $postData = array_map('trim',preg_replace('/\s+/', ' ', $this->request->getData()));
            $table = TableRegistry::get(STATES);
            $getErrors = $table->newEntity($postData,[VALIDATE => 'update']);
            $editID = base64_encode($postData[EDIT_TOKEN]);
			if(isset($postData[COUNTRY_ID]) && !empty($postData[STATE])){
				$existStateName = $table->find()->where(array(COUNTRY_ID => trim($postData[COUNTRY_ID]),STATE => ucwords(trim($postData[STATE])),ID.' !=' => $this->decryptData($postData[EDIT_TOKEN])))->all();
				if($existStateName->count() > 0){
					$this->Flash->set('State name already exists.', array(ELEMENT => ALERT_ERROR));
					$this->redirect(ADMIN_FOLDER.'edit-state'.'/'.$editID);
				}
			}
            if(!$getErrors->getErrors() && $existStateName->count() == 0){
                try{
                    $saveData[ID] = $this->decryptData($postData[EDIT_TOKEN]);
					$saveData[COUNTRY_ID] = $postData[COUNTRY_ID];
					$saveData[STATE] = ucwords($postData[STATE]);
					$saveData[ABBREVIATION] = strtoupper($postData[ABBREVIATION]);
					if(isset($postData[STATUS])){
						$saveData[STATUS] = $postData[STATUS];
					}else{
						$saveData[STATUS] = 2;
					}
					$saveData[MODIFIED] = time();
					$tableEntity = $table->newEntity($saveData);
                    $table->save($tableEntity);
                    $this->Flash->set('State information has been updated successfully.', array(ELEMENT => ALERT_SUCCESS));
                    $this->redirect(ADMIN_FOLDER.'edit-state'.'/'.$editID);
                }catch( \Exception $e){
                    $this->Flash->set(INTERNAL_ERROR, array(ELEMENT => ALERT_ERROR));
                    $this->redirect(ADMIN_FOLDER.'edit-state'.'/'.$editID);
                }
            }else{
                $error = $getErrors->getErrors();
                $this->setErrorMessage($error);
				$this->set('error',$error);
                $this->redirect(ADMIN_FOLDER.'edit-state'.'/'.$editID);
            }
        }
        if(!empty($editID)){
            #decrypt request ID
            $editID = $this->decryptData(base64_decode($editID));
            #get row data
            $table = TableRegistry::get('States');
            $editData = $table->find()->where(array(ID => $editID))->first();
            if(isset($editData->id) && !empty($editData->id)){
                $this->set(compact(EDITDATA));
				$this->set(COUNTRYLIST,$this->getCountryList());
            }else{
                $this->redirect(ADMIN_FOLDER.'state-management'.'/');
            }
        }else{
            $this->redirect(ADMIN_FOLDER.'state-management'.'/');
        }
    }

	#city page
    public function cities(){
        #check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
        #get content data
        $table = TableRegistry::get(CITIES);
        # Conditions...
		$conditions = array();
		$conditions[CONDITIONS] = array(CITIES.'.'.STATUS.' !=' => 3);
		$conditions[ORDER] =  array(COUNTRY_ID => ASC);
		$conditions[LIMIT] =  PAGE_LIMIT;
        #delete post session data
        $session = $this->request->getSession();
		if($session->check(POSTDATA)){ $session->delete(POSTDATA);}
        #get record data
		$this->paginate = $conditions;
		$query = $table->find('all')->contain([COUNTRIES]);
		$cities = $this->paginate($query);
        $this->set(compact('cities'));
		$this->set(COUNTRYLIST,$this->getCountryList());
		$stateTbl = TableRegistry::get(STATES);
		$this->set('stateList',$stateTbl->find('list', ['keyField' => 'id','valueField' => STATE])->where(['status =' => 1])->order([STATE => 'asc']));
    }

	/**************************** pagination page ****************************/
	public function citiesFilter(){
        #check User Auth
        $this->checkValidSession();
		$table = TableRegistry::get(CITIES);
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
        if(isset($postData[COUNTRY_ID]) && !empty($postData[COUNTRY_ID])){
			$cond['Cities.country_id'] =  array(COUNTRY_ID => $postData[COUNTRY_ID]);
		}
		if(isset($postData[STATEID]) && !empty($postData[STATEID])){
			$cond['Cities.state_id'] =  array(STATEID => $postData[STATEID]);
		}
		if(isset($postData['city']) && !empty($postData['city'])){
			$cond['Cities.city'] =  array('Cities.city'.' '.LIKE => '%'.trim($postData['city']).'%');
		}
		if(isset($postData[STATUS]) && !empty($postData[STATUS])){
			$cond['Cities.status'] =  array('Cities.status' => $postData[STATUS]);
		}
		$conditions[CONDITIONS] = array('Cities.status !=' => 3);
		$conditions[ORDER] =  array('Cities.country_id' => ASC);
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
		$query = $table->find('all')->contain([COUNTRIES]);
		$cities = $this->paginate($query);
        $this->set(compact('cities'));
		# Pass all data to render for display...
		$this->render('citiesFilter');
	}

	#edit Static Content
    public function addCity(){
        #check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
        if($this->request->is(['post','put'])){
            #get request data
            $postData = array_map('trim',preg_replace('/\s+/', ' ', $this->request->getData()));
            $table = TableRegistry::get(CITIES);
            $getErrors = $table->newEntity($postData,[VALIDATE => 'add']);
			if(isset($postData[COUNTRY_ID]) && !empty($postData[COUNTRY_ID])&& isset($postData[STATEID]) && !empty($postData[STATEID]) && isset($postData['city']) && !empty($postData['city'])){
				$existCityName = $table->find()->where(array(COUNTRY_ID => trim($postData[COUNTRY_ID]),STATEID => trim($postData[STATEID]),'city' => ucwords(trim($postData['city']))))->all();
				if($existCityName->count() > 0){
					$this->Flash->set('City name already exists.', array(ELEMENT => ALERT_ERROR));
					$this->redirect(ADMIN_FOLDER.'add-city'.'/');
				}
			}
            if(!$getErrors->getErrors() && $existCityName->count() == 0){
                try{
					$saveData[COUNTRY_ID] = $postData[COUNTRY_ID];
					$saveData[STATEID] = $postData[STATEID];
					$saveData['city'] = ucwords($postData['city']);
					if(isset($postData[STATUS])){
						$saveData[STATUS] = $postData[STATUS];
					}
					$saveData[CREATED] = time();
					$saveData[MODIFIED] = time();
					$tableEntity = $table->newEntity(array_map('trim',preg_replace('/\s+/', ' ', $saveData)));
                    $record = $table->save($tableEntity);
                    $this->Flash->set('City has been created successfully.', array(ELEMENT => ALERT_SUCCESS));
                    $this->redirect(ADMIN_FOLDER.'edit-city'.'/'.base64_encode($this->encryptData($record->id)));
                }catch( \Exception $e){
                    $this->Flash->set(INTERNAL_ERROR, array(ELEMENT => ALERT_ERROR));
                    $this->redirect(ADMIN_FOLDER.'add-city'.'/');
                }
            }else{
                $error = $getErrors->getErrors();
                $this->setErrorMessage($error);
                $this->redirect(ADMIN_FOLDER.'add-city'.'/');
            }
        }
		$this->set(COUNTRYLIST,$this->getCountryList());
    }

	#edit City
    public function editCity($editID = NULL){
        #check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
        if($this->request->is(['post','put'])){
            #get request data
            $postData = array_map('trim',preg_replace('/\s+/', ' ', $this->request->getData()));
            $table = TableRegistry::get('Cities');
            $getErrors = $table->newEntity($postData,[VALIDATE => 'update']);
            $editID = base64_encode($postData[EDIT_TOKEN]);
			if(isset($postData[COUNTRY_ID]) && !empty($postData[COUNTRY_ID])&& isset($postData[STATEID]) && !empty($postData[STATEID]) && isset($postData['city']) && !empty($postData['city'])){
				$existCityName = $table->find()->where(array(COUNTRY_ID => trim($postData[COUNTRY_ID]),STATEID => trim($postData[STATEID]),'city' => ucwords(trim($postData['city'])),'id !=' => $this->decryptData($postData[EDIT_TOKEN])))->all();
				if($existCityName->count() > 0){
					$this->Flash->set('City name already exists.', array(ELEMENT => ALERT_ERROR));
					$this->redirect(ADMIN_FOLDER.'edit-city'.'/'.$editID);
				}
			}
            if(!$getErrors->getErrors() && $existCityName->count() == 0){
                try{
                    $saveData[ID] = $this->decryptData($postData[EDIT_TOKEN]);
					$saveData[STATEID] = $postData[STATEID];
					$saveData['country_id'] = $postData['country_id'];
					$saveData['city'] = ucwords($postData['city']);
					if(isset($postData[STATUS])){
						$saveData[STATUS] = $postData[STATUS];
					}else{
						$saveData[STATUS] = 2;
					}
					$saveData['modified'] = time();
					$tableEntity = $table->newEntity(array_map('trim',preg_replace('/\s+/', ' ', $saveData)));
                    $table->save($tableEntity);
                    $this->Flash->set('City information has been updated successfully.', array(ELEMENT => ALERT_SUCCESS));
                    $this->redirect(ADMIN_FOLDER.'edit-city'.'/'.$editID);
                }catch( \Exception $e){
                    $this->Flash->set(INTERNAL_ERROR, array(ELEMENT => ALERT_ERROR));
                    $this->redirect(ADMIN_FOLDER.'edit-city'.'/'.$editID);
                }
            }else{
                $error = $getErrors->getErrors();
                $this->setErrorMessage($error);
				$this->set('error',$error);
                $this->redirect(ADMIN_FOLDER.'edit-city'.'/'.$editID);
            }
        }
        if(!empty($editID)){
            #decrypt request ID
            $editID = $this->decryptData(base64_decode($editID));
            #get row data
            $table = TableRegistry::get('Cities');
            $editData = $table->find()->where(array(ID => $editID))->first();
            if(isset($editData->id) && !empty($editData->id)){
                $this->set(compact(EDITDATA));
				$this->set(COUNTRYLIST,$this->getCountryList());
            }else{
                $this->redirect(ADMIN_FOLDER.'city-management'.'/');
            }
        }else{
            $this->redirect(ADMIN_FOLDER.'city-management'.'/');
        }
    }

    #setErrorMessage
    function setErrorMessage($error){
        if(isset($error[COUNTRYNAME][CHECK_EMPTY]) && !empty($error[COUNTRYNAME][CHECK_EMPTY])){
            $this->Flash->set($error[COUNTRYNAME][CHECK_EMPTY], array(ELEMENT => ALERT_ERROR));
        }
        if(isset($error[COUNTRYCODE][CHECK_EMPTY]) && !empty($error[COUNTRYCODE][CHECK_EMPTY])){
            $this->Flash->set($error[COUNTRYCODE][CHECK_EMPTY], array(ELEMENT => ALERT_ERROR));
        }
		if(isset($error[PHONENOFORMAT][CHECK_EMPTY]) && !empty($error[PHONENOFORMAT][CHECK_EMPTY])){
            $this->Flash->set($error[PHONENOFORMAT][CHECK_EMPTY], array(ELEMENT => ALERT_ERROR));
        }
		if(isset($error[COUNTRY_ID][CHECK_EMPTY]) && !empty($error[COUNTRY_ID][CHECK_EMPTY])){
            $this->Flash->set($error[COUNTRY_ID][CHECK_EMPTY], array(ELEMENT => ALERT_ERROR));
        }
		if(isset($error[STATEID][CHECK_EMPTY]) && !empty($error[STATEID][CHECK_EMPTY])){
            $this->Flash->set($error[STATEID][CHECK_EMPTY], array(ELEMENT => ALERT_ERROR));
        }
		if(isset($error[STATE][CHECK_EMPTY]) && !empty($error[STATE][CHECK_EMPTY])){
            $this->Flash->set($error[STATE][CHECK_EMPTY], array(ELEMENT => ALERT_ERROR));
        }
		if(isset($error[CITY][CHECK_EMPTY]) && !empty($error[CITY][CHECK_EMPTY])){
            $this->Flash->set($error[CITY][CHECK_EMPTY], array(ELEMENT => ALERT_ERROR));
        }
		if(isset($error[ABBREVIATION][CHECK_EMPTY]) && !empty($error[ABBREVIATION][CHECK_EMPTY])){
            $this->Flash->set($error[ABBREVIATION][CHECK_EMPTY], array(ELEMENT => ALERT_ERROR));
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

	#zipcode remove
	function removeZipcode(){
		$this->viewBuilder()->layout = false;
        $this->autoRender = false;
		if($this->request->is('Ajax')){
			$table = TableRegistry::get(COUNTRIES);
			$postData = $this->request->getData();
			$rowId = $this->decryptData($postData['editId']);
			$value = $postData['value'];
			$tableData = $table->find()->where(array(ID => $rowId))->first();
			$zipCodeFormat = $tableData->zipcode_format;
			$zipcodeArr = explode(',',$zipCodeFormat);
			$arr = array_merge(array_diff($zipcodeArr, array($value)));

			$saveData['id'] = $rowId;
			$saveData['zipcode_format'] = implode(',',$arr);
			$tableEntity = $table->newEntity($saveData);
			$table->save($tableEntity);
		}
		exit;
	}

    function getCountryList(){
        $countryTbl = TableRegistry::get(COUNTRIES);
		return $countryTbl->find('list', ['keyField' => 'id','valueField' => 'country_name'])->where(['status =' => 1])->order(['ordering' => 'asc']);
    }
}
?>
