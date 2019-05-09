<?php

namespace Pingu\Jsgrid\Traits;

use ContextualLinks,Notify;
use Illuminate\Http\Request;
use Pingu\Core\Entities\BaseModel;
use Pingu\Forms\Form;
use Pingu\Jsgrid\Contracts\JsGridableModel;

trait JsGridController
{
	/**
	 * builds jsGrid options for a related jsGrid list view.
	 * @param  JsGridableModel    $model
	 * @param  Request   $request
	 * @param  array|null     $contextualLink
	 * @return array
	 */
	public function buildRelatedJsGridView(JsGridableModel $model, Request $request, ?array $contextualLink = null)
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
	public function buildJsGridView(Request $request)
	{
		$model = $this->getModel();
		$title = str_plural($model::friendlyName());
		$options = $model::buildJsGridOptions();
		$options['ajaxUrl'] = $model::apiUrl();
		$options['editUrl'] = $model::adminEditUrl();
		
		return [
			'title' => $title,
			'name' => $model::jsGridInstanceName(),
			'fields' => $model::buildJsGridFields(),
			'options' => json_encode($options),
			'extra' => json_encode($extraOptions ?? []),
			'addUrl' => $model::adminAddUrl()
		];
	}

	/**
	 * Builds a jsGrid for a related object
	 * @param  Request   $request
	 * @param  JsGridableModel $model
	 * @return view
	 */
	public function relatedJsGridList(Request $request, JsGridableModel $model)
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
	 * Point of entry for a jsGrid list
	 * The model class must be set manually within the route.
	 * If listing results for a related model, the contextualLink must be set within the route.
	 * @param  Request $request
	 * @return view
	 */
	public function jsGridList(Request $request)
	{
		$options = $this->buildJsGridView($request);

		return view('jsgrid::list')->with($options);
	}
}
