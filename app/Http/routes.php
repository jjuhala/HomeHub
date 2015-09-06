<?php

Route::get('/', function () {
    return view('welcome');
});

Route::group( ['prefix' => 'api/v1', 'namespace' => 'Api\v1'], function () {

    // Nodes
    Route::resource(
        'nodes',
        'NodeController',
        [
            'only' => ['index', 'show', 'store', 'destroy', 'update']
        ]
    );

    // Controls
    Route::resource(
        'nodes.controls',
        'ControlController',
        [
            'only' => ['index', 'store', 'destroy', 'update']
        ]
    );

    // Actions
    Route::resource(
        'actions',
        'ActionController',
        [
            'only' => ['index', 'show', 'store', 'destroy', 'update']
        ]
    );

    // Triggers
    Route::resource(
        'actions.triggers',
        'TriggerController',
        [
            'only' => ['index', 'show', 'store', 'destroy', 'update']
        ]
    );

});
