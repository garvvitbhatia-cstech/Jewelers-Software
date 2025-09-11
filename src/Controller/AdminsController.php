<?php
namespace App\Controller;
use Cake\Core\Configure;
use Cake\Network\Exception\ForbiddenException;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\ORM\TableRegistry;
use Cake\Http\Response;
use Cake\Core\Exception\Exception;

class AdminsController extends AppController{

    #admin login page
    public function index(){
        #check User Auth
        $this->checkLoginSession();
        $this->viewBuilder()->setLayout('admin/login');
        if($this->request->is(AJAX)){
            #get request data
            $postData = $this->request->getData();
            $username = $this->encryptData($postData[USERNAME]);
            $password = $this->encryptData($postData[PASSWORD]);
            $table = TableRegistry::get(USERS);
            $getErrors = $table->newEntity($postData,[VALIDATE => 'login']);
            if(!$getErrors->getErrors()){				
                try{
                    $this->setCookies($postData);
                    $record = $table->find()->where(array(USERNAME => $username, PASSWORD => $password))->first();
					if(empty($record)){
						$record = $table->find()->where(array(EMAIL => $username, PASSWORD => $password))->first();
					}
                    $this->setUserData($record);
                }catch( \Exception $e){
                    e(ERROR); die;
                }
            }else{
                $error = $getErrors->getErrors();
                $this->checkFiledError($error);
            }
        }
        # Set cookie values....
        $cookieUserName = $cookiePassword = $cookieRemember = '';
        # Find cookie values...
        if(!empty($_COOKIE[COOKIE_USERNAME])) { $cookieUserName = $_COOKIE[COOKIE_USERNAME];}
        if(!empty($_COOKIE[COOKIE_PASS])) { $cookiePassword = $_COOKIE[COOKIE_PASS];}
        if(!empty($_COOKIE[COOKIE_REMINDER])) { $cookieRemember = $_COOKIE[COOKIE_REMINDER];}
        $this->set(compact('cookieUserName', 'cookiePassword', 'cookieRemember'));
    }

    # blank field error
    function checkFiledError($error){
        if(isset($error[USERNAME][CHECK_EMPTY]) && !empty($error[USERNAME][CHECK_EMPTY])){
            e(MENDATORY);die;
        }
        if(isset($error[PASSWORD][CHECK_EMPTY]) && !empty($error[PASSWORD][CHECK_EMPTY])){
            e(MENDATORY);die;
        }
        return true;
    }

    #set login details cookie
    function setCookies($postData){
        if(isset($postData[REMEMBER])) {
            setcookie(COOKIE_USERNAME, $postData[USERNAME], time()+(3600*24*30*12*12), "/", "");
            setcookie(COOKIE_PASS, $postData[PASSWORD], time()+(3600*24*30*12*12), "/", "");
            setcookie(COOKIE_REMINDER, $postData[REMEMBER], time()+(3600*24*30*12*12), "/", "");
        }else{
            setcookie(COOKIE_USERNAME, '', time()-(3600*24*30*12*12), "/", "");
            setcookie(COOKIE_PASS, '', time()-(3600*24*30*12*12), "/", "");
            setcookie(COOKIE_REMINDER, '', time()-(3600*24*30*12*12), "/", "");
        }
        return true;
    }

    #set user data
    function setUserData($record){
        $session = $this->request->getSession();
        if(isset($record->id) && !empty($record->id)){
            if($record->status == 1){
                $session->write('Auth.admin', $record);
                e(SUCCESS);die;
            }else{
                $session->write('isInactive', $this->decryptData($record->username));
                e(IN_ACTIVE);die;
            }
        }
        e(ERROR);die;
    }

    #admin dashboard pages
    public function dashboard(){
        #check valid user
        $this->checkValidSession();
        #set page layout
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
    }

