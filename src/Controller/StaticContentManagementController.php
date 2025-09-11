<?php
namespace App\Controller;
use Cake\Core\Configure;
use Cake\Network\Exception\ForbiddenException;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\ORM\TableRegistry;
use Cake\Http\Response;
use Cake\Core\Exception\Exception;
class StaticContentManagementController extends AppController{

    #static Content Management page
    public function staticContentManagement(){
        #check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
        #get content data
        $table = TableRegistry::get(STATIC_CONTENT);
        # Conditions...
		$conditions = array();
		$conditions[CONDITIONS] = array(STATUS.' !=' => 3);
		$conditions[ORDER] =  array(SECTION_NAME => ASC);
		$conditions[LIMIT] =  PAGE_LIMIT;
        #delete post session data
        $session = $this->request->getSession();
		if($session->check(POSTDATA)){ $session->delete(POSTDATA);}
        #get record data
		$this->paginate = $conditions;
		$records = $this->paginate($table);
        $this->set(compact('records'));
    }
    /**************************** pagination page ****************************/
	public function staticContentFilter(){
        #check User Auth
        $this->checkValidSession();
		$table = TableRegistry::get(STATIC_CONTENT);
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
        if(isset($postData[SECTION_NAME]) && !empty($postData[SECTION_NAME])){
			$cond[SECTION_NAME] =  array(SECTION_NAME.' '.LIKE => '%'.trim($postData[SECTION_NAME]).'%');
		}
		if(isset($postData[TITLE]) && !empty($postData[TITLE])){
			$cond[TITLE] =  array(TITLE.' '.LIKE => '%'.trim($postData[TITLE]).'%');
		}
		if(isset($postData[STATUS]) && !empty($postData[STATUS])){
			$cond[STATUS] =  array(STATUS => $postData[STATUS]);
		}
		$conditions[CONDITIONS] = array(STATUS.' !=' => 3);
		$conditions[LIMIT] =  PAGE_LIMIT;
		$conditions[ORDER] =  array(SECTION_NAME => ASC);
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
		$records = $this->paginate($table);
        $this->set(compact('records'));
		# Pass all data to render for display...
		$this->render('staticContentFilter');
	}

    #edit Static Content
    public function editStaticContent($editID = NULL){
        #check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
        if($this->request->is('post')){
            #get request data
            $postData = $this->request->getData();
            $table = TableRegistry::get(STATIC_CONTENT);
            $getErrors = $table->newEntity($postData,[VALIDATE => 'update']);
            $editID = base64_encode($postData[EDIT_TOKEN]);
            if(!$getErrors->getErrors()){
                try{
                    $saveData[ID] = $this->decryptData($postData[EDIT_TOKEN]);
                    $saveData[TITLE] = $postData[TITLE];
                    $saveData[DESCRIPTIONS] = $postData[DESCRIPTIONS];
                    $tableEntity = $table->newEntity($saveData);
                    $table->save($tableEntity);
                    $this->Flash->set('Static content section information has been updated successfully.', array(ELEMENT => ALERT_SUCCESS));
                    $this->redirect(ADMIN_FOLDER.EDIT_STATIC_CONTENT_URL.'/'.$editID);
                }catch( \Exception $e){
                    $this->Flash->set(INTERNAL_ERROR, array(ELEMENT => ALERT_ERROR));
                    $this->redirect(ADMIN_FOLDER.EDIT_STATIC_CONTENT_URL.'/'.$editID);
                }
            }else{
                $error = $getErrors->getErrors();
                $this->setErrorMessage($error);
                $this->redirect(ADMIN_FOLDER.EDIT_STATIC_CONTENT_URL.'/'.$editID);
            }
        }
        if(!empty($editID)){
            #decrypt request ID
            $editID = $this->decryptData(base64_decode($editID));
            #get row data
            $table = TableRegistry::get(STATIC_CONTENT);
            $editData = $table->find()->where(array(ID => $editID))->first();
            if(isset($editData->id) && !empty($editData->id)){
                $this->set(compact('editData'));
            }else{
                $this->redirect(ADMIN_FOLDER.STATIC_CONTENT_MANAGEMENT_URL.'/');
            }
        }else{
            $this->redirect(ADMIN_FOLDER.STATIC_CONTENT_MANAGEMENT_URL.'/');
        }
    }

    #setErrorMessage
    function setErrorMessage($error){
        if(isset($error[TITLE][CHECK_EMPTY]) && !empty($error[TITLE][CHECK_EMPTY])){
            $this->Flash->set($error[TITLE][CHECK_EMPTY], array(ELEMENT => ALERT_ERROR));
        }
        if(isset($error[DESCRIPTIONS][CHECK_EMPTY]) && !empty($error[DESCRIPTIONS][CHECK_EMPTY])){
            $this->Flash->set($error[DESCRIPTIONS][CHECK_EMPTY], array(ELEMENT => ALERT_ERROR));
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

}
?>
