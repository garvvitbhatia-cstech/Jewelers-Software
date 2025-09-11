<?php
namespace App\View\Helper;
use Cake\View\Helper;
use Cake\ORM\TableRegistry;
use Aws\S3\S3Client;
use Cake\Core\Configure;
use Cake\Core\Configure\Engine\PhpConfig;

class EcommerceHelper extends Helper{

	#get sub category  data
    public function getSubCategory($categoryList=NULL,$parentId=NULL){
		$Table = TableRegistry::get(CATEGORIES);
		$list = '';		
		$list .= '<option value="0">Root</option>';
		if(!empty($categoryList)){
			foreach($categoryList as $keys => $vals):
				$seleted = '';
				$newList = $Table->find()->where(array('parent_id' => $keys))->all();
				if($parentId == $keys){$seleted = 'selected="selected"';}				
				$list .= '<option '.$seleted.' value="'.$keys.'">'.ucwords($vals).'</option>';
				foreach($newList as $nKey => $nVal):
					$list .= '<option disabled="disabled" value=""> → '.ucwords($nVal['name']).'</option>';
				endforeach;				
			endforeach;				
			return $list;
		}        
	}
	
	#get all category  data
    public function getAllCategory($catId=NULL){
		$Table = TableRegistry::get(CATEGORIES);
		$list = '';		
		$list .= '<option value="0">Category Name</option>';
		$categoryList = $Table->find('all')->where(array('status' => 1))->order(array('ordering' => 'ASC'));
		if(!empty($categoryList)){
			foreach($categoryList as $keys => $vals):
				$seleted = '';				
				if($catId == $vals->id){$seleted = 'selected="selected"';}
				$list .= '<option '.$seleted.' value="'.$vals->id.'">'.ucwords($vals->name).'</option>';
			endforeach;				
			return $list;
		}        
	}
	
	#get cagetgory name
	public function getCategoryNameById($catId=NULL){
		$Table = TableRegistry::get(CATEGORIES);
		$category = $Table->find()->where(array('id' => $catId))->first();
		if(!empty($category)){
			return ucwords($category->name);
		}
	}
	
	#get cagetgory name
	public function getCategoryExist($catId=NULL){
		$Table = TableRegistry::get(PRODUCTS);
		$category = $Table->find()->where(array('category_id' => $catId))->all();
		if($category->count() > 0){
			return $category->count();
		}else{
			return 0;
		}
	}
	
	#get product details
    public function getItemDetails($itemId = NULL){
		$Table = TableRegistry::get(PRODUCTS);
        return $Table->find()->where(array(ID => $itemId))->first();
	}
	
	#get user details
    public function getUserDetails($userId = NULL){
		$Table = TableRegistry::get(USERS);
        return $Table->find()->where(array(ID => $userId))->first();
	}
	
	#no to words
	function convertInWords($number){
	   $no = round($number);
	   $point = round($number - $no, 2) * 100;
	   $hundred = null;
	   $digits_1 = strlen($no);
	   $i = 0;
	   $str = array();
	   $words = array('0' => '', '1' => 'one', '2' => 'two',
		'3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
		'7' => 'seven', '8' => 'eight', '9' => 'nine',
		'10' => 'ten', '11' => 'eleven', '12' => 'twelve',
		'13' => 'thirteen', '14' => 'fourteen',
		'15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
		'18' => 'eighteen', '19' =>'nineteen', '20' => 'twenty',
		'30' => 'thirty', '40' => 'forty', '50' => 'fifty',
		'60' => 'sixty', '70' => 'seventy',
		'80' => 'eighty', '90' => 'ninety');
	   $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
	   
	   while ($i < $digits_1) {
		 $divider = ($i == 2) ? 10 : 100;
		 $number = floor($no % $divider);
		 $no = floor($no / $divider);
		 $i += ($divider == 10) ? 1 : 2;
		 if ($number) {
			$plural = (($counter = count($str)) && $number > 9) ? 's' : null;
			$hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
			$str [] = ($number < 21) ? $words[$number] .
				" " . $digits[$counter] . $plural . " " . $hundred
				:
				$words[floor($number / 10) * 10]
				. " " . $words[$number % 10] . " "
				. $digits[$counter] . $plural . " " . $hundred;
		 } else $str[] = null;
	  }
	  
	  $str = array_reverse($str);
	  $result = implode('', $str);
	  $points = ($point) ?
		"." . $words[$point / 10] . " " . 
			  $words[$point = $point % 10] : '';
		//$result . "Rupees  " . $points . " Paise";	  

	  return ucwords($result);
	}
}