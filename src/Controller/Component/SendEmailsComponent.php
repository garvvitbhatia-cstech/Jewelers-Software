<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use Cake\Mailer\Mailer;
use Cake\Core\Exception\Exception;
class SendEmailsComponent extends Component{

	#send Emails
	public function sendEmail($sendEmailData) {
		// send email notification
		$emailtable = TableRegistry::get('EmailTemplates');
		$emailTemplate = $emailtable->find()->where(array('EmailTemplates.id' => $sendEmailData['template_id']))->first();
		$sendToUser = ADMIN;
		if(isset($sendEmailData[SENDEMAILTO]) && $sendEmailData[SENDEMAILTO] != ADMIN){
			$sendToUser = $sendEmailData[SENDEMAILTO];
			$this->sendEmailData($sendEmailData);
		}
		if(!isset($sendEmailData[SENDEMAILTO])){
			$this->sendEmailData($sendEmailData);
		}
		#send  Email to Admins
		if(isset($emailTemplate->email_template_email_address) && !empty($emailTemplate->email_template_email_address) && $sendToUser != 'UserOnly'){
			$adminEmails = explode('||',$emailTemplate->email_template_email_address);
			if(count($adminEmails) > 0){
				foreach($adminEmails as $adminEmail){
					$sendEmailData[SENDEMAILTO] = ADMIN;
					$sendEmailData['to'] = $adminEmail;
					if(trim($adminEmail) !='' && (filter_var($adminEmail, FILTER_VALIDATE_EMAIL))){
						$this->sendEmailData($sendEmailData);
					}
				}
			}
		}
		return SUCCESS;
	}
	function sendEmailData($sendEmailData){
		$returnMsg = EMAIL_NOT_SEND;
		#get email template data
		$emailtable = TableRegistry::get('EmailTemplates');
		$emailTemplate = $emailtable->find()->where(array('EmailTemplates.id' => $sendEmailData['template_id']))->first();
		#get site configuration data
		$sitetable = TableRegistry::get(SETTINGS);
		$siteConfiguration = $sitetable->find()->where(array('id' => 1))->first();
		if(isset($emailTemplate->email_template_status) && $emailTemplate->email_template_status == 1){
			try{
				$mailer = new Mailer();
				$mailer
				->setEmailFormat('html')
				->setTo($sendEmailData['to'])
				->setSubject($emailTemplate->email_template_subject)
				->setFrom([ $emailTemplate->email_template_sender_email_address => $emailTemplate->email_template_sender_name])
				->set(compact('emailTemplate','siteConfiguration','sendEmailData'))
				->viewBuilder()
				->setTemplate($sendEmailData['template']);
				$mailer->deliver();
				$returnMsg =  SUCCESS;
			}catch( \MissingTemplateException $e){
				$returnMsg = EMAIL_NOT_SEND;
			}catch( \SocketException $e){
				$returnMsg = EMAIL_NOT_SEND;
			}catch( \Exception $e){
				$returnMsg = EMAIL_NOT_SEND;
			}
			return $returnMsg;
		}
	}
}
?>
