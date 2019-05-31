<?php 
namespace Pingu\Jsgrid\Contracts;

use Pingu\Core\Contracts\AjaxableModel;
use Pingu\Forms\Contracts\FormableModel;

interface JsGridableModel extends AjaxableModel, FormableModel
{

	/**
	 * List of fields displayed in jsGrid
	 * @return array
	 * @see  http://js-grid.com/docs/#grid-fields
	 */
    public static function jsGridFields();

    /**
	 * Returns the name of this jsgrid instance
	 * @param  string $model
	 * @return string
	 */
	public static function jsGridInstanceName();

}