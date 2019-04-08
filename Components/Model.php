<?php
namespace Modules\JsGrid\Components;

use Modules\Forms\Components\Fields\Field;

class Model extends JsGridField
{
	public function __construct(array $options, Field $field)
	{
		parent::__construct($options, $field);
		$this->options['valueField'] = $this->options['valueField'] ?? 'id';
	}
}