<?php
require_once __DIR__ . '/vendor/autoload.php';

use Swagger\Annotations as SWG;

/**
 * @SWG\Info(
 *     title="Minha API",
 *     version="1.0.0",
 *     description="Descrição da minha API",
 *     @SWG\Contact(
 *         email="contact@minhaapi.com"
 *     )
 * )
 */

/**
 * @SWG\Get(
 *     path="/api/users",
 *     summary="Retorna todos os usuários",
 *     @SWG\Response(
 *         response=200,
 *         description="Uma lista de usuários",
 *         @SWG\Schema(
 *             type="array",
 *             @SWG\Items(type="string")
 *         )
 *     )
 * )
 */
$app->get('/api/users', function ($request, $response, $args) {
    $data = ['user1', 'user2', 'user3'];
    return $response->withJson($data);
});

$swagger = \Swagger\scan(__DIR__ . '/src');
header('Content-Type: application/json');
echo $swagger;
