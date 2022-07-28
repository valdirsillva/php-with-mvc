<?php 

namespace App\Controller\Pages;

use App\Utils\View;
use App\Model\Entity\Testimony as EntityTestimony;
use \WilliamCosta\DatabaseManager\Pagination;


class Testimony extends Page
{
    public static function getTestimonies($request) 
    {
        
        $content = View::render('pages/testimonies', [
            'itens' =>  self::getTestimonyItems($request, $pagination),
            'pagination' => parent::getPagination($request, $pagination)
        ]);

        return parent::getPage('DEPOIMENTOS', $content);
    }

    // Obtem os dados dos itens a ser renderizados
    private static function getTestimonyItems($request, &$pagination) 
    {
        $itens = '';

        // quantidade total registros
        $quantidadeTotal = EntityTestimony::getTestimonies(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;
        $paginaAtual = $request->getQueryParams();
        $paginaAtual = $queryParams['page'] ?? 1;

        // instancia paginacao
        $pagination = new Pagination($quantidadeTotal, $paginaAtual, 2);

        $results = EntityTestimony::getTestimonies(null, 'id DESC', $pagination->getLimit());

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

        return self::getTestimonies($request, $pagination);
    }
}