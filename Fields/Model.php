<?php
namespace Pingu\Jsgrid\Fields;

use Pingu\Forms\Fields\Model as ModelField;

class Model extends JsGridField
{
	public function __construct(array $options, ModelField $field)
	{
		parent::__construct($options, $field);
		$this->options['valueField'] = $this->options['valueField'] ?? 'id';
		$this->options['items'] = $field->buildItems();
	}
}