<?php
namespace App\Controller;
use Cake\Core\Configure;
use Cake\Network\Exception\ForbiddenException;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\ORM\TableRegistry;
use Cake\Http\Response;
use Cake\Core\Exception\Exception;

class WeightsController extends AppController{
    
    #weight types page
     public function weightTypes(){
        #check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
        #get content data

        $table = TableRegistry::get('WeightTypes');
        # Conditions...

        $conditions = array();
        $conditions[CONDITIONS] = array(STATUS.' !=' => 3);
        $conditions[ORDER] =  array('id' => 'desc');
        $conditions[LIMIT] =  PAGE_LIMIT;
        #delete post session data

        $session = $this->request->getSession();
        if($session->check(POSTDATA)){ $session->delete(POSTDATA);}

        #get record data
        $this->paginate = $conditions;
        $weightTypes = $this->paginate($table);
        $this->set(compact('weightTypes'));
    }

     public function weightTypeFilter(){
        #check User Auth
        $this->checkValidSession();
        $table = TableRegistry::get('WeightTypes');
        
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
        if(isset($postData['type']) && !empty($postData['type'])){
            $cond['type'] =  array('type'.' '.LIKE => '%'.trim($postData['type']).'%');
        }
       
        if(isset($postData[STATUS]) && !empty($postData[STATUS])){
            $cond[STATUS] =  array(STATUS => $postData[STATUS]);
        }

        $conditions[CONDITIONS] = array(STATUS.' !=' => 3);
        $conditions[LIMIT] =  PAGE_LIMIT;
        $conditions[ORDER] =  array('id' => 'desc');

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
        $weightTypes = $this->paginate($table);
        $this->set(compact('weightTypes'));

        # Pass all data to render for display...
        $this->render('weightTypeFilter');
    }

    #add weight type Content
    public function addWeightType(){
        #check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
        if($this->request->is(['post','put'])){
            #get request data
            $postData = $this->request->getData();
            $table = TableRegistry::get('WeightTypes');  
            $getErrors = $table->newEntity($postData,[VALIDATE => 'add']);
            if(isset($postData['type'])  &&  !empty($postData['type'])){ 
                $existWeightType = $table->find()->where(array('type' => trim(ucwords($postData['type']))))->all();
                if($existWeightType->count() > 0){
                    $this->Flash->set('Weight type already exists.', array(ELEMENT => ALERT_ERROR));
                    $this->redirect(ADMIN_FOLDER.'add-weight-type/');
                }
            }
            if(!$getErrors->getErrors() && $existWeightType->count() == 0){
                try{
                    $query = $table->find();
                    
                    $saveData['type'] = trim(ucwords($postData['type']));
                  
                    if(isset($postData[STATUS])){
                        $saveData[STATUS] = $postData[STATUS];
                    }

                    $saveData[CREATED] = time();
                    $saveData[MODIFIED] = time();
                    $tableEntity = $table->newEntity(array_map('trim',preg_replace('/\s+/', ' ', $saveData)));
                    $record = $table->save($tableEntity);
                    $this->Flash->set('Weight type has been created successfully.', array(ELEMENT => ALERT_SUCCESS));
                    $this->redirect(ADMIN_FOLDER.'edit-weight-type'.'/'.base64_encode($this->encryptData($record->id)));

                }catch( \Exception $e){
                    $this->Flash->set(INTERNAL_ERROR, array(ELEMENT => ALERT_ERROR));
                    $this->redirect(ADMIN_FOLDER.'add-weight-type'.'/');
                }

            }else{

                $error = $getErrors->getErrors();
                $this->setErrorMessage($error);
                $this->redirect(ADMIN_FOLDER.'add-weight-type'.'/');
            }
        }
    }


