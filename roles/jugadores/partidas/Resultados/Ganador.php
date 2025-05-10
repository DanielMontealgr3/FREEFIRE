<?php 
include("../../../../database/database.php");
$conexion = new database;
$con = $conexion->conectar();
session_start();

$id_usuario = $_SESSION['id_usuario'];

$_SESSION['ganador'] = $id_usuario;


if (!isset($id_usuario)) {
    echo "<script>window.location = '../../../../session/destruirSession.php'</script>";
}

$id_sala = $_SESSION['id_sala'];

$actualizarEstado = $con->prepare("UPDATE salas SET estado = 1 WHERE id_sala = $id_sala");
$actualizarEstado->execute();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganador!</title>
    <link rel="stylesheet" href="css/stilos.css">
</head>
<body>



<section class="fondo">

    <section class="contenedorNegro">

        <div class="booya-salir">
            <div class="divBooya">
                <img class="booyaImg" src="imgs/BOOYAH! (1).png" alt="">
            </div>

            <a class="regresarLobby" href="../../lobby.php"><p>Regresar A La Lobby</p></a>
        </div>


        <div class="ganadorInfo" id="ganadorInfo">
            
    
        </div>
    </section>

</section>



</body>



<script>

        

        const url = new URLSearchParams(window.location.search)
        const idSala = url.get('id_sala')

        const id_usuario = <?php echo json_encode($id_usuario); ?>;


        async function registroPartida() {
            const response = await fetch('../registrarPartida/registrarPartida.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: idSala
            });

            


        }


        registroPartida()


        setInterval(() => {
            
            mostrarGanador()
        }, 100);

        async function mostrarGanador() {

            const ganadorInfo = document.getElementById("ganadorInfo")
            const response = await fetch("./mostrarGanador.php", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                }
                
            })

            const data = await response.json()
            const contador  = localStorage.getItem('contador')

            ganadorInfo.innerHTML = `
                <div class="infoGanador">
                <p>🏆 Ganador : ${data.username} </p>
                <p>⏱️ Duracion : ${contador} </p>
                <p>💯 Puntos : ${data.puntos}</p>
                <p>🎚️ Nivel: ${data.nivel} </p>
                <p>📊 Vida: ${data.vida} </p>

            </div>
            
                <img src="../../../.${data.imagen}" class="personaje" alt="">
            `


        }

        async function sacarDeSala() {

            const sacarDeSalas = await fetch('./sacarDeSala.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: id_usuario
            })

            const data = await sacarDeSalas.json()

        }

        sacarDeSala()

   
</script>
</html>