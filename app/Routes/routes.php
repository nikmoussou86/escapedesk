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
};
