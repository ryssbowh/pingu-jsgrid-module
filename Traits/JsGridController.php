<?php

namespace Pingu\Jsgrid\Traits;

use ContextualLinks,Notify;
use Illuminate\Http\Request;
use Pingu\Core\Entities\BaseModel;
use Pingu\Forms\Form;
use Pingu\Jsgrid\Contracts\JsGridableModel;
use Pingu\Jsgrid\Events\JsGridOptionsBuilt;
use Pingu\Jsgrid\Exceptions\JsGridException;

trait JsGridController
{
	/**
	 * builds jsGrid options for a related jsGrid list view.
	 * @param  JsGridableModel    $model
	 * @param  Request   $request
	 * @param  array|null     $contextualLink
	 * @return array
	 */
	protected function buildRelatedJsGridView(JsGridableModel $model, Request $request, ?array $contextualLink = null)
	{
		$extraOptions = [
			'relatedModel' => get_class($model),
			'relatedId' => $model->id,
			'contextualLink' => $request->route()->action['contextualLink']
		];

		$title = $model::friendlyName().'\'s '.str_plural($contextualLink['model']::friendlyName());
		$relatedModel = $contextualLink['model'];

		$options = $relatedModel::buildJsGridOptions();
		$options['ajaxUrl'] = $relatedModel::apiUrl();
		$options['editUrl'] = $relatedModel::adminEditUrl();
		
		return [
			'title' => $title,
			'name' => $relatedModel::jsGridInstanceName(),
			'fields' => $relatedModel::buildJsGridFields(),
			'options' => json_encode($options),
			'extra' => json_encode($extraOptions ?? []),
			'addUrl' => $contextualLink['relatedAddUrl'] ?? $relatedModel::adminAddUrl()
		];
	}

	/**
	 * builds jsGrid options for a jsGrid list view.
	 * @param  Request   $request
	 * @return array
	 */
	protected function buildJsGridView(Request $request)
	{
		$model = $this->getModel();
		if(!(new $model) instanceof JsGridableModel){
			throw new JsGridException($model." must implement JsGridableModel to use JsGrid");
		}
		$options = array_merge(config("jsgrid.jsGridDefaults"), $this->getJsGridOptions());
		$controls = $this->controls();
		$options['primaryKey'] = $model::keyName();
		$options['ajaxIndexUri'] = $this->getAjaxIndexUri();
		$options['canClick'] = $this->canClick();
		$options['editing'] = $controls['editButton'] = $this->canEdit();
		$options['deleting'] = $controls['deleteButton'] = $this->canDelete();
		$options['fields'] = $model::buildJsGridFields($this->fields());
		$options['fields'][] = $controls;
		if($this->canClick()){
			$options['clickUrl'] = $this->getClickLink();
		}
		if($this->canEdit()){
			$options['ajaxUpdateUri'] = $this->getAjaxUpdateUri();
		}
		if($this->canDelete()){
			$options['ajaxDeleteUri'] = $this->getAjaxDeleteUri();
		}
		$name = $model::jsGridInstanceName();
		event(new JsGridOptionsBuilt($name, $options));
		
		return [
			'name' => $name, 
			'options' => $options
		];
	}

	/**
	 * returns jsgrid options for that instance of jsgrid
	 * @return array
	 */
	protected function getJsGridOptions()
	{
		return [];
	}

	/**
	 * Builds a jsGrid for a related object
	 * @param  Request   $request
	 * @param  JsGridableModel $model
	 * @return view
	 */
	protected function relatedJsGridList(Request $request, JsGridableModel $model)
	{
		if(!isset($request->route()->action['contextualLink'])) throw new Exception('contextualLink is not set for that route');
		$contextualLink = $request->route()->action['contextualLink'];

		$contextualLinks = $model->getContextualLinks();
		if(!isset($contextualLinks[$contextualLink])) throw new Exception('contextual link '.$contextualLink.' doesn\'t exist for '.get_class($model));
		$contextualLink = $contextualLinks[$contextualLink];
		ContextualLinks::addLinks($contextualLinks);
		$options = $this->buildRelatedJsGridView($model, $request, $contextualLink);
		return view('jsgrid::list')->with($options);
	}

	/**
	 * Replace the routeslug in the rui with the primary key
	 * @param  string $uri
	 * @return string
	 */
	protected function replaceUriTokens(string $uri)
	{
		if(is_null($uri)) return null;
		$model = $this->getModel();
		$slug = $model::routeSlug();
		$key = (new $model)->getKeyName();

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
	 * @return string
	 */
	protected function getAjaxIndexUri()
	{
		return $this->getModel()::getAjaxUri('index', true);
	}

	/**
	 * The uri used by jsgrid to get models
	 * @return string
	 */
	protected function getAjaxDeleteUri()
	{
		return $this->replaceUriTokens($this->getModel()::getAjaxUri('delete', true));
	}

	/**
	 * The uri used by jsgrid to edit models
	 * @return string
	 */
	protected function getAjaxUpdateUri()
	{
		return $this->replaceUriTokens($this->getModel()::getAjaxUri('update', true));
	}

	/**
	 * The url used by jsgrid to redirect when an element is clicked
	 * @return [type] [description]
	 */
	protected function getClickLink()
	{
		$model = $this->getModel();
		$slug = $model::routeSlug();
		$key = (new $model)->getKeyName();
		return '/admin/'.$slug.'/{'.$key.'}/edit';
	}

	/**
	 * Can the user click on items and be redirected
	 * @return bool
	 */
	protected function canClick()
	{
		return false;
	}

	/**
	 * Can the user edit objects
	 * @return bool
	 */
	protected function canEdit()
	{
		return false;
	}

	/**
	 * Can the user delete objects
	 * @return bool
	 */
	protected function canDelete()
	{
		return false;
	}

	/**
	 * Fields to show for hat instance of jsgrid, default to all defined fields
	 * @return array
	 */
	protected function fields()
	{
		$model = $this->getModel();
		return array_keys($model::jsGridFields());
	}

	/**
	 * JsGrid controls for that instance of jsgrid
	 * @return array
	 */
	protected function controls()
	{
		return ['type' => 'control'];
	}

}