    #edit weight type content
    public function editWeightType($editID = NULL){
        #check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
        if($this->request->is(['post','put'])){
            #get request data
            $postData = $this->request->getData(); 
            $table = TableRegistry::get('WeightTypes');
            $getErrors = $table->newEntity($postData,[VALIDATE => UPDATE]);
           
            $editID = base64_encode($postData[EDIT_TOKEN]);
            if(isset($postData['type']) && !empty($postData['type'])){
                $existTitle = $table->find()->where(array('type' => trim(ucwords($postData['type'])),ID.' !=' => $this->decryptData($postData[EDIT_TOKEN])))->all();
                if($existTitle->count() > 0){
                    if($existTitle->count() > 0){
                        $this->Flash->set('Weight type already exists.', array(ELEMENT => ALERT_ERROR));
                    }
                    $this->redirect(ADMIN_FOLDER.'edit-weight-type'.'/'.$editID);
                }
            }

            if(!$getErrors->getErrors() && $existTitle->count() == 0){
                try{
                    $saveData[ID] = $this->decryptData($postData[EDIT_TOKEN]);
                    $saveData['type'] = trim(ucwords($postData['type']));
                    if(isset($postData[STATUS])){
                        $saveData[STATUS] = $postData[STATUS];
                    }else{
                        $saveData[STATUS] = 2;
                    }

                    $saveData[MODIFIED] = time();
                    $tableEntity = $table->newEntity(array_map('trim',preg_replace('/\s+/', ' ', $saveData)));
                    $table->save($tableEntity);
                    $this->Flash->set('Weight type has been updated successfully.', array(ELEMENT => ALERT_SUCCESS));
                    $this->redirect(ADMIN_FOLDER.'edit-weight-type'.'/'.$editID);

                }catch( \Exception $e){
                    $this->Flash->set(INTERNAL_ERROR, array(ELEMENT => ALERT_ERROR));
                    $this->redirect(ADMIN_FOLDER.'edit-weight-type'.'/'.$editID);
                }

            }else{

                $error = $getErrors->getErrors();
               $this->setErrorMessage($error);
                $this->set(ALERT_ERROR,$error);
                $this->redirect(ADMIN_FOLDER.'edit-weight-type'.'/'.$editID);
            }

        }

        if(!empty($editID)){
            #decrypt request ID
            $editID = $this->decryptData(base64_decode($editID));
            
            #get row data
            $table = TableRegistry::get('WeightTypes');
            $editData = $table->find()->where(array(ID => $editID))->first();
            if(isset($editData->id) && !empty($editData->id)){
                $this->set(compact(EDITDATA));
            }else{
                $this->redirect(ADMIN_FOLDER.'edit-weight-type'.'/');
            }

        }else{
            $this->redirect(ADMIN_FOLDER.'edit-weight-type'.'/');
        }
    }

    #weights page
    public function weights(){
        #check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
        #get content data

        $table = TableRegistry::get(WEIGHTS);
        # Conditions...

        $conditions = array();
        $conditions[CONDITIONS] = array(STATUS.' !=' => 3);
        $conditions[ORDER] =  array('id' => 'desc');
        $conditions[LIMIT] =  PAGE_LIMIT;
        #delete post session data

        $session = $this->request->getSession();
        if($session->check(POSTDATA)){ $session->delete(POSTDATA);}

        #get record data
        $this->paginate = $conditions;
        $weights = $this->paginate($table);
        $this->set(compact('weights'));
        $this->set('weightType',$this->getWeightTypes());
    }

    /**************************** pagination page ****************************/

    public function weightFilter(){
        #check User Auth
        $this->checkValidSession();
        $table = TableRegistry::get(WEIGHTS);
        
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
        if(isset($postData[WEIGHT]) && !empty($postData[WEIGHT])){
            $cond[WEIGHT] =  array(WEIGHT.' '.LIKE => '%'.trim($postData[WEIGHT]).'%');
        }
        if(isset($postData['weight_type_id']) && !empty($postData['weight_type_id'])){
            $cond['weight_type_id'] =  array('weight_type_id' => $postData['weight_type_id']);
        }
        if(isset($postData[STATUS]) && !empty($postData[STATUS])){
            $cond[STATUS] =  array(STATUS => $postData[STATUS]);
        }

        $conditions[CONDITIONS] = array(STATUS.' !=' => 3);
        $conditions[LIMIT] =  PAGE_LIMIT;
        $conditions[ORDER] =  array('id' => 'desc');

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
        $weights = $this->paginate($table);
        $this->set(compact('weights'));
        $this->set('weightType',$this->getWeightTypes());

        # Pass all data to render for display...
        $this->render('weightFilter');
    }

