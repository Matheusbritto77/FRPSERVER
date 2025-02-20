<?php

// routers/web.php

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$routes = new RouteCollection();

// Definindo as rotas
$routes->add('home', new Route('/', [
    '_controller' => 'App\Controllers\HomeController::index'
]));

$routes->add('about', new Route('/about', [
    '_controller' => 'App\Controllers\AboutController::index'
]));

$routes->add('contact', new Route('/contact', [
    '_controller' => 'App\Controllers\ContactController::index'
]));

// Retorna as rotas
return $routes;
