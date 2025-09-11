<?php
declare(strict_types=1);
namespace App\Controller;
use Cake\Core\Configure;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;
use Cake\View\Exception\MissingTemplateException;
use Cake\ORM\TableRegistry;
use Cake\Core\Exception\Exception;
class AjaxController extends AppController{

	#save Admin Profile Image
	public function saveAdminProfileImage(){
		if($this->request->is(AJAX)){
			$session = $this->request->getSession();
			if($session->check(AUTHADMIN) && isset($_REQUEST[FILENAME]) && !empty($_REQUEST[FILENAME])){
				try{
					$adminsTable = TableRegistry::get(ADMINISTRATORS);
					$adminData = $adminsTable->find()->where(array(ID => $session->read(AUTHADMINID)))->count();
					if($adminData > 0){
						$saveData[ID] = $session->read(AUTHADMINID);
						$saveData[PROFILE_IMAGE] = $_REQUEST[FILENAME];
						$tableEntity = $adminsTable->newEntity($saveData);
						if($adminsTable->save($tableEntity)){
							e(SUCCESS); die;
						}
					}
				}catch( \Exception $e){
					e(ERROR); die;
				}
			}
			e(ERROR); die;
		}else{
			$this->redirect('/');
		}
	}

	#save logo Image
	public function saveSiteLogoImage(){
		if($this->request->is(AJAX)){
			$session = $this->request->getSession();
			if($session->check(AUTHADMIN) && isset($_REQUEST[FILENAME]) && !empty($_REQUEST[FILENAME])){
				try{
					$adminsTable = TableRegistry::get(SETTINGS);
					$adminData = $adminsTable->find()->where(array(ID => 1))->count();
					if($adminData > 0){
						$saveData[ID] = $session->read(AUTHADMINID);
						$saveData[LOGO] = $_REQUEST[FILENAME];
						$tableEntity = $adminsTable->newEntity($saveData);
						if($adminsTable->save($tableEntity)){
							e(SUCCESS); die;
						}
					}
				}catch( \Exception $e){
					e(ERROR); die;
				}
			}
			e(ERROR); die;
		}else{
			$this->redirect('/');
		}
	}

	#Change Status
	public function changeStatus(){
		if($this->request->is(AJAX)){
			$session = $this->request->getSession();
			if(!$session->check(AUTHADMIN)){
				e(ERROR);die;
			}
			try{
				$Table = TableRegistry::get($_REQUEST[MODEL]);
				$status = $_REQUEST[CURRENTSTATUS] == 1 ? 2 : 1 ;
				$tableEntity[ID] = $this->decryptData($_REQUEST[DATATOKEN]);
				$tableEntity[STATUS] = $status;
				$tableEntity = $Table->newEntity($tableEntity);
				$Table->save($tableEntity);
				e(SUCCESS); die;
			}catch( \Exception $e){
				e(ERROR); die;
			}
		}else{
			$this->redirect('/');
		}
	}

	#Change Status
	public function changeVerifyStatus(){
		if($this->request->is(AJAX)){
			$session = $this->request->getSession();
			if(!$session->check(AUTHADMIN)){
				e(ERROR);die;
			}
			try{
				$Table = TableRegistry::get($_REQUEST[MODEL]);
				$status = $_REQUEST[CURRENTSTATUS] == 1 ? 2 : 1 ;
				$tableEntity[ID] = $this->decryptData($_REQUEST[DATATOKEN]);
				$tableEntity['verify_status'] = $status;
				$tableEntity = $Table->newEntity($tableEntity);
				$Table->save($tableEntity);
				e(SUCCESS); die;
			}catch( \Exception $e){
				e(ERROR); die;
			}
		}else{
			$this->redirect('/');
		}
	}

	# Check Password
	public function checkpassword(){
		if($this->request->is(AJAX)){
			$session = $this->request->getSession();
			$postData = $this->request->getData();
			$oldpassword = $this->encryptData($postData['oldpassword']);
			$chkid = $session->read(AUTHADMINID);
			$table = TableRegistry::get(USERS);
			$oldPassword = $table->find()->where(array(PASSWORD => $oldpassword, ID => $chkid))->count();
			if($oldPassword > 0){
				e(SUCCESS);die;
			}else{
				e('not exist');die;
			}
		}else{
			$this->redirect('/');
		}
	}

	public function updateOrder(){
		$this->viewBuilder()->setLayout('false');
		if($this->request->is('Ajax')){
			$actualVal = 0;
			$postData = $this->request->getData();
			$id = $postData['id'];
			$prev = $postData['prev'];
			$modal = $this->decryptData($postData['modal']);
			$currval = $postData['curval'];

			$table = TableRegistry::get($modal);
			$query = $table->find();
			$record= $query->select(array('max_order' => $query->func()->max(ORDERING)))->first();
			$actualVal = $record['max_order'];
			if($currval <= $actualVal && $currval != 0 && is_numeric($currval)){
				$data = $table->find()->where(array(ORDERING => $currval))->first();
				#save current row
				$saveData['id'] = $id;
				$saveData[ORDERING] = $currval;
				$tableEntity = $table->newEntity($saveData);
				$table->save($tableEntity);

				#save previous row
				$newData['id'] = $data['id'];
				$newData[ORDERING] = $prev;
				$tableEntityNew = $table->newEntity($newData);
				$table->save($tableEntityNew);
			}
		}
		exit;
	}

