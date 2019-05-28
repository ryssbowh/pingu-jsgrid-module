<?php
namespace Pingu\Jsgrid\Fields;

use Pingu\Forms\Fields\Field;

abstract class JsGridField
{
	protected $options;

	public function __construct(array $options, Field $field)
	{
		$this->options = $options;
		$this->name = $field->getName();
		$this->type = strtolower(class_basename($this));
		$this->options['type'] = strtolower(class_basename($this));
		$this->options = array_merge($field->getOptions(), $this->options);
		if(!isset($this->options['title'])) $this->options['title'] = $this->options['label'] ?? ucfirst($this->name);
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