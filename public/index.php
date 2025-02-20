<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Carregar as rotas
$routes = include(__DIR__ . '/../routers/web.php');

// Obter a URL da requisição
$request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();

// Definir o roteador
$context = new \Symfony\Component\Routing\RequestContext();
$matcher = new \Symfony\Component\Routing\Matcher\UrlMatcher($routes, $context);

// Verificar qual rota corresponde à URL da requisição
try {
    $parameters = $matcher->match($request->getPathInfo());

    // Executar o controlador correspondente
    $controllerInfo = explode('::', $parameters['_controller']);
    $controllerClass = $controllerInfo[0];
    $controllerMethod = $controllerInfo[1];

    $controller = new $controllerClass();
    $response = $controller->$controllerMethod();

    // Enviar a resposta
    $response->send();
} catch (\Symfony\Component\Routing\Exception\ResourceNotFoundException $e) {
    echo 'Página não encontrada';
}
