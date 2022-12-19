<?php

namespace App\Core\PhpMailer;

use App\Core\PHPMailer\{ PHPMailer, SMTP };
use Exception;

class Mailer {

    private $host = ''; // smtp host
    private $username = ''; // email sender
    private $password = ''; // password email

    public function __construct(){

        $this->mail = new PHPmailer(true);

        // $this->mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $this->mail->isSMTP();
        $this->mail->Host       = $this->host;
        $this->mail->SMTPAuth   = true;
        $this->mail->Username   = $this->username;
        $this->mail->Password   = $this->password;
        $this->mail->SMTPSecure = 'tls';
        $this->mail->Port       = 587;

        $this->mail->setFrom($this->username, 'MVC Annonces');
        $this->mail->CharSet = 'UTF-8';
    }

    /**
     * Reset password email
     *
     * @param string $to
     * @param string $link
     */
    public function resetPassword(string $to, string $link){

        $this->mail->addAddress($to);

        $this->mail->Subject = 'Mettez à jour votre mot de passe';
        $this->mail->Body = "Veuillez vous rendre à l'adresse suivante pour mettre à jour votre mot de passe : $link";

        if(!$this->mail->send()){
            throw new Exception("Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}");
        }
    }
}
