<?php
namespace Modules\JsGrid\Components;

use Modules\Forms\Components\Fields\Field;

abstract class JsGridField
{
	protected $options;

	public function __construct(array $options, Field $field)
	{
		$this->options = $options;
		$this->name = $field->getName();
		$this->type = strtolower(classname($this));
		$this->options['type'] = strtolower(classname($this));
		$this->options = array_merge($field->getOptions(), $this->options);
		if(!isset($this->options['title'])) $this->options['title'] = $this->options['label'];
		unset($this->options['label']);
	}

	public function getOptions()
	{
		return $this->options;
	}

	public function setOption($name, $value)
	{
		$this->options[$name] = $value;
	}
}