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
   use Cake\Mailer\Mailer;
   use Cake\Utility\Security;
   //use Cake\Auth\DefaultPasswordHasher;
   use Cake\ORM\Entity;
   
   class ValidationsController extends AppController{
   
      public function userSubjectValidation($data){
         if(!isset($data['subject'])){
            $result = array('response' => 400, 'status' => 'Error', 'msg' => 'subject param does not exist');
            echo json_encode($result);
            die;
         }
         if($data['subject'] == ""){
            $result = array('response' => 400, 'status' => 'Error', 'msg' => 'subject cannot blank');
            echo json_encode($result);
            die;
         }
      }
    
      public function userDescriptionValidation($data){
         if(!isset($data['description'])){
            $result = array('response' => 400, 'status' => 'Error', 'msg' => 'description param does not exist');
            echo json_encode($result);
            die;
         }
         if($data['description'] == ""){
            $result = array('response' => 400, 'status' => 'Error', 'msg' => 'description cannot blank');
            echo json_encode($result);
            die;
         }
      }
   
   	public function userPhoneValidation($data){
   		if(!isset($data['userPhone'])){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'userPhone param does not exist');
   			echo json_encode($result); die;
   		}
   		if($data['userPhone'] == ""){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'Phone number cannot blank');
   			echo json_encode($result); die;
   		}
   		if(!preg_match("/^[0-9]{10}+$/",$data['userPhone'])){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'Invalid phone format');
   			echo json_encode($result); die;
   		}	
   	}
   	
   	public function userEmailValidation($data){
   		if(!isset($data['userEmail'])){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'userEmail param does not exist');
   			echo json_encode($result); die;
   		}
   		if($data['userEmail'] == ""){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'userEmail cannot blank');
   			echo json_encode($result); die;
   		}
   		if(!filter_var($data['userEmail'], FILTER_VALIDATE_EMAIL)){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'Invalid email format');
   			echo json_encode($result); die;
   		}
   		$table = TableRegistry::get(USERS);
   		$existUser = $table->find()->where(array(EMAIL => $data['userEmail']))->count();
   		if($existUser > 0){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'userEmail already exist');
   			echo json_encode($result); die;
   		}
   	}
   
   	public function userLoginEmailValidation($data){
   		if(!isset($data['userEmail'])){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'userEmail param does not exist');
   			echo json_encode($result); die;
   		}
   		if($data['userEmail'] == ""){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'userEmail cannot blank');
   			echo json_encode($result); die;
   		}
   		if(!filter_var($data['userEmail'], FILTER_VALIDATE_EMAIL)){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'Invalid email format');
   			echo json_encode($result); die;
   		}
      $table = TableRegistry::get(USERS);
      $existUser = $table->find()->where(array('status' => 1, EMAIL => $data['userEmail']))->count();
      if($existUser == 0){
        $result = array('response' => 400,'status' => 'Error','msg' => 'email not registered');
        echo json_encode($result); die;
      }
   	}
   
   	public function userLoginPasswordValidation($data){
   		if(!isset($data['userPassword'])){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'userPassword param does not exist');
   			echo json_encode($result); die;
   		}
   		if($data['userPassword'] == ""){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'userPassword cannot blank');
   			echo json_encode($result); die;
   		}
   		if(strlen($data['userPassword']) < 5){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'userPassword length should be greather then 6');
   			echo json_encode($result); die;
   		}
   	}
   
   	public function checkPrice($data){
   		if(!isset($data['price'])){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'price param does not exist');
   			echo json_encode($result); die;
   		}
   		if($data['price'] == ""){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'price cannot blank');
   			echo json_encode($result); die;
   		}
   	}	
   
   	public function userNameValidation($data){
   		if(!isset($data['fullname'])){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'fullname param does not exist');
   			echo json_encode($result); die;
   		}
   		if($data['fullname'] == ""){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'fullname cannot blank');
   			echo json_encode($result); die;
   		}
   		if(!preg_match("/^([a-zA-Z' ]+)$/",$data['fullname'])){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'fullname format not valid');
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
   		$existUser = $table->find()->where(array('status' => 1 , USER_TOKEN => $data['userToken']))->all();
   		if($existUser->count() == 0){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'InValid userToken');
   			echo json_encode($result); die;
   		}
   	}
   
   	public function checkStateValidation($data){
   		if(!isset($data['state_id'])){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'state_id param does not exist');
   			echo json_encode($result); die;
   		}
   		if($data['state_id'] == ""){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'state_id cannot blank');
   			echo json_encode($result); die;
   		}
   		if(!is_numeric($data['state_id'])){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'state_id have numeric format');
   			echo json_encode($result); die;
   		}
   
   		$table = TableRegistry::get(STATES);
   		$existUser = $table->find()->where(array('status' => 1, COUNTRY_ID => 101, ID => $data['state_id']))->all();
   		if($existUser->count() == 0){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'InValid state_id');
   			echo json_encode($result); die;
   		}
   	}
   
   	public function checkCityValidation($data){
   		if(!isset($data['city_id'])){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'city_id param does not exist');
   			echo json_encode($result); die;
   		}
   		if($data['city_id'] == ""){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'city_id cannot blank');
   			echo json_encode($result); die;
   		}
   		if(!is_numeric($data['city_id'])){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'city_id have numeric format');
   			echo json_encode($result); die;
   		}
   
   		$table = TableRegistry::get(CITIES);
   		$existUser = $table->find()->where(array('status' => 1, COUNTRY_ID => 101, ID => $data['city_id']))->all();
   		if($existUser->count() == 0){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'InValid city_id');
   			echo json_encode($result); die;
   		}
   	}
  
    public function userDLValidation($data){
      if(!isset($data['dl_no'])){
        $result = array('response' => 400,'status' => 'Error','msg' => 'dl_no param does not exist');
        echo json_encode($result); die;
      }
      if($data['dl_no'] == ""){
        $result = array('response' => 400,'status' => 'Error','msg' => 'dl_no cannot blank');
        echo json_encode($result); die;
      }
    }


   	public function userPincodeValidation($data){
   		if(!isset($data['pincode'])){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'pincode param does not exist');
   			echo json_encode($result); die;
   		}
   		if($data['pincode'] == ""){
   			$result = array('response' => 400,'status' => 'Error','msg' => 'pincode cannot blank');
   			echo json_encode($result); die;
   		}
      if(!is_numeric($data['pincode'])){
        $result = array('response' => 400,'status' => 'Error','msg' => 'pincode have numeric format');
         echo json_encode($result); die;
      }
      if(!preg_match("/^[0-9]{6}+$/",$data['pincode'])){
        $result = array('response' => 400,'status' => 'Error','msg' => 'enter valid 6 digit pincode format');
        echo json_encode($result); die;
      }  
    }

      function mime2ext($mime){
          $all_mimes = '{"png":["image\/png","image\/x-png"],"bmp":["image\/bmp","image\/x-bmp",
          "image\/x-bitmap","image\/x-xbitmap","image\/x-win-bitmap","image\/x-windows-bmp",
          "image\/ms-bmp","image\/x-ms-bmp","application\/bmp","application\/x-bmp",
          "application\/x-win-bitmap"],"gif":["image\/gif"],"jpeg":["image\/jpeg",
          "image\/pjpeg"],"xspf":["application\/xspf+xml"],"vlc":["application\/videolan"],
          "wmv":["video\/x-ms-wmv","video\/x-ms-asf"],"au":["audio\/x-au"],
          "ac3":["audio\/ac3"],"flac":["audio\/x-flac"],"ogg":["audio\/ogg",
          "video\/ogg","application\/ogg"],"kmz":["application\/vnd.google-earth.kmz"],
          "kml":["application\/vnd.google-earth.kml+xml"],"rtx":["text\/richtext"],
          "rtf":["text\/rtf"],"jar":["application\/java-archive","application\/x-java-application",
          "application\/x-jar"],"zip":["application\/x-zip","application\/zip",
          "application\/x-zip-compressed","application\/s-compressed","multipart\/x-zip"],
          "7zip":["application\/x-compressed"],"xml":["application\/xml","text\/xml"],
          "svg":["image\/svg+xml"],"3g2":["video\/3gpp2"],"3gp":["video\/3gp","video\/3gpp"],
          "mp4":["video\/mp4"],"m4a":["audio\/x-m4a"],"f4v":["video\/x-f4v"],"flv":["video\/x-flv"],
          "webm":["video\/webm"],"aac":["audio\/x-acc"],"m4u":["application\/vnd.mpegurl"],
          "pdf":["application\/pdf","application\/octet-stream"],
          "pptx":["application\/vnd.openxmlformats-officedocument.presentationml.presentation"],
          "ppt":["application\/powerpoint","application\/vnd.ms-powerpoint","application\/vnd.ms-office",
          "application\/msword"],"docx":["application\/vnd.openxmlformats-officedocument.wordprocessingml.document"],
          "xlsx":["application\/vnd.openxmlformats-officedocument.spreadsheetml.sheet","application\/vnd.ms-excel"],
          "xl":["application\/excel"],"xls":["application\/msexcel","application\/x-msexcel","application\/x-ms-excel",
          "application\/x-excel","application\/x-dos_ms_excel","application\/xls","application\/x-xls"],
          "xsl":["text\/xsl"],"mpeg":["video\/mpeg"],"mov":["video\/quicktime"],"avi":["video\/x-msvideo",
          "video\/msvideo","video\/avi","application\/x-troff-msvideo"],"movie":["video\/x-sgi-movie"],
          "log":["text\/x-log"],"txt":["text\/plain"],"css":["text\/css"],"html":["text\/html"],
          "wav":["audio\/x-wav","audio\/wave","audio\/wav"],"xhtml":["application\/xhtml+xml"],
          "tar":["application\/x-tar"],"tgz":["application\/x-gzip-compressed"],"psd":["application\/x-photoshop",
          "image\/vnd.adobe.photoshop"],"exe":["application\/x-msdownload"],"js":["application\/x-javascript"],
          "mp3":["audio\/mpeg","audio\/mpg","audio\/mpeg3","audio\/mp3"],"rar":["application\/x-rar","application\/rar",
          "application\/x-rar-compressed"],"gzip":["application\/x-gzip"],"hqx":["application\/mac-binhex40",
          "application\/mac-binhex","application\/x-binhex40","application\/x-mac-binhex40"],
          "cpt":["application\/mac-compactpro"],"bin":["application\/macbinary","application\/mac-binary",
          "application\/x-binary","application\/x-macbinary"],"oda":["application\/oda"],
          "ai":["application\/postscript"],"smil":["application\/smil"],"mif":["application\/vnd.mif"],
          "wbxml":["application\/wbxml"],"wmlc":["application\/wmlc"],"dcr":["application\/x-director"],
          "dvi":["application\/x-dvi"],"gtar":["application\/x-gtar"],"php":["application\/x-httpd-php",
          "application\/php","application\/x-php","text\/php","text\/x-php","application\/x-httpd-php-source"],
          "swf":["application\/x-shockwave-flash"],"sit":["application\/x-stuffit"],"z":["application\/x-compress"],
          "mid":["audio\/midi"],"aif":["audio\/x-aiff","audio\/aiff"],"ram":["audio\/x-pn-realaudio"],
          "rpm":["audio\/x-pn-realaudio-plugin"],"ra":["audio\/x-realaudio"],"rv":["video\/vnd.rn-realvideo"],
          "jp2":["image\/jp2","video\/mj2","image\/jpx","image\/jpm"],"tiff":["image\/tiff"],
          "eml":["message\/rfc822"],"pem":["application\/x-x509-user-cert","application\/x-pem-file"],
          "p10":["application\/x-pkcs10","application\/pkcs10"],"p12":["application\/x-pkcs12"],
          "p7a":["application\/x-pkcs7-signature"],"p7c":["application\/pkcs7-mime","application\/x-pkcs7-mime"],"p7r":["application\/x-pkcs7-certreqresp"],"p7s":["application\/pkcs7-signature"],"crt":["application\/x-x509-ca-cert","application\/pkix-cert"],"crl":["application\/pkix-crl","application\/pkcs-crl"],"pgp":["application\/pgp"],"gpg":["application\/gpg-keys"],"rsa":["application\/x-pkcs7"],"ics":["text\/calendar"],"zsh":["text\/x-scriptzsh"],"cdr":["application\/cdr","application\/coreldraw","application\/x-cdr","application\/x-coreldraw","image\/cdr","image\/x-cdr","zz-application\/zz-winassoc-cdr"],"wma":["audio\/x-ms-wma"],"vcf":["text\/x-vcard"],"srt":["text\/srt"],"vtt":["text\/vtt"],"ico":["image\/x-icon","image\/x-ico","image\/vnd.microsoft.icon"],"csv":["text\/x-comma-separated-values","text\/comma-separated-values","application\/vnd.msexcel"],"json":["application\/json","text\/json"]}';
          $all_mimes = json_decode($all_mimes,true);
          foreach ($all_mimes as $key => $value) {
              if(array_search($mime,$value) !== false) return $key;
          }
          return false;
      }

   
   }
   ?>