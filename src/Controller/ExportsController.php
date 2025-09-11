<?php
namespace App\Controller;
use Cake\Core\Configure;
use Cake\Network\Exception\ForbiddenException;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\ORM\TableRegistry;
use Cake\Mailer\Email;
use PHPExcel;
use PHPExcel_IOFactory;

class ExportsController extends AppController {
	 	
	public function uploadBulkProducts(){
		$productsTable = TableRegistry::get('Products');
		$user_arr = array();		
		if($this->request->is('post')){
			$session = $this->request->getSession();
			$postData = $this->request->getData();
									
			$fileName = $_FILES["file"]["tmp_name"];				
			if(isset($fileName) && !empty($fileName)){
				
				$csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel');
					
					if(!empty($_FILES['file']['name']) && $_FILES["file"]["size"] > 0 && in_array($_FILES['file']['type'], $csvMimes)){									
					$file = fopen($fileName, "r");
					$num = 1;
					$bulkPids = array();
					while(($column = fgetcsv($file, 10000, ",")) !== FALSE){
						
						if($num > 1){
																				
							$insert_data['category_id'] = $insert_data['product_name'] = '';
							$insert_data['purity'] = $insert_data['diam_stone_wgt'] = '';
							$insert_data['tunch'] = $insert_data['wstg'] = '';
							$insert_data['price'] = $insert_data['gross_weight'] = '';
							$insert_data['net_weight'] = $insert_data['worker_name'] = '';
							$insert_data['percentage'] = $insert_data['qty'] = '';
							$insert_data['huid_code'] = $insert_data['tag_name'] = '';
							$insert_data['party_name'] = $insert_data['party_phone'] = '';
							$insert_data['type'] = '';
							
							if(isset($column[0])){
								$insert_data['category_id'] = $column[0];
							}
							if(isset($column[1])){
								$insert_data['type'] = $column[1];
							}
							if(isset($column[2])){
								$insert_data['product_name'] = $column[2];
							}
							if(isset($column[3])){
								$insert_data['party_name'] = $column[3];
							}
							if(isset($column[4])){
								$insert_data['party_phone'] = $column[4];
							}						
							if(isset($column[5])){
								$insert_data['purity'] = $column[5];
							}
							if(isset($column[6])){
								$insert_data['diam_stone_wgt'] = $column[6];
							}							
							if(isset($column[7])){
								$insert_data['tunch'] = $column[7];
							}
							if(isset($column[8])){
								$insert_data['wstg'] = $column[8];
							}
							if(isset($column[9])){
								$insert_data['Price'] = $column[9];
							}
							if(isset($column[10])){
								$insert_data['gross_weight'] = $column[10];
							}
							if(isset($column[11])){
								$insert_data['net_weight'] = $column[11];
							}
							if(isset($column[12])){
								$insert_data['worker_name'] = $column[12];
							}
							if(isset($column[13])){
								$insert_data['percentage'] = $column[13];
							}
							if(isset($column[14])){
								$insert_data['qty'] = $column[14];
							}
							if(isset($column[15])){
								$insert_data['huid_code'] = $column[15];
							}
							if(isset($column[16])){
								$insert_data['tag_name'] = $column[16];
							}							
							$insert_data['status'] = 1;
							$insert_data['created'] = time();
							$insert_data['modified'] = time();
							
							$tableEntity = $productsTable->newEntity($insert_data);
                    		$record = $productsTable->save($tableEntity);
							$bulkPids[$record->id] = $insert_data['product_name'];
							
							$uniqueId = $this->getUniqueID($record->id);
							$queryOrdUpd = $productsTable->query();
							$queryOrdUpd->update()->set(['unique_code' => $uniqueId])->where([ID => $record->id])->execute();						
						}
						$num++;
					}
					
					if(count($bulkPids) > 0){
						$delimiter = ",";
						$filename = "product_ids_" . date('d-F-Y') . ".csv";
						
						//create a file pointer
						$f = fopen('php://memory', 'w');
						  
						//set column headers
						$fields = array(
										'Product Ids',
										'Product Name',
									);
						 
						fputcsv($f, $fields, $delimiter);
						
						$rowCounter = 2;
						foreach($bulkPids as $key => $value):
							$lineData = array(
								$key,
								$value,
							);
														
							fputcsv($f, $lineData, $delimiter);						
							$rowCounter++;
							
						endforeach;
						
						//move back to beginning of file
						fseek($f, 0);
						//set headers to download file rather than displayed
						header('Content-Type: text/csv');
						header('Content-Disposition: attachment; filename="' . $filename . '";');
						
						//output all remaining data on a file pointer
						fpassthru($f);						
						unset($bulkPids);
						return $this->redirect(ADMIN_FOLDER.'/products');
						exit;
					}				
					return $this->redirect(ADMIN_FOLDER.'/products');
				}				
			}else{				
				return $this->redirect(ADMIN_FOLDER.'/products');
			}
		}
	}
	
	function getUniqueID($string){
		$length = 8;
		$rand = str_shuffle(rand(1111,9999).rand(1111,9999));
		return str_pad($string,$length,$rand, STR_PAD_LEFT);
	}
	
