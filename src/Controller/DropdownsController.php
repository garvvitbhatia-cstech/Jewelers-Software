<?php
namespace App\Controller;
use Cake\Core\Configure;
use Cake\Network\Exception\ForbiddenException;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\ORM\TableRegistry;
use Cake\Http\Response;
use Cake\Core\Exception\Exception;

class DropdownsController extends AppController{

    #services page
    public function categories(){
        #check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
        #get content data

        $table = TableRegistry::get('Categories');
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
		$categories = $this->paginate($table);
        $this->set(compact('categories'));
    }

	/**************************** pagination page ****************************/

	public function categoriesFilter(){
        #check User Auth
        $this->checkValidSession();
		$table = TableRegistry::get('Categories');
		
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
			$cond['name'] =  array('name'.' '.LIKE => '%'.trim($postData[TITLE]).'%');
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
		$categories = $this->paginate($table);
        $this->set(compact('categories'));

		# Pass all data to render for display...
		$this->render('categoriesFilter');
	}

	#edit Static Content
    public function addCategory(){
        #check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
        if($this->request->is(['post','put'])){
            #get request data
            $postData = $this->request->getData();
            $table = TableRegistry::get('Categories');
            $getErrors = $table->newEntity($postData,[VALIDATE => 'add']);
            if(!$getErrors->getErrors()){
                try{
					$query = $table->find();
					$findOrder= $query->select(array('max_order' => $query->func()->max(ORDERING)))->first();
					if($findOrder->max_order == ''){
						$saveData[ORDERING] = 1;
					}else{
						$saveData[ORDERING] = ($findOrder->max_order+1);
					}					
                    $saveData['name'] = ucwords($postData['name']);
					if(isset($postData[STATUS])){
						$saveData[STATUS] = $postData[STATUS];
					}
					$saveData[CREATED] = time();
					$saveData[MODIFIED] = time();
					$tableEntity = $table->newEntity(array_map('trim',preg_replace('/\s+/', ' ', $saveData)));
                    $record = $table->save($tableEntity);
                    $this->Flash->set('Category has been created successfully.', array(ELEMENT => ALERT_SUCCESS));
                    $this->redirect(ADMIN_FOLDER.'categories'.'/');

                }catch( \Exception $e){
                    $this->Flash->set(INTERNAL_ERROR, array(ELEMENT => ALERT_ERROR));
                    $this->redirect(ADMIN_FOLDER.'add-category'.'/');
                }
            }else{
                $error = $getErrors->getErrors();
                $this->setErrorMessage($error);
                $this->redirect(ADMIN_FOLDER.'add-category'.'/');
            }
        }
    }


	#edit Country
    public function editCategory($editID = NULL){
        #check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
        if($this->request->is(['post','put'])){
            #get request data
            $postData = $this->request->getData();
            $table = TableRegistry::get('Categories');
            $getErrors = $table->newEntity($postData,[VALIDATE => UPDATE]);
            $editID = base64_encode($postData[EDIT_TOKEN]);
            if(!$getErrors->getErrors()){
                try{
                    $saveData[ID] = $this->decryptData($postData[EDIT_TOKEN]);
                    $saveData['name'] = ucwords($postData['name']);
					if(isset($postData[STATUS])){
						$saveData[STATUS] = $postData[STATUS];
					}else{
						$saveData[STATUS] = 2;
					}

					$saveData[MODIFIED] = time();
                    $tableEntity = $table->newEntity(array_map('trim',preg_replace('/\s+/', ' ', $saveData)));
                    $table->save($tableEntity);
                    $this->Flash->set('Category has been updated successfully.', array(ELEMENT => ALERT_SUCCESS));
                    $this->redirect(ADMIN_FOLDER.'edit-category'.'/'.$editID);

                }catch( \Exception $e){
                    $this->Flash->set(INTERNAL_ERROR, array(ELEMENT => ALERT_ERROR));
                    $this->redirect(ADMIN_FOLDER.'edit-category'.'/'.$editID);
                }
            }else{
                $error = $getErrors->getErrors();
                $this->setErrorMessage($error);
				$this->set(ALERT_ERROR,$error);
                $this->redirect(ADMIN_FOLDER.'edit-category'.'/'.$editID);
            }
        }

        if(!empty($editID)){
            #decrypt request ID
            $editID = $this->decryptData(base64_decode($editID));			
            #get row data
            $table = TableRegistry::get('Categories');
            $editData = $table->find()->where(array(ID => $editID))->first();
            if(isset($editData->id) && !empty($editData->id)){
                $this->set(compact(EDITDATA));
            }else{
                $this->redirect(ADMIN_FOLDER.'edit-category'.'/');
            }

        }else{
            $this->redirect(ADMIN_FOLDER.'edit-category'.'/');
        }
    }
	
