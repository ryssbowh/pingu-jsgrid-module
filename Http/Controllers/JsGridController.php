<?php

namespace Pingu\Jsgrid\Http\Controllers;

use Pingu\Jsgrid\Events\JsGridOptionsBuilt;

abstract class JsGridController
{
	/**
	 * builds jsGrid options for a jsGrid list view.
	 * @param  Request   $request
	 * @return array
	 */
	protected function buildJsGridView()
	{
		$options = array_merge(config("jsgrid.jsGridDefaults"), $this->getJsGridOptions());
		$controls = $this->controls();
		$options['data'] = $this->getJsGridData();
		$options['fields'] = $this->getJsGridFields();
		if($controls){
			$options['fields'][] = $controls;
		}
		$name = $this->jsGridInstanceName();

		event(new JsGridOptionsBuilt($name, $options));
		
		return [
			'name' => $name, 
			'options' => $options
		];
	}

	/**
	 * returns jsgrid options for that instance of jsgrid
	 * 
	 * @return array
	 */
	protected function getJsGridOptions()
	{
		return [];
	}

	abstract protected function getJsGridFields();

	/**
	 * The uri used by jsgrid to get data
	 * 
	 * @return string
	 */
	abstract protected function getJsGridData();

	/**
	 * Unique name for this instance
	 * 
	 * @return string
	 */
	abstract protected function jsGridInstanceName();

	/**
	 * JsGrid controls for that instance of jsgrid
	 *
	 * @see http://js-grid.com/docs/#control
	 * @return array
	 */
	protected function controls()
	{
		return ['type' => 'control'];
	}

}
