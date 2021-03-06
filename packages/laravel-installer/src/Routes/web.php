<?php

Route::group(['prefix' => 'install','as' => 'LaravelInstaller::','namespace' => 'RachidLaasri\LaravelInstaller\Controllers','middleware' => ['web', 'install']], function() {
    Route::get('/', ['as' => 'welcome', 'uses' => 'WelcomeController@welcome']);
    Route::post('/', ['as' => 'welcome', 'uses' => 'WelcomeController@proceed']);

    Route::get('environment', ['as' => 'environment', 'uses' => 'EnvironmentController@environment', 'middleware' => ['check']]);
    Route::post('environment', ['as' => 'environment', 'uses' => 'EnvironmentController@save']);

    Route::get('requirements', ['as' => 'requirements', 'uses' => 'RequirementsController@requirements', 'middleware' => ['check']]);
    Route::get('permissions', ['as' => 'permissions', 'uses' => 'PermissionsController@permissions', 'middleware' => ['check']]);
    Route::get('final', ['as' => 'final', 'uses' => 'FinalController@finish']);
});

Route::group(['prefix' => 'update', 'as' => 'LaravelUpdater::', 'namespace' => 'RachidLaasri\LaravelInstaller\Controllers','middleware' => 'web'],function() {
    Route::group(['middleware' => 'update'], function() {
        Route::get('/', ['as' => 'welcome', 'uses' => 'UpdateController@welcome']);
        Route::get('overview', ['as' => 'overview', 'uses' => 'UpdateController@overview']);
        Route::get('database', ['as' => 'database', 'uses' => 'UpdateController@database']);
    });

    // This needs to be out of the middleware because right after the migration has been
    // run, the middleware sends a 404.
    Route::get('final', ['as' => 'final', 'uses' => 'UpdateController@finish']);
});


Route::group(['prefix' => 'verify', 'as' => 'LaravelVerify::', 'namespace' => 'RachidLaasri\LaravelInstaller\Controllers','middleware' => ['web', 'verify']],function() {
    Route::get('/', ['as' => 'overview', 'uses' => 'VerifyController@overview']);
    Route::post('/', ['as' => 'overview', 'uses' => 'VerifyController@verify']);
});
