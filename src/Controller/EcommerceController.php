<?php
namespace App\Controller;
use Cake\Core\Configure;
use Cake\Network\Exception\ForbiddenException;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\ORM\TableRegistry;
use Cake\Http\Response;
use Cake\Core\Exception\Exception;

//require_once(ROOT . DS  . 'vendor' . DS  . 'ImageResize' . DS . 'ImageResize.php');
//use Eventviva\ImageResize;
//use Eventviva\ImageResizeException;

class EcommerceController extends AppController{
	
	#categories page
    public function salesManager(){
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
		$invoices = $this->paginate($table);
        $this->set(compact('invoices'));
    }

	/**************************** pagination page ****************************/
	public function salesManagerFilter(){
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
        if(isset($postData['customer_contact']) && !empty($postData['customer_contact'])){
			$cond['customer_contact'] =  array('customer_contact'.' '.LIKE => '%'.trim($postData['customer_contact']).'%');
		}
		if(isset($postData['invoice_id']) && !empty($postData['invoice_id'])){
			$cond['invoice_no'] =  array('invoice_no'.' '.LIKE => '%'.trim($postData['invoice_id']).'%');
		}
		if(isset($postData['customer_name']) && !empty($postData['customer_name'])){
			$cond['customer_name'] =  array('customer_name'.' '.LIKE => '%'.trim($postData['customer_name']).'%');
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
		$invoices = $this->paginate($table);
        $this->set(compact('invoices'));
		# Pass all data to render for display...
		$this->render('salesManagerFilter');
	}
	
	#edit Static Content
    public function addInvoice(){
        #check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
		$table = TableRegistry::get('Billings');
		$table2 = TableRegistry::get('BillingProducts');
        if($this->request->is(['post','put'])){
            #get request data
            $postData = $this->request->getData();
            $getErrors = $table->newEntity($postData);
            if(!$getErrors->getErrors()){
                try{
                    $saveData['customer_name'] = $postData['customer_name'];
					$saveData['customer_id'] = $postData['customer_id'];
					$saveData['customer_address'] = $postData['customer_address'];
					$saveData['delivery_address'] = $postData['delivery_address'];
					$saveData['customer_contact'] = $postData['customer_contact'];
					$saveData['invoice_no'] = $postData['invoice_no'];					
					$saveData['date'] = $postData['date'];
					$saveData['discount'] = $postData['discount'];
					$saveData['amount'] = $postData['grand_total'];
					$saveData['gst'] = $postData['gst'];
					$saveData['order_invoice_id'] = $postData['order_invoice_id'];
					$saveData['return_jewellery'] = $postData['return_jewellery'];
					$saveData['payment_type'] = $postData['payment_type'];
					$saveData[STATUS] = 1;
					$saveData[CREATED] = time();
					$saveData[MODIFIED] = time();					
					$tableEntity = $table->newEntity(array_map('trim',preg_replace('/\s+/', ' ', $saveData)));
                    if($record = $table->save($tableEntity)){
						$productTbl = TableRegistry::get('Products');								
						if(isset($postData['product_id'][0]) && !empty($postData['product_id'][0])){
							foreach($postData['product_id'] as $key => $product){							
								if(!empty($product)){
									$setData['bill_id'] = $record->id;
									$setData['product_id'] = $product;
									$setData['product_name'] = $postData['product_name'][$key];
									$setData['huid_code'] = $postData['huid_code'][$key];
									$setData['quantity'] = $postData['quantity'][$key];
									$setData['price'] = $postData['price'][$key];									
									if(isset($postData['purity'][$key]) && !empty($postData['purity'][$key])){
										$setData['purity'] = $postData['purity'][$key];
									}else{
										$setData['purity'] = '14 K';	
									}
									$setData['diam_stone_wgt'] = $postData['diam_stone_wgt'][$key];
									$setData['tunch'] = $postData['tunch'][$key];
									$setData['labour'] = $postData['labour'][$key];
									$setData['gross_wt'] = $postData['gross_wt'][$key];
									$setData['net_wt'] = $postData['net_wt'][$key];
									$setData['wstg'] = $postData['wstg'][$key];
									$setData['grand_total'] = $postData['total'][$key];
									
									$tableEntity2 = $table->newEntity(array_map('trim',preg_replace('/\s+/', ' ', $setData)));
									$table2->save($tableEntity2);
									
									$productQty = $productTbl->find()->select('qty')->where([ID => $product])->first();
									$updatedQty = $productQty->qty - $setData['quantity'];
									
									$queryOrdUpd = $productTbl->query();
									$queryOrdUpd->update()->set(['qty' => $updatedQty])->where([ID => $product])->execute();
								}
							}	
						}		
					}
                    $this->Flash->set('Invoice has been created successfully.', array(ELEMENT => ALERT_SUCCESS));
                    $this->redirect(ADMIN_FOLDER.'view-invoice'.'/'.base64_encode($this->encryptData($record->id)));

                }catch( \Exception $e){
                    $this->Flash->set(INTERNAL_ERROR, array(ELEMENT => ALERT_ERROR));
                    $this->redirect(ADMIN_FOLDER.'add-invoice'.'/');
                }
            }else{
                $error = $getErrors->getErrors();
                $this->setErrorMessage($error);
                $this->redirect(ADMIN_FOLDER.'add-invoice'.'/');
            }
        }
		$existUnique = $table->find()->select('invoice_no')->where(['invoice_no !=' => ''])->order(['id' => 'desc'])->first();
		$invoice = '10001';
		if(isset($existUnique['invoice_no']) && $existUnique['invoice_no'] != ''){
			$explodeUnique = $existUnique['invoice_no'];
			if(isset($explodeUnique)){
				$uniqcount = $explodeUnique+1;
				$invoice = $uniqcount;
			}else{
				$invoice = '10001';
			}
		}		
		$this->set('invoice',$invoice);
		
		$settingTbl = TableRegistry::get('Settings');
		$existLabour = $settingTbl->find()->select(['labour','gst'])->where(['id' => 1])->first();
		$this->set('labour',$existLabour->labour);
		$this->set('gst',$existLabour->gst);
    }

	#edit Country
    public function editInvoice($editID = NULL){
        #check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
		
		if(!empty($editID)){
            #decrypt request ID
            $editID = $this->decryptData(base64_decode($editID));			
            #get row data
            $table = TableRegistry::get('Billings');
			$table2 = TableRegistry::get('BillingProducts');
			$ordrTbl = TableRegistry::get('Orders');
            $editRow = $table->find()->select(['order_invoice_id','id'])->where(array(ID => $editID))->first();
            if(isset($editRow->id) && !empty($editRow->id)){
				$settingTbl = TableRegistry::get('Settings');
				$existLabour = $settingTbl->find()->select(['labour','gst'])->where(['id' => 1])->first();
				$this->set('gst',$existLabour->gst);
				$this->set('labour',$existLabour->labour);
				
				$products = $table2->find()->where(array('bill_id' => $editID))->all();
				if($products->count() > 0){
					$total = 0;
					foreach($products as $key => $product){
						$sumTotal = $product->grand_total;
						$total+= $sumTotal;
					}
					$gstAmt = $total/100*$existLabour->gst;
					$queryBillingUpd = $table->query();
					$queryBillingUpd->update()->set(['gst' => $gstAmt])->where([ID => $editID])->execute();
				}			
				$products = $table2->find()->where(array('bill_id' => $editID))->all();				
				$orderData = $ordrTbl->find()->select(['customer_name','invoice_id','advance_amt'])->where(['invoice_id' => $editRow->order_invoice_id])->first();
				$editData = $table->find()->where(array(ID => $editID))->first();
                $this->set(compact(EDITDATA,'products','orderData'));
            }else{
                $this->redirect(ADMIN_FOLDER.'sales-manager'.'/');
            }
        }else{
            $this->redirect(ADMIN_FOLDER.'sales-manager'.'/');
        }
		
        if($this->request->is(['post','put'])){
            #get request data
            $postData = $this->request->getData();
            $table = TableRegistry::get('Billings');
			$table2 = TableRegistry::get('BillingProducts');
            $getErrors = $table->newEntity($postData);
            $editID = base64_encode($postData[EDIT_TOKEN]);
            if(!$getErrors->getErrors()){
                try{
                    $saveData[ID] = $this->decryptData($postData[EDIT_TOKEN]);
                   	$saveData['customer_name'] = $postData['customer_name'];
					$saveData['customer_id'] = $postData['customer_id'];
					$saveData['customer_address'] = $postData['customer_address'];
					$saveData['delivery_address'] = $postData['delivery_address'];
					$saveData['customer_contact'] = $postData['customer_contact'];
					$saveData['invoice_no'] = $postData['invoice_no'];
					$saveData['date'] = $postData['date'];
					$saveData['discount'] = $postData['discount'];
					if(isset($postData['grand_total']) && !empty($postData['grand_total'])){
						$saveData['amount'] = $postData['grand_total'];
					}else{
						$saveData['amount'] = $editData->amount;
					}
					$saveData['gst'] = $postData['gst'];
					$saveData['order_invoice_id'] = $postData['order_invoice_id'];
					$saveData['return_jewellery'] = $postData['return_jewellery'];
					$saveData['payment_type'] = $postData['payment_type'];
					$saveData[STATUS] = 1;
					$saveData[CREATED] = time();
					$saveData[MODIFIED] = time();
					$tableEntity = $table->newEntity(array_map('trim',preg_replace('/\s+/', ' ', $saveData)));
                    if($record = $table->save($tableEntity)){
						$productTbl = TableRegistry::get('Products');
						if(isset($postData['edit_product_id'][0]) && !empty($postData['edit_product_id'][0])){							
							foreach($postData['edit_product_id'] as $key => $product){							
								if(!empty($product)){
									$setData2['id'] = $postData['edit_pid'][$key];
									$setData2['bill_id'] = $record->id;
									$setData2['product_id'] = $product;
									$setData2['product_name'] = $postData['edit_product_name'][$key];
									$setData2['huid_code'] = $postData['edit_huid_code'][$key];
									$setData2['quantity'] = $postData['edit_quantity'][$key];
									$setData2['price'] = $postData['edit_price'][$key];
									if(isset($postData['edit_purity'][$key]) && !empty($postData['edit_purity'][$key])){
										$setData2['purity'] = $postData['edit_purity'][$key];
									}else{
										$setData2['purity'] = '14 K';	
									}
									$setData2['diam_stone_wgt'] = $postData['edit_diam_stone_wgt'][$key];
									$setData2['tunch'] = $postData['edit_tunch'][$key];
									$setData2['labour'] = $postData['edit_labour'][$key];
									$setData2['gross_wt'] = $postData['edit_gross_wt'][$key];
									$setData2['net_wt'] = $postData['edit_net_wt'][$key];
									$setData2['wstg'] = $postData['edit_wstg'][$key];
									$setData2['grand_total'] = $postData['edit_total'][$key];
									
									$tableEntity3 = $table2->newEntity(array_map('trim',preg_replace('/\s+/', ' ', $setData2)));
									$table2->save($tableEntity3);
									
									if(isset($postData['edit_remain_qty'][$key]) && !empty($postData['edit_remain_qty'][$key])){
										$updatedQty = $postData['edit_remain_qty'][$key]-$setData2['quantity'];
										if(is_int($updatedQty)){
											$queryOrdUpd = $productTbl->query();
											$queryOrdUpd->update()->set(['qty' => $updatedQty])->where([ID => $product])->execute();
										}
									}
								}
							}
						}
						
						if(isset($postData['product_id'][0]) && !empty($postData['product_id'][0])){
							foreach($postData['product_id'] as $key => $product){							
								if(!empty($product)){
									$setData['bill_id'] = $record->id;
									$setData['product_id'] = $product;
									$setData['product_name'] = $postData['product_name'][$key];
									$setData['huid_code'] = $postData['huid_code'][$key];
									$setData['quantity'] = $postData['quantity'][$key];
									$setData['price'] = $postData['price'][$key];
									if(isset($postData['purity'][$key]) && !empty($postData['purity'][$key])){
										$setData['purity'] = $postData['purity'][$key];
									}else{
										$setData['purity'] = '14 K';
									}
									$setData['diam_stone_wgt'] = $postData['diam_stone_wgt'][$key];
									$setData['tunch'] = $postData['tunch'][$key];
									$setData['labour'] = $postData['labour'][$key];
									$setData['gross_wt'] = $postData['gross_wt'][$key];
									$setData['net_wt'] = $postData['net_wt'][$key];
									$setData['wstg'] = $postData['wstg'][$key];
									$setData['grand_total'] = $postData['total'][$key];
									
									$tableEntity2 = $table2->newEntity(array_map('trim',preg_replace('/\s+/', ' ', $setData)));
									$table2->save($tableEntity2);
									
									$productQty = $productTbl->find()->select('qty')->where([ID => $product])->first();
									$updatedQty = $productQty->qty - $setData['quantity'];
									
									$queryOrdUpd = $productTbl->query();
									$queryOrdUpd->update()->set(['qty' => $updatedQty])->where([ID => $product])->execute();
								}
							}	
						}
					}
                    $this->Flash->set('Invoice has been updated successfully.', array(ELEMENT => ALERT_SUCCESS));
                    $this->redirect(ADMIN_FOLDER.'view-invoice'.'/'.$editID);

                }catch(\Exception $e){
                    $this->Flash->set(INTERNAL_ERROR, array(ELEMENT => ALERT_ERROR));
                    $this->redirect(ADMIN_FOLDER.'edit-invoice'.'/'.$editID);
                }
            }else{
                $error = $getErrors->getErrors();
                $this->setErrorMessage($error);
				$this->set(ALERT_ERROR,$error);
                $this->redirect(ADMIN_FOLDER.'edit-invoice'.'/'.$editID);
            }			
        }
    }
	
	#edit Static Content
    public function viewInvoice($viewID = NULL){
		#check User Auth
        $this->checkValidSession();
		$this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
		$table = TableRegistry::get('Billings');			
		$editID = $this->decryptData(base64_decode($viewID));
		$existOrder = $table->find()->where(array(ID => $editID))->first();
		if(isset($existOrder->id) && !empty($existOrder)){
			$this->set('order',$existOrder);
			$table2 = TableRegistry::get('BillingProducts');
			$attachmentData = $table2->find()->where(array('bill_id' => $existOrder->id))->all();
			if (isset($attachmentData) && !empty($attachmentData)){
				$this->set(compact('attachmentData'));
				$settingTbl = TableRegistry::get('Settings');
				$existLabour = $settingTbl->find()->select(['labour','gst'])->where(['id' => 1])->first();
				$this->set('gst',$existLabour->gst);

				$ordrTbl = TableRegistry::get('Orders');
				$orderData = $ordrTbl->find()->select(['customer_name','invoice_id','advance_amt','customer_contact','return_silver','return_gold'])->where(['invoice_id' => $existOrder->order_invoice_id])->first();
				$this->set('orderData',$orderData);
			}
		}else{
			$this->redirect(ADMIN_FOLDER.'sales-manager'.'/');	
		}
	}
	
	public function printInvoice($viewID = NULL){
		#check User Auth
        $this->checkValidSession();
		$this->viewBuilder()->setLayout('print');
		$table = TableRegistry::get('Billings');			
		$editID = $this->decryptData(base64_decode($viewID));
		$existOrder = $table->find()->where(array(ID => $editID))->first();
		if(isset($existOrder->id) && !empty($existOrder)){
			$this->set('order',$existOrder);
			$table2 = TableRegistry::get('BillingProducts');
			$attachmentData = $table2->find()->where(array('bill_id' => $existOrder->id))->all();
			if(isset($attachmentData) && !empty($attachmentData)){
				$this->set(compact('attachmentData'));
				$settingTbl = TableRegistry::get('Settings');
				$existLabour = $settingTbl->find()->select(['labour','gst'])->where(['id' => 1])->first();
				$this->set('gst',$existLabour->gst);

				$ordrTbl = TableRegistry::get('Orders');
				$orderData = $ordrTbl->find()->where(['invoice_id' => $existOrder->order_invoice_id])->first();
				$this->set('orderData',$orderData);
			}
		}else{
			$this->redirect(ADMIN_FOLDER.'sales-manager'.'/');	
		}	
	}
	
	public function deleteBanner(){
		$this->viewBuilder()->setLayout('false');
		if($this->request->is('Ajax')){
			$table = TableRegistry::get('BillingProducts');
			$postData = $this->request->getData();			
			$product = $table->find()->select('banner')->where(['id' => $postData['rowId']])->first();
			if(is_file(WWW_ROOT.'img/banners/'.$product->banner)){														
				unlink(WWW_ROOT.'img/banners/'.$product->banner);
				
				$query = $table->query();					
				$query->update()->set(['banner' => NULL])->where([ID => $postData['rowId']])->execute();
			}
			echo json_encode('Success');
		}
		exit;
	}
	
	public function searchOrderByName(){
		$this->viewBuilder()->setLayout('false');
		if($this->request->is('Ajax')){
			$keyword = strtolower(trim($_REQUEST["term"]));
			if (!$keyword) return;
			$orderTable = TableRegistry::get('Orders');
			$orderList = $orderTable->find('all', ['id','invoice_id','customer_contact','customer_name','advance_amt','return_silver','return_gold','return_gold_amt'])->where(['customer_name LIKE'=>'%'.$keyword.'%'])->limit(10)->order(['id' => DESC]);
			$countRecord = $orderList->count();
			if($countRecord == 0){
				$orderList = $orderTable->find('all', ['id','invoice_id','customer_contact','customer_name','advance_amt','return_silver','return_gold','return_gold_amt'])->where(['customer_contact LIKE'=>'%'.$keyword.'%'])->limit(10)->order(['id' => DESC]);	
				$countRecord = $orderList->count();
			}
			$html = array();
			if($countRecord > 0){
			foreach($orderList as $key => $order):
				$silverReturn = $goldReturn = 0;
				$customerName = ucwords($order['customer_name']);
				$orderId = $order['id'];
				$contact = $order['customer_contact'];
				
				$html[$key]['label'] = $customerName.' ('.$order['customer_contact'].' - Invoice:'.$order['invoice_id'].' - Advance:'.$order['advance_amt'].')';
				$html[$key]['value'] = $customerName;
				$html[$key]['advance'] = $order['advance_amt'];
				$html[$key]['invoice_id'] = $order['invoice_id'];
				$goldAmount = $order['return_gold_amt'];
				if($order['return_gold_amt'] == '' || $order['return_gold_amt'] == 0){
					$goldAmount = 0;
				}
				$html[$key]['return_gold'] = $goldAmount;
			endforeach;
			}
			echo json_encode($html);
		}
		exit;
	}
	
	public function searchCustomerByPhone(){
		$this->viewBuilder()->setLayout('false');
		if($this->request->is('Ajax')){
			$keyword = strtolower(trim($_REQUEST["term"]));
			if (!$keyword) return;	
			$customerTable = TableRegistry::get('Customers');			
			$customerList = $customerTable->find('all', ['id','name','contact','address'])->where(['contact LIKE'=>'%'.$keyword.'%','status'=>1])->order(['contact' => ASC]);			
			$countRecord = $customerList->count();
			$html = array();
			foreach($customerList as $key => $customer):
				$customerName = ucwords($customer['name']);
				$customerId = $customer['id'];
				$contact = $customer['contact'];
				$address = $customer['address'];
				
				$html[$key]['customer_id'] = $customerId;
				$html[$key]['label'] = $contact.' ('.ucwords($customer['name']).')';
				$html[$key]['value'] = $contact;
				$html[$key]['name'] = $customerName;
				$html[$key]['address'] = $address;
			endforeach;				
			echo json_encode($html);
		}
		exit;	
	}
	
	public function searchCustomer(){
		$this->viewBuilder()->setLayout('false');
		if($this->request->is('Ajax')){
			$keyword = strtolower(trim($_REQUEST["term"]));
			if (!$keyword) return;	
			$customerTable = TableRegistry::get('Customers');			
			$customerList = $customerTable->find('all', ['id','name','contact','address'])->where(['name LIKE'=>'%'.$keyword.'%','status'=>1])->order(['name' => ASC]);			
			$countRecord = $customerList->count();
			$html = array();
			foreach($customerList as $key => $customer):
				$customerName = ucwords($customer['name']);
				$customerId = $customer['id'];
				$contact = $customer['contact'];
				$address = $customer['address'];
				
				$html[$key]['customer_id'] = $customerId;
				$html[$key]['label'] = $customerName.' ('.$customer['contact'].')';
				$html[$key]['value'] = $customerName;
				$html[$key]['contact'] = $contact;
				$html[$key]['address'] = $address;
			endforeach;				
			echo json_encode($html);
		}
		exit;	
	}
		
	public function searchOrderProduct(){
		$this->viewBuilder()->setLayout('false');
		if($this->request->is('Ajax')){
			$keyword = strtolower(trim($_REQUEST["term"]));
			if(!$keyword) return;	
				$productTable = TableRegistry::get('Products');
				$productList = $productTable->find('all', ['id','product_name'])->where(['product_name LIKE'=>'%'.$keyword.'%','status'=>1])->order(['product_name' => ASC]);
				$countRecord = $productList->count();
				$html = array();
				if($countRecord > 0){
					foreach($productList as $key => $product):
						$productImgTbl = TableRegistry::get('ProductImages');
						$productImage = $productImgTbl->find()->where(['product_id'=>$product['id']])->first();					
						$productName = ucwords($product['product_name']);
						
						$html[$key]['label'] = $productName;
						$html[$key]['value'] = $productName;
					endforeach;
				}
			echo json_encode($html);
		}
		exit;	
	}
	
	public function searchProduct(){
		$this->viewBuilder()->setLayout('false');
		if($this->request->is('Ajax')){
			$keyword = strtolower(trim($_REQUEST["term"]));
			if(!$keyword) return;	
				$productTable = TableRegistry::get('Products');
				$productList = $productTable->find('all', ['id','product_name','price','huid_code','unique_code','type'])->where(['unique_code LIKE'=>'%'.$keyword.'%','status'=>1])->order(['id' => DESC]);				
				$countRecord = $productList->count();				
				$html = array();
				if($countRecord > 0){
					$settingTbl = TableRegistry::get('Settings');
					$settingData = $settingTbl->find()->where(['id'=>1])->first();
					foreach($productList as $key => $product):
						if($product['type'] == 'Gold'){
							$price = $settingData->gold_price;
						}else{
							$price = $settingData->silver_price;
						}						
						$productName = ucwords($product['unique_code']);
						$productId = $product['id'];
						$huid_code = $product['huid_code'];
						$quantity = $product['qty'];				
						$purity = $product['purity'];
						$diam_stone_wgt = $product['diam_stone_wgt'];
						$tunch = $product['tunch'];
						$wstg = $product['wstg'];
						$gross_weight = $product['gross_weight'];
						$net_weight = $product['net_weight'];
						
						$html[$key]['label'] = $productName;
						$html[$key]['value'] = $productName;
						$html[$key]['pid'] = $productId;
						$html[$key]['quantity'] = $quantity;
						$html[$key]['price'] = $price;
						$html[$key]['huid_code'] = $huid_code;
						$html[$key]['purity'] = $purity;
						$html[$key]['diam_stone_wgt'] = $diam_stone_wgt;
						$html[$key]['tunch'] = $tunch;
						$html[$key]['wstg'] = $wstg;
						$html[$key]['gross_weight'] = $gross_weight;
						$html[$key]['net_weight'] = $net_weight;
					endforeach;
				}
				
			echo json_encode($html);
		}
		exit;	
	}
	
	public function loadProducts(){
		if($this->request->is('Ajax')){
			$row = rand();
			$count = $_REQUEST['count'];
			$settingTbl = TableRegistry::get('Settings');
			$existLabour = $settingTbl->find()->select(['labour','gst'])->where(['id' => 1])->first();
			
			$this->set('count',$count);
			$this->set('row',$row);		
			$this->set('labour',$existLabour->labour);
			$this->set('gst',$existLabour->gst);
			
			$this->render('load_products');
		}
		exit;
	}
	
	public function loadOrderProducts(){
		if($this->request->is('Ajax')){
			$row = rand();
			$count = $_REQUEST['count'];
			
			$this->set('count',$count);
			$this->set('row',$row);			
			$this->render('load_order_products');
		}
		exit;	
	}

    #categories page
    public function orders(){
        #check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
        #get content data
        $table = TableRegistry::get('Orders');
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
		$orders = $this->paginate($table);
        $this->set(compact('orders'));
    }

	/**************************** pagination page ****************************/
	public function ordersFilter(){
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
		if(isset($postData['customer_contact']) && !empty($postData['customer_contact'])){
			$cond['customer_contact'] =  array('customer_contact'.' '.LIKE => '%'.trim($postData['customer_contact']).'%');
		}
		if(isset($postData['status']) && !empty($postData['status'])){
			$cond['status'] =  array('status' => trim($postData['status']));
		}
		if(isset($postData['customer_name']) && !empty($postData['customer_name'])){
			$cond['customer_name'] =  array('customer_name'.' '.LIKE => '%'.trim($postData['customer_name']).'%');
		}		
		if(isset($postData['delivery_date']) && !empty($postData['delivery_date'])){
			
			if($postData['delivery_date'] == 'Ascending'){
				$conditions[ORDER] =  array('delivery_date' => ASC);
			}else{
				$conditions[ORDER] =  array('delivery_date' => DESC);	
			}
			
		}else{
			$conditions[ORDER] =  array(ID => DESC);
		}
		$conditions[CONDITIONS] = array(STATUS.' !=' => 3);
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
		$orders = $this->paginate($table);
        $this->set(compact('orders'));
		# Pass all data to render for display...
		$this->render('ordersFilter');
	}
	
	#edit Static Content
    public function addOrder(){
        #check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
        if($this->request->is(['post','put'])){
			$postData = $this->request->getData();
			
			$userTbl = TableRegistry::get('Customers');
			$existuser = $userTbl->find()->where(array('contact' => $postData['customer_contact']))->first();
			if(!isset($existuser->id)){
				$existUnique = $userTbl->find()->select(['unique_id'])->where(['unique_id !=' => ''])->order(['id' => 'desc'])->first();
				$uniqueId = 'BRJC101';
				$invoiceID = '10001';
				if(isset($existUnique['unique_id']) && $existUnique['unique_id'] != ''){
					$explodeUnique = explode('C',$existUnique['unique_id']);
					if(isset($explodeUnique[1])){
						$uniqcount = $explodeUnique[1]+1;
						$uniqueId = 'BRJC'.$uniqcount;
					}else{
						$uniqueId = 'BRJC101';
					}
				}
				$userData['unique_id'] = $uniqueId;
				$userData['name'] = $postData['customer_name'];
				$userData['address'] = $postData['customer_address'];
				$userData['contact'] = $postData['customer_contact'];						
				$userData[STATUS] = 1;
				$userData[CREATED] = time();
				$userData[MODIFIED] = time();
				$tableEntity33 = $userTbl->newEntity($userData);
				$records = $userTbl->save($tableEntity33);						
				$customerId = $records->id;	
			}else{
				$customerId = $existuser->id;
			}
			
			$orderTbl = TableRegistry::get('Orders');
			
			$saveData['customer_name'] = $postData['customer_name'];
			$saveData['user_id'] = $customerId;
			$saveData['customer_address'] = $postData['customer_address'];
			$saveData['customer_contact'] = $postData['customer_contact'];
			$saveData['delivery_date'] = $postData['delivery_date'];
			$saveData['return_gold'] = $postData['return_gold'];
			$saveData['return_silver'] = $postData['return_silver'];
			$saveData['remarks'] = $postData['remarks'];
			$saveData['percentage'] = $postData['percentage'];
			$saveData['return_gold_amt'] = $postData['return_gold_amt'];
			if(isset($postData['advance_amt']) && !empty($postData['advance_amt'])){
				$saveData['advance_amt'] = $postData['advance_amt'];
			}else{
				$saveData['advance_amt'] = 0;	
			}
			$saveData['order_day'] = date("d",time());
			$saveData['order_month'] = date("m",time());
			$saveData['order_year'] = date("y",time());
			$saveData[STATUS] = 2;
			$saveData[CREATED] = time();
			$saveData[MODIFIED] = time();
			
			$orderTableEntity = $orderTbl->newEntity($saveData);
			$orderRecord = $orderTbl->save($orderTableEntity);						
			$lastOrderID = $orderRecord->id;
			
			#update invoice id
			$invoiceID = '0001';
			$query = $orderTbl->find();
			$findOrder= $query->select(array('max_invoice_id' => $query->func()->max('invoice_id')))->first();
			if(isset($findOrder->max_invoice_id) && $findOrder->max_invoice_id > 0){
				$invoiceID = $findOrder->max_invoice_id+1;
				$invoiceID = str_pad($invoiceID,4,"0",STR_PAD_LEFT);
			}
			
			$updateOrder['id'] = $lastOrderID;
			$updateOrder['invoice_id'] = $invoiceID;
			$orderTableEntity2 = $orderTbl->newEntity($updateOrder);
			$orderTbl->save($orderTableEntity2);
			
			$productOrderTbl = TableRegistry::get('ProductOrder');
			
			if(isset($postData['product_name'][0]) && !empty($postData['product_name'][0])){
				foreach($postData['product_name'] as $key => $product){	
					if($product != ''){
						$setProduct['order_id'] = $lastOrderID;
						$setProduct['product_name'] = $product;
						$setProduct['comment'] = $postData['comment'][$key];
						$new_file_name = '';
						
						if($_FILES['product_image']["name"][$key] != ''){
							$files = $_FILES['product_image']["name"][$key];
							$target_dir = "products/";
							$target_file = $target_dir . basename($files);
							$ext = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

							if($ext == "jpg" || $ext == "png" || $ext == "jpeg" || $ext == "webp" || $ext == "JPG" || $ext == "PNG" || $ext == "JPEG" || $ext == "WEBP"){
								$file_full = WWW_ROOT.'img/products/'; //Image storage path
								$file_temp_name = $_FILES['product_image']['tmp_name'][$key];
								$new_file_name = $key.time().mt_rand().'.'.$ext;
								move_uploaded_file($file_temp_name, $file_full.$new_file_name);
							}								
							
						}
						$setProduct['product_image'] = $new_file_name;											
						$productOrderEntity = $productOrderTbl->newEntity($setProduct);
						$productOrderTbl->save($productOrderEntity);
						
					}
				}
			}
			
			$this->Flash->set('Order has been created successfully.', array(ELEMENT => ALERT_SUCCESS));
            $this->redirect(ADMIN_FOLDER.'order-manager'.'/');	
		}
    }
	
	#edit Static Content
    public function editOrder($editID = NULL){
        #check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
        if($this->request->is(['post','put'])){
			#get request data
            $postData = $this->request->getData();

			$userTbl = TableRegistry::get('Customers');
			$existuser = $userTbl->find()->where(array('contact' => $postData['customer_contact']))->first();
			if(!isset($existuser->id)){
				$existUnique = $userTbl->find()->select(['unique_id'])->where(['unique_id !=' => ''])->order(['id' => 'desc'])->first();
				$uniqueId = 'BRJC101';
				$invoiceID = '10001';
				if(isset($existUnique['unique_id']) && $existUnique['unique_id'] != ''){
					$explodeUnique = explode('C',$existUnique['unique_id']);
					if(isset($explodeUnique[1])){
						$uniqcount = $explodeUnique[1]+1;
						$uniqueId = 'BRJC'.$uniqcount;
					}else{
						$uniqueId = 'BRJC101';
					}
				}
				$userData['unique_id'] = $uniqueId;
				$userData['name'] = $postData['customer_name'];
				$userData['address'] = $postData['customer_address'];
				$userData['contact'] = $postData['customer_contact'];						
				$userData[STATUS] = 1;
				$userData[CREATED] = time();
				$userData[MODIFIED] = time();
				$tableEntity33 = $userTbl->newEntity($userData);
				$records = $userTbl->save($tableEntity33);						
				$customerId = $records->id;	
			}else{
				$customerId = $existuser->id;
			}
			
			$orderTbl = TableRegistry::get('Orders');
			
			$saveData[ID] = $this->decryptData($postData[EDIT_TOKEN]);
			$saveData['customer_name'] = $postData['customer_name'];
			$saveData['user_id'] = $customerId;
			$saveData['customer_address'] = $postData['customer_address'];
			$saveData['customer_contact'] = $postData['customer_contact'];
			$saveData['delivery_date'] = $postData['delivery_date'];
			$saveData['return_gold'] = $postData['return_gold'];
			$saveData['return_silver'] = $postData['return_silver'];
			$saveData['remarks'] = $postData['remarks'];
			$saveData['percentage'] = $postData['percentage'];
			$saveData['return_gold_amt'] = $postData['return_gold_amt'];
			if(isset($postData['advance_amt']) && !empty($postData['advance_amt'])){
				$saveData['advance_amt'] = $postData['advance_amt'];
			}else{
				$saveData['advance_amt'] = 0;	
			}
			$saveData['order_day'] = date("d",time());
			$saveData['order_month'] = date("m",time());
			$saveData['order_year'] = date("y",time());
			$saveData[STATUS] = 2;
			$saveData[CREATED] = time();
			$saveData[MODIFIED] = time();
			
			$orderTableEntity = $orderTbl->newEntity($saveData);
			$orderRecord = $orderTbl->save($orderTableEntity);						
			
			$productOrderTbl = TableRegistry::get('ProductOrder');
			
			#upload new images
			if(isset($postData['product_name'][0]) && !empty($postData['product_name'][0])){
				foreach($postData['product_name'] as $key => $product){	
					if($product != ''){
						$setProduct['order_id'] = $this->decryptData($postData[EDIT_TOKEN]);
						$setProduct['product_name'] = $product;
						$setProduct['comment'] = $postData['comment'][$key];
						$new_file_name = '';
						
						if($_FILES['product_image']["name"][$key] != ''){
							$files = $_FILES['product_image']["name"][$key];
							$target_dir = "products/";
							$target_file = $target_dir . basename($files);
							$ext = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

							if($ext == "jpg" || $ext == "png" || $ext == "jpeg" || $ext == "webp" || $ext == "JPG" || $ext == "PNG" || $ext == "JPEG" || $ext == "WEBP"){
								$file_full = WWW_ROOT.'img/products/'; //Image storage path
								$file_temp_name = $_FILES['product_image']['tmp_name'][$key];
								$new_file_name = $key.time().mt_rand().'.'.$ext;
								move_uploaded_file($file_temp_name, $file_full.$new_file_name);
							}								
							
						}
						$setProduct['product_image'] = $new_file_name;											
						$productOrderEntity = $productOrderTbl->newEntity($setProduct);
						$productOrderTbl->save($productOrderEntity);
						
					}
				}
			}
			#update old image
			if(isset($postData['edit_img_id'][0]) && !empty($postData['edit_img_id'][0])){
				foreach($postData['edit_img_id'] as $key2 => $productID){	
					if($productID != '' && $productID > 0){
						$setProduct2['id'] = $productID;
						$setProduct2['product_name'] = $postData['product_name_edit'][$key2];
						$setProduct2['comment'] = $postData['comment_edit'][$key2];
						$new_file_name2 = '';
						
						if($_FILES['product_image_edit']["name"][$key2] != ''){
							$files = $_FILES['product_image_edit']["name"][$key2];
							$target_dir = "products/";
							$target_file = $target_dir . basename($files);
							$ext = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

							if($ext == "jpg" || $ext == "png" || $ext == "jpeg" || $ext == "webp" || $ext == "JPG" || $ext == "PNG" || $ext == "JPEG" || $ext == "WEBP"){
								$file_full = WWW_ROOT.'img/products/'; //Image storage path
								$file_temp_name = $_FILES['product_image_edit']['tmp_name'][$key2];
								$new_file_name2 = $key2.time().mt_rand().'.'.$ext;
								move_uploaded_file($file_temp_name, $file_full.$new_file_name2);
								$setProduct2['product_image'] = $new_file_name2;
							}								
							
						}									
						$productOrderEntity = $productOrderTbl->newEntity($setProduct2);
						$productOrderTbl->save($productOrderEntity);
						
					}
				}
			}
			
			$this->Flash->set('Order has been updated successfully.', array(ELEMENT => ALERT_SUCCESS));
            $this->redirect(ADMIN_FOLDER.'edit-order'.'/'.base64_encode($postData[EDIT_TOKEN]));
		}
		if(!empty($editID)){
            #decrypt request ID
            $editID = $this->decryptData(base64_decode($editID));            
            #get row data
            $table = TableRegistry::get('Orders');
			$productOrderTbl = TableRegistry::get('ProductOrder');
            $editData = $table->find()->where(array(ID => $editID))->first();
            if(isset($editData->id) && !empty($editData->id)){
                $this->set(compact(EDITDATA));
				$attachmentData = $productOrderTbl->find()->where(array('order_id' => $editData->id))->all();
                if (isset($attachmentData) && !empty($attachmentData)){
                    $this->set(compact('attachmentData'));
                }
            }else{
                $this->redirect(ADMIN_FOLDER.'order-manager'.'/');
            }

        }else{
            $this->redirect(ADMIN_FOLDER.'order-manager'.'/');
        }
    }

	#edit Static Content
    public function viewOrder($viewID = NULL){
		#check User Auth
        $this->checkValidSession();
		$this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);		
		$table = TableRegistry::get('Orders');
		$editID = $this->decryptData(base64_decode($viewID));
		$existOrder = $table->find()->where(array(ID => $editID))->first();
		if(isset($existOrder->id) && !empty($existOrder)){
			$this->set('order',$existOrder);
			$productOrderTbl = TableRegistry::get('ProductOrder');
			$attachmentData = $productOrderTbl->find()->where(array('order_id' => $existOrder->id))->all();
			if (isset($attachmentData) && !empty($attachmentData)){
				$this->set(compact('attachmentData'));
			}
		}else{
			$this->redirect(ADMIN_FOLDER.'order-manager'.'/');	
		}
	}
	
