<?php
include("../../../database/database.php");
$conexion = new database;
$con = $conexion->conectar();
session_start();

if (!isset($_GET['id_sala']) || !isset($_SESSION['id_usuario'])) {

    header("Location: ../../../session/destruirSession.php");
}
$id_usuario = $_SESSION['id_usuario'];

$id_sala = $_GET['id_sala'];
$_SESSION['id_sala'] = $id_sala;

$darVida = $con->prepare("UPDATE usuario SET vida = 100 WHERE id_usuario = :id_usuario");
$darVida->bindParam("id_usuario", $id_usuario);
$darVida->execute();


$consulta = $con->prepare("SELECT * FROM usuario WHERE id_usuario = $id_usuario");
$consulta->execute();
$vida = $consulta->fetch(PDO::FETCH_ASSOC);

if (($id_sala == 1 || $id_sala == 2) && $vida['nivel'] !== 1) {
    echo "<script>window.location = '../mundo1.php'</script>";
} else if (($id_sala == 3 || $id_sala == 4) && $vida['nivel'] !== 2) {
    echo "<script>window.location = '../mundo2.php'</script>";
}



?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sala de Espera - ID <?php echo $id_sala; ?></title>
    <link rel="stylesheet" href="sala_espera.css">
    <!-- Fuente de videojuego -->
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
</head>

<body>
    <div class="video-container">
        <video autoplay loop muted src="../../../assets/fondos/salaDeEspera.mp4" type="video/mp4"></video>
    </div>

    <div class="contenedor">
        <div style="display:flex;align-items: center;justify-content: space-between; ">
            <a href="../lobby.php"><img src="../../../assets/img/salir (1).png" width="30px" height="30px" alt=""></a>
            <h1>Sala de Espera - ID <?php echo $id_sala; ?></h1>
        </div>

        <div class="jugadores-lista" id="jugadores"></div>
        <button id="estado" onclick="marcarListo()">¡Estoy Listo!</button>
    </div>

    <!-- Sección del Timer -->
    <div id="timer-overlay" class="hidden">
        <div id="timer-text">La partida empieza en <span id="countdown">10</span> segundos...</div>
    </div>

    <script>
        const idsala = <?php echo json_encode($id_sala); ?>;
        const divjugadores = document.getElementById("jugadores");
        const estado = document.getElementById("estado");

        const timerOverlay = document.getElementById("timer-overlay");
        timerOverlay.style.display = "none"


        const timerText = document.getElementById("timer-text");
        let intervaloContador = null;
        let currentUserListo = false;

        async function mostrarUsuarios() {
            const response = await fetch('./jugadoresEnEspera.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: idsala
            });

            const data = await response.json();
            divjugadores.innerHTML = "";

            data.forEach(persona => {
                divjugadores.innerHTML += `
                    <div class="jugador ${persona.listo ? 'listo' : 'no-listo'}">
                        <img src="../../../${persona.imagen}" alt="Avatar" class="avatar">
                        <p class="p">${persona.username}</p>
                        <p style="font-size:0.7rem;">Puntos: ${persona.puntos}</p>
                        <span class="estado">${persona.listo ? 'Listo' : 'Esperando'}</span>
                    </div>
                `;

                if (persona.id_usuario === <?php echo json_encode($id_usuario); ?>) {
                    currentUserListo = persona.listo;
                }
            });

            estado.innerHTML = currentUserListo ? "¡No Estoy Listo!" : "¡Estoy Listo!";

            verificarInicio(data);
        }

        function marcarListo() {
            fetch('marcar_listo.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `id_sala=${idsala}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        currentUserListo = !currentUserListo;
                        mostrarUsuarios();
                    }
                });
        }

        function verificarInicio(jugadores) {
            const listos = jugadores.filter(j => j.listo).length;
            if (listos === jugadores.length && jugadores.length > 1) {
                if (!intervaloContador) {
                    estado.disabled = true;
                    iniciarContador();
                }
            }
        }

        function iniciarContador() {
            timerOverlay.classList.remove("hidden"); 
            timerOverlay.style.display = ""
            let contador = 10;
            intervaloContador = setInterval(() => {
                document.getElementById("countdown").innerText = contador;
                if (contador === 0) {
                    clearInterval(intervaloContador);
                    window.location.href = `../partidas/sala${idsala}.php?id_sala=${idsala}`;
                }
                contador--;
            }, 1000);
        }

        mostrarUsuarios();
        
        // setInterval(mostrarUsuarios, 1000);
    </script>
</body>

</html>