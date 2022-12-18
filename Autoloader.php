<?php 

namespace App;
/**
 * Autoloader class
 */
class Autoloader
{
    static function register()
    {
        // detecte la nouvelle classe
        spl_autoload_register([
            // Recupere la classe
            __CLASS__,
            // Appelle la méthode autoload()
            'autoload'
        ]);
    }

    static function autoload($class)
    {  
        // recupere le namespace de la classe
        
        // supprimer la racine du namespace (ici le App\)
        $class = str_replace(__NAMESPACE__.'\\', '', $class);
        // remplace les "\" par des "/"
        $class = str_replace('\\','/', $class);

        // verifie si le fichier existe
        $fichier =  __DIR__.'/'.$class.'.php';

        if(file_exists($fichier)){
            require_once $fichier;
        }
    }
}