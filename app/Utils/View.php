<?php 



namespace App\Utils;

class View 
{
    private static $vars = [];

    public static function init($vars = []) 
    {
        self::$vars = $vars;
    }

    private static function getContentView($view) 
    {
        $file = __DIR__ . '/../../resources/view/'. $view . '.html';

        return file_exists($file) ? file_get_contents($file) : '';
    }

    public static function render($view, $vars = []) 
    {   
        // conteúdo da view
        $contentView = self::getContentView($view);
        // merge de variaveis da view 
        $vars = array_merge(self::$vars, $vars);

        // chaves do array de variaveis
        $keys = array_keys($vars);
        $keys = array_map(function($item) {
            return '{{'.$item.'}}';
        }, $keys);

        $content = str_replace($keys, array_values($vars), $contentView);
        
        return $content;
    }
}