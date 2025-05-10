<?php
include("../../../../database/database.php");
$conexion = new database;
$con = $conexion->conectar();
session_start();

$id_usuario = $_SESSION['id_usuario'];
$username = $_SESSION['username'];

if (!isset($username) || !isset($id_usuario)) {
    echo "<script>window.location = '../../../../session/destruirSession.php'</script>";
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¡Perdiste!</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
            font-family: 'Arial', sans-serif;
            background: #000;
            color: #fff;
        }

        .container {
            position: relative;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background: black
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
        }

        .message {
            text-align: center;
            z-index: 1;
        }

        .message h1 {
            font-size: 4rem;
            margin: 0;
            color: #eeb72b;
            text-shadow: 0 0 10px #eeb72b, 0 0 20px #eeb72b;
            animation: glow 1.5s infinite alternate;
        }

        .message p {
            font-size: 1.5rem;
            margin: 10px 0;
        }

        .btn {
            background:#eeb72b;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 1.2rem;
            cursor: pointer;
            border-radius: 5px;
            transition: background 0.3s ease;
            color: black;
        }

        .btn:hover {
            background:rgb(247, 213, 127);
        }

        @keyframes glow {
            from {
                text-shadow: 0 0 10px #eeb72b, 0 0 20px #ff4444, 0 0 30px #ff4444;
            }
            to {
                text-shadow: 0 0 20px #ff4444, 0 0 30px #ff4444, 0 0 40px #eeb72b;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="overlay"></div>
        <div class="message">
            <h1>¡Perdiste!</h1>
            <p>Mejor suerte la próxima vez <?php echo $username ?>.</p>
            <button class="btn" onclick="regresarASala()">Regresar A La Lobby</button>
        </div>
    </div>

    <script>
        const url = new URLSearchParams(window.location.search);
        const idSala = url.get('id_sala');
        const id_usuario = <?php echo json_encode($id_usuario); ?>;

        async function registroPartida() {
            const response = await fetch('../registrarPartida/registrarPartida.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body:  idSala 
            });

            const data = await response.json();
            console.log(data);
        }

        async function sacarDeSala() {
            const response = await fetch('./sacarDeSala.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: id_usuario
            });

            const data = await response.json();
            console.log(data);
        }

        function regresarASala() {
            window.location.href = `../../lobby.php`;
        }

        registroPartida();
        sacarDeSala();
    </script>
</body>

</html>