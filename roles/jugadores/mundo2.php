<?php
include("../../database/database.php");
$conexion = new database;
$con = $conexion->conectar();
session_start();

$userId = $_SESSION['user_id'];
$username = $_SESSION['username'];
$id_usuario = $_SESSION['id_usuario'];

if (!isset($username) || !isset($id_usuario)) {
    header("Location: ../../session/destruirSession.php");
}


$consulta = $con->prepare("SELECT * FROM usuario WHERE id_usuario = $id_usuario");
$consulta->execute();
$vida = $consulta->fetch(PDO::FETCH_ASSOC);

if ($vida['puntos'] < 500) {
    $actualizarNivel = $con->prepare("UPDATE usuario SET  nivel = 1 WHERE id_usuario = $id_usuario ");
    $actualizarNivel->execute();
} else if ($vida['puntos'] >= 500) {
    $actualizarNivel = $con->prepare("UPDATE usuario SET  nivel = 2 WHERE id_usuario = $id_usuario");
    $actualizarNivel->execute();
}


if ($vida['nivel'] != 2) {
    echo "<script>window.location = './lobby.php'</script>";
}

$limpiar = $con->prepare('DELETE FROM jugadores_en_sala WHERE id_usuario = ?');
$limpiar->execute([$id_usuario]);

$consulta = $con->prepare('SELECT salas.*, mundos.nombreMundo, 
        (SELECT COUNT(*) FROM jugadores_en_sala WHERE jugadores_en_sala.id_sala = salas.id_sala) AS jugadores 
        FROM salas 
        INNER JOIN mundos ON mundos.id_mundo = salas.id_mundo 
        WHERE mundos.id_mundo = 2');
$consulta->execute();
$salas = $consulta->fetchAll(PDO::FETCH_ASSOC);






?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elegir Sala</title>
    <link rel="stylesheet" href="css/mundos.css">
</head>

<body>

    <section class="padre">
        <div class="div1">
            <a href="" class="salas1">
                <h1>Salas Nivel 2</h1>
            </a>
        </div>

        <div class="div2">
            <div class="botonSalir">
                <a href="./lobby.php"> <img class="salir" src="../../assets/img/salir (1).png" alt=""></a>
            </div>

            <img width="200px" height="40px" src="../../assets/img/ff-logo.png" alt="">
        </div>

        <div class="div3">
            <h1>Seleccione Una Sala</h1>
        </div>

        <div class="div4">
            <?php foreach ($salas as $sala) { ?>
                <div class="salaDisponible" id="sala-<?php echo $sala['id_sala']; ?>">
                    <div>
                        <img class="mapa" src="./mundos/mapa1.jpeg" height="80px" width="80px" alt="">
                    </div>

                    <div class="infoIdSala">
                        <b>Sala De Juego</b>
                        <p>ID: <?php echo $sala['nombreMundo'] . ' - ' . $sala['id_sala']; ?></p>
                    </div>

                    <div class="jugadoresActuales">
                        <b>Número De Jugadores</b>
                        <p id="jugadores-<?php echo $sala['id_sala']; ?>">
                            <?php echo $sala['jugadores']; ?> / 5
                        </p>
                    </div>

                    <div class="divUnirse">
                        <button class="unirse" onclick="unirseSala(<?php echo $sala['id_sala']; ?>, '<?php echo $sala['direccion']; ?>')">
                            Unirse
                        </button>
                    </div>
                </div>
            <?php } ?>
        </div>
    </section>

    <script>
         function unirseSala(idSala, direccion) {
            fetch('unirse_sala.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `id_sala=${idSala}`
                })
                .then(response => response.json())
                .then(data => {
                    const boton = document.querySelector(`button[onclick="unirseSala(${idSala}, '${direccion}')"]`);

                    if (data.desactivar) {
                        boton.disabled = true; 
                        boton.style.backgroundColor = "red"; 
                        boton.style.cursor = "not-allowed"; 
                        boton.style.opacity = "0.6"; 
                        boton.textContent = 'Sala Llena'
                    } else {
                        boton.disabled = false;
                        boton.style.backgroundColor = "";
                        boton.style.cursor = "pointer";
                        boton.style.opacity = "1";
                    }


                    if (data.success) {
                        actualizarJugadores();
                        window.location = direccion + `?id_sala=${idSala}`;
                    }
                })
                .catch(error => console.error('Error:', error));
        }


        function actualizarJugadores() {
            fetch('obtener_jugadores.php')
                .then(response => response.json())
                .then(data => {
                    data.forEach(sala => {
                        const elemento = document.getElementById(`jugadores-${sala.id_sala}`);
                        if (elemento) {
                            elemento.innerText = `${sala.jugadores} / 5`;
                        }
                    });
                })
                .catch(error => console.error('Error al actualizar jugadores:', error));
        }


        setInterval(actualizarJugadores, 1000);
    </script>

</body>

</html>