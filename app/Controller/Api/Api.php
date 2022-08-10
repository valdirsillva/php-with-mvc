<?php 


namespace App\Controller\Api;


class Api 
{
    public static function getDetails($request) 
    {
        return [
            'nome' => 'API - MVC',
            'versao' => 'v1.0.0',
            'autor' => 'Valdir Silva',
            'email' => 'valdirpiresba@gmail.com'
        ];
    }
}