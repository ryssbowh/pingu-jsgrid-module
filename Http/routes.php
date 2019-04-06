<?php

Route::group(['middleware' => 'web', 'prefix' => 'jsgrid', 'namespace' => 'Modules\JsGrid\Http\Controllers'], function()
{
    Route::get('/', 'JsGridController@index');
});
