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

class CitiesTable extends Table{

	public function initialize(array $config): void{
        $this->belongsTo('Countries')->setForeignKey('country_id')->setJoinType('INNER');
    }
			
	public function validationUpdate($validator){
		$validator
			->requirePresence('country_id','create')
			->notEmptyString('country_id', __('Please select country name.'))			
							
			->requirePresence('state_id','create')
			->notEmptyString('state_id', __('Please select state name.'))
			
			->requirePresence('city','create')
			->notEmptyString('city', __('Please enter city name.'));		
		return $validator;
	}
	
	public function validationAdd($validator){
		$validator
			->requirePresence('country_id','create')
			->notEmptyString('country_id', __('Please select country name.'))			
							
			->requirePresence('state_id','create')
			->notEmptyString('state_id', __('Please select state name.'))
			
			->requirePresence('city','create')
			->notEmptyString('city', __('Please enter city name.'));						
		return $validator;
	}
	
}
?>