<?php

namespace App\Controllers;

use App\Core\Form;
use App\Models\AnnoncesModel;

class AnnoncesController extends Controller
{
    /**
     * Cette méthode affichera la vue
     */
    public function index()
    {
        // On instancie AnnoncesModel
        $annoncesModel = new AnnoncesModel;

        // On va chercher toutes les annonces
        $annonces = $annoncesModel->findBy(['actif' => 1]);

        // On génere la vue
        // On met compact() à la place de ['annonces' => $annonces]
        $this->render('annonces/index', compact('annonces'));
    }

    public function lire(int $id)
    {
        // On instancie le model
        $annoncesModel = new AnnoncesModel;

        // On recupere l'annonce
        $annonce = $annoncesModel->findOneBy("id", $id);

        // On génere la vue
        $this->render('annonces/lire', compact('annonce'));
    }

    /**
     * Ajouter une annonce
     */
    public function ajouter()
    {
        // On vérifie si l'utilisateur est connecté
        if (isset($_SESSION['user']) && !empty($_SESSION['user']['id'])) {
            // On verifie le formulaire
            if (Form::validation($_POST, ['titre', 'description'])) {
                // On se protege contre les failles XSS
                $titre = strip_tags($_POST['titre']);
                $description = strip_tags($_POST['description']);

                // On traite l'image et on récupère son nom dans la variable $image
                $image = GlobalController::addImage($_FILES['image'], "uploads/");

                // On instancie notre model
                $annonce = new AnnoncesModel;

                // On hydrate
                $annonce->setTitre($titre)
                    ->setDescription($description)
                    ->setImage($image)
                    ->setUsers_id($_SESSION['user']['id']);

                // On enregistre en bdd
                $annonce->create();

                // On redirige
                GlobalController::alert('success', 'Vous avez ajouté un article !');
                header('Location: /mvc/public/annonces');
                exit;
            }

            // On genere le formulaire
            $form = new Form;

            $form->debutForm('post', '#', ["enctype" => "multipart/form-data"])
                ->ajoutLabelFor('titre', 'Titre :')
                ->ajoutInput('text', 'titre', ['class' => 'form-control', 'id' => 'titre'])
                ->ajoutLabelFor('description', 'Description :')
                ->ajoutTextarea('description', '', ['class' => 'form-control', 'id' => 'description'])
                ->ajoutLabelFor('image', 'Image :')
                ->ajoutInput('file', 'image', ['class' => 'form-control', 'id' => 'image'])
                ->ajoutBouton("Ajouter", ['class' => 'btn btn-primary'])
                ->finForm();

            $this->render('annonces/ajouter', ['formCreate' => $form->create()]);
        } else {
            // L'utilisateur n'est pas connecté
            GlobalController::alert('danger', "Vous devez être connecté(e) pour accéder à cette page");
            header('Location: /mvc/public/users/login');
        }
    }

    /**
     * Modifier une annonce
     *
     * @param integer $id
     */
    public function modifier(int $id)
    {
        // On vérifie si l'utilisateur est connecté
        if (isset($_SESSION['user']) && !empty($_SESSION['user']['id'])) {
            // On verifie si l'annonce existe
            // On instancie notre model
            $annoncesModel = new AnnoncesModel;

            // On cherche l'annonce avec l'id
            $annonce = $annoncesModel->findOneBy('id', $id);

            // On verifie si l'annonce exite
            if (!$annonce) {
                http_response_code(404);
                GlobalController::alert('danger', "L'annonce n'existe pas");
                header('Location: /mvc/public/annonces');
                exit;
            }

            // On verifie si l'utilisateur est proprietaire de l'annonce ou si il est admin
            if ($annonce->users_id !== $_SESSION['user']['id']) {
                if (!in_array("ROLE_ADMIN", $_SESSION['user']['roles'])) {
                    GlobalController::alert('danger', "Vous n'avez pas le droit de modifier cette annonce !");
                    header('Location: /mvc/public/annonces');
                    exit;
                }
            }

            // On traite le formulaire
            if (Form::validation($_POST, ['titre', 'description'])) {
                // On se protege contre les failles XSS 
                $titre = strip_tags($_POST['titre']);
                $description = strip_tags($_POST['description']);

                // On instancie le model
                $annonceModif = new AnnoncesModel;

                // On recupere l'image actuel
                $image = $annoncesModel->findOneBy("id", $id)->image;

                // si on upload une nouvelle img alors on supprime l'ancienne
                if ($_FILES['image']['size'] > 0) {
                    unlink('uploads/'.$image);
                    $newImage = GlobalController::addImage($_FILES['image'], 'uploads/');
                } else {
                    // sinon on garde l'ancienne
                    $newImage = $image;
                }

                // On hydrate
                $annonceModif->setId($annonce->id)
                    ->setTitre($titre)
                    ->setImage($newImage)
                    ->setDescription($description);

                // On met à jour l'annonce
                $annonceModif->update();
                GlobalController::alert('success', 'Vous avez modifié une annonce');
                header('Location: /mvc/public/annonces/lire/' . $annonceModif->getId());
            }

            // On genere le formulaire
            $form = new Form;

            $form->debutForm('post', '#', ["enctype" => "multipart/form-data"])
                ->ajoutLabelFor('titre', 'Titre :')
                ->ajoutInput('text', 'titre', ['class' => 'form-control', 'id' => 'titre', 'value' => $annonce->titre])
                ->ajoutLabelFor('description', 'Description :')
                ->ajoutTextarea('description', $annonce->description, ['class' => 'form-control', 'id' => 'description'])
                ->ajoutLabelFor('image', 'Image :')
                ->ajoutInput('file', 'image', ['class' => 'form-control', 'id' => 'image'])
                ->ajoutBouton("Modifier", ['class' => 'btn btn-primary'])
                ->finForm();

            $this->render('annonces/modifier', ['formModifier' => $form->create()]);
        } else {
            // L'utilisateur n"est pas connecté
            GlobalController::alert('danger', "Vous devez être connecté(e) pour accéder à cette page");
            header('Location: /mvc/public/users/login');
        }
    }
}
