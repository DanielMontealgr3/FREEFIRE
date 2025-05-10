<?php

require_once('./database/database.php');

$conexion = new database;
$con = $conexion->conectar();
session_start();
$codigo = $_SESSION['code'];
$correo = $_SESSION['correo'];

if(!isset($correo)){
    header("Location: session/destruirSession.php");
}

if(isset($_POST['submit'])){
    $codigoInput = $_POST['codigoInput'];

    if($codigo == $codigoInput){
        echo "<script>window.location = './nuevaContraseña.php'</script>";
    }else{
        echo "<script>alert('¡Codigo Incorrecto!')</script>";
    }
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingrese Codigo</title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/alerta.css">
</head>

<body>


    <video class="video-fondo" autoplay loop muted playsinline>
        <source src="./assets/fondos/fondoAnimado.mp4" type="video/mp4">
        Tu navegador no soporta la reproducción de videos.
    </video>

    <section class="mainGrid">
        <div class="logoFreeFire">
            <img src="./assets/img/ff-logo.png" class="logo" alt="Free Fire Logo">
        </div>

        <div class="mainLogin">
            <form method="POST" class="recuperarForm">
                <h1 class="recuperar">Recuperar contraseña</h1>

                <h4 class="mensajeCodigo">Te enviaremos un código al correo registrado para verificar tu identidad. </h4>
                <div class="datos">
                    <p class="correoElectronico"><?php echo $correo ?></p>

                </div>

                <div class="datos">
                    <p class="datoP">Ingrese el codigo</p>
                    <input type="number" name="codigoInput" class="inputs codigoSeparacion">

                </div>

                <div class="olvidasteContraseñaDiv">
                    <a class="olvidasteContraseña" href="index.php">¿La Recordaste? Iniciar Sesion</a>
                </div>

                <input type="submit" name="submit" value="Comprobar" id="submit" class="inicioSesion">

                <hr style="color:white; width:90%; height:5px; background:white;margin-top:10px;">

            </form>
        </div>
    </section>


</body>

</html>