    #edit Static Content
    public function addWeight(){
        #check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
        if($this->request->is(['post','put'])){
            #get request data
            $postData = $this->request->getData();//print_r( $postData); die;
            $table = TableRegistry::get(WEIGHTS);  
            $getErrors = $table->newEntity($postData,[VALIDATE => 'add']);
            if(isset($postData['weight_type_id']) && isset($postData[WEIGHT]) && !empty($postData[WEIGHT]) &&  !empty($postData['weight_type_id'])){ 
                $existWeight = $table->find()->where(array('weight_type_id' => $postData['weight_type_id'],WEIGHT => trim($postData[WEIGHT])))->all();
                if($existWeight->count() > 0){
                    $this->Flash->set('Weight type and weight already exists.', array(ELEMENT => ALERT_ERROR));
                    $this->redirect(ADMIN_FOLDER.'add-weight/');
                }
            }
            if(!$getErrors->getErrors() && $existWeight->count() == 0){
                try{
                    $query = $table->find();
                   
                    $saveData['weight_type_id'] = trim($postData['weight_type_id']);
                    $saveData[WEIGHT] = trim($postData[WEIGHT]);
                    if(isset($postData[STATUS])){
                        $saveData[STATUS] = $postData[STATUS];
                    }

                    $saveData[CREATED] = time();
                    $saveData[MODIFIED] = time();
                    $tableEntity = $table->newEntity(array_map('trim',preg_replace('/\s+/', ' ', $saveData)));
                    $record = $table->save($tableEntity);
                    $this->Flash->set('Weight has been created successfully.', array(ELEMENT => ALERT_SUCCESS));
                    $this->redirect(ADMIN_FOLDER.'edit-weight'.'/'.base64_encode($this->encryptData($record->id)));

                }catch( \Exception $e){
                    $this->Flash->set(INTERNAL_ERROR, array(ELEMENT => ALERT_ERROR));
                    $this->redirect(ADMIN_FOLDER.'add-weight'.'/');
                }

            }else{

                $error = $getErrors->getErrors();
                $this->setErrorMessage($error);
                $this->redirect(ADMIN_FOLDER.'add-weight'.'/');
            }
        }
         $this->set('weightTypeList',$this->getWeightTypes());
    }


    #edit Country
    public function editWeight($editID = NULL){
        #check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
        if($this->request->is(['post','put'])){
            #get request data
            $postData = $this->request->getData(); 
            $table = TableRegistry::get(WEIGHTS);
            $getErrors = $table->newEntity($postData,[VALIDATE => UPDATE]);
            
            $editID = base64_encode($postData[EDIT_TOKEN]);
            if(isset($postData[WEIGHT]) && isset($postData['weight_type_id']) && !empty($postData[WEIGHT]) && !empty($postData['weight_type_id'])){
                $existTitle = $table->find()->where(array(WEIGHT => trim($postData[WEIGHT]),'weight_type_id' => trim($postData['weight_type_id']),ID.' !=' => $this->decryptData($postData[EDIT_TOKEN])))->all();
              
                    if($existTitle->count() > 0){
                        $this->Flash->set('Weight type and weight already exists.', array(ELEMENT => ALERT_ERROR));
                    }
                    $this->redirect(ADMIN_FOLDER.'edit-weight'.'/'.$editID);
                }
           

            if(!$getErrors->getErrors() && $existTitle->count() == 0){
                try{
                    $saveData[ID] = $this->decryptData($postData[EDIT_TOKEN]);
                    $saveData['weight_type_id'] = trim($postData['weight_type_id']);
                     $saveData[WEIGHT] = trim($postData[WEIGHT]);
                    if(isset($postData[STATUS])){
                        $saveData[STATUS] = $postData[STATUS];
                    }else{
                        $saveData[STATUS] = 2;
                    }

                    $saveData[MODIFIED] = time();
                    $tableEntity = $table->newEntity(array_map('trim',preg_replace('/\s+/', ' ', $saveData)));
                    $table->save($tableEntity);
                    $this->Flash->set('Weight has been updated successfully.', array(ELEMENT => ALERT_SUCCESS));
                    $this->redirect(ADMIN_FOLDER.'edit-weight'.'/'.$editID);

                }catch( \Exception $e){
                    $this->Flash->set(INTERNAL_ERROR, array(ELEMENT => ALERT_ERROR));
                    $this->redirect(ADMIN_FOLDER.'edit-weight'.'/'.$editID);
                }

            }else{

                $error = $getErrors->getErrors();
               $this->setErrorMessage($error);
                $this->set(ALERT_ERROR,$error);
                $this->redirect(ADMIN_FOLDER.'edit-weight'.'/'.$editID);
            }

        }

        if(!empty($editID)){
            #decrypt request ID
            $editID = $this->decryptData(base64_decode($editID));
            
            #get row data
            $table = TableRegistry::get(WEIGHTS);
            $editData = $table->find()->where(array(ID => $editID))->first();
            if(isset($editData->id) && !empty($editData->id)){
                $this->set(compact(EDITDATA));
                $this->set('weightTypeList',$this->getWeightTypes());
            }else{
                $this->redirect(ADMIN_FOLDER.'edit-weight'.'/');
            }

        }else{
            $this->redirect(ADMIN_FOLDER.'edit-weight'.'/');
        }
    }
    
