<?php


namespace App\Controller\Admin;

use App\Utils\View;

class Page 
{
    /**
     * Método responsável por retornar o conteúdo da (view) da estrutura genérica de pagina do painel
     */

    public static function getPage($title, $content) 
    {
        return View::render('admin/page', [
            'title' => $title,
            'content' => $content
        ]);
    }
}