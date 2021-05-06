<?php
namespace App;

class Autoloader
{
    static function register()
    {
        spl_autoload_register([
            __CLASS__,
            'autoload'
        ]);
    }
    static function autoload($class)
    {
        // on récupère dans la $class la totalité du namespace de la class concernée
        // On retire App\
        $class = str_replace(__NAMESPACE__ . '\\', '', $class);

        // On remplace les \ par des /
        $class = str_replace('\\', '/', $class );

        $file = __DIR__ . '/' . $class . '.php';
        // On vérifie si le fichier existe
        if(file_exist($file)){
            require_once $file;
        }   
    }

}