     #services page
    public function distances(){
        #check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
        #get content data

        $table = TableRegistry::get('Distances');
        # Conditions...

        $conditions = array();
        $conditions[CONDITIONS] = array(STATUS.' !=' => 3);
        $conditions[ORDER] =  array(ID => 'desc');
        $conditions[LIMIT] =  PAGE_LIMIT;
        #delete post session data

        $session = $this->request->getSession();
        if($session->check(POSTDATA)){ $session->delete(POSTDATA);}

        #get record data
        $this->paginate = $conditions;
        $distances = $this->paginate($table);
        $this->set(compact('distances'));
        $this->set('weightList',$this->getWeights());
        //$this->set('weightType',$this->getWeightType());
        $this->set('weightType',$this->getWeightTypes());
    }

    /**************************** pagination page ****************************/

    public function distanceFilter(){
        #check User Auth
        $this->checkValidSession();
        $table = TableRegistry::get('Distances');
        
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
        if(isset($postData['weight_type_id']) && !empty($postData['weight_type_id'])){
            $cond['weight_type_id'] =  array('weight_type_id' => trim($postData['weight_type_id']));
        }
        if(isset($postData['weight_id']) && !empty($postData['weight_id'])){
            $cond['weight_id'] =  array('weight_id' => trim($postData['weight_id']),'weight_type_id' => trim($postData['weight_type_id']));
        }
        if(isset($postData['dist_from']) && !empty($postData['dist_from'])){
            $cond['dist_from'] =  array('dist_from >= '=> trim($postData['dist_from']));
        }
        if(isset($postData['dist_to']) && !empty($postData['dist_to'])){
            $cond['dist_to'] =  array('dist_to <= ' => trim($postData['dist_to']));
        }
        
        if(isset($postData['price']) && !empty($postData['price'])){
            $cond['price'] =  array('price' => trim($postData['price']));
        }

        if(isset($postData[STATUS]) && !empty($postData[STATUS])){
            $cond[STATUS] =  array(STATUS => $postData[STATUS]);
        }

        $conditions[CONDITIONS] = array(STATUS.' !=' => 3);
        $conditions[LIMIT] =  PAGE_LIMIT;
        $conditions[ORDER] =  array(ID => 'desc');
        //print_r($conditions);
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
        $distances = $this->paginate($table);
        $this->set(compact('distances'));
        $this->set('weightType',$this->getWeightTypes());

        # Pass all data to render for display...
        $this->render('distanceFilter');
    }