	public function getWeight(){
		if($this->request->is('Ajax')){
			$postData = $this->request->getData();
			$table = TableRegistry::get('Weights');
			$weight = $table->find('list', ['keyField' => 'id','valueField' => 'weight'])->where(['weight_type_id' => $postData['weightId'], 'status' => 1])->order(['id' => 'asc']);
			$this->set(compact('weight'));
		}
	}
 
	public function getState(){
		if($this->request->is('Ajax')){
			$postData = $this->request->getData();
			$table = TableRegistry::get('States');
			$states = $table->find('list', ['keyField' => 'id','valueField' => 'state'])->where(['country_id' => $postData['countryId'], 'status' => 1])->order(['country_id' => 'asc']);
			$this->set(compact('states'));
		}
	}

	public function getCity(){
		if($this->request->is('Ajax')){
			$postData = $this->request->getData();
			$table = TableRegistry::get('Cities');
			$cities = $table->find('list', ['keyField' => 'id','valueField' => 'city'])->where(['state_id' => $postData['stateId'], 'status' => 1])->order(['city' => 'asc']);
			$this->set(compact('cities'));
		}
	}

	public function deleteRecord(){
		$this->viewBuilder()->setLayout('false');
		if($this->request->is('Ajax')){
			$postData = $this->request->getData();
			if(!empty($postData['model']) && !empty($postData['rowId'])){
				$modal = $postData['model'];
				$rowId = $this->decryptData(base64_decode($postData['rowId']));
				$table = TableRegistry::get($modal);

				if($modal == 'Users'){
					$deleteRecord = $table->find()->where(array(ID => $rowId))->first();
					$imageName = $this->decryptData($deleteRecord->profile);
					if(file_exists(WWW_ROOT.'img/users/'.$imageName)){
						unlink(WWW_ROOT.'img/users/'.$imageName);
					}
				}
				if($modal == 'Billings'){
					$table2 = TableRegistry::get('BillingProducts');
					$products = $table2->find()->select('id')->where(array('bill_id' => $rowId))->all();
					if($products->count() > 0){
						foreach($products as $keys => $product){
							$record = $table2->get($product->id);
         					$table2->delete($record);
						}	
					}
				}
				if($modal == 'BillingProducts'){
					$table2 = TableRegistry::get('BillingProducts');
					$deleteRecord = $table2->find()->where(array(ID => $rowId))->first();
					$imageName = $deleteRecord->banner;
					if(file_exists(WWW_ROOT.'img/banners/'.$imageName)){
						unlink(WWW_ROOT.'img/banners/'.$imageName);
					}
				}
				if($modal == 'Testimonials'){
					$deleteRecord = $table->find()->where(array(ID => $rowId))->first();
					$imageName = $deleteRecord->profile;
					if(file_exists(WWW_ROOT.'img/testimonials/'.$imageName)){
						unlink(WWW_ROOT.'img/testimonials/'.$imageName);
					}
				}
				if($modal == 'Orders'){
					$order = $table->find()->where(array(ID => $rowId))->first();
					$orderId = $order->id;
					$productOrdTbl = TableRegistry::get('ProductOrder');
					$products = $productOrdTbl->find()->where(array('order_id' => $orderId))->all();
						if($products->count() > 0){
							foreach($products as $keys => $product){
							if(isset($product->id)){
								if(file_exists(WWW_ROOT.'img/products/'.$product->product_image)){
									unlink(WWW_ROOT.'img/products/'.$product->product_image);
								}		
								$records = $productOrdTbl->get($product->id);
								$table->delete($records);
							}
						}
					}
				}
				if($modal == 'ProductOrder'){
					$deleteRecord = $table->find()->where(array(ID => $rowId))->first();
					$imageName = $deleteRecord->product_image;
					if(file_exists(WWW_ROOT.'img/products/'.$imageName)){
						unlink(WWW_ROOT.'img/products/'.$imageName);
					}
				}

				/*if($model == 'weights'){
					$data = $table->find()->where(array('ordering' => 'asc'))->all();
					$i = 1;
					foreach($data as $val){
						$setData['id'] = $val->id;
						$setData['ordreing'] = $i;
						
						$this->$modal->save($setData);
						
						$i++;	
					}
				}*/
				$record = $table->get($rowId);
         		$table->delete($record);
			}
		}
		exit;
	}
}
