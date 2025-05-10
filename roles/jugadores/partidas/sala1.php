<?php
include("../../../database/database.php");
$conexion = new database;
$con = $conexion->conectar();
session_start();

$userId = $_SESSION['user_id'];
$username = $_SESSION['username'];
$id_usuario = $_SESSION['id_usuario'];
$id_sala = $_SESSION['id_sala'];

if (!isset($id_usuario)) {
    echo "<script>window.location = '../../../session/destruirSession.php'</script>";
}

$cancelarListo = $con->prepare("UPDATE salas SET estado = 2 WHERE id_sala = 1");
$cancelarListo->execute();

$id_sala = $_GET['id_sala'];

$codigoConsulta = $con->prepare("SELECT `codigoPartida`FROM `jugadores_en_sala`  WHERE id_sala = $id_sala LIMIT 1");
$codigoConsulta->execute();
$codigo = $codigoConsulta->fetchColumn();

$_SESSION['codigo']  = $codigo;

$cantidadjUgadores = $con->prepare("SELECT COUNT(*) FROM jugadores_en_sala WHERE id_sala = $id_sala");
$cantidadjUgadores->execute();
$totalJugadores = $cantidadjUgadores->fetchColumn();

if($totalJugadores == 0){
    echo "<script>window.location = '../lobby.php'</script>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sala De Juego</title>
    <link rel="stylesheet" href="../css/salaDiseños.css"> 
</head>

<body>
    <section class="padre">
        <div class="botonSalir">
            <b class="contador">⏱️ 300</b>
            <a href="../mundo1.php"> <img class="salir" src="../../../assets/img/salir (1).png" alt=""></a>
        </div>

        <section class="logMovimientos"></section>

        <section class="personas" id="personas">
            <!-- Jugadores aqui -->
        </section>

        <?php
        $consultaArmas = $con->prepare('SELECT * FROM armas WHERE nivelRequerido <= 1');
        $consultaArmas->execute();
        $armas = $consultaArmas->fetchAll(PDO::FETCH_ASSOC);

        $consultaUsuario = $con->prepare('SELECT * FROM usuario WHERE id_usuario = ?');
        $consultaUsuario->execute([$id_usuario]);
        $usuario = $consultaUsuario->fetch(PDO::FETCH_ASSOC);
        ?>

        <section class="menuArmas">
            <div class="contenedroDeArmasMain">
                <div class="armas">
                    <div class="containerArma">
                        <b><?php echo $armas[0]['nombreArma'] ?></b>
                        <img class="imgArma" src="../armas/<?php echo $armas[0]['foto'] ?>" alt="">
                    </div>
                </div>

                <div class="armas">
                    <div class="containerArma">
                        <b><?php echo $armas[4]['nombreArma'] ?></b>
                        <img class="imgArma" src="../armas/<?php echo $armas[4]['foto'] ?>" alt="">
                    </div>
                </div>

            </div>
            <div class="vida" id="vidaJugador">

            </div>

        </section>
    </section>

    <section id="menuAtacar" class="menuAtacar">
        <div class="contrincante" id="contrincante">

        </div>

        <h5><button class="cerrar" onclick="cerrar()">X</button></h5>
        <div class="sectionArmasUsuario">

            <div class="scrollArmas">
                <?php foreach ($armas as $armasindex) { ?>
                <div style="display: flex; flex-direction:column;">

                    <div class="armasAtacar">
                        <div style="display: flex; flex-direction:column">
                            <b><?php echo $armasindex['nombreArma'] ?></b>
                            <p> Daño: <?php echo $armasindex['daño'] ?></p>
                        </div>
                        <img class="imgArma" src="../armas/<?php echo $armasindex['foto'] ?>" alt="">
                    </div>

                
                    <button class="atacarHacerDaño" 
                    data-daño="<?php echo $armasindex['daño']; ?>" 
                    data-idarma="<?php echo $armasindex['id_arma']; ?>" 
                    onclick="seleccionarArma(this)">Atacar</button>

                </div>
                <?php } ?>
            </div>

        </div>

    </section>
     

    <!-- CONTENEDOR PARA MOSTRAR LAS PARTES DEL CUERPO -->
     
    <section id="ventanaPartesCuerpo" class="ventanaPartesCuerpo">
    <div class="sectionPartesCuerpo" id="sectionPartesCuerpo"></div>
    </section>

    <!-- scripts opciones basicas para que el juego se vea -->
    <script>
        const menuAtacar = document.getElementById('menuAtacar');
        const contrincante = document.getElementById('contrincante');
        const vidaPersonaje = document.getElementById('vidaPersonaje');
        const sectionPartesCuerpo = document.getElementById('sectionPartesCuerpo');
        const ventanaPartesCuerpo = document.getElementById('ventanaPartesCuerpo');
        

        
        
        let idArmaSeleccionada;  // ID del arma seleccionada
        let idUsuarioAtacante = <?php echo $id_usuario; ?>; // ID del usuario atacante 
        let codigoPartida = "<?php echo $_SESSION['codigo']; ?>"; // Código de la partida

        const url = new URLSearchParams(window.location.search)
        const idSala = url.get('id_sala')
        const vidaDiv = document.getElementById("vidaJugador")
        let contador = 300;

        async function vidaJugador() {

            const consultarVida = await fetch("./vidaJugador.php", {

                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                }
            })

            const data = await consultarVida.json()
            vidaDiv.innerHTML = `<b>Vida Actual: ${data.vida} </b>`

            actualizarVida(data.vida);

            function actualizarVida(vida) {

                let porcentaje = Math.max(vida, 0);
                let color1 = porcentaje > 50 ? 'green' : porcentaje > 20 ? 'yellow' : 'red';
                let color2 = 'gray';
                vidaDiv.style.background = `linear-gradient(to right, ${color1} ${porcentaje}%, ${color2} ${porcentaje}%)`;
            }

        }


        // ################################################################################################
        // ################################################################################################
        // ################################################################################################
        // ################################################################################################


        async function abrirMenu(idUsuario) {

            conseguirId = idUsuario

            const response = await fetch('./usuarioAtacar.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: idUsuario
            })


            const data = await response.json();
            const informacionUsuario = data.usuario;


            if (!informacionUsuario.vida == 0) {

                contrincante.innerHTML = `
            

                    <h1 class="nombreUsuario">${informacionUsuario.username}</h1>
                    <progress value="${informacionUsuario.vida}" max="100"></progress>

                    <div class="containerAtacar">
                        <div class="personaAtacada">
                            <img class="personaAtacagaImg" src="../../../${informacionUsuario.imagen} ?>" alt="">
                        </div>
                    </div>

                    `
                menuAtacar.style.display = 'flex';
            } else {
                menuAtacar.style.display = 'none';
            }


        }


        // ################################################################################################
        // ################################################################################################
        // ################################################################################################
        // ################################################################################################




        const personas = document.getElementById('personas');

        async function vidaPersonajeFuncion() {


            const response = await fetch('./listadoJugadores.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: idSala
            })

            const data = await response.json();
            const jugadores = data.jugadores;


            personas.innerHTML = '';
            jugadores.forEach((jugadores, index) => {

                if (!jugadores.vida == 0) {

                    personas.innerHTML += `
                    
                    <div class="Jugador1">
                        <div class="nombreJugador">
                        <b class="username"> ${jugadores.username} </b>
                        <progress id="vidaPersonaje" value="${jugadores.vida}" max="100"></progress>
                            <div class="divImgPersona">
                                <img class="imgPersona" src="../../../${jugadores.imagen}" alt="">
                            </div>
                            <button class="atacar" onclick="abrirMenu(${jugadores.id_usuario})">Atacar</button>
                            </div>
                        </div>
                        
                        `

                }
            });

        }

        // ################################################################################################
        // ################################################################################################
        // ################################################################################################
        // ################################################################################################

        async function seleccionarArma(element) {
        dañoArma = element.dataset.daño;
        idArmaSeleccionada = element.dataset.idarma;
            
        try {
            const response = await fetch('./Partecuerpo.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    idArma: idArmaSeleccionada
                })
            });
        
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
        
            const data = await response.json();
        
            if (data.partesCuerpo && Array.isArray(data.partesCuerpo)) {
            
                // CONTENEDOR TITULO PARA CSS
                let partesCuerpoHTML = `
                    <div class="tituloContainer">
                        <b>Selecciona a dónde atacar:</b>
                    </div>
                
                    <div class="partesCuerpoBotonesContainer">
                `;
            
                data.partesCuerpo.forEach(parte => {
                    let imagenSrc = '';
                
                    if (parte.id_zonaAtaque === 1) {
                        imagenSrc = '../../../assets/img/cabeza.png';
                    } else if (parte.id_zonaAtaque === 2) {
                        imagenSrc = '../../../assets/img/pecho.png';
                    } else if (parte.id_zonaAtaque === 3) {
                        imagenSrc = '../../../assets/img/brazo.png';
                    } else {
                        imagenSrc = '../../../assets/img/pierna.png';
                    }
                
                    console.log("Ruta de la imagen:", imagenSrc);
                
                    partesCuerpoHTML += `
                        <button class="parteCuerpoButton" name="parteCuerpoSeleccionada" data-idZonaAtaque="${parte.id_zonaAtaque}" onclick="atacar(this, ${parte.id_zonaAtaque})">
                            <img src="${imagenSrc}" alt="${parte.nombreZona}" class="partesImg">
                            <span class="nombre-zona">${parte.nombreZona}</span>
                        </button>
                    `;
                });
            
                partesCuerpoHTML += `</div>`;
            
                sectionPartesCuerpo.innerHTML = partesCuerpoHTML;
                ventanaPartesCuerpo.style.display = 'flex';
            }
        
        } catch (error) {
            console.error("Error fetching partes del cuerpo:", error);
            sectionPartesCuerpo.innerHTML = `<p>Error al cargar partes del cuerpo: ${error}</p>`;
        }
    }

        // ################################################################################################
        // ################################################################################################
        // ################################################################################################
        // ################################################################################################

        async function vivoNoVivo() {

            const estadoUsuario = await fetch('./vivoNoVivo.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: idSala
            })

            const datos = await estadoUsuario.json();

            if (datos.vida == 0) {
                window.location = "./Resultados/Perdedor.php?id_sala=" + idSala
            } else {
                verificarGanador()
            }


        }
        // ################################################################################################
        // ################################################################################################
        // ################################################################################################
        // ################################################################################################



        async function verificarGanador() {
            const response = await fetch('./verificarVivos.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: idSala
            });
            const data = await response.json();
            const jugadores = data.id_usuario;


            if (data.length === 1) {
                window.location = "./Resultados/Ganador.php" + "?id_sala=" + idSala
            }
        }


        // ################################################################################################
        // ################################################################################################
        // ################################################################################################
        // ################################################################################################

        async function atacar(element, idZonaAtaque) {  
        const dataAEnviar = {  
            daño: dañoArma,  
            idUsuarioAtacado: conseguirId,  
            idArma: idArmaSeleccionada,  
            idZonaAtaque: idZonaAtaque,  
            idUsuarioAtacante: idUsuarioAtacante,  
            codigoPartida: codigoPartida  
        };  

        const segundaRespuesta = await fetch("./hacerDaño.php", {  
            method: 'POST',  
            headers: { 'Content-Type': 'application/json' },  
            body: JSON.stringify(dataAEnviar)  
        });  

        const respuestaJson = await segundaRespuesta.json();  

        // OCULTAR LA VENTANA
        ventanaPartesCuerpo.style.display = 'none';  
        // OCULTAR EL MENU DE ARMAS
        menuAtacar.style.display = 'none'; 


        }

        // ################################################################################################
        // ################################################################################################
        // ################################################################################################
        // ################################################################################################
    

        function cerrar() {
            menuAtacar.style.display = 'none';
        }

        vidaPersonajeFuncion()

        setInterval(() => {
            vivoNoVivo()
            vidaPersonajeFuncion()
            vidaJugador()


            contador--
            document.querySelector('.contador').innerHTML = `⏱️ ${contador}`;
            if (contador === 0) {
                clearInterval(intervalo)
                window.location.href = '../mundo1.php'
            }


            localStorage.setItem('contador', contador)

        }, 1000);
    </script>

</body>

</html>