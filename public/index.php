<?php

use App\Autoloader;
use App\Core\Main;

// On definie une constante contenant le dossier racine du projet
define('ROOT', dirname(__DIR__));

// On importe l'Autoloader
require_once ROOT.'/Autoloader.php';
Autoloader::register();

// On instancie Main
$app = new Main;

// On démarre l'application
$app->start();