<?php

namespace App\Validation;

use Respect\Validation\Validator as Respect;
use Respect\Validation\Exceptions\NestedValidationException;

/**
* 
*/
class validator
{
	protected $errors;
	public function validate($request, array $rules)
	{
		foreach ($rules as $field => $rule) {
			try {
				$rule->setName(ucfirst($field))->assert($request->getParam($field));				
			} catch (NestedValidationException $e) {
				$this->errors[$field] = $e->getMessages(); 
				
			}

		}

		
		return $this;
	}
	public function failed()
	{
		return !empty($this->errors);
	}
	public function msg()
	 {
	 	foreach ($this->errors as $key => $value) {
	 		$arr = array(
	 			$key => $value[0]
	 			);
	 		$arrtmp[]=$arr;
	 	}
	 	return json_encode($arrtmp);
	}
}