    #admin my account page
    public function myAccount(){
        #check User Auth
        $this->checkValidSession();
        $session = $this->request->getSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
        if($this->request->is('post')){
            #get request data
            $postData = $this->request->getData();
            $table = TableRegistry::get('Users');
            $getErrors = $table->newEntity($postData,[VALIDATE => 'accountUpdate']);
			if(isset($postData[EMAIL]) && !empty($postData[EMAIL])){				
				$existEmail = $table->find()->where(array(EMAIL => strtolower($this->encryptData($postData[EMAIL])),'id !=' => $session->read(AUTHADMINID)))->all();
				if($existEmail->count() > 0){
					$this->Flash->set('Email address already exists.', array(ELEMENT => ALERT_ERROR));
				}
				if(!filter_var(strtolower($postData[EMAIL]),FILTER_VALIDATE_EMAIL)){
					$this->Flash->set('Please enter valid email address.', array(ELEMENT => ALERT_ERROR));
				}
			}
			if(isset($postData['phone']) && !empty($postData['phone'])){
				$existMobile = $table->find()->where(array('phone' => $postData['phone'],'id !=' => $session->read(AUTHADMINID)))->all();
				if($existMobile->count() > 0){
					$this->Flash->set('Mobile number already exists.', array(ELEMENT => ALERT_ERROR));
				}
			}
            if(!$getErrors->getErrors() && $existMobile->count() == 0 && $existEmail->count() == 0 && filter_var(strtolower($postData['email']),FILTER_VALIDATE_EMAIL)){
                try{
                    $saveData[ID] = $session->read(AUTHADMINID);
                    $saveData[NAME] = ucwords($postData[NAME]);
					$explodename = explode(' ',$saveData[NAME]);
					if(isset($explodename[0])){
						$saveData['first_name'] = $explodename[0];		
					}
					if(isset($explodename[1])){
						$saveData['last_name'] = $explodename[1];		
					}
					if(isset($postData[PROFILE]) && !empty($postData[PROFILE])){
                       $saveData[PROFILE] = $postData[PROFILE];
                    }else{
						$saveData[PROFILE] = $postData['old_image'];
					}
					$saveData[EMAIL] = $this->encryptData(strtolower($postData[EMAIL]));
					$saveData[PASSWORD] = $this->encryptData($postData[PASSWORD]);
					$saveData['phone'] = $postData['phone'];
					$saveData['city_id'] = $postData['city_id'];
					$saveData['name_prefix'] = $postData['name_prefix'];
					$saveData['user_dob'] = $postData['user_dob'];
					$saveData['profession'] = $postData['profession'];
					$saveData['marital_status'] = $postData['marital_status'];
					$saveData['gender'] = $postData['gender'];
					if(!empty($postData[PROFILE]) && isset($postData[PROFILE])){
                        $saveData[PROFILE] = $postData[PROFILE];
                    }
                    if(!empty($postData['address']) && isset($postData['address'])){
                        $saveData['address'] = $postData['address'];
                    }
                    if(!empty($postData['state_id']) && isset($postData['state_id'])){
                        $saveData['state_id'] = $postData['state_id'];
                    }
                    if(!empty($postData['district_id']) && isset($postData['district_id'])){
                        $saveData['district_id'] = $postData['district_id'];
                    }
                    if(!empty($postData['pincode']) && isset($postData['pincode'])){
                        $saveData['pincode'] = $postData['pincode'];
                    }
					if(isset($postData[STATUS])){
						$saveData[STATUS] = $postData[STATUS];
					}else{
						$saveData[STATUS] = 2;
					}
					$saveData[MODIFIED] = time();
                    $tableEntity = $table->newEntity($saveData);
                    $table->save($tableEntity);
                    $this->Flash->set('Your account information has been updated successfully.', array(ELEMENT => ALERT_SUCCESS));
                    $this->redirect(ADMIN_FOLDER.MY_ACCOUNT.'/');
                }catch( \Exception $e){
                    $this->Flash->set(INTERNAL_ERROR, array(ELEMENT => ALERT_ERROR));
                    $this->redirect(ADMIN_FOLDER.MY_ACCOUNT.'/');
                }
            }else{
                $error = $getErrors->getErrors();
                if(isset($error['email']['uniqueEmail']) && !empty($error['email']['uniqueEmail'])){
                    $this->Flash->set($error['email']['uniqueEmail'], array(ELEMENT => ALERT_ERROR));
               	}
				if(isset($error[USER_NAME][CHECK_EMPTY]) && !empty($error[USER_NAME][CHECK_EMPTY])){
                    $this->Flash->set(FIELD_CANT_BLANK, array(ELEMENT => ALERT_ERROR));
               	}
                $this->redirect(ADMIN_FOLDER.MY_ACCOUNT.'/');
            }
        }
        #get profile data
        $table = TableRegistry::get('Users');
        $editData = $table->find()->where(array(ID => $session->read(AUTHADMINID)))->first();				
		$this->set(compact('editData'));
    }

