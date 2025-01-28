<?php
declare(strict_types=1);

namespace App\Routes;

use FastRoute\RouteCollector;

return function (RouteCollector $r) {
    $r->get('/', 'HomeController@index');
    $r->get('/about', 'HomeController@about');

    // Authentication routes
    $r->get('/login', 'AuthController@showLoginForm');
    $r->post('/login', 'AuthController@login');

    $r->post('/logout', 'AuthController@logout');
    
    
    $r->addGroup('/users', function (RouteCollector $r) {
        $r->addRoute('GET', '/create', 'UserController@create');
        $r->addRoute('POST', '/store', 'UserController@store');
        $r->addRoute('GET', '/edit/{userId}', 'UserController@edit');
        $r->addRoute('POST', '/update', 'UserController@update');
        $r->addRoute('POST', '/delete/{userId}', 'UserController@delete');
    });
};
