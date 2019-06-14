<?php 
namespace Pingu\Jsgrid\Contracts\Models;

use Pingu\Core\Contracts\Models\HasAjaxRoutesContract;
use Pingu\Forms\Contracts\Models\FormableContract;


interface JsGridableContract extends HasAjaxRoutesContract, FormableContract
{

	/**
	 * List of fields displayed in jsGrid
	 * @return array
	 * @see  http://js-grid.com/docs/#grid-fields
	 */
    public function jsGridFields();

    /**
	 * Returns the name of this jsgrid instance
	 * @param  string $model
	 * @return string
	 */
	public static function jsGridInstanceName();

}