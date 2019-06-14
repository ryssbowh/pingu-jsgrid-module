<?php
namespace Pingu\Jsgrid\Fields;

use Pingu\Forms\Support\Field;

abstract class JsGridField
{
	protected $options;
	public $name;
	public $type;
	public $field;

	public function __construct(array $options, Field $field)
	{
		$this->name = $field->getName();
		$this->field = $field;
		$this->type = strtolower(class_basename($this));
		$options['title'] = $options['title'] ?? $field->option('label') ?? ucfirst($this->name);
		$this->options = collect($options);
	}

	public function getType()
	{
		return $this->type;
	}

	public function getOptions()
	{
		return $this->options;
	}

	public function option($name, $value = null)
	{
		if(is_null($values)){
			return $this->options->get($name);
		}
		$this->options->put($name, $value);
		return $this;
	}

	public function toArray()
	{
		$array = $this->options->toArray();
		$array['name'] = $this->name;
		$array['type'] = $this->getType();
		$array['options'] = $this->field->options->toArray();
		$array['attributes'] = $this->field->attributes->toArray();
		return $array;
	}
}