    #edit Static Content
    public function addDistance(){
        #check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
        if($this->request->is(['post','put'])){
            #get request data
            $postData = $this->request->getData();//pr( $postData);die;
            $table = TableRegistry::get('Distances');
            $existdata = $table->find('all')->where(array('Distances.weight_type_id' => $postData['weight_type_id'],'weight_id' => $postData['weight_id'],'dist_from' => $postData['dist_from'],'dist_to' => $postData['dist_to']));
           if($existdata->count() > 0){
                        $this->Flash->set('This Weight type,weight,distance from and distance to already exists.', array(ELEMENT => ALERT_ERROR));
                    }
                     $this->redirect(ADMIN_FOLDER.'add-distance/');
                
            $getErrors = $table->newEntity($postData,[VALIDATE => 'add']);
            if(!$getErrors->getErrors() && $existdata->count() == 0){
                try{
                    $saveData['weight_type_id'] = $postData['weight_type_id'];
                    $saveData['weight_id'] = $postData['weight_id'];
                    $saveData['dist_from'] = trim($postData['dist_from']);
                    $saveData['dist_to'] = trim($postData['dist_to']);
                    $saveData['price'] = trim($postData['price']);
                    if(isset($postData[STATUS])){
                        $saveData[STATUS] = $postData[STATUS];
                    }

                    $saveData[CREATED] = time();
                    $saveData[MODIFIED] = time();
                    $tableEntity = $table->newEntity(array_map('trim',preg_replace('/\s+/', ' ', $saveData)));
                    $record = $table->save($tableEntity);
                    $this->Flash->set('Distance has been created successfully.', array(ELEMENT => ALERT_SUCCESS));
                    $this->redirect(ADMIN_FOLDER.'edit-distance'.'/'.base64_encode($this->encryptData($record->id)));

                }catch( \Exception $e){
                    $this->Flash->set(INTERNAL_ERROR, array(ELEMENT => ALERT_ERROR));
                    $this->redirect(ADMIN_FOLDER.'add-distance'.'/');
                }

            }else{

                $error = $getErrors->getErrors();
                $this->setErrorMessage($error);
                $this->redirect(ADMIN_FOLDER.'add-distance'.'/');
            }
        }
        $this->set('weightList',$this->getWeights());
        $this->set('weightTypeList',$this->getWeightTypes());
    }


    #edit Country
    public function editDistance($editID = NULL){
        #check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
        if($this->request->is(['post','put'])){
            #get request data
            $postData = $this->request->getData();
            $table = TableRegistry::get('Distances');
            $existdata = $table->find('all')->where(array('Distances.weight_type_id' => $postData['weight_type_id'],'weight_id' => $postData['weight_id'],'dist_from' => $postData['dist_from'],'dist_to' => $postData['dist_to']));
           if($existdata->count() > 0){
                        $this->Flash->set('This Weight type,weight,distance from and distance to already exists.', array(ELEMENT => ALERT_ERROR));
                    }
                 $this->redirect(ADMIN_FOLDER.'edit-distance'.'/'.$editID);
            $getErrors = $table->newEntity($postData,[VALIDATE => UPDATE]);

            $editID = base64_encode($postData[EDIT_TOKEN]);
            if(!$getErrors->getErrors() && $existdata->count() ==0){
                try{
                    $saveData[ID] = $this->decryptData($postData[EDIT_TOKEN]);
                    $saveData['weight_type_id'] = $postData['weight_type_id'];
                    $saveData['weight_id'] = $postData['weight_id'];
                    $saveData['dist_from'] = trim($postData['dist_from']);
                    $saveData['dist_to'] = trim($postData['dist_to']);
                    $saveData['price'] = trim($postData['price']);
                    if(isset($postData[STATUS])){
                        $saveData[STATUS] = $postData[STATUS];
                    }else{
                        $saveData[STATUS] = 2;
                    }

                    $saveData[MODIFIED] = time();
                    $tableEntity = $table->newEntity(array_map('trim',preg_replace('/\s+/', ' ', $saveData)));
                    $table->save($tableEntity);
                    $this->Flash->set('Distance has been updated successfully.', array(ELEMENT => ALERT_SUCCESS));
                    $this->redirect(ADMIN_FOLDER.'edit-distance'.'/'.$editID);

                }catch( \Exception $e){
                    $this->Flash->set(INTERNAL_ERROR, array(ELEMENT => ALERT_ERROR));
                    $this->redirect(ADMIN_FOLDER.'edit-distance'.'/'.$editID);
                }

            }else{

                $error = $getErrors->getErrors();
                $this->setErrorMessage($error);
                $this->set(ALERT_ERROR,$error);
                $this->redirect(ADMIN_FOLDER.'edit-distance'.'/'.$editID);
            }

        }

        if(!empty($editID)){
            #decrypt request ID
            $editID = $this->decryptData(base64_decode($editID));
            
            #get row data
            $table = TableRegistry::get('Distances');
            $editData = $table->find()->where(array(ID => $editID))->first();
            if(isset($editData->id) && !empty($editData->id)){
                $this->set(compact(EDITDATA));
                $this->set('weightList',$this->getWeights());
                $this->set('weightTypeList',$this->getWeightTypes());
            }else{
                $this->redirect(ADMIN_FOLDER.'edit-distance'.'/');
            }

        }else{
            $this->redirect(ADMIN_FOLDER.'edit-distance'.'/');
        }
    }

