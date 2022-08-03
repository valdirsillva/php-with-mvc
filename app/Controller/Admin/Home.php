<?php


namespace App\Controller\Admin;

use App\Utils\View;

class Home extends Page 
{
    /**
     * Método resppnsável por renderizar a view de home do painel
     */
    public static function getHome($request) 
    {
        $content = View::render('admin/modules/home/index', []);

        // retorna a pagina completa
        return parent::getPanel('Home > Sistema', $content, 'home');
    }
}