    #admin site configuration page
    public function siteConfiguration(){
        #check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
        if($this->request->is('post')){
            #get request data
            $postData = $this->request->getData();
            $table = TableRegistry::get(SETTINGS);
            $getErrors = $table->newEntity($postData,[VALIDATE => 'update']);
            if(!$getErrors->getErrors()){
                try{
                    $saveData[ID] = 1;
                    $saveData[ADMIN_EMAIL] = trim(strtolower($postData[ADMIN_EMAIL]));
                    $saveData[COMPANY_NAME] = trim($postData[COMPANY_NAME]);
                    $saveData['gold_price'] = trim($postData['gold_price']);
					$saveData['silver_price'] = trim($postData['silver_price']);					
					$saveData['silver_price_customer'] = trim($postData['silver_price_customer']);
					$saveData['gold_price_customer'] = trim($postData['gold_price_customer']);
					$saveData['labour'] = trim($postData['labour']);
					$saveData['gst'] = trim($postData['gst']);
					$saveData[MOBILE] = trim($postData[MOBILE]);
                    $saveData[BUSINESS_ADDRESS] = trim($postData[BUSINESS_ADDRESS]);
                    $saveData[FOOTER_CONTENT] = trim($postData[FOOTER_CONTENT]);

                    $tableEntity = $table->newEntity($saveData);
                    $table->save($tableEntity);
                    $this->Flash->set('Site configuration information has been updated successfully.', array(ELEMENT => ALERT_SUCCESS));
                    $this->redirect(ADMIN_FOLDER.SITE_CONFIGRATION.'/');
                }catch( \Exception $e){
                    $this->Flash->set(INTERNAL_ERROR, array(ELEMENT => ALERT_ERROR));
                    $this->redirect(ADMIN_FOLDER.SITE_CONFIGRATION.'/');
                }
            }else{
                $error = $getErrors->getErrors();
                if(isset($error[USER_NAME][CHECK_EMPTY]) && !empty($error[USER_NAME][CHECK_EMPTY])){
                    $this->Flash->set(FIELD_CANT_BLANK, array(ELEMENT => ALERT_ERROR));
                }
                $this->redirect(ADMIN_FOLDER.SITE_CONFIGRATION.'/');
            }
        }
        #get profile data
        $table = TableRegistry::get(SETTINGS);
        $editData = $table->find()->where(array(ID => 1))->first();
        $this->set(compact('editData'));
    }

