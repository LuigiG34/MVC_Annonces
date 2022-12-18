<?php

namespace App\Controllers;

abstract class GlobalController
{
    /**
     * Afficher un message après une requete
     *
     * @param $type
     * @param $message
     */
    static public function alert($class, $message)
    {
        $_SESSION['alert'] = [
            "class" => $class,
            "msg" => $message
        ];
        return $_SESSION['alert'];
    }
}