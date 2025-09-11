<?php
namespace App\View\Helper;
use Cake\View\Helper;
use Cake\ORM\TableRegistry;
use Aws\S3\S3Client;
use Cake\Core\Configure;
use Cake\Core\Configure\Engine\PhpConfig;

class CountryHelper extends Helper{
	
    #get state data
    public function getStateExist($countryId){
		$table = TableRegistry::get('States');
		return $table->find()->where(array('country_id' => $countryId))->count();
	}
}
