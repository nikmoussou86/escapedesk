<?php
declare(strict_types=1);

namespace App\Routes;

use FastRoute\RouteCollector;

return function (RouteCollector $r) {
    // Authentication routes
    $r->get('/login', 'AuthController@showLoginForm');
    $r->post('/login', 'AuthController@login');

    $r->post('/logout', 'AuthController@logout');
    
    
    $r->addGroup('/users', function (RouteCollector $r) {
        $r->addRoute('GET', '', 'UserController@index');
        $r->addRoute('GET', '/create', 'UserController@create');
        $r->addRoute('POST', '/store', 'UserController@store');
        $r->addRoute('GET', '/edit/{userId}', 'UserController@edit');
        $r->addRoute('POST', '/update/{userId}', 'UserController@update');
        $r->addRoute('POST', '/delete/{userId}', 'UserController@delete');
    });

    $r->addGroup('/vacation_requests', function (RouteCollector $r) {
        $r->addRoute('GET', '', 'VacationRequestController@index');
        $r->addRoute('GET', '/create', 'VacationRequestController@create');
        $r->addRoute('POST', '/store', 'VacationRequestController@store');
        $r->addRoute('GET', '/edit/{vacationRequestId}', 'VacationRequestController@edit');
        $r->addRoute('POST', '/update', 'VacationRequestController@update');
        $r->addRoute('POST', '/delete/{vacationRequestId}', 'VacationRequestController@delete');
    });
};
