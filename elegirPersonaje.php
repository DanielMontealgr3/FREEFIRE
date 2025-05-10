<?php 
include_once("./database/database.php");
$conexion = new database;
$con = $conexion->conectar();
session_start();

$userId = $_SESSION['user_id'];
$username = $_SESSION['username'];
$id_usuario = $_SESSION['id_usuario'];

$validar = $con->prepare("SELECT estado FROM usuario WHERE id_usuario = $id_usuario");
$validar->execute();
$estado = $validar->fetchColumn();


if($estado == 2){
    echo "<script>window.location.href = './index.php'</script>";

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/elegirPersonaje.css">
    <title>Elegir personaje</title>
</head>
<body>
    
<nav>
    <div class="tituloSeleccione">
        <img class="imgPersonita" src="assets/img/user (1).png" alt="">
        <h1 class="tittle">Seleccione Un Avatar <?php echo $username ?></h1>
    </div>

    <img src="assets/img/ff-logo.png" class="logo" alt="">
</nav>
<form action="" method="POST">
    
<section class="main">
    <section class="contenidoMain">

        <section class="barraPersonajes">
        <?php
        $sql = $con->prepare("SELECT * FROM personaje ORDER BY id_personaje ASC");
        $sql->execute();
        
        $fila = $sql->fetchAll(PDO::FETCH_ASSOC);

        foreach($fila as $resu){
        ?>
            <button name="<?php echo $resu['id_personaje'] ?>"
            onclick="mostrar(<?php echo $resu['id_personaje'] ?>)" 
            style="background-color: transparent;border:none" > 
                <div class="contenedorPersonjae">
                    <img class="personajeImg" src="<?php echo $resu['imagen'] ?>" alt="">
                </div>
            </button>
        <?php 
        }
        ?>
        </section>

        <a href="./roles/jugadores/lobby.php"> 
        <div class="seleccionarPersonaje">
        <input class="seleccionarBoton" type="button" value="Continuar">
        </div>
    </a>
    </section>

    
    </form>

    <section class="infoPersonaje">
        <div class="info">
            <?php 
            $sqlPersonaje = $con->prepare("SELECT usuario.personaje, personaje.nombrePersonaje, personaje.imagen FROM usuario  
                                        INNER JOIN personaje ON personaje.id_personaje = usuario.personaje 
                                        WHERE usuario.id_usuario = :id_usuario");
            $sqlPersonaje->bindParam(':id_usuario', $id_usuario);
            $sqlPersonaje->execute();
            $personajeActual = $sqlPersonaje->fetch(PDO::FETCH_ASSOC);
            ?>
            
            <p class="nombrePersonaje" id="nombrePersonaje">
            <?php 
            if($personajeActual) {
                echo  $personajeActual['nombrePersonaje'];
            } else {
                echo "Seleccione un personaje";
            }
            ?>
            </p>

        </div>
        <img id="imagenPersonaje" src="<?php echo $personajeActual ? $personajeActual['imagen'] : './assets/img/ff-logo.png'; ?>"  >
    </section>

</section>
<script>
    function mostrar(id){
        event.preventDefault();
        const xmlt = new XMLHttpRequest();

        xmlt.onreadystatechange = function(){
            if(this.status == 200 && this.readyState == 4){
              
                const response = JSON.parse(this.responseText);
                
               
                document.getElementById("nombrePersonaje").innerHTML = response.mensaje;
                
              
                document.getElementById("imagenPersonaje").src = response.imagen;
            }
        }

        xmlt.open("POST","./consultas/asignarPersonaje.php",true);
        xmlt.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlt.send("id=" + id + "&userId=" + <?php echo $id_usuario ?>);
    }
</script>

</body>
</html>