<?php
// src/Model/Table/UsersTable.php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Utility\Text;
// the EventInterface class
use Cake\Event\EventInterface;
use Cake\ORM\Rule\IsUnique;
use Cake\ORM\TableRegistry;

class InnerPagesTable extends Table{
	
	public function validationUpdate($validator){
		$validator
			->notEmptyString('title', __('Please enter title.'))			
			->add('title', 'checkUniqueNameUpdate', [
				'rule' => 'isCheckUniqueNameUpdate',
				'message' => 'title name already exists.',
				'provider' => 'table',
			]);	
			
		return $validator;
	}
	
	public function isCheckUniqueNameUpdate($field){
		$innerPages = TableRegistry::getTableLocator()->get('InnerPages');
		$innerPage = $innerPages->find()->where(array('title' => $field))->all();
		if($innerPage->count() > 1){
			return false;	
		}else{
			return true;	
		}
	}
}
?>