<?php

namespace App\Core;

// On importe PDO
use PDO;
use PDOException;

/**
 * Classe Db
 */
class Db extends PDO
{
    // Instance unique de la classe
    private static $instance;

    // Informations de connexion
    private const DBHOST = "localhost";
    private const DBUSER = "root";
    private const DBPASS = "";
    private const DBNAME = "mvc";

    /**
     * Constructeur de la classe Db
     */
    private function __construct()
    {
        // DSN de connexion
        $_dsn = 'mysql:dbname='. self::DBNAME . ";host=" . self::DBHOST;

        // On execute un try/catch au cas où la connexion ne fonctionne pas
        try{
            // On appelle le constructeur de la classe PDO
            parent::__construct($_dsn, self::DBUSER, self::DBPASS);
            
            // Permet d'effectuer les transitions sur de l'UTF-8
            $this->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, 'SET NAMES utf8');
            // Permet d'effectuer tout nos fetch/fetchAll en tableau associatif
            $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            // Definir le mode de transfert d'erreur
            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        }catch(PDOException $e){
            die($e->getMessage());
        }
    }

    /**
     * Génère et/ou récupère l'instance de la BDD
     */
    public static function getInstance(): self
    {
        if(self::$instance === null){
            self::$instance = new self();
        }
        return self::$instance;
    }
}