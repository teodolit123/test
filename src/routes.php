<?php

use App\Router;

/**
 * RESTful API routes
 */
Router::post('/api/item/create', 'API\ItemController@create');
//Router::put('/api/blog/replace', 'API\ItemController@replace');
//Router::patch('/api/blog/update', 'API\ItemController@update');

/**
 * There is no route defined for a certain location
 */
Router::error(function () {
    die('404 Page not found');
});

///**
// * TODO: need refactor to command line start
// * It will commented automatically again
// */
// createTables();

Router::dispatch();
