<?php
namespace Pingu\Jsgrid\Fields;

use Pingu\Forms\Contracts\HasItemsField;

class Select extends JsGridField
{
	public function __construct(string $name, array $options, HasItemsField $field)
	{
		parent::__construct($name, $options, $field);
		$this->options['items'] = $field->getItems();
	}
}