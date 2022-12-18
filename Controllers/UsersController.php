<?php

namespace App\Controllers;

use App\Core\Form;
use App\Models\UsersModel;

class UsersController extends Controller
{
    /**
     * Connexion
     */
    public function login()
    {
        // On vérifie si le formulaire est complet
        if(Form::validation($_POST, ['email', 'password'])){
            // Le formulaire est valide
            // On nettoie l'adresse email (on pourrai utiliser htmlspecialchars())
            $usersModel = new UsersModel;
            $userArray = $usersModel->findOneBy('email', strip_tags($_POST['email']));

            if(!$userArray){
                // On envoie un message de session
                GlobalController::alert('danger',"L'adresse mail et/ou le mot de passe est incorrect !");
                header('Location: /mvc/public/users/login');
                exit;
            }

            // L'utilisateur existe
            $user = $usersModel->hydrate($userArray);
            
            // verifier les mots de passes
            if(password_verify($_POST["password"], $user->getPassword())){
                // succés
                $user->setSession();
                GlobalController::alert('success',"Vous êtes connecté !");
                header('Location: /mvc/public');
            }else{
                // Mauvais mot de passe
                GlobalController::alert('danger',"L'adresse mail et/ou le mot de passe est incorrect !");
                header('Location: /mvc/public/users/login');
                exit;
            }
        }

        // On génère le formulaire
        $form = new Form;

        $form->debutForm()
        ->ajoutLabelFor('email', 'Email :')
        ->ajoutInput('email', 'email', ['class' => 'form-control', 'id' => 'email'])
        ->ajoutLabelFor('password', 'Mot de passe :')
        ->ajoutInput('password', 'password', ['class' => 'form-control', 'id' => 'password'])
        ->ajoutBouton('Me connecter', ['class' => 'btn btn-primary'])
        ->finForm(); 

        $this->render('users/login', ['loginForm' => $form->create()]); 
    }

    /**
     * Inscription
     */
    public function register()
    {
        // On vérifie si le formulaire est valide
        if(Form::validation($_POST, ['email', 'password'])){
            // Le formulaire est valide
            // On nettoie l'adresse email (on pourrai utiliser htmlspecialchars())
            $email = strip_tags($_POST['email']);

            // On hash le mot de passe
            $password = password_hash(htmlspecialchars($_POST['password']), PASSWORD_ARGON2I);

            // On hydrate l'utilisateur
            $user = new UsersModel;
            
            $user->setEmail($email)
            ->setPassword($password);

            // On stocke l'utilisateur
            $user->create();
        }

        // On génère le formulaire
        $form = new Form;

        $form->debutForm()
        ->ajoutLabelFor('email', 'Email :')
        ->ajoutInput('email', 'email', ['class' => 'form-control', 'id' => 'email'])
        ->ajoutLabelFor('password', 'Mot de passe :')
        ->ajoutInput('password', 'password', ['class' => 'form-control', 'id' => 'password'])
        ->ajoutBouton("M'inscrire", ['class' => 'btn btn-primary'])
        ->finForm();

        $this->render('users/register', ['registerForm' => $form->create()]); 
    }

    /**
     * Deconexion      
     */
    public function logout()
    {
        unset($_SESSION["user"]);
        header('Location: /mvc/public');
        exit;
    }
}