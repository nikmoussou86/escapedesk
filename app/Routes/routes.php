<?php
declare(strict_types=1);

namespace App\Routes;

use FastRoute\RouteCollector;

return function (RouteCollector $r) {
    // Authentication routes
    $r->addRoute('GET', '/', 'AuthController@index');
    $r->addRoute('GET', '/login', 'AuthController@showLoginForm');
    $r->addRoute('POST', '/login', 'AuthController@login');
    $r->addRoute('GET', '/logout', 'AuthController@logout');
    
    // User routes
    $r->addGroup('/users', function (RouteCollector $r) {
        $r->addRoute('GET', '', 'UserController@index');
        $r->addRoute('GET', '/create', 'UserController@create');
        $r->addRoute('POST', '/store', 'UserController@store');
        $r->addRoute('GET', '/edit/{userId}', 'UserController@edit');
        $r->addRoute('POST', '/update', 'UserController@update');
        $r->addRoute('POST', '/delete', 'UserController@delete');
    });

    // Vacation Requests routes
    $r->addGroup('/vacation_requests', function (RouteCollector $r) {
        $r->addRoute('GET', '', 'VacationRequestController@index');
        $r->addRoute('GET', '/create', 'VacationRequestController@create');
        $r->addRoute('POST', '/store', 'VacationRequestController@store');
        $r->addRoute('GET', '/edit/{vacationRequestId}', 'VacationRequestController@edit');
        $r->addRoute('POST', '/update', 'VacationRequestController@update');
        $r->addRoute('POST', '/delete', 'VacationRequestController@delete');
    });
};
