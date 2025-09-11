<?php
namespace App\Controller;
use Cake\Core\Configure;
use Cake\Network\Exception\ForbiddenException;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\ORM\TableRegistry;
use Cake\Http\Response;
use Cake\Core\Exception\Exception;

class ReportsController extends AppController{

    #countries page
    public function salesItemsReport(){
        #check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
        #get content data
        $table = TableRegistry::get('Billings');
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
		$billings = $this->paginate($table);
        $this->set(compact('billings'));
    }

	/**************************** pagination page ****************************/
	public function salesItemsReportFilter(){
        #check User Auth
        $this->checkValidSession();
		$table = TableRegistry::get('Billings');
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
		if(!empty($postData['start_date']) || !empty($postData['end_date'])){
            
            $sdate = strtotime($postData['start_date']);
            $edate = strtotime($postData['end_date'].'11:59:59 pm');
            
            if(!empty($sdate) && empty($edate)){
                $cond['created'] =  array('created >=' => $sdate);
            }else if(empty($sdate) && !empty($edate)){
                $cond['created'] =  array('created <=' => $edate);
            }else{
                $cond['created'] =  array('created >=' => $sdate, 'created <=' => $edate);
            }

        }else{
			$sdate = strtotime(date('Y-m-d'));
			$edate = strtotime(date('Y-m-d').'11:59:59 pm');
			$cond['created'] =  array('created >=' => $sdate, 'created <=' => $edate);
		}
		if(isset($postData['invoice_no']) && !empty($postData['invoice_no'])){
			$cond['invoice_no'] =  array('invoice_no'.' '.LIKE => '%'.trim($postData['invoice_no']).'%');
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
		$billings = $this->paginate($table);
        $this->set(compact('billings'));
		# Pass all data to render for display...
		$this->render('salesItemsReportFilter');
	}
	
	#countries page
    public function oldGoldReport(){
        #check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
        #get content data
        $table = TableRegistry::get('Orders');
        # Conditions...
		$conditions = array();		
		$conditions[CONDITIONS] = array('return_gold'.' !=' => NULL);
				
		$query = $table->find();
		$orders = $query->select(['return_gold','percentage', 'total' => $query->func()->sum('return_gold_amt')])->where(['return_gold !=' => ''])->group(['percentage']);
		
		/*$conditions[CONDITIONS] = array(STATUS.' !=' => 3);
		$conditions[ORDER] =  array(ID => DESC);
		$conditions[LIMIT] =  PAGE_LIMIT;
        #delete post session data
        $session = $this->request->getSession();
		if($session->check(POSTDATA)){ $session->delete(POSTDATA);}
        #get record data
		$this->paginate = $conditions;
		$orders = $this->paginate($table);*/
        $this->set(compact('orders'));
    }

	/**************************** pagination page ****************************/
	public function oldGoldReportFilter(){
        #check User Auth
        $this->checkValidSession();
		$table = TableRegistry::get('Orders');
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
		if(!empty($postData['start_date']) || !empty($postData['end_date'])){
            
            $sdate = strtotime($postData['start_date']);
            $edate = strtotime($postData['end_date'].'11:59:59 pm');
            
            if(!empty($sdate) && empty($edate)){
                $cond['created'] =  array('created >=' => $sdate);
            }else if(empty($sdate) && !empty($edate)){
                $cond['created'] =  array('created <=' => $edate);
            }else{
                $cond['created'] =  array('created >=' => $sdate, 'created <=' => $edate);
            }

        }
		if(isset($postData['invoice_no']) && !empty($postData['invoice_no'])){
			$cond['invoice_no'] =  array('invoice_no'.' '.LIKE => '%'.trim($postData['invoice_no']).'%');
		}
		
		$conditions[CONDITIONS] = array('return_gold !=' => '');
		
        #set next page number
		$pageNo = 0;
		if(isset($_REQUEST[PAGE])){
			$pageNo = $_GET[PAGE]-1;
		}		
		$i = 0;
		foreach($cond as $value){
			$conditions[CONDITIONS][$i] = $value;
			$i++;
		}
		
		$query = $table->find();
		$orders = $query->select(['return_gold','percentage', 'total' => $query->func()->sum('return_gold_amt')])->where($conditions[CONDITIONS])->group(['percentage']);
		# Set data...
        $this->set(compact('orders'));
		# Pass all data to render for display...
		$this->render('oldGoldReportFilter');
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