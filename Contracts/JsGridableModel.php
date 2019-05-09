<?php 
namespace Pingu\Jsgrid\Contracts;

use Pingu\Core\Contracts\APIableModel;
use Pingu\Forms\Contracts\FormableModel;

interface JsGridableModel extends APIableModel, FormableModel
{

	/**
	 * List of fields displayed in jsGrid
	 * @return array
	 * @see  http://js-grid.com/docs/#grid-fields
	 */
    public static function jsGridFields();

    /**
	 * JsGrids control field
	 * @return array|false
	 * @see  http://js-grid.com/docs/#grid-fields
	 */
    public static function jsGridControls();

    /**
	 * Returns the name of this jsgrid instance
	 * @param  string $model
	 * @return string
	 */
	public static function jsGridInstanceName();

}