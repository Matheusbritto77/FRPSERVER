<?php

// Definindo as rotas
$routes = [
    '/' => 'home',    // Rota raiz
    '/about' => 'about',
    '/contact' => 'contact',
];

// Função para carregar a visualização
function view($viewName) {
    $viewPath = __DIR__ . '/../resources/views/' . $viewName . '.php';
    
    if (file_exists($viewPath)) {
        include $viewPath;
    } else {
        echo 'Página não encontrada';
    }
}

// Função para definir a lógica de cada rota
function home() {
    view('home');  // Carrega a visualização home.php
}

function about() {
    view('about');  // Carrega a visualização about.php
}

function contact() {
    view('contact');  // Carrega a visualização contact.php
}

// Função para rotear a requisição
function route($url) {
    global $routes;

    // Se a URL for vazia ou '/', redireciona para a rota home
    if ($url == '/' || empty($url)) {
        call_user_func($routes['/']);  // Chama a função home()
    } elseif (array_key_exists($url, $routes)) {
        call_user_func($routes[$url]);  // Chama a função da rota correspondente
    } else {
        echo 'Página não encontrada';
    }
}
