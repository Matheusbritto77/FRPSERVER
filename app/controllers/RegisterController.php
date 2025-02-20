<?php



namespace App\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RegisterController
{
    // Exibe o formulário de registro
    public function showForm()
    {
        // Caminho para a view
        $viewPath = __DIR__ . "/../../resources/views/register.php";
        
        // Verifica se a view existe
        if (file_exists($viewPath)) {
            // Inclui a view e retorna a resposta
            ob_start(); // Inicia o buffer de saída
            include $viewPath; // Inclui o arquivo de visualização
            $content = ob_get_clean(); // Captura a saída do buffer

            return new Response($content);
        } else {
            return new Response('Página não encontrada', 404);
        }
    }

    // Processa o envio do formulário de registro
    public function registerUser(Request $request)
    {
        // Obtém os dados do formulário
        $username = $request->request->get('username');
        $email = $request->request->get('email');
        $password = $request->request->get('password');

        // Aqui você pode adicionar lógica para validar e salvar o usuário no banco de dados

        // Para simplificação, vamos apenas retornar uma mensagem de sucesso
        return new Response('Usuário registrado com sucesso: ' . $username);
    }
}
