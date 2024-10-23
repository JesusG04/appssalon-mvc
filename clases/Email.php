<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email {

    public $email;
    public $nombre;
    public $token;


    public function __construct($email, $nombre, $token)
    {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion(){
        //Crear el obejeto de email
        $mail= new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];

        $mail->setFrom('cuentas@appsalon.com','AppSalon.com');
        $mail->addAddress('cuentas@appsalon.com', 'AppSalon');
        $mail->Subject='Confirma tu cuenta';

        //set HTML
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        $contenido = "<html>";
        $contenido .= "<p><strong>Hola ".$this->nombre ."</strong> Has creado tu cuenta en App Salon, solo debes confirmala presionando el siguiente enlace</p>";
        $contenido .="<p>Presiona aqui: <a href='".$_ENV['APP_URL']."/confirm-account?token=". $this->token."'> Confirmar Cuenta</a> </p>";
        $contenido .="<p>Si tu no creaste esta cuenta, puedes ignorar el mensaje</p>";
        $contenido .= "</html>";

        $mail->Body = $contenido;
        $mail->send();

    }
    public function enviarInstrucciones(){
          //Crear el obejeto de email
          $mail= new PHPMailer();
          $mail->isSMTP();
          $mail->Host = $_ENV['EMAIL_HOST'];
          $mail->SMTPAuth = true;
          $mail->Port = $_ENV['EMAIL_PORT'];
          $mail->Username = $_ENV['EMAIL_USER'];
          $mail->Password = $_ENV['EMAIL_PASS'];
  
          $mail->setFrom('cuentas@appsalon.com','AppSalon.com');
          $mail->addAddress('cuentas@appsalon.com', 'AppSalon');
          $mail->Subject='Restablece tu Consraseña';
  
          //set HTML
          $mail->isHTML(true);
          $mail->CharSet = 'UTF-8';
  
          $contenido = "<html>";
          $contenido .= "<p><strong>Hola ".$this->nombre ."</strong> Has solicitado reestablecer tu contraseña, sigue el siguiente enlace para hacerlo.</p>";
          $contenido .="<p>Presiona aqui: <a href='".$_ENV['APP_URL']."/recover?token=". $this->token."'>Reestablecer Contraseña</a> </p>";
          $contenido .="<p>Si tu no solicitaste este cambio, puedes ignorar el mensaje</p>";
          $contenido .= "</html>";
  
          $mail->Body = $contenido;
          $mail->send();
  
    }
}