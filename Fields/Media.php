<?php
namespace Pingu\Jsgrid\Fields;

class Media extends JsGridField
{
	public function __construct(string $name, array $options, ?Field $field)
	{
		$options['title'] = $options['title'] ?? ucfirst($name);
		parent::__construct($name, $options, $field);
	}

	public function toArray()
	{
		$array = $this->options->toArray();
		$array['name'] = $this->name;
		$array['type'] = $this->getType();
		return $array;
	}
}