	public function exportSalesItems(){
		//$this->checkValidSession();
        # Conditions...
        $conditions['conditions'] = array();
        $cond = array();
        $table = TableRegistry::get('Billings');
		$billingProductTbl = TableRegistry::get('BillingProducts');
		$productTbl = TableRegistry::get('Products');
		
        $postData = $this->request->getData();        
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
        $i = 0;
        foreach ($cond as $key => $value) {
            $conditions['conditions'][$i] = $value;
            $i++;
        }
		
		$reports = $table->find()->where(array($conditions['conditions']))->order(array('id' => 'DESC'))->all();
		$session = $this->request->getSession();
        if($reports->count() > 0){
			$delimiter = ",";
            $filename = "sales_items_report_" . date('d-F-Y') . ".csv";
            
            //create a file pointer
            $f = fopen('php://memory', 'w');            
            //set column headers
			
			$rowCounter = 2;
			
			$totalCount = $count = $sum = 0; 
			$calculate = array();
			$calculate['count'] = $calculate['sum'] = 0;
			$rowCounter = 2;
			
            foreach($reports as $key => $report):		
                if($key != 0){
					$lineData4 = array('','','','','','','','','','','');						
					fputcsv($f, $lineData4, $delimiter);					
				}
				$fields = array(
                            'S.No', 
                            'Invoice ID', 
                            'Name',
                            'Delivery Address',
							'Payment Type',
							'Date'
                           );             
            	fputcsv($f, $fields, $delimiter);		
					
				$lineData = array(
								$rowCounter-1,
								$report->invoice_no, 
								$report->customer_name, 
								$report->delivery_address, 
								$report->payment_type, 
								date('d-m-Y H:i a',$report->created)
							);
                fputcsv($f, $lineData, $delimiter);
									
				$products = $billingProductTbl->find()->where(array('bill_id' => $report->id))->order(array('id' => 'DESC'))->all();
				if($products->count() > 0){				
					$fields3 = array(
								'Product S.No',
								'Product Name',
								'Unique Code',
								'Gross Weight',
								'Net Weight',
								'Quantity',
								'Price',
								'Labour',
								'Grand Total'
							   );
				 
					fputcsv($f, $fields3, $delimiter);
					$totalSum = 0;							
					foreach($products as $key2 => $product){
						$productDeails = $productTbl->find()->select('product_name')->where(array('id' => $product->product_id))->first();
						$lineData2 = array(
							$key2+1,
							$productDeails->product_name,
							$product->product_name,						
							$product->gross_wt.' gm', 
							$product->net_wt.' gm', 
							$product->quantity,
							$product->price,
							$product->labour,
							$product->grand_total,											               
						);
						fputcsv($f, $lineData2, $delimiter);
						$totalSum += $product->grand_total;
					}					
					$lineData4 = array(
							'Total',
							'',													
							'',							
							'',
							'',
							'',
							'',
							'',
							$totalSum
						);
					fputcsv($f, $lineData4, $delimiter);
				}				
								
                $rowCounter++;
            endforeach;
			
			
            //move back to beginning of file
            fseek($f, 0);
            //set headers to download file rather than displayed
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '";');
            
            //output all remaining data on a file pointer
            fpassthru($f);			
			exit;
        }else{			
			$this->redirect(ADMIN_FOLDER.'/sales-item-report'.'/');
			//$this->redirect(ADMIN_FOLDER.'franchise-reports/');
			$this->Flash->set(INTERNAL_ERROR, array(ELEMENT => ALERT_ERROR));
		}	
	}	
	
	public function exportOldGold(){
		//$this->checkValidSession();
        # Conditions...
        $conditions['conditions'] = array();
        $cond = array();
        $table = TableRegistry::get('Orders');
		
        $postData = $this->request->getData();        
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
		
		$conditions[CONDITIONS] = array('return_gold'.' !=' => '');
		
        $i = 0;
        foreach ($cond as $key => $value) {
            $conditions['conditions'][$i] = $value;
            $i++;
        }
		
		
		
		$query = $table->find();
		$reports = $query->select(['return_gold','percentage', 'total' => $query->func()->sum('return_gold_amt')])->where($conditions[CONDITIONS])->group(['percentage']);
		$session = $this->request->getSession();
        if(!empty($reports) > 0){
			$delimiter = ",";
            $filename = "old_gold_report_" . date('d-F-Y') . ".csv";
            
            //create a file pointer
            $f = fopen('php://memory', 'w');            
            //set column headers
			
			$rowCounter = 2;
			
			$fields = array(
						'S.No',
						'Return Gold', 
						'Percentge',
						'Amount',
					   );             
			fputcsv($f, $fields, $delimiter);	
			
			$totalCount = $count = $sum = 0; 
			$calculate = array();
			$calculate['count'] = $calculate['sum'] = 0;
			$rowCounter = 2;
			
            foreach($reports as $key => $report):				
					
				$lineData = array(
								$rowCounter-1,
								$report->return_gold.' gm', 
								$report->percentage, 
								$report->total
							);
                fputcsv($f, $lineData, $delimiter);				
								
                $rowCounter++;
            endforeach;
			
			
            //move back to beginning of file
            fseek($f, 0);
            //set headers to download file rather than displayed
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '";');
            
            //output all remaining data on a file pointer
            fpassthru($f);			
			exit;
        }else{			
			$this->redirect(ADMIN_FOLDER.'/old-gold-report'.'/');
			//$this->redirect(ADMIN_FOLDER.'franchise-reports/');
			$this->Flash->set(INTERNAL_ERROR, array(ELEMENT => ALERT_ERROR));
		}	
	}
}	
?>