<?php 
namespace Pingu\Jsgrid\Traits\Models;

use Pingu\Forms\Fields\Number;
use Pingu\Forms\Fields\Text;
use Pingu\Jsgrid\Events\JsGridFieldsBuilt;
use Pingu\Jsgrid\Events\JsGridOptionsBuilt;
use Pingu\Jsgrid\Fields\Number as JsGridNumber;
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
	public static function buildJsGridFields(array $fields)
	{
		$fieldsDef = (new self)->getFieldDefinitions();
		$jsGridFields = array_intersect_key(self::jsGridFields(), array_flip($fields));
		$fields = [];

		$idDef = ['id' => ['type' => Number::class]];
		$idJsGrid = ['id' => ['type' => JsGridNumber::class, 'visible' => false, 'editing' => false, 'filtering' => false]];

		if(!isset($jsGridFields['id'])){
			$jsGridFields = $idJsGrid + $jsGridFields;
		}

		if(!isset($fieldsDef['id'])){
			$fieldsDef = $idDef + $fieldsDef;
		}
		else{
			$fieldsDef['id']['editing'] = false;
		}

		foreach($jsGridFields as $name => $jsgridOptions){
			if(!isset($fieldsDef[$name])) continue;
			if(!isset($jsGridFields[$name]['type'])) $jsGridFields[$name]['type'] = JsGridText::class;

			$options = $fieldsDef[$name];
			$field = new $options['type']($name, $fieldsDef[$name]);
			$jsGridField = new $jsGridFields[$name]['type']($jsGridFields[$name], $field);

			$fields[$name] = $jsGridField->getOptions();
		}

		event(new JsGridFieldsBuilt($name, $fields));

		return array_values($fields);
	}
}