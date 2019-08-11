<?php 
namespace Pingu\Jsgrid\Contracts\Models;

use Pingu\Core\Contracts\Models\HasCrudUrisContract;
use Pingu\Forms\Contracts\Models\FormableContract;

interface JsGridableContract extends HasCrudUrisContract, FormableContract
{

	/**
	 * List of fields displayed in jsGrid, that can also define fields that
	 * don't exists in the model, in which case an accessor will be called
	 * eg : getJsGridImageField for the field 'image'
	 * 
	 * @return array
	 * @see  http://js-grid.com/docs/#grid-fields
	 */
    public function jsGridFields();

    /**
	 * Returns the name of this jsgrid instance
	 * 
	 * @param  string $model
	 * @return string
	 */
	public static function jsGridInstanceName();

}