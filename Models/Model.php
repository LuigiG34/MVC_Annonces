<?php

namespace App\Models;

use App\Core\Db;

/**
 * Class Model
 */
class Model extends Db
{
    // Table de la base de données
    protected $table;
    // Instance de Db
    private $db;

    /**
     * FetchAll
     */
    public function findAll(): array|false
    {
        $query = $this->sendQuery('SELECT * FROM '.$this->table);
        return $query->fetchAll();
    }

    /**
     * findBy
     * 
     * On passe en parametre un tableau associatif
     * par exemple : ["id" => 1]
     */
    public function findBy(array $criteres): array|false
    {
        $champs = [];
        $valeurs = [];

        // On boucle pour éclater le tableau
        foreach($criteres as $champ => $valeur){
            // WHERE champs = valeur
            $champs[] = "$champ = ?";
            $valeurs[] = $valeur;
        }

        // On transforme le tableau champs en une chaine de caracteres
        $liste_champs = implode(' AND ', $champs);

        // On execute la requete
        return $this->sendQuery('SELECT * FROM '. $this->table . ' WHERE '. $liste_champs, $valeurs)->fetchAll();
    }

    /**
     * find one by
     */
    public function findOneBy(string $champ, $valeur): object|false
    {
        return $this->sendQuery("SELECT * FROM $this->table WHERE $champ = '$valeur'")->fetch();
    }

    /**
     * Create
     */
    public function create(): object
    {
        $champs = [];
        $interogations = [];
        $valeurs = [];

        // On boucle pour éclater le tableau
        foreach($this as $champ => $valeur){
            if($valeur !== null && $champ != 'db' && $champ != 'table'){
                // INSERT INTO table (champs) VALUES (?,?,?)
                $champs[] = "$champ";
                $interogations[] = "?";
                $valeurs[] = $valeur;
            }
        }

        // On transforme le tableau champs en une chaine de caracteres
        $liste_champs = implode(', ', $champs);
        $liste_interogations = implode(', ', $interogations);

        // On execute la requete
        return $this->sendQuery('INSERT INTO '. $this->table . ' ('.$liste_champs.') VALUES ('.$liste_interogations.')', $valeurs);
    }

    /**
     * Hydrate
     */
    public function hydrate($donnees)
    {
        foreach($donnees as $key => $value){
            // On recupere le nom du setter correspondant à la clé
            $setter = 'set' . ucfirst($key);

            // On vérifie si le setter existe
            if(method_exists($this, $setter)){
                // On appelle le setter
                $this->$setter($value);
            }
        }
        return $this;
    }

    /**
     * Update
     */
    public function update(): object
    {
        $champs = [];
        $valeurs = [];

        // On boucle pour éclater le tableau
        foreach($this as $champ => $valeur){
            if($valeur !== null && $champ != 'db' && $champ != 'table'){
                // UPDATE table SET champ = valeur WHERE $id
                $champs[] = "$champ = ?";
                $valeurs[] = $valeur;
            }
        }
        $valeurs[] = $this->id;

        // On transforme le tableau champs en une chaine de caracteres
        $liste_champs = implode(', ', $champs);

        // On execute la requete
        return $this->sendQuery('UPDATE '. $this->table . ' SET '.$liste_champs.' WHERE id = ?', $valeurs);
    }

    /**
     * Delete
     */
    public function delete(int $id)
    {
        return $this->sendQuery("DELETE FROM $this->table WHERE id = ?", [$id]);
    }

    /**
     * Query
     */
    public function sendQuery(string $sql, array $attributs = null): object|array
    {
        // On récupère l'instance de Db
        $this->db = Db::getInstance();

        // On vérifie si on a des attributs
        if($attributs !== null){
            // Requête preparée
            $query = $this->db->prepare($sql);
            $query->execute($attributs);
            return $query;

        }else{
            // Requête simple
            return $this->db->query($sql);
        }
    }
}