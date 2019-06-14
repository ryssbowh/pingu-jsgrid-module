<?php
namespace Pingu\Jsgrid\Fields;

use Pingu\Forms\Contracts\HasItemsField;

class Select extends JsGridField
{
	public function __construct(array $options, HasItemsField $field)
	{
		parent::__construct($options, $field);
		$this->options['items'] = $field->getItems();
	}
}