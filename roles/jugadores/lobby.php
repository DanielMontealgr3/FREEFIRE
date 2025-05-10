<?php

include("../../database/database.php");
$conexion = new database;
$con = $conexion->conectar();
session_start();


$username = $_SESSION['username'];
$id_usuario = $_SESSION['id_usuario'];

if (!isset($username) || !isset($id_usuario)) {
    header("Location: ../../session/destruirSession.php");
}

$validar = $con->prepare("SELECT estado FROM usuario WHERE id_usuario = $id_usuario");
$validar->execute();
$estado = $validar->fetchColumn();


if($estado == 2){
    echo "<script>window.location.href = '../../index.php'</script>";

}


$sql = $con->prepare("SELECT * FROM `usuario` 
    INNER JOIN personaje on personaje.id_personaje = usuario.personaje  
     WHERE id_usuario = $id_usuario");
$sql->execute();
$consulta = $sql->fetch(PDO::FETCH_ASSOC);



$limpiar = $con->prepare('DELETE FROM jugadores_en_sala WHERE id_usuario = ?');
$limpiar->execute([$id_usuario]);


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lobby</title>
    <link rel="stylesheet" href="css/lobby.css">
    <link rel="icon" href="/assets/img/pngwing.com-_1_.ico" type="image/x-icon">
</head>

<body>
    <section class="main">

        <section class="colum1">
            <div class="usuario">
                <div class="recuadro">
                    <img class="imgPerfil" src="../.<?php echo $consulta['imagen'] ?>">
                </div>
                <div class="datosUsuario">
                    <h1 class="username"><?php echo $username ?></h1>
                    <h2 class="consultaPersonaje">Personaje: <?php echo $consulta['nombrePersonaje'] ?></h2>
                </div>
            </div>



            <div class="nivel">
                <div class="datosNivel" id="nivel">

                </div>
            </div>


            <a href="../../elegirPersonaje.php">
                <div class="opciones">
                    <div class="nombreOpciones">
                        <img class="imgIconPersona" src="../../assets/img/user (1).png" alt="">
                        <h2>Personajes</h2>

                    </div>
                </div>

                <a href="./armas.php">
                    <div class="opciones">
                        <div class="nombreOpciones">
                            <img class="imgIcon" src="../../assets/img/logopistola.png" alt="">
                            <h2>Armas</h2>

                        </div>
                    </div>

                    <a href="./historialPartidas/partidas.php">
                        <div class="opciones">
                            <div class="nombreOpciones">
                                <img class="imgControl" src="../../assets/img/consola (1).png" alt="">
                                <h2>Partidas</h2>

                            </div>
                        </div>
                    </a>


        </section>
        <section class="colum2">

            <img class="personajeImg" src="../.<?php echo $consulta['imagen'] ?>" alt="">

        </section>
        <section class="colum3">

            <div>
                <a href="../../session/destruirSession.php"><img src="../../assets/img/salir (1).png" width="30px" height="30px" alt=""></a>
                <img class="logo" src="../../assets/img/ff-logo.png" alt="">
            </div>


            <button id="botonAbrir" onclick="validarNivel(<?php echo $consulta['nivel'] ?>)" type="button" class="seleccionDeMapa">
                <div class="seleccionarDiv">
                    <img class="escudo" src="../../assets/img/escudo.png" alt="">
                    <hr style="height: 100%;">
                    <h4 class="tituloSeleccioneMa">SELECCIONE MAPA</h4>
                    <hr style="height: 100%;">
                    <img class="soldado" src="../../assets/img/logosoldado.png" alt="">
                </div>


                <img src="../../assets/img/iniciar.png" class="partida" alt="">
            </button>


        </section>


    </section>


    <section id="eleccionDeMapa" class="eleccionDeMapa">

        <div class="cerrar"><button id="botonCerrar" class="botonCerrar">X</button></div>

        <div>
            <H2 class="tituloSeleccioneMapa">Seleccione Un Mapa</H2>
        </div>

        <div class="mundos">
            <a href="./mundo1.php" id="mundo1" class="mundo-container">
                <img class="mundo" src="./mundos/mapa1.jpeg" alt="">
            </a>
            <a href="./mundo2.php" id="mundo2" class="mundo-container">
                <img class="mundo" src="./mundos/mapa2.png" alt="">
            </a>
        </div>
    </section>


    <script src="./js/lobby.js"></script>

    <script>
        const eleccionDeMapa = document.getElementById('eleccionDeMapa');
        const botonCerrar = document.getElementById('botonCerrar');
        const botonAbrir = document.getElementById('botonAbrir');

        const mundo1 = document.getElementById('mundo1');
        const mundo2 = document.getElementById('mundo2');

        botonAbrir.addEventListener('click', () => {
            eleccionDeMapa.style.display = 'block';
        })

        botonCerrar.addEventListener('click', () => {
            eleccionDeMapa.style.display = 'none';
        })

        const nivel = document.getElementById("nivel")
        const id_usuario = <?php echo json_encode($id_usuario) ?>


        async function nivelProgress() {

            const response = await fetch("./lobbyDependencias/consultarNivel.php", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: id_usuario
            })

            const data = await response.json()


            if (data.nivel == 1) {
                nivel.innerHTML = `

                <h2>NVL.${data.nivel}</h2>
                <progress value="${data.puntos}" max="500"></progress>
                `
            } else if (data.nivel == 2) {
                nivel.innerHTML = `

                <h2>NVL.${data.nivel}</h2>
                <progress value="${data.puntos}" min="500" max="3000"></progress>
                `
            }


        }



        function validarNivel(nivel) {
            const mundo2 = document.getElementById('mundo2');

            if (nivel < 2) {
                mundo2.className = 'mundo-bloqueado';
                mundo2.removeAttribute('href');
                mundo2.addEventListener('click', (e) => {
                    e.preventDefault();
                });
                mundo1.className = 'mundo-container'
                mundo1.href = './mundo1.php'

            } else {
                mundo2.className = 'mundo-container';
                mundo2.href = './mundo2.php';
                mundo1.className = 'mundo-bloqueado';
                mundo1.removeAttribute('href');
                mundo1.addEventListener('click', (e) => {
                    e.preventDefault();
                });
            }
        }

        nivelProgress()
        setInterval(() => {
            nivelProgress()
        }, 3000);
    </script>
</body>

</html>