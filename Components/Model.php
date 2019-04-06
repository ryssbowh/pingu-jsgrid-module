<?php
namespace Modules\JsGrid\Components;

use Modules\JsGrid\Exceptions\JsGridException;

class Model extends JsGridField
{

	public function __construct(string $name, array $options)
	{	
		$options['lazyLoad'] = $options['lazyLoad'] ?? false;
		$options['allowNoValue'] = $options['allowNoValue'] ?? true;
		$options['noValueLabel'] = $options['noValueLabel'] ?? 'All';
		$options['separator'] = $options['separator'] ?? ' - ';
		$options['lazyLoad'] = $options['lazyLoad'] ?? false;
		parent::__construct($name, $options);
		if(!isset($options['model'])){
			throw new JsGridException('Missing a \'model\' attribute for jsGrid field '.$this->name);
		}
		if(!isset($options['fields'])){
			throw new JsGridException('Missing a \'fields\' attribute for jsGrid field '.$this->name);
		}
		$this->loadObjects();
	}

	public function loadObjects()
	{	
		if($this->options['lazyLoad']) return;
		$values = [];
		$models = $this->options['model']::all();
		foreach($models as $model){
            $value = [];
            foreach($this->options['fields'] as $field){
                $value[] = $model->$field;
            }
            $values[$model->id] = implode($this->options['separator'], $value);
        }
        $this->options['items'] = $values;
	}
}