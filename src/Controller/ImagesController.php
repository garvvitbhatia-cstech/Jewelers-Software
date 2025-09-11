<?php
declare(strict_types=1);
namespace App\Controller;
use Cake\Core\Configure;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;
use Cake\View\Exception\MissingTemplateException;
use Cake\ORM\TableRegistry;
class ImagesController extends AppController{

    #save Image
    public function saveImage(){
        if($this->request->is('ajax')){
            $setData = array();
            $setData[EXT] = $_REQUEST[EXT];
            $setData[FOLDER] = $this->decryptData($_REQUEST[FLD_DATA]);
            $setData[OLD_IMAGE] = $this->decryptData($_REQUEST[OLD_IMAGE]);
            $setData[CROP_IMG] = $_REQUEST[CROP_IMG];
            return $this->uploadVideoBanner($setData);
        }else{
            $this->redirect('/');
        }
    }
    //upload video banners
    function uploadVideoBanner($setData){
        $ext = $setData[EXT];
        $folder = $setData[FOLDER];
        $destination = WWW_ROOT.'img/'.$folder.'/';
        $actual_image_name = time().random_int(0,99999).".".$ext;
        if(isset($setData[OLD_IMAGE]) && !empty($setData[OLD_IMAGE])){
            $actual_image_name = $setData[OLD_IMAGE];
        }
        $this->createImage($setData[CROP_IMG], $destination.$actual_image_name, $ext);
        $result = json_encode(array(FILENAME => $actual_image_name));
        e($result);die;
    }

    #create Image
    function createImage($base64_string, $output_file, $ext = NULL) {
        if($ext == 'jpg'){
            $image = imagecreatefrompng($base64_string);
            imagejpeg($image, $output_file, 100);
            imagedestroy($image);
        }else{
            $ifp = fopen($output_file, "wb");
            $data = explode(',', $base64_string);
            fwrite($ifp, base64_decode($data[1]));
            fclose($ifp);
        }
    }

}