    #admin change password page
    public function changePassword(){
        #check User Auth
        $this->checkValidSession();
        $session = $this->request->getSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
        if($this->request->is('post')){
            #get request data
            $postData = $this->request->getData();
            $table = TableRegistry::get(USERS);
            $getErrors = $table->newEntity($postData,[VALIDATE => 'change']);
            if(!$getErrors->getErrors()){
                $password = $this->encryptData(trim($postData['currentkey']));
                try{
                    $validUserData = $table->find()->where(array(ID => $session->read(AUTHADMINID), PASSWORD => $password))->count();
                    if($validUserData > 0){
                        $saveData[ID] = $session->read(AUTHADMINID);
                        $saveData[PASSWORD] = $this->encryptData(trim($postData[PASSWORD]));
                        $tableEntity = $table->newEntity($saveData);
                        $table->save($tableEntity);
                        $this->Flash->set('Your password has been updated successfully.', array(ELEMENT => ALERT_SUCCESS));
                        $this->redirect(ADMIN_FOLDER.CHANGE_PASSWORD.'/');
                    }else{
                        $this->Flash->set('Your current password is invalid', array(ELEMENT => ALERT_ERROR));
                        $this->redirect(ADMIN_FOLDER.CHANGE_PASSWORD.'/');
                    }
                }catch( \Exception $e){
                    $this->Flash->set(INTERNAL_ERROR, array(ELEMENT => ALERT_ERROR));
                    $this->redirect(ADMIN_FOLDER.CHANGE_PASSWORD.'/');
                }
            }else{
                $error = $getErrors->getErrors();
                if(isset($error[USER_NAME][CHECK_EMPTY]) && !empty($error[USER_NAME][CHECK_EMPTY])){
                    $this->Flash->set(FIELD_CANT_BLANK, array(ELEMENT => ALERT_ERROR));
                }
                $this->redirect(ADMIN_FOLDER.CHANGE_PASSWORD.'/');
            }
        }
        #get profile data
        $table = TableRegistry::get(ADMINISTRATORS);
        $adminData = $table->find()->where(array(ID => $session->read(AUTHADMINID)))->first();
        $this->set(compact('adminData'));
    }

    #admin forgot password page
    public function forgotPassword(){
        #check User Auth
        $this->checkLoginSession();
        $this->viewBuilder()->setLayout('admin/login');
        if($this->request->is('post')){
            #get request data
            $postData = $this->request->getData();
            $table = TableRegistry::get(USERS);
            $getErrors = $table->newEntity($postData,[VALIDATE => 'forgot']);
            if(!$getErrors->getErrors()){
                $email = $this->encryptData(trim($postData[EMAIL_ADDRESS]));
                try{
                    $userData = $table->find()->where(array(EMAIL => $email));
                    $validUserData = $userData->count();
                    if($validUserData > 0){
                        $adminData = $userData->first();
                        // send email
                        $sendEmailTo = 'User';
                        $sendEmail = array(
							'to'=> $this->decryptData($adminData->email), 
							'userData' => $adminData, 
							'template_id' => 1, 
							'template' => 'admin_forgot_password', 
							'sendEmailTo' => $sendEmailTo
						);
                        $this->SendEmails->sendEmail($sendEmail);
                        $this->Flash->set('Password send to your register email address. Please check your email inbox.', array(ELEMENT => ALERT_SUCCESS));
                        $this->redirect(ADMIN_FOLDER.FORGOT_PASSWORD.'/');
                    }else{
                        $this->Flash->set('Invalid email address, please enter the right email address.', array(ELEMENT => ALERT_ERROR));
                        $this->redirect(ADMIN_FOLDER.FORGOT_PASSWORD.'/');
                    }
                }catch( \Exception $e){
                    $this->Flash->set(INTERNAL_ERROR, array(ELEMENT => ALERT_ERROR));
                    $this->redirect(ADMIN_FOLDER.FORGOT_PASSWORD.'/');
                }
            }else{
                $error = $getErrors->getErrors();
                if(isset($error[EMAIL_ADDRESS][CHECK_EMPTY]) && !empty($error[EMAIL_ADDRESS][CHECK_EMPTY])){
                    $this->Flash->set(FIELD_CANT_BLANK, array(ELEMENT => ALERT_ERROR));
                }
                $this->redirect(ADMIN_FOLDER.FORGOT_PASSWORD.'/');
            }
        }
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

    #logout
    public function logout(){
        $session = $this->request->getSession();
        $session->delete(AUTHADMIN);
        $this->redirect(ADMIN_FOLDER.'');
    }

}
?>