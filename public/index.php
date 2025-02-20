<?php

// Incluindo o arquivo de rotas
require_once __DIR__ . '/../routers/web.php';

// Obtém a URL atual (sem o domínio)
$currentUrl = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Roteia a requisição para a função correspondente
route($currentUrl);
