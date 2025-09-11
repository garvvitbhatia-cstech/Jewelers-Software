<?php
namespace App\Controller;
use Cake\Core\Configure;
use Cake\Network\Exception\ForbiddenException;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\ORM\TableRegistry;
use Cake\Http\Response;
use Cake\Core\Exception\Exception;

class CmsManagementController extends AppController{
    	
	#inner page
    public function innerPages(){
        #check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
		
        #get content data
        $table = TableRegistry::get('InnerPages');
		
        # Conditions...
		$conditions = array();
		$conditions[CONDITIONS] = array(STATUS.' !=' => 3);
		$conditions[ORDER] =  array(ID => DESC);
		$conditions[LIMIT] =  PAGE_LIMIT;
		
        #delete post session data
        $session = $this->request->getSession();
		if($session->check(POSTDATA)){$session->delete(POSTDATA);}

        #get record data
		$this->paginate = $conditions;
		$innerPages = $this->paginate($table);
        $this->set(compact('innerPages'));
    }

	/**************************** pagination page ****************************/
	public function innerPagesFilter(){
        #check User Auth
        $this->checkValidSession();
		$table = TableRegistry::get('InnerPages');
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
        if(isset($postData[TITLE]) && !empty($postData[TITLE])){
			$cond[TITLE] =  array(TITLE.' '.LIKE => '%'.trim($postData[TITLE]).'%');
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
		$innerPages = $this->paginate($table);
        $this->set(compact('innerPages'));

		# Pass all data to render for display...
		$this->render('innerPagesFilter');
	}

	#edit Country
    public function editInnerPage($editID = NULL){
        #check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
        if($this->request->is(['post','put'])){
            #get request data
            $postData = $this->request->getData();
            $table = TableRegistry::get('InnerPages');
            $getErrors = $table->newEntity($postData,[VALIDATE => UPDATE]);
            $editID = base64_encode($postData[EDIT_TOKEN]);
			if(isset($postData[TITLE]) && !empty($postData[TITLE])){
				$existTitle = $table->find()->where(array(TITLE => ucwords(trim($postData[TITLE])),ID.' !=' => $this->decryptData($postData[EDIT_TOKEN])))->all();
				if($existTitle->count() > 0){
					if($existTitle->count() > 0){
						$this->Flash->set('Title name already exists.', array(ELEMENT => ALERT_ERROR));
					}
					$this->redirect(ADMIN_FOLDER.'edit-inner-page'.'/'.$editID);
				}
			}

            if(!$getErrors->getErrors() && $existTitle->count() == 0){
                try{
                    $saveData[ID] = $this->decryptData($postData[EDIT_TOKEN]);
                    $saveData[TITLE] = ucwords($postData[TITLE]);
					$saveData[DESCRIPTION] = $postData[DESCRIPTION];
					$saveData[HEADING] = $postData[HEADING];
					$saveData[SUB_HEADING] = $postData[SUB_HEADING];
					$saveData[EDIT_HEADING] = $postData[EDIT_HEADING];
					$saveData[EDIT_SUB_HEADING] = $postData[EDIT_SUB_HEADING];
					$saveData[BANNER_IMAGE] = $postData[BANNER_IMAGE];
					$saveData[SEO_TITLE] = $postData[SEO_TITLE];
					$saveData[SEO_DESCRIPTION] = $postData[SEO_DESCRIPTION];
					$saveData[SEO_KEYWORDS] = $postData[SEO_KEYWORDS];
					$saveData[SEOTAGS] = $postData[SEOTAGS];
					if(isset($postData[BANNER_STATUS])){
						$saveData[BANNER_STATUS] = $postData[BANNER_STATUS];
					}else{
						$saveData[BANNER_STATUS] = 2;
					}
					if(isset($postData[STATUS])){
						$saveData[STATUS] = $postData[STATUS];
					}else{
						$saveData[STATUS] = 2;
					}

					$saveData[MODIFIED] = time();
                    $tableEntity = $table->newEntity(array_map('trim',preg_replace('/\s+/', ' ', $saveData)));
                    $table->save($tableEntity);
                    $this->Flash->set('Inner Page has been updated successfully.', array(ELEMENT => ALERT_SUCCESS));
                    $this->redirect(ADMIN_FOLDER.'edit-inner-page'.'/'.$editID);

                }catch( \Exception $e){
                    $this->Flash->set(INTERNAL_ERROR, array(ELEMENT => ALERT_ERROR));
                    $this->redirect(ADMIN_FOLDER.'edit-inner-page'.'/'.$editID);
                }

            }else{

                $error = $getErrors->getErrors();
                $this->setErrorMessage($error);
				$this->set(ALERT_ERROR,$error);
                $this->redirect(ADMIN_FOLDER.'edit-inner-page'.'/'.$editID);
            }

        }

        if(!empty($editID)){
            #decrypt request ID
            $editID = $this->decryptData(base64_decode($editID));
            #get row data

            $table = TableRegistry::get('InnerPages');
            $editData = $table->find()->where(array(ID => $editID))->first();
            if(isset($editData->id) && !empty($editData->id)){
                $this->set(compact(EDITDATA));
            }else{
                $this->redirect(ADMIN_FOLDER.'edit-inner-page'.'/');
            }

        }else{
            $this->redirect(ADMIN_FOLDER.'edit-inner-page'.'/');
        }
    }
	
	#cms page
    public function cms(){
        #check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
		
        #get content data
        $table = TableRegistry::get('Cms');
		
        # Conditions...
		$conditions = array();
		$conditions[CONDITIONS] = array(STATUS.' !=' => 3);
		$conditions[ORDER] =  array(ID => DESC);
		$conditions[LIMIT] =  PAGE_LIMIT;
		
        #delete post session data
        $session = $this->request->getSession();
		if($session->check(POSTDATA)){$session->delete(POSTDATA);}

        #get record data
		$this->paginate = $conditions;
		$cmsPages = $this->paginate($table);
        $this->set(compact('cmsPages'));
    }

	/**************************** pagination page ****************************/
	public function cmsFilter(){
        #check User Auth
        $this->checkValidSession();
		$table = TableRegistry::get('Cms');
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
        if(isset($postData[TITLE]) && !empty($postData[TITLE])){
			$cond[TITLE] =  array(TITLE.' '.LIKE => '%'.trim($postData[TITLE]).'%');
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
		$cmsPages = $this->paginate($table);
        $this->set(compact('cmsPages'));

		# Pass all data to render for display...
		$this->render('cmsFilter');
	}
	
	public function addCms(){
        #check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
        if($this->request->is(['post','put'])){
            #get request data
            $postData = $this->request->getData();
			$postData[TITLE] = trim($postData[TITLE]);
            $table = TableRegistry::get('Cms');
            $getErrors = $table->newEntity($postData,[VALIDATE => ADD]);
            if(!$getErrors->getErrors()){
                try{
                    $saveData[TITLE] = ucwords($postData[TITLE]);
					$saveData[DESCRIPTION] = $postData[DESCRIPTION];
					$saveData[SEO_TITLE] = $postData[SEO_TITLE];
					$saveData[SEO_DESCRIPTION] = $postData[SEO_DESCRIPTION];
					$saveData[SEO_KEYWORDS] = $postData[SEO_KEYWORDS];
					$saveData[SEOTAGS] = $postData[SEOTAGS];
					if(isset($postData[STATUS])){
						$saveData[STATUS] = $postData[STATUS];
					}else{
						$saveData[STATUS] = 2;
					}

					$saveData[CREATED] = time();
					$saveData[MODIFIED] = time();
                    $tableEntity = $table->newEntity(array_map('trim',preg_replace('/\s+/', ' ', $saveData)));
                    $result = $table->save($tableEntity);
                    $this->Flash->set('Cms page has been created successfully.', array(ELEMENT => ALERT_SUCCESS));
                    $this->redirect(ADMIN_FOLDER.'edit-cms'.'/'.base64_encode($this->encryptData($result->id)));

                }catch( \Exception $e){
                    $this->Flash->set(INTERNAL_ERROR, array(ELEMENT => ALERT_ERROR));
                    $this->redirect(ADMIN_FOLDER.'add-cms/');
                }
            }else{
                $error = $getErrors->getErrors();
                $this->setErrorMessage($error);
				$this->set(ALERT_ERROR,$error);
                $this->redirect(ADMIN_FOLDER.'add-cms'.'/');
            }
        }    	
	}	
	
	#edit Country
    public function editCms($editID = NULL){
        #check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
        if($this->request->is(['post','put'])){
            #get request data
            $postData = $this->request->getData();
			$postData[TITLE] = trim($postData[TITLE]);
            $table = TableRegistry::get('Cms');
            $getErrors = $table->newEntity($postData,[VALIDATE => UPDATE]);
            $editID = base64_encode($postData[EDIT_TOKEN]);
			
            if(!$getErrors->getErrors()){
                try{
                    $saveData[ID] = $this->decryptData($postData[EDIT_TOKEN]);
                    $saveData[TITLE] = ucwords($postData[TITLE]);
					$saveData[DESCRIPTION] = $postData[DESCRIPTION];					
					$saveData[SEO_TITLE] = $postData[SEO_TITLE];
					$saveData[SEO_DESCRIPTION] = $postData[SEO_DESCRIPTION];
					$saveData[SEO_KEYWORDS] = $postData[SEO_KEYWORDS];
					$saveData[SEOTAGS] = $postData[SEOTAGS];
					if(isset($postData[STATUS])){
						$saveData[STATUS] = $postData[STATUS];
					}else{
						$saveData[STATUS] = 2;
					}

					$saveData[MODIFIED] = time();
                    $tableEntity = $table->newEntity(array_map('trim',preg_replace('/\s+/', ' ', $saveData)));
                    $table->save($tableEntity);
                    $this->Flash->set('Cms Page has been updated successfully.', array(ELEMENT => ALERT_SUCCESS));
                    $this->redirect(ADMIN_FOLDER.'edit-cms'.'/'.$editID);

                }catch( \Exception $e){
                    $this->Flash->set(INTERNAL_ERROR, array(ELEMENT => ALERT_ERROR));
                    $this->redirect(ADMIN_FOLDER.'edit-cms'.'/'.$editID);
                }

            }else{

                $error = $getErrors->getErrors();
                $this->setErrorMessage($error);
				$this->set(ALERT_ERROR,$error);
                $this->redirect(ADMIN_FOLDER.'edit-cms'.'/'.$editID);
            }

        }

        if(!empty($editID)){
            #decrypt request ID
            $editID = $this->decryptData(base64_decode($editID));
            #get row data

            $table = TableRegistry::get('Cms');
            $editData = $table->find()->where(array(ID => $editID))->first();
            if(isset($editData->id) && !empty($editData->id)){
                $this->set(compact(EDITDATA));
            }else{
                $this->redirect(ADMIN_FOLDER.'edit-cms'.'/'.$editID);
            }

        }else{
            $this->redirect(ADMIN_FOLDER.'edit-cms'.'/'.$editID);
        }
    }
	
	#footer page
    public function headerNavigations(){
        #check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
		
        #get content data
        $table = TableRegistry::get('HeaderNavigations');
		
        # Conditions...
		$conditions = array();
		$conditions[CONDITIONS] = array(STATUS.' !=' => 3);
		$conditions[ORDER] =  array('parent_id' => ASC);
		$conditions[LIMIT] =  PAGE_LIMIT;
		
        #delete post session data
        $session = $this->request->getSession();
		if($session->check(POSTDATA)){$session->delete(POSTDATA);}

        #get record data
		$this->paginate = $conditions;
		$navigations = $this->paginate($table);
        $this->set(compact('navigations'));
    }

	/**************************** pagination page ****************************/
	public function headerNavigationFilter(){
        #check User Auth
        $this->checkValidSession();
		$table = TableRegistry::get('HeaderNavigations');
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
		
		if(isset($postData[STATUS]) && !empty($postData[STATUS])){
			$cond[STATUS] =  array(STATUS => $postData[STATUS]);
		}

		$conditions[CONDITIONS] = array(STATUS.' !=' => 3);
		$conditions[LIMIT] =  PAGE_LIMIT;
		$conditions[ORDER] =  array('parent_id' => ASC);

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
		$navigations = $this->paginate($table);
        $this->set(compact('navigations'));

		# Pass all data to render for display...
		$this->render('headerNavigationFilter');
	}
	
	public function addHeaderNavigation(){
        #check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
        if($this->request->is(['post','put'])){
            #get request data
            $postData = $this->request->getData();
            $table = TableRegistry::get('HeaderNavigations');
			try{				
				if(isset($postData['parent_id'])){
					$saveData['parent_id'] = $postData['parent_id'];
				}else{
					$saveData['parent_id'] = 0;
				}
				$saveData['target_window'] = $postData['target_window'];
				$saveData['menu_type'] = $postData['type'];
				$saveData['menu_page_id'] = $postData['menu_page_id'];
				$saveData['url'] = $postData['url'];
				$saveData[TITLE] = trim($postData[TITLE]);
				$saveData[SEO_TITLE] = $postData[SEO_TITLE];
				$saveData[SEO_DESCRIPTION] = $postData[SEO_DESCRIPTION];
				$saveData[SEO_KEYWORDS] = $postData[SEO_KEYWORDS];
				$saveData[SEOTAGS] = $postData[SEOTAGS];
				if(isset($postData[STATUS])){
					$saveData[STATUS] = $postData[STATUS];
				}else{
					$saveData[STATUS] = 2;
				}

				$saveData[CREATED] = time();
				$saveData[MODIFIED] = time();
				$tableEntity = $table->newEntity(array_map('trim',preg_replace('/\s+/', ' ', $saveData)));
				$result = $table->save($tableEntity);
				$this->Flash->set('Header Navigation page has been created successfully.', array(ELEMENT => ALERT_SUCCESS));
				$this->redirect(ADMIN_FOLDER.'edit-header-navigation'.'/'.base64_encode($this->encryptData($result->id)));

			}catch( \Exception $e){
				$this->Flash->set(INTERNAL_ERROR, array(ELEMENT => ALERT_ERROR));
				$this->redirect(ADMIN_FOLDER.'add-header-navigation/');
			}
        }
		$this->set('cmsPageList',$this->getCmsPages());
		$this->set('headerNavigationList',$this->getHeaderNavigations());
	}	
	
	#edit Country
    public function editHeaderNavigation($editID = NULL){
        #check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
        if($this->request->is(['post','put'])){
            #get request data
            $postData = $this->request->getData();
            $table = TableRegistry::get('HeaderNavigations');
            $editID = base64_encode($postData[EDIT_TOKEN]);			
			try{
				$saveData[ID] = $this->decryptData($postData[EDIT_TOKEN]);
				$saveData['parent_id'] = $postData['parent_id'];
				$saveData['target_window'] = $postData['target_window'];
				$saveData['menu_type'] = $postData['type'];
				$saveData['menu_page_id'] = $postData['menu_page_id'];
				$saveData['url'] = $postData['url'];
				$saveData[TITLE] = trim($postData[TITLE]);
				$saveData[SEO_TITLE] = $postData[SEO_TITLE];
				$saveData[SEO_DESCRIPTION] = $postData[SEO_DESCRIPTION];
				$saveData[SEO_KEYWORDS] = $postData[SEO_KEYWORDS];
				$saveData[SEOTAGS] = $postData[SEOTAGS];
				if(isset($postData[STATUS])){
					$saveData[STATUS] = $postData[STATUS];
				}else{
					$saveData[STATUS] = 2;
				}

				$saveData[MODIFIED] = time();
				$tableEntity = $table->newEntity(array_map('trim',preg_replace('/\s+/', ' ', $saveData)));
				$table->save($tableEntity);
				$this->Flash->set('Header Navigation Page has been updated successfully.', array(ELEMENT => ALERT_SUCCESS));
				$this->redirect(ADMIN_FOLDER.'edit-header-navigation'.'/'.$editID);

			}catch( \Exception $e){
				$this->Flash->set(INTERNAL_ERROR, array(ELEMENT => ALERT_ERROR));
				$this->redirect(ADMIN_FOLDER.'edit-header-navigation'.'/'.$editID);
			}
        }

        if(!empty($editID)){
            #decrypt request ID
            $editID = $this->decryptData(base64_decode($editID));
            #get row data

            $table = TableRegistry::get('HeaderNavigations');
            $editData = $table->find()->where(array(ID => $editID))->first();
            if(isset($editData->id) && !empty($editData->id)){
                $this->set(compact(EDITDATA));
				$this->set('cmsPageList',$this->getCmsPages());
				$this->set('headerNavigationList',$this->getHeaderNavigations());
            }else{
                $this->redirect(ADMIN_FOLDER.'edit-header-navigation'.'/'.$editID);
            }

        }else{
            $this->redirect(ADMIN_FOLDER.'edit-header-navigation'.'/'.$editID);
        }
    }
	
	#footer page
    public function footerNavigations(){
        #check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
		
        #get content data
        $table = TableRegistry::get('FooterNavigations');
		
        # Conditions...
		$conditions = array();
		$conditions[CONDITIONS] = array(STATUS.' !=' => 3);
		$conditions[ORDER] =  array(ID => DESC);
		$conditions[LIMIT] =  PAGE_LIMIT;
		
        #delete post session data
        $session = $this->request->getSession();
		if($session->check(POSTDATA)){$session->delete(POSTDATA);}

        #get record data
		$this->paginate = $conditions;
		$navigations = $this->paginate($table);
        $this->set(compact('navigations'));
    }

	/**************************** pagination page ****************************/
	public function footerNavigationFilter(){
        #check User Auth
        $this->checkValidSession();
		$table = TableRegistry::get('FooterNavigations');
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
        if(isset($postData[TITLE]) && !empty($postData[TITLE])){
			$cond[TITLE] =  array(TITLE.' '.LIKE => '%'.trim($postData[TITLE]).'%');
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
		$navigations = $this->paginate($table);
        $this->set(compact('navigations'));

		# Pass all data to render for display...
		$this->render('footerNavigationFilter');
	}
	
	public function addFooterNavigation(){
        #check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
        if($this->request->is(['post','put'])){
            #get request data
            $postData = $this->request->getData();
            $table = TableRegistry::get('FooterNavigations');
			try{
				$saveData['target_window'] = $postData['target_window'];
				$saveData['type'] = $postData['type'];
				$saveData['menu_page_id'] = $postData['menu_page_id'];
				$saveData['url'] = $postData['url'];
				$saveData[TITLE] = trim($postData[TITLE]);
				$saveData[SEO_TITLE] = $postData[SEO_TITLE];
				$saveData[SEO_DESCRIPTION] = $postData[SEO_DESCRIPTION];
				$saveData[SEO_KEYWORDS] = $postData[SEO_KEYWORDS];
				$saveData[SEOTAGS] = $postData[SEOTAGS];
				if(isset($postData[STATUS])){
					$saveData[STATUS] = $postData[STATUS];
				}else{
					$saveData[STATUS] = 2;
				}

				$saveData[CREATED] = time();
				$saveData[MODIFIED] = time();
				$tableEntity = $table->newEntity(array_map('trim',preg_replace('/\s+/', ' ', $saveData)));
				$result = $table->save($tableEntity);
				$this->Flash->set('Footer Navigation page has been created successfully.', array(ELEMENT => ALERT_SUCCESS));
				$this->redirect(ADMIN_FOLDER.'edit-footer-navigation'.'/'.base64_encode($this->encryptData($result->id)));

			}catch( \Exception $e){
				$this->Flash->set(INTERNAL_ERROR, array(ELEMENT => ALERT_ERROR));
				$this->redirect(ADMIN_FOLDER.'add-footer-navigation/');
			}
        }
		$this->set('cmsPageList',$this->getCmsPages());
	}	
	
	#edit Country
    public function editFooterNavigation($editID = NULL){
        #check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
        if($this->request->is(['post','put'])){
            #get request data
            $postData = $this->request->getData();
            $table = TableRegistry::get('FooterNavigations');
            $editID = base64_encode($postData[EDIT_TOKEN]);			
			try{
				$saveData[ID] = $this->decryptData($postData[EDIT_TOKEN]);
				$saveData['target_window'] = $postData['target_window'];
				$saveData['type'] = $postData['type'];
				$saveData['menu_page_id'] = $postData['menu_page_id'];
				$saveData['url'] = $postData['url'];
				$saveData[TITLE] = trim($postData[TITLE]);
				$saveData[SEO_TITLE] = $postData[SEO_TITLE];
				$saveData[SEO_DESCRIPTION] = $postData[SEO_DESCRIPTION];
				$saveData[SEO_KEYWORDS] = $postData[SEO_KEYWORDS];
				$saveData[SEOTAGS] = $postData[SEOTAGS];
				if(isset($postData[STATUS])){
					$saveData[STATUS] = $postData[STATUS];
				}else{
					$saveData[STATUS] = 2;
				}

				$saveData[MODIFIED] = time();
				$tableEntity = $table->newEntity(array_map('trim',preg_replace('/\s+/', ' ', $saveData)));
				$table->save($tableEntity);
				$this->Flash->set('Footer Navigation Page has been updated successfully.', array(ELEMENT => ALERT_SUCCESS));
				$this->redirect(ADMIN_FOLDER.'edit-footer-navigation'.'/'.$editID);

			}catch( \Exception $e){
				$this->Flash->set(INTERNAL_ERROR, array(ELEMENT => ALERT_ERROR));
				$this->redirect(ADMIN_FOLDER.'edit-footer-navigation'.'/'.$editID);
			}
        }

        if(!empty($editID)){
            #decrypt request ID
            $editID = $this->decryptData(base64_decode($editID));
            #get row data

            $table = TableRegistry::get('FooterNavigations');
            $editData = $table->find()->where(array(ID => $editID))->first();
            if(isset($editData->id) && !empty($editData->id)){
                $this->set(compact(EDITDATA));
				$this->set('cmsPageList',$this->getCmsPages());
            }else{
                $this->redirect(ADMIN_FOLDER.'edit-footer-navigation'.'/'.$editID);
            }

        }else{
            $this->redirect(ADMIN_FOLDER.'edit-footer-navigation'.'/'.$editID);
        }
    }	
	
	#setErrorMessage
    function setErrorMessage($error){
        if(isset($error[TITLE][CHECK_EMPTY]) && !empty($error[TITLE][CHECK_EMPTY])){
            $this->Flash->set($error[TITLE][CHECK_EMPTY], array(ELEMENT => ALERT_ERROR));
        }
		if(isset($error[TITLE]['checkUniqueTitleUpdate']) && !empty($error[TITLE]['checkUniqueTitleUpdate'])){
            $this->Flash->set($error[TITLE]['checkUniqueTitleUpdate'], array(ELEMENT => ALERT_ERROR));
        }
		if(isset($error[TITLE]['checkUniqueTitle']) && !empty($error[TITLE]['checkUniqueTitle'])){
            $this->Flash->set($error[TITLE]['checkUniqueTitle'], array(ELEMENT => ALERT_ERROR));
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
	
	function getCmsPages(){
		$table = TableRegistry::get('Cms');
		return $table->find('list', ['keyField' => 'id','valueField' => 'title'])->where(['status' => 1])->order(['title' => 'asc']);
	}
	
	function getHeaderNavigations(){
		$table = TableRegistry::get('HeaderNavigations');
		$headerNavigationList = $table->find('list', ['keyField' => 'id','valueField' => 'title'])->where(['status' => 1,'parent_id' => 0])->order(['id' => 'asc']);
		return $headerNavigationList;
	}

}
?>