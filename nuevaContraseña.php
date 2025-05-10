<?php 
include_once("./database/database.php");
session_start();

if (!isset($_SESSION['correo'])) {
    header("Location: session/destruirSession.php");
    exit();
}

$correo = $_SESSION['correo'];
$conexion = new database;
$con = $conexion->conectar();

if (isset($_POST['submit'])) {
    $contraseña = $_POST['contraseña'];
    $confirmarContraseña = $_POST['confirmarContraseña'];

    if ($contraseña === $confirmarContraseña) {
        $contraseñaEnc = password_hash($contraseña, PASSWORD_DEFAULT);

        $inse = $con->prepare("UPDATE usuario SET contrasena = ? WHERE correo = ?");
        $inse->execute([$contraseñaEnc, $correo]);

        session_destroy();
        header("Location: session/destruirSession.php");
        exit();
    } else {
        $error = "Las contraseñas no coinciden.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Contraseña</title>
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
        <form method="POST" class="login" onsubmit="return validarFormulario()">
            <h1 class="cambiarContraseña">Cambiar Contraseña</h1>

            <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>

            <div class="datos">
                <p class="datoP">Contraseña</p>
                <input type="password" name="contraseña" id="username" class="inputs">
                <p style="color:red" id="advertencia"></p>
            </div>

            <div class="datos">
                <p class="datoP">Repita La Contraseña</p>
                <input type="password" id="confirmarContraseña" name="confirmarContraseña" class="inputs">
                <p style="color:red" id="advertencia2"></p>
            </div>

            <input type="submit" name="submit" value="Cambiar Contraseña" id="submit" class="inicioSesion">
        </form>
    </div>
</section>

</body>
</html>
