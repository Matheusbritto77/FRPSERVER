<?php


namespace App\Controllers;

use Symfony\Component\HttpFoundation\Response;

class HomeController
{
    // Exibe a página inicial
    public function index()
    {
        // Caminho para a view
        $viewPath = __DIR__ . "/../../resources/views/home.php";
        
        // Verifica se a view existe
        if (file_exists($viewPath)) {
            // Inicia o buffer de saída e inclui a view
            ob_start();
            include $viewPath;
            $content = ob_get_clean(); // Captura o conteúdo do buffer

            return new Response($content);
        } else {
            return new Response('Página não encontrada', 404);
        }
    }
}
