<?php 
namespace Modules\JsGrid\Contracts;

use Modules\Core\Contracts\APIableModelContract;
use Modules\Forms\Contracts\FormableContract;

interface JsGridableContract extends APIableModelContract, FormableContract {

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