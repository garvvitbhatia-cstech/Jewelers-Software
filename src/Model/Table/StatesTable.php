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

class StatesTable extends Table{

	public function initialize(array $config): void{
        $this
			->belongsTo('Countries')
            ->setForeignKey('country_id')
            ->setJoinType('INNER');
    }
			
	public function validationUpdate($validator){
		$validator
			// A list of fields
			->requirePresence('country_id','create')
			->notEmptyString('country_id', __('Please select country name.'))			
							
			->requirePresence('state','create')
			->notEmptyString('state', __('Please enter state name.'))
			
			->requirePresence('abbreviation','create')
			->notEmptyString('abbreviation', __('Please enter state abbreviation.'));
		
		return $validator;
	}
	
	public function validationAdd($validator){
		$validator
			// A list of fields
			->requirePresence('country_id','create')
			->notEmptyString('country_id', __('Please select country name.'))			
							
			->requirePresence('state','create')
			->notEmptyString('state', __('Please enter state name.'))
			
			->requirePresence('abbreviation','create')
			->notEmptyString('abbreviation', __('Please enter state abbreviation.'));
						
		return $validator;
	}
	
}
?>
