<?php

namespace Pingu\Jsgrid\Contracts;

use Illuminate\Http\Request;
use Pingu\Core\Contracts\UsesModel;
use Pingu\Jsgrid\Contracts\JsGridableModel;

interface JsGridController extends UsesModel
{
	/**
	 * builds jsGrid options for a related jsGrid list view.
	 * @param  JsGridableModel    $model
	 * @param  Request   $request
	 * @param  array|null     $contextualLink
	 * @return array
	 */
	public function buildRelatedJsGridView(JsGridableModel $model, Request $request, ?array $contextualLink = null);

	/**
	 * builds jsGrid options for a jsGrid list view.
	 * @param  string    $model
	 * @param  Request   $request
	 * @param  array|null     $contextualLink
	 * @return array
	 */
	public function buildJsGridView(Request $request);

	/**
	 * Builds a jsGrid for a related object
	 * @param  Request   $request
	 * @param  JsGridableModel $model
	 * @return view
	 */
	public function relatedJsGridList(Request $request, JsGridableModel $model);

	/**
	 * Point of entry for a jsGrid list
	 * If listing results for a related model, the contextualLink must be set within the route.
	 * @param  Request $request
	 * @return view
	 */
	public function jsGridList(Request $request);
}
