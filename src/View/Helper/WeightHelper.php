<?php
namespace App\View\Helper;
use Cake\View\Helper;
use Cake\ORM\TableRegistry;
use Aws\S3\S3Client;
use Cake\Core\Configure;
use Cake\Core\Configure\Engine\PhpConfig;

class WeightHelper extends Helper{
	
	public function getWeightTypeExist($typeId){
		$table = TableRegistry::get('Weights');
		return $table->find()->where(array('weight_type_id' => $typeId))->count();
	}

	public function getWeightExist($weightId){
		$table = TableRegistry::get('Distances');
		return $table->find()->where(array('weight_id' => $weightId))->count();
	}
    #get state data
    public function getWeightById($weightId){
		$table = TableRegistry::get('Weights');
        return $table->find('list', ['keyField' => 'id','valueField' => 'weight'])->where(['id' => $weightId, 'status' => 1])->order(['id' => 'asc']);
	}


	public function getWeightTypeById($weightTypeId=NULL){
		if($weightTypeId != ''){
			$Table = TableRegistry::get('WeightTypes');
			$data = $Table->find()->where(array(ID => $weightTypeId))->first();
			return $data->type;
		}else{
			return '';
		}
	}
}