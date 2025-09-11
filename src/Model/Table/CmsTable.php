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

class CmsTable extends Table{
	
	public function validationAdd($validator){
		$validator
			->requirePresence('title','create')
			->notEmptyString('title', __('Please enter page title.'))
			->add('title', 'checkUniqueTitle', [
				'rule' => 'isCheckUniqueTitle',
				'message' => 'Title already exists.',
				'provider' => 'table',
			]);
		return $validator;
	}
	
	public function validationUpdate($validator){
		$validator
			->requirePresence('title','create')
			->notEmptyString('title', __('Please enter page title.'))
			->add('title', 'checkUniqueTitleUpdate', [
				'rule' => 'isCheckUniqueTitleUpdate',
				'message' => 'Title already exists.',
				'provider' => 'table',
			]);
		return $validator;
	}
	
	public function isCheckUniqueTitle($field){
		$users = TableRegistry::getTableLocator()->get('Cms');
		$userData = $users->find()->where(array('title' => $field))->first();
		if(isset($userData->id)){
			return false;	
		}else{
			return true;	
		}
	}
	
	public function isCheckUniqueTitleUpdate($field){
		$users = TableRegistry::getTableLocator()->get('Cms');
		$user = $users->find()->where(array('title' => $field))->all();
		if($user->count() > 1){
			return false;	
		}else{
			return true;	
		}
	}
}
?>