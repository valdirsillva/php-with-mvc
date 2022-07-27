<?php 

namespace App\Controller\Pages;

use App\Utils\View;
use App\Model\Entity\Testimony as EntityTestimony;


class Testimony extends Page
{
    public static function getTestimonies() 
    {
        
        $content = View::render('pages/testimonies', [
            'itens' =>  self::getTestimonyItems()
        ]);

        return parent::getPage('DEPOIMENTOS', $content);
    }

    // Obtem os dados dos itens a ser renderizados
    private static function getTestimonyItems() 
    {
        $itens = '';

        $results = EntityTestimony::getTestimonies(null, 'id DESC');

        // renderiza o item
        while($obTestimony = $results->fetchObject(EntityTestimony::class)) {
            $itens .= View::render('pages/testimony/item', [
                'nome' => $obTestimony->nome,
                'mensagem' => $obTestimony->mensagem,
                'data' => date('d/m/Y H:i:s', strtotime($obTestimony->data))
            ]);
        }

        return $itens;
    }


    public static function insertTestimony($request): string 
    {
        $postVars = $request->getPostVars();

        $entityTestimony = new EntityTestimony;
        $entityTestimony->nome = $postVars['nome'];
        $entityTestimony->mensagem = $postVars['mensagem'];

        $entityTestimony->add();

        return self::getTestimonies();
    }
}