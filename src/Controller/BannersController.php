<?php
namespace App\Controller;
use Cake\Core\Configure;
use Cake\Network\Exception\ForbiddenException;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\ORM\TableRegistry;
use Cake\Http\Response;
use Cake\Core\Exception\Exception;
use PHPExcel;
use PHPExcel_IOFactory;

require_once(ROOT . DS  . 'vendor' . DS  . 'ImageResize' . DS . 'ImageResize.php');
use Eventviva\ImageResize;
use Eventviva\ImageResizeException;

class BannersController extends AppController{
    #countries page
    public function index(){
        #check User Auth
        $this->checkValidSession();
        $this->viewBuilder()->setLayout(ADMIN_DASHBOARD_LAYOUT);
        $table = TableRegistry::get(BANNERS);
        $session = $this->request->getSession();
        $userProfiles = $table->find()->where(array('user_id' => $session->read(AUTHADMINID)))->all();
        $this->set(compact('userProfiles'));
    }

    #checkLoginSession
    function checkValidSession(){
        $session = $this->request->getSession();
        $nextPageUrl = $_SERVER["REQUEST_URI"];
        $session->write('nextPageUrl', $nextPageUrl);
        if(!$session->check(AUTHADMINID)){
            return $this->redirect(ADMIN_FOLDER);
        }
    }

    public function uploadFile(){
        $this->viewBuilder()->setLayout('false');
        if($this->request->is('Ajax')){
            if(!empty($_FILES)){
                $msg = "Error";
                $fileName = $_FILES['file']['name']; //Get the image
                $file_full = WWW_ROOT.BANNERPATH; //Image storage path
                $file_temp_name = $_FILES['file']['tmp_name'];
                $pathInfo = pathinfo(basename($fileName));
                $ext = $pathInfo['extension'];
                $checkImage = getimagesize($file_temp_name);
                if($checkImage !== false){
                    $new_file_name = date('d_m_Y_H_i_'.mt_rand(111, 999).'_a.').$ext;
                    if(move_uploaded_file($file_temp_name, $file_full.$new_file_name)){
                        #### rezize image #######
						$image = new ImageResize($file_full.$new_file_name);
						$image->crop(300, 300);
						$image->save($file_full.$new_file_name);
						######## end ############
                        $table = TableRegistry::get(BANNERS);
                        $session = $this->request->getSession();
                        $saveData['user_id'] = $session->read(AUTHADMINID);
                        $saveData['image_profile'] = $new_file_name;
                        $tableEntity = $table->newEntity($saveData);
                        $table->save($tableEntity);
                        $msg = "Success";
                    }
                }
            }
            echo json_encode(array('msg' => $msg));
        }
        exit;
    }

    public function deleteBannerImage(){
        $this->viewBuilder()->setLayout('false');
        if($this->request->is('Ajax')){
            $postData = $this->request->getData();
            if(!empty($postData)){
                $rowId = $this->decryptData($postData['rowId']);
                $table = TableRegistry::get(BANNERS);
                $deleteRecord = $table->find()->where(array(ID => $rowId))->first();
                $imageName = $deleteRecord->image_profile;
                if(file_exists(WWW_ROOT.BANNERPATH.$imageName)){
                    unlink(WWW_ROOT.BANNERPATH.$imageName);
                }
                $record = $table->get($rowId);
                $table->delete($record);
            }
        }
        exit;
    }
}
?>