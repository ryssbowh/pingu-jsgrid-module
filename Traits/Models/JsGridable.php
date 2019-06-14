<?php 
namespace Pingu\Jsgrid\Traits\Models;

use Pingu\Forms\Support\Field;
use Pingu\Forms\Support\Fields\TextInput;
use Pingu\Forms\Support\Types\Integer;
use Pingu\Jsgrid\Events\JsGridFieldsBuilt;
use Pingu\Jsgrid\Events\JsGridOptionsBuilt;
use Pingu\Jsgrid\Fields\Number as JsGridNumber;
use Pingu\Jsgrid\Fields\Number;
use Pingu\Jsgrid\Fields\Text as JsGridText;

trait JsGridable {

    /**
	 * Returns the name of this jsgrid instance
	 * @param  string $model
	 * @return string
	 */
	public static function jsGridInstanceName()
	{
		return strtolower(class_basename(get_called_class()));
	}

	/**
	 * Builds field definitions for a jsgrid instance
	 * @param  string $model
	 * @param  array  $fields
	 * @return array
	 * @see http://js-grid.com/docs/#configuration
	 */
	public function buildJsGridFields(array $fieldDefs)
	{
		$fieldsDef = $this->getFieldDefinitions();
		$fields = [];

		foreach($fieldDefs as $name => $jsGridField){
			//Silently assigning a default text type
			if(!isset($jsGridField['type'])) $jsGridField['type'] = JsGridText::class;

			$field = $this->buildFieldClass($name);

			$jsGridFieldInstance = new $jsGridField['type']($jsGridField['options'] ?? [], $field);

			$fields[] = $jsGridFieldInstance;
		}

		if(!isset($fields[$this->getKeyName()])){
			$fields[] = new JsGridText([
					'visible' => false, 
					'editing' => false, 
					'filtering' => false
				],
				new TextInput($this->getKeyName()));
		}
		else{
			$fields[$this->getKeyName()]->option('editing', false);
		}

		event(new JsGridFieldsBuilt($name, $fields));

		return array_map(function($field){
			return $field->toArray();
		}, $fields);
	}
}