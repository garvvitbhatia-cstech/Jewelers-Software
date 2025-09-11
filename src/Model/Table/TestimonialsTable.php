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

class TestimonialsTable extends Table{
		
	public function validationAddtestimonial($validator){
		$validator
			->requirePresence('username','create')
			->notEmptyString('username', __('Please enter user name.'))							
			->requirePresence('testimonial','create')
			->notEmptyString('testimonial', __('Please enter testimonial.'));		
		return $validator;
	}
}
?>