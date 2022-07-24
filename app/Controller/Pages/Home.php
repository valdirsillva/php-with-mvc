<?php 

namespace App\Controller\Pages;

use App\Utils\View;

class Home extends Page
{
    public static function getHome() 
    {
        $content = View::render('pages/home', [
            "name" => "Valdir - Software Enginee",
            "description" => "course PHP & MVC"
        ]);

        return parent::getPage('Pagina teste', $content);
    }
}