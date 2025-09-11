<?php
namespace App\View\Helper;
use Cake\View\Helper;
use Cake\ORM\TableRegistry;
use Aws\S3\S3Client;
use Cake\Core\Configure;
use Cake\Core\Configure\Engine\PhpConfig;

class NavigationHelper extends Helper{
	
	#get sub category  data
    public function getSubCategory($categoryList=NULL,$parentId=NULL,$editId=NULL){
		$Table = TableRegistry::get('HeaderNavigations');
		$list = '<option value="0">Root</option>';
		if(!empty($categoryList)){
			foreach($categoryList as $keys => $vals):
				$seleted = '';
				$disabled = '';
				$newList = $Table->find()->where(array('parent_id' => $keys))->all();
				if($parentId == $keys){$seleted = 'selected="selected"';}
				if($editId == $keys){$disabled = 'disabled="disabled"';}
				$list .= '<option '.$seleted.' '.$disabled.' value="'.$keys.'">'.ucwords($vals).'</option>';				
				foreach($newList as $nKey => $nVal):				
					$menuPageTitle = $this->getCmsPagesTitle($nVal->menu_page_id);
					$list .= '<option disabled="disabled" value=""> → '.$menuPageTitle.'</option>';
				endforeach;				
			endforeach;				
			return $list;
		}        
	}
	
	function getCmsPagesTitle($id=NULL){
		$table = TableRegistry::get('Cms');
		$data = $table->find()->where(array('id' => $id))->first();
		return $data->title;
	}
	
	function getHeaderPageTitle($parent_id=NULL){
		$table = TableRegistry::get('HeaderNavigations');
		$data = $table->find()->where(array('id' => $parent_id))->first();
		return $data->title;
	}
	
}
