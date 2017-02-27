<?php

use Illuminate\Http\Request;

Route::group(['prefix' => '1'], function() {
    
	Route::resource('donors', 'DonorController');
	Route::resource('areas', 'AreaController');

	Route::get('getRegBatches','DonorController@getRegBatches');
	Route::get('getDonorCount','DonorController@getDonorCount');
	

});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
