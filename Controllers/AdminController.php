<?php

namespace App\Controllers;

use App\Models\AnnoncesModel;

class AdminController extends Controller
{
    public function index()
    {
        // On vérifie que l'utilisateur est admin
        if($this->isAdmin()){
            $this->render('admin/index', [], 'admin');
        }
    }

    /**
     * Afficher les annonces en tableau
     */
    public function annonces()
    {
        if($this->isAdmin()){
            $annoncesModel = new AnnoncesModel;

            $annonces = $annoncesModel->findAll();

            $this->render('admin/annonces', compact('annonces'), 'admin');
        }
    }

    /**
     * Supprimer une annonce
     *
     * @param integer $id
     */
    public function supprimeAnnonce(int $id)
    {
        if($this->isAdmin()){
            
            $annoncesModel = new AnnoncesModel;

            // Recupere l'image
            $image = $annoncesModel->findOneBy("id", $id)->image;

            // Si l'image existe supprime la et supprime l'annonce
            if (file_exists('uploads/'.$image)) {
                unlink('uploads/'.$image);
                $annoncesModel->delete($id);
                header('Location: /mvc/public/admin/annonces');
            }
        }
    }

    /**
     * Active ou desactive une annonce
     *
     * @param integer $id
     */
    public function activeAnnonce(int $id)
    {
        // verifie si on est admin
        if($this->isAdmin()){
            // instancie annoncesModel
            $annoncesModel = new AnnoncesModel;

            // on recupere l'annonce grace à son id
            $annonceArray = $annoncesModel->findOneBy('id', $id);

            // si l'annonce existe
            if($annonceArray){
                // on l'hydrate
                $annonce = $annoncesModel->hydrate($annonceArray);

                // Si actif est = 1 on le set a 0 sinon on set a 1
                $annonce->setActif($annonce->getActif() ? 0 : 1);

                // On mets à jour annonce
                $annonce->update();
            }
        }
    }

    /**
     * Verifie si on est administrateur
     */
    private function isAdmin()
    {
        // On verifie si on est connecté et si on est admin
        if(isset($_SESSION['user']) && in_array("ROLE_ADMIN", $_SESSION['user']['roles'])){
            // On est admin
            return true;
        }else{
            GlobalController::alert('danger', "Vous n'avez pas accès à cette page.");
            header('Location: /mvc/public/');
            exit;
        }
    }
}