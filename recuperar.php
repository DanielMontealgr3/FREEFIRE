<?php


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';


require_once('./database/database.php');

$conexion = new database;
$con = $conexion->conectar();   


if(isset($_POST['enviar'])){

    $elEmail = $_POST['input_correo'];
    
    if(empty($_POST['input_correo'])){
        echo "<script>alert('Archivos vacios')</script>";
        die();
    }


    $Cemail = $con->prepare("SELECT correo FROM usuario WHERE correo = '$elEmail' AND estado = 1");
    $Cemail->execute();

    
    $Cenviar = $Cemail->fetchColumn();

    
    $user = $con->prepare("SELECT * FROM usuario WHERE correo = '$elEmail' AND estado = 1");
    $user->execute();
    
    $usuario = $user->fetch(PDO::FETCH_ASSOC);
    
    if($usuario){
        
        //generamos un número aleatorio
        $numero_aleatorio = rand(1000,9999);
        
        session_start();
        
        $_SESSION['correo'] = $elEmail; 
        $_SESSION['user'] = $usuario['id_usuario'];
        $_SESSION['code'] = $numero_aleatorio;

        
    }

    if($Cenviar){

        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            //Server settings
            
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'freefirenoreplay8@gmail.com';                     //SMTP username
            $mail->Password   = 'jsqd xtxq ynqf evcv';                               //SMTP password
            $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom('freefirenoreplay8@gmail.com', 'FREE FIRE');
            $mail->addAddress($Cenviar);     //Add a recipient
                           //Name is optional
            

         

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'FREE FIRE REESTABLECER';
            $mail->Body    = 'Su codigo para restablecer contraseña es el siguiente: ' . $_SESSION['code'];
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            header("location: ./cambiarContraseña.php");
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }


    }else{
        echo "<script>alert('correo no encontrado')</script>";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
    <link rel="icon" type="image/png" href="assets/img/garena.png">
    <link rel="stylesheet" href="css/index.css">

</head>
<body>
<video class="video-fondo" autoplay loop muted playsinline>
        <source src="./assets/fondos/fondoAnimado.mp4" type="video/mp4">
        Tu navegador no soporta la reproducción de videos.
    </video>


    <div class="contenido_grid">

        

        <div class="imagen"></div>

    </div>

    <section class="mainGrid">
        <div class="logoFreeFire">
            <img src="./assets/img/ff-logo.png" class="logo" alt="Free Fire Logo">
        </div>

        <div class="mainLogin">
            <form method="POST" class="recuperarForm" onsubmit="return validarFormulario()">
                <h1 class="recuperar">Recuperar Contraseña</h1>
                
                <div class="datos">
                    

                    <p class="datoP">Correo:*</p>
                    <input type="mail" class="inputs" id="input_correo" name="input_correo" required>
                </div>

               

                <div class="olvidasteContraseñaDiv">
                    <a class="olvidasteContraseña" href="index.php">¿La Recordaste? Iniciar Sesion</a>
                </div>

                <input type="submit" name = "enviar" id="submit" class="inicioSesion">

             
            </form>
        </div>
    </section>
</body>
</html>