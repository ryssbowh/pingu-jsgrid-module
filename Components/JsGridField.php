<?php
namespace Modules\JsGrid\Components;

abstract class JsGridField
{
	protected $options;
	protected $name;
	protected $type;

	public function __construct(string $name, array $options)
	{
		$this->options = $options;
		$this->name = $name;
		$this->type = strtolower(classname($this));
		$this->options['type'] = $this->type;
		$this->options['name'] = $this->name;
	}

	public function render()
	{
		return json_encode($this->options);
	}

	public function setOption($name, $value)
	{
		$this->options[$name] = $value;
	}
}