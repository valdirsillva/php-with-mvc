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

    protected static function getPagination($request, $pagination) 
    {
        $queryParams = $request->getQueryParams();

        $pages = $pagination->getPages();

        return [
            'paginaAtual' => isset($queryParams['page']) ? $queryParams['page'] : 1,
            'quantidadePaginas' => !empty($pages) ? count($pages) : 1
        ];
    }
}