    #setErrorMessage
    function setErrorMessage($error){
        
        if(isset($error['weight']['checkUniqueName']) && !empty($error['weight']['checkUniqueName'])){
            $this->Flash->set($error['weight']['checkUniqueName'], array(ELEMENT => ALERT_ERROR));
        }
        if(isset($error['weight']['checkUniqueNameUpdate']) && !empty($error['weight']['checkUniqueNameUpdate'])){
            $this->Flash->set($error['weight']['checkUniqueNameUpdate'], array(ELEMENT => ALERT_ERROR));
        }

         if(isset($error['type'][CHECK_EMPTY]) && !empty($error['type'][CHECK_EMPTY])){
            $this->Flash->set($error['type'][CHECK_EMPTY], array(ELEMENT => ALERT_ERROR));
        }
        if(isset($error['weight_type_id'][CHECK_EMPTY]) && !empty($error['weight_type_id'][CHECK_EMPTY])){
            $this->Flash->set($error['weight_type_id'][CHECK_EMPTY], array(ELEMENT => ALERT_ERROR));
        }
        if(isset($error['weight'][CHECK_EMPTY]) && !empty($error['weight'][CHECK_EMPTY])){
            $this->Flash->set($error['weight'][CHECK_EMPTY], array(ELEMENT => ALERT_ERROR));
        }
        if(isset($error['weight_id'][CHECK_EMPTY]) && !empty($error['weight_id'][CHECK_EMPTY])){
            $this->Flash->set($error['weight_id'][CHECK_EMPTY], array(ELEMENT => ALERT_ERROR));
        }
        if(isset($error['dist_from'][CHECK_EMPTY]) && !empty($error['dist_from'][CHECK_EMPTY])){
            $this->Flash->set($error['dist_from'][CHECK_EMPTY], array(ELEMENT => ALERT_ERROR));
        }
        if(isset($error['dist_to'][CHECK_EMPTY]) && !empty($error['dist_to'][CHECK_EMPTY])){
            $this->Flash->set($error['dist_to'][CHECK_EMPTY], array(ELEMENT => ALERT_ERROR));
        }
        if(isset($error['price'][CHECK_EMPTY]) && !empty($error['price'][CHECK_EMPTY])){
            $this->Flash->set($error['price'][CHECK_EMPTY], array(ELEMENT => ALERT_ERROR));
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
    
    function getWeights(){
        $table = TableRegistry::get(WEIGHTS);
         return $table->find('list', ['keyField' => 'id','valueField' => 'weight'])->where(['status' => 1])->order(['weight' => 'asc']);
    }
/*
    
     function getWeightType(){
        $table = TableRegistry::get(WEIGHTS);
         return $table->find('list', ['keyField' => 'id','valueField' => 'weight_type'])->where(['status' => 1])->order(['ordering' => 'asc']);
    }*/
    function getWeightTypes(){
        $table = TableRegistry::get('WeightTypes');
         return $table->find('list', ['keyField' => 'id','valueField' => 'type'])->where(['status' => 1])->order(['type' => 'asc']);
    }

}
?>