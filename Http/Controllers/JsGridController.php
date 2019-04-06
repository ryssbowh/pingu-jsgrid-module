<?php

namespace Modules\JsGrid\Http\Controllers;

use Illuminate\Http\Request;
use ContextualLinks,Notify;
use Modules\Core\Entities\BaseModel;
use Modules\Forms\Components\Form;

trait JsGridController
{
	/**
	 * builds jsGrid options for a related jsGrid list view.
	 * @param  string    $model
	 * @param  Request   $request
	 * @param  array|null     $contextualLink
	 * @return array
	 */
	protected function buildRelatedJsGridView(BaseModel $model, Request $request, ?array $contextualLink = null)
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
	 * @param  string    $model
	 * @param  Request   $request
	 * @param  array|null     $contextualLink
	 * @return array
	 */
	protected function buildJsGridView(string $model, Request $request, ?array $contextualLink = null)
	{
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
	 * @param  BaseModel $model
	 * @return view
	 */
	public function relatedJsGridList(Request $request, BaseModel $model)
	{
		if(!isset($request->route()->action['contextualLink'])) throw new Exception('contextualLink is not set for that route');
		$contextualLink = $request->route()->action['contextualLink'];

		$contextualLinks = $model->getContextualLinks();
		if(!isset($contextualLinks[$contextualLink])) throw new Exception('contextual link '.$contextualLink.' doesn\'t exist for '.get_class($model));
		$contextualLink = $contextualLinks[$contextualLink];
		ContextualLinks::addLinks($contextualLinks);
		$options = $this->buildRelatedJsGridView($model, $request, $contextualLink);
		return view('pages.jsGridList')->with($options);
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
		$model = $this->checkIfRouteHasModel($request);

		$options = $this->buildJsGridView($model, $request);

		return view('pages.jsGridList')->with($options);
	}
}