	public function getCurrentPrice(){
		$this->viewBuilder()->layout = false;
		if($this->request->is('Ajax')){			
			$postData = $this->request->getData();			
			if(!empty($postData)){
				$settingTbl = TableRegistry::get('Settings');
				$settingData = $settingTbl->find()->where(['id'=>1])->first();
				$type = $postData['type'];
				$return = $postData['price'];
				$percent = $postData['percent'];
				$amount = 0;	
				
				$todayGoldPrice = $settingData->gold_price_customer;
				$todaySilverPrice = $settingData->silver_price_customer;
				
				if($type == 'gold'){
					$amount = ($return*$todayGoldPrice)*$percent/100;
				}else{
					$amount = ($return*$todaySilverPrice)*$percent/100;
				}
				echo json_encode(array('amount'=>$amount));
			}
			exit;			
		}	
	}
	
	public function updateProductImage(){
		$this->viewBuilder()->layout = false;
		if($this->request->is('Ajax')){
			$file = $_FILES;
			$msg = [];			
			if(!empty($file) && isset($_REQUEST['model']) && !empty($_REQUEST['model'])){	 
				if($_REQUEST['model'] == 'Banner'){
					$fileName = $file['bannerIcon']['name']; //Get the image
					$file_full = WWW_ROOT.'img/banners/'; //Image storage path
					$file_temp_name = $file['bannerIcon']['tmp_name'];
					$pathInfo = pathinfo(basename($fileName));
					$ext = $pathInfo['extension'];
					$checkImage = getimagesize($file_temp_name);
					if($checkImage !== false){
						$new_file_name = time().rand().'.'.$ext;
						if(move_uploaded_file($file_temp_name, $file_full.$new_file_name)){
							$msg['msg'] = "Success";
							$msg['path'] = SITEURL.'img/banners/'.$new_file_name;
							$msg['name'] = $new_file_name;
														
							if(isset($_REQUEST['old_image']) && !empty($_REQUEST['old_image'])){
								if(is_file(WWW_ROOT.'img/banners/'.$_REQUEST['old_image'])){									
									unlink(WWW_ROOT.'img/banners/'.$_REQUEST['old_image']);									
								}															
							}									
							$productTbl = TableRegistry::get('BillingProducts');
							$query = $productTbl->query();
							$query->update()->set([
								'banner' => $new_file_name,
							])->where([
								'id' => $_REQUEST['editId']
							])->execute();
						}else{
							$msg['msg'] = "Error";
						}
					}else{
						$msg['msg'] = "Error";
					}
				} 			
            }			
			echo json_encode(array('data'=>$msg));
		}		
		exit;
	}
	
