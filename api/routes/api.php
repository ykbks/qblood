<?php

use Illuminate\Http\Request;

Route::group(['prefix' => '1'], function() {
    
	Route::resource('donors', 'DonorController');

});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
