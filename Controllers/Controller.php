<?php

namespace App\Controllers;

abstract class Controller
{
    public function render(string $fichier, array $donnees = [], string $template = 'default')
    {
        // On extrait le contenu de $donnees
        extract($donnees);

        // On démarre le buffer de sortie
        // A partir de ce point toute sortie (echo ou HTML) est conservé en mémoire
        ob_start();

        // On créé le chemin vers la vue
        require_once ROOT . '/Views/' . $fichier . '.php';

        // On stocke dans la variable le buffer
        $contenu = ob_get_clean();

        // On recupere notre template
        require_once ROOT . '/Views/'.$template.'.php';
    }
}