    #setErrorMessage
    function setErrorMessage($error){
		if(isset($error[NAME][CHECK_EMPTY]) && !empty($error[NAME][CHECK_EMPTY])){
            $this->Flash->set($error[NAME][CHECK_EMPTY], array(ELEMENT => ALERT_ERROR));
        }
		if(isset($error[NAME][CHECKUNIQUENAME]) && !empty($error[NAME][CHECKUNIQUENAME])){
            $this->Flash->set($error[NAME][CHECKUNIQUENAME], array(ELEMENT => ALERT_ERROR));
        }
		if(isset($error[ICON][CHECK_EMPTY]) && !empty($error[ICON][CHECK_EMPTY])){
            $this->Flash->set($error[ICON][CHECK_EMPTY], array(ELEMENT => ALERT_ERROR));
        }
		if(isset($error['category_id'][CHECK_EMPTY]) && !empty($error['category_id'][CHECK_EMPTY])){
            $this->Flash->set($error['category_id'][CHECK_EMPTY], array(ELEMENT => ALERT_ERROR));
        }
		if(isset($error['product_name'][CHECK_EMPTY]) && !empty($error['product_name'][CHECK_EMPTY])){
            $this->Flash->set($error['product_name'][CHECK_EMPTY], array(ELEMENT => ALERT_ERROR));
        }
		if(isset($error['price'][CHECK_EMPTY]) && !empty($error['price'][CHECK_EMPTY])){
            $this->Flash->set($error['price'][CHECK_EMPTY], array(ELEMENT => ALERT_ERROR));
        }
		if(isset($error['description'][CHECK_EMPTY]) && !empty($error['description'][CHECK_EMPTY])){
            $this->Flash->set($error['description'][CHECK_EMPTY], array(ELEMENT => ALERT_ERROR));
        }
		if(isset($error['seo_title'][CHECK_EMPTY]) && !empty($error['seo_title'][CHECK_EMPTY])){
            $this->Flash->set($error['seo_title'][CHECK_EMPTY], array(ELEMENT => ALERT_ERROR));
        }
		if(isset($error['seo_keywords'][CHECK_EMPTY]) && !empty($error['seo_keywords'][CHECK_EMPTY])){
            $this->Flash->set($error['seo_keywords'][CHECK_EMPTY], array(ELEMENT => ALERT_ERROR));
        }
		if(isset($error['seo_description'][CHECK_EMPTY]) && !empty($error['seo_description'][CHECK_EMPTY])){
            $this->Flash->set($error['seo_description'][CHECK_EMPTY], array(ELEMENT => ALERT_ERROR));
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
	
	function getInvoiceId($orderID){		
		return 1001+$orderID;
	}
	
}
?>