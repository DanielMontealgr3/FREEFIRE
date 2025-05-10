<?php 
include_once("./database/database.php");
$conexion = new database;
$con = $conexion->conectar();

if(isset($_POST['submit'])){
    $username = $_POST['username'];
    $contraseña = $_POST['contraseña'];

    if(empty($username) || empty($contraseña)){
        echo "<script>alert('Campos Vacíos')</script>";
    } else {
        $consulta = $con->prepare("SELECT * FROM usuario WHERE username = :username");
        $consulta->bindParam(':username', $username);
        $consulta->execute();
        $fila = $consulta->fetch(PDO::FETCH_ASSOC);
        
        if ($fila && password_verify($contraseña, $fila['contrasena'])) {
            session_start();

            // Verificar si ha pasado más de 10 días desde el último ingreso
            $ultimoIngreso = strtotime($fila['ultimoIngreso']);
            $diferenciaDias = (time() - $ultimoIngreso) / (60 * 60 * 24); // Convertir a días

            // if ($diferenciaDias > 10) {
            //     // Bloquear al usuario si ha estado inactivo por más de 10 días
            //     $bloquear = $con->prepare("UPDATE usuario SET estado = 2 WHERE id = :id");
            //     $bloquear->bindParam(':id', $fila['id']);
            //     $bloquear->execute();

            //     echo "<script>alert('Tu cuenta ha sido bloqueada por inactividad. Contacta con soporte.');</script>";
            //     exit;
            // }

        
            // $actualizarIngreso = $con->prepare("UPDATE usuario SET ultimoIngreso = NOW() WHERE id = :id");
            // $actualizarIngreso->bindParam(':id', $fila['id']);
            // $actualizarIngreso->execute();

            $_SESSION['user_id'] = $fila['id'];
            $_SESSION['username'] = $fila['username'];
            $_SESSION['id_usuario'] = $fila['id_usuario'];

            if ($fila['id_rol'] == 2) {
                if ($fila['personaje'] == null) {
                    if ($fila['estado'] == 1) {
                        header('Location: ./elegirPersonaje.php');
                    } else {
                        echo "<script>alert('Lo siento, estás bloqueado.');</script>";
                    }
                } else {
                    header('Location: ./roles/jugadores/lobby.php');
                }
            } else {
                header('Location: ./roles/admin/inicio.php');
            }
            
        } else {
            echo "<script>alert('Usuario o contraseña incorrectos');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio De sesion</title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/alerta.css">
    <link rel="icon" href="/assets/img/pngwing.com-_1__1_11zon.webp" type="image/x-icon">
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
                <h1 class="titleLogin">Login</h1>
                
                <div class="datos">
                    <p class="datoP">Username</p>
                    <input type="text" name="username" id="username" class="inputs">
                    <p style="color:red" id="advertencia"></p>
                </div>

                <div class="datos">
                    <p class="datoP">Contraseña</p>
                    <input type="password" id="contraseña" name="contraseña" class="inputs">
                    <p style="color:red" id="advertencia2"></p>
                </div>

                <div class="olvidasteContraseñaDiv">
                    <a class="olvidasteContraseña" href="recuperar.php">¿Olvidaste Tu Contraseña?</a>
                </div>

                <input type="submit" name="submit" value="Iniciar Sesion" id="submit" class="inicioSesion">

                <hr style="color:white; width:90%; height:5px; background:white;margin-top:10px;">

                <div class="sinCuenta">
                    <p style="color:gray; margin-top:15px">¿No Tienes Cuenta? <a href="./registro.php" class="registrate">Registrate</a></p>
                </div>
            </form>
        </div>
    </section>

<script>
function validarFormulario() {
    var username = document.getElementById('username').value;
    var contraseña = document.getElementById('contraseña').value;
    var advertencia = document.getElementById('advertencia');
    var advertencia2 = document.getElementById('advertencia2');
    var valido = true;
    
    advertencia.textContent = '';
    advertencia2.textContent = '';
    
    if(username === '') {
        advertencia.textContent = 'El username es obligatorio';
        valido = false;
    }
    
    if(contraseña === '') {
        advertencia2.textContent = 'La contraseña es obligatoria';
        valido = false;
    }
    
    return valido;
}
</script>

<script src="js/alerta.js"></script>

</body>
</html>