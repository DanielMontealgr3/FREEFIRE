<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../PHPMailer/Exception.php';
require '../../PHPMailer/PHPMailer.php';
require '../../PHPMailer/SMTP.php';

$mail = new PHPMailer(true);

try {
    // Configuración de SMTP
    $mail->isSMTP();                                          
    $mail->Host       = 'smtp.gmail.com';                    
    $mail->SMTPAuth   = true;                                  
    $mail->Username   = 'freefirenoreplay8@gmail.com';                     
    $mail->Password   = 'jsqd xtxq ynqf evcv';  
    $mail->Port       = 587;   

    if (isset($_GET['correos']) && !empty($_GET['correos'])) {
        $correos_str = $_GET['correos'];  
        $correos_array = explode(',', $correos_str);  

        
        foreach ($correos_array as $correo_usuario) {
            if (filter_var($correo_usuario, FILTER_VALIDATE_EMAIL)) {
                // Datos de quien recibe
                $mail->setFrom('freefiremailadso@gmail.com', 'YA PUEDE JUGAR');
                $mail->addAddress(trim($correo_usuario)); // Añadimos el correo al destinatario

                // Contenido del mensaje
                $mail->isHTML(true);                                  
                $mail->Subject = 'ACTIVADO EN FREEFIRE';
                $mail->Body = '
                    <p>Estimado usuario,</p>
                    <p>Se le informa que usted ha sido habilitado por el Administrador de Free Fire para poder jugar.</p>
                    <br>
                    <p>Atentamente,</p>
                    <p><strong>Equipo Free Fire</strong></p>
                ';

                // Enviar el correo
                $mail->send();
                echo '<script>alert("El mensaje se ha enviado correctamente a: " . htmlspecialchars($correo_usuario) . "<br>");</script>';
                echo '<script>window.history.go(-1);</script>';
            } else {
                echo "El correo no es válido: " . htmlspecialchars($correo_usuario) . "<br>";
                echo '<script>window.history.go(-1);</script>';
            }

            // Limpiar el objeto de PHPMailer para evitar que los destinatarios anteriores interfieran
            $mail->clearAddresses();
        }
    } else {
        echo 'No se han recibido correos para enviar.';
        echo '<script>window.history.go(-1);</script>';
    }

} catch (Exception $e) {
    echo "Hubo un error al enviar el mensaje: {$mail->ErrorInfo}";
    echo '<script>window.history.go(-1);</script>';
}
?>
