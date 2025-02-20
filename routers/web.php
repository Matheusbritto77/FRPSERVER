<?php



// routers/web.php
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$routes = new RouteCollection();

// Definindo as rotas
$routes->add('home', new Route('/', [
    '_controller' => 'App\\Controllers\\HomeController::index'
]));

$routes->add('about', new Route('/about', [
    '_controller' => 'App\\Controllers\\AboutController::index'
]));

$routes->add('contact', new Route('/contact', [
    '_controller' => 'App\\Controllers\\ContactController::index'
]));

// Rota de registro
$routes->add('register', new Route('/register', [
    '_controller' => 'App\\Controllers\\RegisterController::showForm'
]));

// Rota para processar o envio do formulário de registro
$routes->add('register_submit', new Route('/register/submit', [
    '_controller' => 'App\\Controllers\\RegisterController::registerUser',
    'methods' => ['POST'],
]));

return $routes;