	#services page
    public function products(){
        #check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
        #get content data
        $table = TableRegistry::get('Products');
		
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
		$products = $this->paginate($table);
        $this->set(compact('products'));
		$this->set('categoryList',$this->getCategories());
    }

	/**************************** pagination page ****************************/

	public function productsFilter(){
        #check User Auth
        $this->checkValidSession();
		$table = TableRegistry::get('Products');
		
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
        if(isset($postData['category_id']) && !empty($postData['category_id'])){
			$cond['category_id'] =  array('category_id' => trim($postData['category_id']));
		}
		if(isset($postData['product_name']) && !empty($postData['product_name'])){
			$cond['product_name'] =  array('product_name'.' '.LIKE => '%'.trim($postData['product_name']).'%');
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
		$products = $this->paginate($table);
        $this->set(compact('products'));

		# Pass all data to render for display...
		$this->render('productsFilter');
	}

	#edit Static Content
    public function addProduct(){
        #check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
        if($this->request->is(['post','put'])){
            #get request data
            $postData = $this->request->getData();
            $table = TableRegistry::get('Products');
            $getErrors = $table->newEntity($postData,[VALIDATE => 'add']);
            if(!$getErrors->getErrors()){
                try{
                    $saveData['category_id'] = $postData['category_id'];
					$saveData['product_name'] = $postData['product_name'];
					$saveData['gross_weight'] = $postData['gross_weight'];
					$saveData['price'] = $postData['price'];
					$saveData['net_weight'] = strtolower($postData['net_weight']);
					$saveData['worker_name'] = $postData['worker_name'];
					$saveData['qty'] = $postData['qty'];
					$saveData['party_name'] = $postData['party_name'];
					$saveData['party_phone'] = $postData['party_phone'];
					$saveData['type'] = $postData['type'];
					$saveData['percentage'] = $postData['percentage'];
					$saveData['huid_code'] = $postData['huid_code'];
					$saveData['tag_name'] = $postData['tag_name'];
					$saveData['purity'] = $postData['purity'];
					$saveData['diam_stone_wgt'] = $postData['diam_stone_wgt'];
					$saveData['tunch'] = $postData['tunch'];
					$saveData['wstg'] = $postData['wstg'];
					if(isset($postData[STATUS])){
						$saveData[STATUS] = $postData[STATUS];
					}
					$saveData[CREATED] = time();
					$saveData[MODIFIED] = time();					
					$tableEntity = $table->newEntity(array_map('trim',preg_replace('/\s+/', ' ', $saveData)));
                    if($record = $table->save($tableEntity)){
						$uniqueId = $this->getUniqueID($record->id);
						$queryOrdUpd = $table->query();
						$queryOrdUpd->update()->set(['unique_code' => $uniqueId])->where([ID => $record->id])->execute();
					}
                    $this->Flash->set('Product has been created successfully.', array(ELEMENT => ALERT_SUCCESS));
                    $this->redirect(ADMIN_FOLDER.'products'.'/');

                }catch( \Exception $e){
                    $this->Flash->set(INTERNAL_ERROR, array(ELEMENT => ALERT_ERROR));
                    $this->redirect(ADMIN_FOLDER.'add-product'.'/');
                }
            }else{
                $error = $getErrors->getErrors();
                $this->setErrorMessage($error);
                $this->redirect(ADMIN_FOLDER.'add-product'.'/');
            }
        }
		$this->set('categoryList',$this->getCategories());
    }


	#edit Country
    public function editProduct($editID = NULL){
        #check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
        if($this->request->is(['post','put'])){
            #get request data
            $postData = $this->request->getData();
            $table = TableRegistry::get('Products');
            $getErrors = $table->newEntity($postData,[VALIDATE => UPDATE]);
            $editID = base64_encode($postData[EDIT_TOKEN]);
            if(!$getErrors->getErrors()){ 
                try{
                    $saveData[ID] = $this->decryptData($postData[EDIT_TOKEN]);
                   	$saveData['category_id'] = $postData['category_id'];
					$saveData['product_name'] = $postData['product_name'];
					$saveData['gross_weight'] = $postData['gross_weight'];
					$saveData['price'] = $postData['price'];
					$saveData['party_name'] = $postData['party_name'];
					$saveData['party_phone'] = $postData['party_phone'];
					$saveData['type'] = $postData['type'];
					$saveData['net_weight'] = strtolower($postData['net_weight']);
					$saveData['worker_name'] = $postData['worker_name'];
					$saveData['percentage'] = $postData['percentage'];
					$saveData['qty'] = $postData['qty'];
					$saveData['huid_code'] = $postData['huid_code'];
					$saveData['tag_name'] = $postData['tag_name'];
					$saveData['purity'] = $postData['purity'];
					$saveData['diam_stone_wgt'] = $postData['diam_stone_wgt'];
					$saveData['tunch'] = $postData['tunch'];
					$saveData['wstg'] = $postData['wstg'];
					if(isset($postData[STATUS])){
						$saveData[STATUS] = $postData[STATUS];
					}else{
						$saveData[STATUS] = 2;
					}

					$saveData[MODIFIED] = time();
                    $tableEntity = $table->newEntity(array_map('trim',preg_replace('/\s+/', ' ', $saveData)));
                    $table->save($tableEntity);
                    $this->Flash->set('Product details has been updated successfully.', array(ELEMENT => ALERT_SUCCESS));
                    $this->redirect(ADMIN_FOLDER.'edit-product'.'/'.$editID);

                }catch( \Exception $e){
                    $this->Flash->set(INTERNAL_ERROR, array(ELEMENT => ALERT_ERROR));
                    $this->redirect(ADMIN_FOLDER.'edit-product'.'/'.$editID);
                }
            }else{
                $error = $getErrors->getErrors();
                $this->setErrorMessage($error);
				$this->set(ALERT_ERROR,$error);
                $this->redirect(ADMIN_FOLDER.'edit-product'.'/'.$editID);
            }
			
        }
		
        if(!empty($editID)){
            #decrypt request ID
            $editID = $this->decryptData(base64_decode($editID));			
            #get row data
            $table = TableRegistry::get('Products');
            $editData = $table->find()->where(array(ID => $editID))->first();
            if(isset($editData->id) && !empty($editData->id)){
				if(empty($editData->unique_code)){
					$uniqueId = $this->getUniqueID($editData->id);
					$queryOrdUpd = $table->query();
					$queryOrdUpd->update()->set(['unique_code' => $uniqueId])->where([ID => $editData->id])->execute();
				}
                $this->set(compact(EDITDATA));
				$this->set('categoryList',$this->getCategories());
            }else{
                $this->redirect(ADMIN_FOLDER.'products'.'/');
            }
        }else{
            $this->redirect(ADMIN_FOLDER.'products'.'/');
        }
    }

    #setErrorMessage
    function setErrorMessage($error){
		if(isset($error['name'][CHECK_EMPTY]) && !empty($error['name'][CHECK_EMPTY])){
            $this->Flash->set($error['name'][CHECK_EMPTY], array(ELEMENT => ALERT_ERROR));
        }
		if(isset($error['product_name'][CHECK_EMPTY]) && !empty($error['product_name'][CHECK_EMPTY])){
            $this->Flash->set($error['product_name'][CHECK_EMPTY], array(ELEMENT => ALERT_ERROR));
        }
		if(isset($error['name']['checkUniqueName']) && !empty($error['name']['checkUniqueName'])){
            $this->Flash->set($error['name']['checkUniqueName'], array(ELEMENT => ALERT_ERROR));
        }
		if(isset($error['product_name']['checkUniqueName']) && !empty($error['product_name']['checkUniqueName'])){
            $this->Flash->set($error['product_name']['checkUniqueName'], array(ELEMENT => ALERT_ERROR));
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
	
	function getCategories(){
		$table = TableRegistry::get('Categories');
		return $table->find('list', ['keyField' => 'id','valueField' => 'name'])->where(['status' => 1])->order(['ordering' => 'asc']);
	}
	
	function getUniqueID($string){
		$length = 8;
		$rand = str_shuffle(rand(1111,9999).rand(1111,9999));
		return str_pad($string,$length,$rand, STR_PAD_LEFT);
	}

}
?>