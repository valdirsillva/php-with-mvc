<?php 

namespace App\Controller\Pages;

use App\Utils\View;
use App\Model\Entity\Testimony as EntityTestimony;


class Testimony extends Page
{
    public static function getTestimonies() 
    {
        
        $content = View::render('pages/testimonies', [
            
        ]);

        return parent::getPage('DEPOIMENTOS', $content);
    }

    public static function insertTestimony($request): string 
    {
        $postVars = $request->getPostVars();

        echo '<pre>';
        print_r($postVars);
        echo '</pre>';

        $entityTestimony = new EntityTestimony;
        $entityTestimony->nome = $postVars['nome'];
        $entityTestimony->mensagem = $postVars['mensagem'];

        $entityTestimony->add();

        return self::getTestimonies();
    }
}