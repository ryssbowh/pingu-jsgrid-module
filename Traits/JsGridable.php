<?php 
namespace Modules\JsGrid\Traits;

use Modules\Forms\Components\Fields\Number;
use Modules\JsGrid\Events\JsGridFieldsBuilt;
use Modules\JsGrid\Events\JsGridOptionsBuilt;

trait JsGridable {

	/**
	 * List of fields displayed in jsGrid
	 * @return array
	 * @see  http://js-grid.com/docs/#grid-fields
	 */
    public static function jsGridFields()
    {
        return [];
    }

    /**
	 * JsGrids control field
	 * @return array|false
	 * @see  http://js-grid.com/docs/#grid-fields
	 */
    public static function jsGridControls()
    {
        return ['type' => 'control'];
    }

    /**
	 * Returns the name of this jsgrid instance
	 * @param  string $model
	 * @return string
	 */
	public static function jsGridInstanceName()
	{
		return strtolower(classname(get_called_class()));
	}

	/**
	 * Returns the default options for a jsgrid
	 * @param  string $model
	 * @return array
	 */
	public static function buildJsGridOptions()
	{
		$options = config("core.jsGridDefaults");
		$name = self::jsGridInstanceName();
		event(new JsGridOptionsBuilt($name, $options));
		return $options;
	}

	/**
	 * Builds field definitions for a jsgrid instance
	 * @param  string $model
	 * @return array
	 * @see http://js-grid.com/docs/#configuration
	 */
	public static function buildJsGridFields()
	{
		$fieldsDef = self::fieldDefinitions();
		$jsGridFields = self::jsGridFields();
		$controls = self::jsGridControls();
		$fields = [];

		$idDef = ['id' => ['type' => Number::class]];
		$idJsGrid = ['id' => ['visible' => false, 'editing' => false]];

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
			$options = $fieldsDef[$name];
			$field = new $options['type']($name, $options['options']??[]);
			$field = array_merge(['type' => $field->getType(), 'name' => $field->getName()], $field->getOptions(), $jsgridOptions);
			$field['title'] = $field['label'];
			unset($field['label']);
			$fields[$name] = $field;
		}

		if($controls){
			$fields['_controls'] = $controls;
		}

		event(new JsGridFieldsBuilt($name, $fields));

		$fields = json_encode(array_values($fields));

		return $fields;
;	}
}