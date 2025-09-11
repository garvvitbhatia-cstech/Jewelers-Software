<?php
   namespace App\Controller\Webservice1;
   use Cake\Core\Configure;
   use Cake\Network\Exception\ForbiddenException;
   use Cake\Network\Exception\NotFoundException;
   use Cake\View\Exception\MissingTemplateException;
   use Cake\ORM\TableRegistry;
   use Cake\Http\Response;
   use Cake\Core\Exception\Exception;
   use App\Controller\AppController;
   
   class OrdersController extends ValidationsController{
   	
   	/*public function faqs(){ 
   		header('Content-Type: application/json');
   		$postData = $this->request->getData();
   		$this->checkUserToken($postData);
   		$table = TableRegistry::get('Faqs');
   		$faqs = $table->find()->where(array('status' => 1))->ORDER(array(ID => ASC))->all();
   		$faqArray = array();
   		foreach($faqs as $key => $faq){
   			$faqArray[$key]['id'] = $faq->id;
   			$faqArray[$key]['question'] = $faq->question;
   			$faqArray[$key]['answer'] = nl2br($faq->answer);
   		}
         $result['faqs'] = $faqArray; 
   		echo json_encode($result); die;
   	}*/
   
   	/*public function aboutUs(){ 
   		header('Content-Type: application/json');
   		$postData = $this->request->getData();
   		$this->checkUserToken($postData);
   		$table = TableRegistry::get('InnerPages');
   		$abouts = $table->find()->where(array('status' => 1, 'id' => 2))->all();
   		$aboutUs = array();
   		foreach($abouts as $key => $about){
   			$aboutUs[$key]['heading'] = $about->heading;
   			$aboutUs[$key]['sub_heading'] = $about->sub_heading;
   			$aboutUs[$key]['description'] = nl2br($about->edit_description);
   		}
         $result['about_us'] = $aboutUs; 
   		echo json_encode($result); die;
   	}*/
   
   	/*public function homepageBanners(){
   		header('Content-Type: application/json');
   		$postData = $this->request->getData();
   		$this->checkUserToken($postData);
   		$table = TableRegistry::get('homeBanners');
   		$banners = $table->find()->where(array('status' => 1))->ORDER(array(ID => ASC))->all();
   		$bannersArray = array();
   		foreach($banners as $key => $banner){
   			$bannersArray[$key]['id'] = $banner->id;
   			$bannersArray[$key]['heading'] = $banner->title;
   			$bannersArray[$key]['description'] = nl2br($banner->description);
   			$bannersArray[$key]['banner_image'] = SITE_URL."img/slider/".$banner->image;
   		}
         $result['homepage_banners'] = $bannersArray;
   		echo json_encode($result); die;
   	}*/
   
   	public function wallet(){
   		header('Content-Type: application/json');
   		$postData = $this->request->getData();
   		#validations
   		$this->checkUserToken($postData);
   		$userToken = $postData['userToken'];
   		$table = TableRegistry::get(USERS);
   		$existUser = $table->find()->where(array(USER_TOKEN => $userToken))->all();
   		$walletArray = array();
   			if($existUser->count() > 0){
   				foreach($existUser as $key => $user){
   					$walletArray[$key]['id'] = $user->id;
                  if (isset($user->wallet) && !empty($user->wallet) && $user->wallet > 0) {
                     $walletArray[$key]['wallet'] = $user->wallet;
                  }else{
   					   $walletArray[$key]['wallet'] = 0;
                  }
   				}
            $result['wallet'] = $walletArray;
   			echo json_encode($result); die;
   		}
   	}

      public function addMoneyInWallet(){
         header('Content-Type: application/json');
         $postData = $this->request->getData();
         #validations
         $this->checkUserToken($postData);
         $this->checkAmount($postData);
         $this->checkTransactionID($postData);
         $userToken = $postData['userToken'];
         $amount = trim($postData['amount']);
         $transactionID = trim($postData['transactionID']);
         $table = TableRegistry::get(USERS);
         $WalletTable = TableRegistry::get('UserWallet');
         $existUser = $table->find()->where(array(USER_TOKEN => $userToken))->all();
         $walletArray = array();
            if($existUser->count() > 0){
               $userData = $existUser->first();
               $userID = $userData['id'];
               $walletAmount = $userData['wallet'];
               $NewWalletAmount = $walletAmount+$amount;
               $saveData[ID] = $userID;
               $saveData['wallet'] = $NewWalletAmount;
               $tableEntity = $table->newEntity($saveData);
               if($table->save($tableEntity)){               
                  $saveDatas['user_id'] = $userID;
                  $saveDatas['type'] = 'Deposit';
                  $saveDatas['amount'] = $amount;
                  $saveDatas['created'] = time();
                  $saveDatas['payment_id'] = $transactionID;
                  $WalletTableEntity = $WalletTable->newEntity($saveDatas);
                  $WalletTable->save($WalletTableEntity);
                  $sendEmailTo = 'User';
                  $newarray = array(
                     'name' => $userData['name'],
                     'email' => $userData['email'],
                     'type' => $saveDatas['type'],
                     'amount' => $saveDatas['amount'],
                     'payment_id' => $saveDatas['payment_id'],
                     'created' => $saveDatas['created']
                  );
                     $sendEmail = array(
                        'to'=> $userData['email'],
                        'userData' => $newarray,
                        'template_id' => 4,
                        'template' => 'add_money_wallet',
                        'sendEmailTo' => $sendEmailTo
                     );
                     if($this->SendEmails->sendEmail($sendEmail)){
                        $otpEmailStatus = 'Email Sent';        
                     }else{
                        $otpEmailStatus = EMAIL_NOT_SEND;   
                     }
               }
               $result = array('response' => 200, 'status' => 'Success','dashboard' => true,'msg' => $amount. ' rupee added successfully in wallet', 'available_balance' => $NewWalletAmount, 'email_status' => $otpEmailStatus);
         }
         echo json_encode($result); die;
      }
   
   	
      
      /*public function transactionHistory(){
         header('Content-Type: application/json');
         $postData = $this->request->getData();
         $this->checkUserToken($postData);
         $userToken = $postData['userToken'];
         $table = TableRegistry::get(USERS);
         $existUser = $table->find()->where(array(USER_TOKEN => $userToken))->all();   
         if($existUser->count() > 0){
            foreach($existUser as $key => $user){
               $userID = $user->id;
            }
            $orderTable = TableRegistry::get('Orders');
            $existOrder = $orderTable->find()->where(array('user_id' => $userID))->order(array('id'=>'desc'))->all();
               if($existOrder->count() > 0){
               $orderArray = array();
               
               foreach($existOrder as $key => $order){
                  $item_id= $order->item_id;
                  $orderArray[$key]['id'] = $order->id;
                  $orderArray[$key]['invoice_id'] = $order->invoice_id;
                  $orderArray[$key]['price'] =  number_format($order->price,2);
                  $orderArray[$key]['shipping_charges'] =  number_format($order->shipping,2);
                  $orderArray[$key]['total_amount'] =  number_format($order->total,2);
                  $orderArray[$key]['delivery_status'] = $order->item_status;
                  $orderArray[$key]['order_date'] = date("F jS, Y h:i A",$order->created);
                  $orderArray[$key]['user_comments'] = $order->notes;
               }
            }
            else{
               $result = array('response' => 400,'status' => 'Error','msg' => 'no order available');
               echo json_encode($result); die;
            }
         }
         $result['transactionHistory'] = $orderArray;
         echo json_encode($result); die;     
      }*/

      public function walletTransactionHistory(){
         header('Content-Type: application/json');
         $postData = $this->request->getData();
         $this->checkUserToken($postData);
         $userToken = $postData['userToken'];
         $table = TableRegistry::get(USERS);
         $existUser = $table->find()->where(array(USER_TOKEN => $userToken))->all();   
         if($existUser->count() > 0){
            foreach($existUser as $key => $user){
               $userID = $user->id;
            }
            $walletTable = TableRegistry::get('UserWallet');
            $existTrans = $walletTable->find()->where(array('user_id' => $userID))->order(array('id'=>'desc'))->all();
               if($existTrans->count() > 0){
               $WalletTransactionArray = array();
               
               foreach($existTrans as $key => $trans){
                  $WalletTransactionArray[$key]['id'] = $trans->id;
                  $WalletTransactionArray[$key]['transaction_id'] = $trans->payment_id;
                  $WalletTransactionArray[$key]['transaction_type'] = $trans->type;
                  $WalletTransactionArray[$key]['transaction_amount'] = $trans->amount;
                  $WalletTransactionArray[$key]['transaction_date'] = date("F jS, Y h:i A",$trans->created);
               }
            }
            else{
               $result = array('response' => 400,'status' => 'Error','msg' => 'no transaction history available');
               echo json_encode($result); die;
            }
         }
         $result['walletTransactionHistory'] = $WalletTransactionArray;
         echo json_encode($result); die;     
      }

   	public function myOrder(){
   		header('Content-Type: application/json');
   		$postData = $this->request->getData();
   		$this->checkUserToken($postData);
   		$userToken = $postData['userToken'];
   		$table = TableRegistry::get(USERS);
   		$existUser = $table->find()->where(array(USER_TOKEN => $userToken))->all();	
   		if($existUser->count() > 0){
   			foreach($existUser as $key => $user){
   				$userID = $user->id;
   			}
   			$orderTable = TableRegistry::get('Orders');
   			$existOrder = $orderTable->find()->where(array('user_id' => $userID))->order(array('id'=>'desc'))->all();
   				if($existOrder->count() > 0){
   				$orderArray = array();
   				
   				foreach($existOrder as $key => $order){
   					$item_id= $order->item_id;
   					$orderArray[$key]['id'] = $order->id;
   					$orderArray[$key]['invoice_id'] = $order->invoice_id;
   					$orderArray[$key]['price'] = $order->price;
   					$orderArray[$key]['shipping_charges'] = $order->shipping;
   					$orderArray[$key]['total_amount'] = $order->total;
   					$orderArray[$key]['delivery_status'] = $order->item_status;
   					$orderArray[$key]['order_date'] = date("F jS, Y h:i A",$order->created);
   					$orderArray[$key]['user_comments'] = $order->notes;
   				}
   			}
   			else{
   				$result = array('response' => 400,'status' => 'Error','msg' => 'no order available');
   				echo json_encode($result); die;
   			}
   		}
   		$result['orders'] = $orderArray;
   		echo json_encode($result); die;		
   	}
      
   	public function myOrderDetail(){
   		header('Content-Type: application/json');
   		$postData = $this->request->getData();
   		$this->checkUserToken($postData);
   		$this->checkOrderId($postData);
   		$userToken = $postData['userToken'];
   		$orderID = $postData['orderId'];
   		$table = TableRegistry::get(USERS);
   		$existUser = $table->find()->where(array(USER_TOKEN => $userToken))->all();	
   		if($existUser->count() > 0){
   			foreach($existUser as $key => $user){
   				$userID = $user->id;
   			}			
   			$orderArray = array();
            $totalArray = array();
            $orderDetailArray = array();
   			$productOrderTable = TableRegistry::get('ProductOrder');
   			$productImageTable = TableRegistry::get('ProductImages');
   			$productTable = TableRegistry::get('Products');
   			$categoryTable = TableRegistry::get('Categories');
   			$orderTable = TableRegistry::get('Orders');
   			$userOrders = $orderTable->find()->where(array('id' => $orderID, 'user_id' => $userID))->first();
               $orderDetailArray['order_id'] = $userOrders->id;
               $orderDetailArray['sub_total'] = $userOrders->total;
               $orderDetailArray['shipping_charges'] = $userOrders->shipping;
               $orderDetailArray['grand_total'] = $userOrders->total + $userOrders->shipping;
               $orderDetailArray['payment_mode'] = $userOrders->payment_method;
               $orderDetailArray['order_date'] = date("F jS, Y h:i A",$userOrders->created);
               $orderDetailArray['delivery_status'] = $userOrders->item_status;
             
   			$productOrders = $productOrderTable->find()->where(array('order_id' => $orderID, 'user_id' => $userID))->all();
            
   			if($productOrders->count() > 0){
   				foreach($productOrders as $key => $order){
   					$product = $productTable->find()->where(array('id' => $order->product_id))->first();
   					$category = $categoryTable->find()->where(array('id' => $product['category_id'],'status' => 1))->first();
   					$orderArray[$key]['id'] = $order->id;
   					$orderArray[$key]['category'] = $category->name;				
   					$orderArray[$key]['product_id'] = $order->product_id;					
   					if(!empty($product)){
   						$orderArray[$key]['product_name'] = $product['product_name'];
   						$orderArray[$key]['product_recipe'] = $product['recipe'];
   						$orderArray[$key]['product_description'] = $product['description'];	
   					}else{
   						$orderArray[$key]['product_name'] = NULL;
   						$orderArray[$key]['product_recipe'] = NULL;
   						$orderArray[$key]['product_description'] = NULL;
   					}
   					$orderArray[$key]['price'] = $order->price;
   					$orderArray[$key]['quantity'] = $order->quantity;
   					$orderArray[$key]['total_amount'] = $order->total;
   					$orderArray[$key]['order_date'] = date("F jS, Y h:i A",$order->created);
   					
   					if(isset($product->id) && !empty($product->id)){
   						$productImage = $productImageTable->find()->where(array('product_id' => $product->id, 'status' => 1))->order(array('ordering'=>'asc'))->first();
                     if(!empty($productImage)){
                        $image = WWW_ROOT."img/products/".$productImage->image_name;
                        if(file_exists($image)){
                           $orderArray[$key]['productImage'] = SITE_URL."img/products/".$productImage->image_name;      
                        }else{
                           $orderArray[$key]['productImage'] = SITE_URL."img/product/product1.jpg";
                        }                        
                     }else{
                        $orderArray[$key]['productImage'] = SITE_URL."img/product/product1.jpg";   
                     }
   					}else{
   						$orderArray[$key]['productImage'] = SITE_URL."img/product/product1.jpg";
   					}
   				}
                  
   			}else{
                  $orderArray[$key]['no_order'] = "no order available";
               }
   		}
         $totalArray['order'] = $orderDetailArray;
         $totalArray['products'] = $orderArray;         
   		$result['my_order_detail'] = $totalArray;
   		echo json_encode($result); die;		
   	}
   	
   
   	public function contactUs(){
   		header('Content-Type: application/json');
   		$postData = $this->request->getData();
   		#validations
   		$this->userNameValidation($postData);
   		$this->userEmailValidation($postData);
   		$this->userPhoneValidation($postData);
   		$this->userSubjectValidation($postData);
   		if(isset($postData['description'])){
   			$description= trim($postData['description']);
   		}else{
   			$description= '';
   		}
   		$table = TableRegistry::get('Contacts');
   		$saveData['name'] = trim(ucwords($postData['name']));
   		$saveData[EMAIL] = strtolower($postData['email']);
   		$saveData[STATUS] = 1;
   		$saveData['contact'] = trim($postData['userPhone']);
   		$saveData['subject'] = trim($postData['subject']);
   		$saveData['description'] = $description;
   		$saveData[CREATED] = time();
   		$saveData[MODIFIED] = time();
   		$tableEntity = $table->newEntity($saveData);
   		$record = $table->save($tableEntity);
   		# send email code with OTP
   		$sendEmailTo = 'User';
         $newarray = array(
            'name' => $saveData['name'],
            'email' => $saveData[EMAIL],
            'subject' => $saveData['subject'],
            'contact' => $saveData['contact'],
            'message' => $saveData['description']
         );
            $sendEmail = array(
               'to'=> $saveData[EMAIL],
               'userData' => $newarray,
               'template_id' => 5,
               'template' => 'inquiry',
               'sendEmailTo' => $sendEmailTo
            );
            if($this->SendEmails->sendEmail($sendEmail)){
               $otpEmailStatus = 'Email Sent';        
            }else{
               $otpEmailStatus = EMAIL_NOT_SEND;   
            }   
   		$result = array('response' => 200, 'status' => 'Success','inqury_email_status' => $otpEmailStatus,'msg' => 'successfully Submitted');
   		echo json_encode($result); die;
   	}
   
   	public function searchProduct(){
   		header('Content-Type: application/json');
   		$postData = $this->request->getData();
   		$this->searchKeywordsValidation($postData);
   		$keywords = trim($postData['keywords']);
   		
   		$productArray = array();
   		$categoryTable = TableRegistry::get(CATEGORIES);
   		$productTable = TableRegistry::get('Products');
   		$productImageTable = TableRegistry::get('ProductImages');
   		$products = $productTable->find('all')->where(array('product_name'.' ' .LIKE => '%'.$keywords.'%', STATUS => 1))->order(['product_name' => ASC]);
   		if($products->count() > 0){
   			foreach($products as $key => $product){
   			$productArray[$key]['id'] = $product->id;
   			$productArray[$key]['p_name'] = $product->product_name;
   			if(!empty($product->discounted_price)){
               $productArray[$key]['p_price'] = $product->discounted_price;
            }else{
               $productArray[$key]['p_price'] = $product->price;
            }
            $productArray[$key]['p_recipe'] = $product->recipe;
            $productArray[$key]['p_description'] = $product->description;
   			$productImage = $productImageTable->find()->where(array('product_id' => $product->id, 'status' => 1))->first();
   			
   				if(isset($productImage['image_name'])){
   					$productArray[$key]['productImage'] = SITE_URL."img/products/".$productImage['image_name'];		
   					}else{
   						$productArray[$key]['productImage'] = SITE_URL."img/no-image.jpg";	
   					}				
   			}
   			$result['search'] = $productArray; 
	   		echo json_encode($result); die;
   		}
   	}
   
   	

   	public function userEmailValidation($data){
   		if(!isset($data['email'])){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'email param does not exist');
   			echo json_encode($result); die;
   		}
   		if($data['email'] == ""){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'Email address cannot blank');
   			echo json_encode($result); die;
   		}
   		if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'Invalid email format');
   			echo json_encode($result); die;
   		}
   		
   	}
   
   	public function userNameValidation($data){
   		if(!isset($data['name'])){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'Name param does not exist');
   			echo json_encode($result); die;
   		}
   		if($data['name'] == ""){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'Name cannot blank');
   			echo json_encode($result); die;
   		}
   		if(!preg_match("/^([a-zA-Z' ]+)$/",$data['name'])){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'Invalid name format');
   			echo json_encode($result); die;
   		}
   	}
   
   	public function userSubjectValidation($data){
   		if(!isset($data['subject'])){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'subject param does not exist');
   			echo json_encode($result); die;
   		}
   		if($data['subject'] == ""){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'subject cannot blank');
   			echo json_encode($result); die;
   		}
   	}
   
   	public function checkUserToken($data){
   		if(!isset($data['userToken'])){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'userToken param does not exist');
   			echo json_encode($result); die;
   		}
   		if($data['userToken'] == ""){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'userToken cannot blank');
   			echo json_encode($result); die;
   		}
   
   		$table = TableRegistry::get(USERS);
   		$existUser = $table->find()->where(array('status' => 1, 'user_token' => $data['userToken']))->all();
   		if($existUser->count() == 0){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'InValid userToken');
   			echo json_encode($result); die;
   		}
   	}
   
   	public function checkOrderId($data){
   		if(!isset($data['orderId'])){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'orderId param does not exist');
   			echo json_encode($result); die;
   		}
   		if($data['orderId'] == ""){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'orderId cannot blank');
   			echo json_encode($result); die;
   		}
   		if(!is_numeric($data['orderId'])){
			$result = array('response' => 400,'status' => 'Error','msg' => 'orderId is numeric value');
			echo json_encode($result); die;
		}
   
   		$table = TableRegistry::get('Orders');
   		$existOrer = $table->find()->where(array('id' => $data['orderId']))->all();
   		if($existOrer->count() == 0){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'InValid orderId');
   			echo json_encode($result); die;
   		}
   	}

      public function checkAmount($data){
         if(!isset($data['amount'])){
            $result = array('response' => 400,'status' => 'Error','msg' => 'amount param does not exist');
            echo json_encode($result); die;
         }
         if($data['amount'] == ""){
            $result = array('response' => 400,'status' => 'Error','msg' => 'amount parm cannot blank');
            echo json_encode($result); die;
         }
         if(!is_numeric($data['amount'])){
         $result = array('response' => 400,'status' => 'Error','msg' => 'amount is numeric value');
         echo json_encode($result); die;
         }
         if($data['amount'] == 0){
            $result = array('response' => 400,'status' => 'Error','msg' => 'amount is grater than 0');
            echo json_encode($result); die;
         }
      }
   
      public function checkTransactionID($data){
         if(!isset($data['transactionID'])){
            $result = array('response' => 400,'status' => 'Error','msg' => 'transactionID param does not exist');
            echo json_encode($result); die;
         }
         if($data['transactionID'] == ""){
            $result = array('response' => 400,'status' => 'Error','msg' => 'transactionID parm cannot blank');
            echo json_encode($result); die;
         }
      }

   	public function checkCatID($data){
   		if(!isset($data['catID'])){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'catID param does not exist');
   			echo json_encode($result); die;
   		}
   		if($data['catID'] == ""){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'catID cannot blank');
   			echo json_encode($result); die;
   		}
   		if(!is_numeric($data['catID'])){
			$result = array('response' => 400,'status' => 'Error','msg' => 'catID is numeric value');
			echo json_encode($result); die;
		}
   
   		$table = TableRegistry::get(CATEGORIES);
   		$existCatID = $table->find()->where(array('id' => $data['catID'], STATUS => 1))->all();
   		if($existCatID->count() == 0){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'InValid catID');
   			echo json_encode($result); die;
   		}
   	}
   
   	public function checkProductId($data){
   		if(!isset($data['productID'])){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'productID param does not exist');
   			echo json_encode($result); die;
   		}
   		if($data['productID'] == ""){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'productID cannot blank');
   			echo json_encode($result); die;
   		}
   		if(!is_numeric($data['productID'])){
			$result = array('response' => 400,'status' => 'Error','msg' => 'productID is numeric value');
			echo json_encode($result); die;
		}
   
   		$table = TableRegistry::get('Products');
   		$existOrer = $table->find()->where(array('id' => $data['productID'], STATUS => 1))->all();
   		if($existOrer->count() == 0){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'InValid productID');
   			echo json_encode($result); die;
   		}
   	}
   
   	public function searchKeywordsValidation($data){
   		if(!isset($data['keywords'])){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'keywords param does not exist');
   			echo json_encode($result); die;
   		}
   		if(trim($data['keywords']) == ""){
   			$result = array('response' => 400,'status' => 'Error','msg' => '404 page not found');
   			echo json_encode($result); die;
   		}
  
    		$table = TableRegistry::get('Products');
   		$productsList = $table->find('all')->where(array('product_name'.' ' .LIKE => '%'.trim($data['keywords']).'%', STATUS => 1));
   		if($productsList->count() == 0){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'No Product Available');
   			echo json_encode($result); die;
   		}
   	}
   
   	
   }
   ?>