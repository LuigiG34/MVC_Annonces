<?php

namespace App\Controllers;
use FFI\Exception;

abstract class GlobalController
{
    /**
     * Traitement d'une image
     *
     * @param array $file
     * @param string $dir
     */
    static public function addImage(array $file,string $dir)
    {
        try {
            // Verifie si on a ajouter un fichier
            if (!isset($file['name']) || empty($file['name'])){
                throw new Exception("You must select an image");
            }

            // On récupère l'extension
            $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            $random = rand(0, 99999);
            $file['name'] = str_replace(" ", "_",$file['name']);
            $target_file = $dir . $random . "_" . $file['name'];

            // Verifie si fichier est bien une image
            if (!getimagesize($file["tmp_name"])){
                throw new Exception("Le fichier sélectionné n'est pas une image.");
            }

            // Vérifie si c'est bien l'extension jpg, jpeg , png ou gif
            if ($extension !== "jpg" && $extension !== "jpeg" && $extension !== "png" && $extension !== "gif"){
                throw new Exception("L'extension n'est pas valable.");
            }

            // Vérifie si le fichier existe déja
            if (file_exists($target_file)){
                throw new Exception("Le fichier existe déjà.");
            }

            // Vérifie la taille de l'image pas trop volumineuse
            if ($file['size'] > 1000000){
                throw new Exception("Le fichier est trop volumineux.");
            }

            // Vérifie si on a bien ajouté l'image
            if (!move_uploaded_file($file['tmp_name'], $target_file)){
                throw new Exception("Ajouter l'image n'a pas fonctionné.");
            }

            // On retourne le nom de l'image si tout est bon
            else{
                return $random . "_" . $file['name'];
            }

        } catch (Exception $e) {
            self::alert("danger", $e->getMessage());
            header("location: /mvc/public/annonces");
            exit;
        }
    }

    /**
     * Afficher un message après une requete
     *
     * @param string $type
     * @param string $message
     */
    static public function alert(string $class, string $message)
    {
        $_SESSION['alert'] = [
            "class" => $class,
            "msg" => $message
        ];
        return $_SESSION['alert'];
    }
}