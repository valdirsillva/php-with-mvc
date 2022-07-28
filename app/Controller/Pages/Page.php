<?php 

namespace App\Controller\Pages;

use App\Utils\View;

class Page 
{
    private static function getHeader() 
    {
        return View::render('pages/header');    
    }

    private static function getFooter() 
    {
        return View::render('pages/footer');    
    }

    public static function getPagination($request, $pagination) 
    {
        $pages = $pagination->getPages();
    
        if (count($pages) <= 1) return '';
        
        $links = '';

        $url = $request->getRouter()->getCurrentUrl();

        $queryParams = $request->getQueryParams();

        // renderiza os links
        foreach ($pages as $page) {
            # altera pagina
            $queryParams['page'] = $page['page'];

            // link da pagina
            $link = $url .'?'. http_build_query($queryParams);

            // VIEW
            $links .=  View::render('pages/paginations/link', [
                "page" => $page['page'],
                "link" => $link,
                "active" => $page['current'] ? 'active' : ''
            ]);
        }

        return View::render('pages/paginations/box', [
            "links" => $links,
        ]);


    }

    public static function getPage($title, $content) 
    {
        return View::render('pages/page', [
            "title" => $title,
            "header" => self::getHeader(),
            "content" => $content,
            "footer" => self::getFooter()
        ]);
    }
}