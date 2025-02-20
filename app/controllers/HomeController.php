<?php


namespace App\Controllers;

class HomeController
{
    public function index()
    {
        return new \Symfony\Component\HttpFoundation\Response(
            'Welcome to the Home Page!'
        );
    }
}
