<?php

namespace Pingu\Jsgrid\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Pingu\Core\Contracts\Models\HasCrudUrisContract;
use Pingu\Core\Exceptions\ClassException;
use Pingu\Core\Http\Controllers\ModelController;
use Pingu\Forms\Contracts\Models\FormableContract;
use Pingu\Jsgrid\Contracts\Models\JsGridableContract;
use Pingu\Jsgrid\Events\JsGridOptionsBuilt;

abstract class JsGridModelController extends ModelController
{
	public function __construct(Request $request)
	{
		$modelStr = $this->getModel();
		$model = new $modelStr;
		if(!($model instanceof JsGridableContract)){
			throw ClassException::missingInterface($modelStr, JsGridableContract::class);
		}
		if(!($model instanceof HasCrudUrisContract)){
			throw ClassException::missingInterface($modelStr, HasCrudUrisContract::class);
		}
		parent::__construct($request);
	}
	
	public function jsGridIndex(Request $request)
	{
		$filters = $request->input('filters', []);
		$options = $request->input('options', []);
		$pageIndex = $request->input('pageIndex', 1);
		$pageSize = $request->input('pageSize', $this->model->getPerPage());
		$sortField = $request->input('sortField', $this->model->getKeyName());
		$sortOrder = $request->input('sortOrder', 'asc');

		$fieldsDef = $this->model->buildFieldDefinitions();
		$fieldsDef = $this->modifyJsGridDefinition($fieldsDef);
		$query = $this->model->newQuery();

		foreach($filters as $field => $value){
			if(!isset($fieldsDef[$field])){
				//this field is not defined, it must be a field that doesn't exist in the model
				//but still has a value for jsgrid (defined by its mutator).
				//Since it doesn't exist in the model, we exclude it here :
				continue;
			}
			$fieldDef = $fieldsDef[$field];
	
			//we have a filter for that field, letting the field type doing its job :
			$fieldDef->option('type')->filterQueryModifier($query, $value);
		}

		$count = $query->count();

		if($sortField){
			$query->orderBy($sortField, $sortOrder);
		}

		$query->offset(($pageIndex-1) * $pageSize)->take($pageSize);

		$models = $query->get();

		return ['models' => $this->jsGridModels($models), 'total' => $count];
	}

	/**
	 * build the response.
	 * 
	 * @param  Collection $models
	 * @return array
	 */
	protected function jsGridModels(Collection $models)
	{
		return $models->toArray();
	}

	/**
	 * builds jsGrid options for a jsGrid list view.
	 * @param  Request   $request
	 * @return array
	 */
	protected function buildJsGridView(Request $request)
	{
		$options = array_merge(config("jsgrid.jsGridDefaults"), $this->getJsGridOptions());
		$controls = $this->controls();
		$options['primaryKey'] = $this->model::keyName();
		$options['ajaxIndexUri'] = $this->getJsGridIndexUri();
		$options['canClick'] = $this->canClick();
		$options['editing'] = $controls['editButton'] = $this->canEdit();
		$options['deleting'] = $controls['deleteButton'] = $this->canDelete();
		$options['fields'] = $this->model->buildJsGridFields();
		$options['fields'][] = $controls;
		$options['extraFilters'] = [];
		if($this->canClick()){
			$options['clickUrl'] = $this->getClickLink();
		}
		if($this->canEdit()){
			$options['ajaxUpdateUri'] = $this->getJsGridUpdateUri();
		}
		if($this->canDelete()){
			$options['ajaxDeleteUri'] = $this->getJsGridDeleteUri();
		}
		$name = $this->model::jsGridInstanceName();

		$options = $this->modifyJsGridDefinition($options);

		event(new JsGridOptionsBuilt($name, $options));
		
		return [
			'name' => $name, 
			'options' => $options
		];
	}

	protected function modifyJsGridDefinition(array $options)
	{
		return $options;
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

	/**
	 * Replace the route slug in the uri with the primary key
	 * 
	 * @param  string $uri
	 * @return string
	 */
	protected function replaceUriTokens(string $uri)
	{
		if(is_null($uri)) return null;
		$slug = $this->model::routeSlug();
		$key = $this->model->getRouteKeyName();

		preg_match('/^.*\{('.$slug.')\}.*$/', $uri, $matches);
        if($matches){
            foreach($matches as $match){
                $uri = str_replace('{'.$match.'}', '{'.$key.'}', $uri);
            }
        }
        return $uri;
	}

	/**
	 * The uri used by jsgrid to get models
	 * 
	 * @return string
	 */
	protected function getJsGridIndexUri()
	{
		return $this->model::getUri('index', ajaxPrefix());
	}

	/**
	 * The uri used by jsgrid to delete models
	 * 
	 * @return string
	 */
	protected function getJsGridDeleteUri()
	{
		return $this->replaceUriTokens($this->model::getUri('delete', ajaxPrefix()));
	}

	/**
	 * The uri used by jsgrid to update models
	 * 
	 * @return string
	 */
	protected function getJsGridUpdateUri()
	{
		return $this->replaceUriTokens($this->model::getUri('update', ajaxPrefix()));
	}

	/**
	 * The url used by jsgrid to redirect when an element is clicked
	 * 
	 * @return string
	 */
	protected function getClickLink()
	{
		return $this->replaceUriTokens($this->model::getUri('edit', adminPrefix()));
	}

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

	/**
	 * Can the user click on items and be redirected
	 * 
	 * @return bool
	 */
	abstract protected function canClick();

	/**
	 * Can the user edit objects
	 * 
	 * @return bool
	 */
	abstract protected function canEdit();

	/**
	 * Can the user delete objects
	 * 
	 * @return bool
	 */
	abstract protected function canDelete();

}
