<?php

namespace App\Controllers;

use App\Core\PhpMailer\Mailer;
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
     * Envoie un email avec le lien pour reset le mot de passe
     *
     * @return void
     */
    public function resetPassword()
    {
        if(Form::validation($_POST, ['email'])){
            // On retire les balise HTML
            $email = strip_tags($_POST['email']);

            // On instancie notre model
            $usersModel = new UsersModel;
            // On recupere l'utilisateur grace à son email
            $user = $usersModel->findOneBy('email', $email);

            // On vérifie si l'utilisateur existe
            if(!$user){
                GlobalController::alert('danger', 'Aucun utilisateur lié à cette adresse mail.');
                header('Location: /mvc/public/users/resetPassword');
                exit;
            }

            // On hydrate
            $user = $usersModel->hydrate($user);
            
            // On génere le token
            $token = bin2hex(random_bytes(32));
            // On hydrate
            $user->setToken($token);
            // On met à jour le token en bdd
            $user->update();

            // On envoie le mail
            $mailer = new Mailer;
            $mailer->resetPassword($user->getEmail(), "http://localhost/mvc/public/users/newPassword/$token");

            GlobalController::alert('success','Un mail vient de vous être envoyé. (Vérifié vos spams)');
        }

        // On génère le premier formulaire
        $form = new Form;

        $form->debutForm()
        ->ajoutLabelFor('email', 'Email :')
        ->ajoutInput('email', 'email', ['class' => 'form-control', 'id' => 'email'])
        ->ajoutBouton("Confirmer", ['class' => 'btn btn-primary'])
        ->finForm();

        $this->render('users/reset', ['resetForm' => $form->create()]); 
    }

    /**
     * L'utilisateur reset son mot de passe avec le mail qu'on lui a envoyé
     *
     * @param string $token
     */
    public function newPassword(string $token)
    {
        // On récupère l'utilisateur via le token en paramètre
        $usersModel = new UsersModel;
        $user = $usersModel->findOneBy('token', $token);

        if(!$user){
            GlobalController::alert('danger', 'Vous n\'avez pas accès à cette page.');
            header('Location: /mvc/public/users/resetPassword');
            exit;
        }

        // On vérifie les input
        if(Form::validation($_POST, ['password'])){
            // On hash le mot de passe
            $password = password_hash(strip_tags($_POST['password']), PASSWORD_ARGON2I);
            // On hydrate l'utilisateur
            $user = $usersModel->hydrate($user);

            // On mets à jour son mot de passe et son token
            $user->setToken('NULL');
            $user->setPassword($password);

            // On mets à jour le user dans la BDD
            $user->update();

            // On renvoie un message de succès et on redirige vers le login
            GlobalController::alert('success', 'Vous avez modifié votre mot de passe');
            header('Location: /mvc/public/users/login');
        }

        // On génère le premier formulaire
        $form = new Form;

        $form->debutForm()
        ->ajoutLabelFor('password', 'Nouveau mot de passe :')
        ->ajoutInput('password', 'password', ['class' => 'form-control', 'id' => 'password'])
        ->ajoutBouton("Confirmer", ['class' => 'btn btn-primary'])
        ->finForm();

        $this->render('users/new-password', ['newPasswordForm' => $form->create()]); 
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