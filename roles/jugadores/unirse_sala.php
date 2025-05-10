<?php
include("../../database/database.php");
$conexion = new database;
$con = $conexion->conectar();
session_start();

header('Content-Type: application/json');

// Validate session
if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['username'])) {
    http_response_code(403); // Unauthorized
    echo json_encode(['success' => false, 'message' => 'Sesión no válida.']);
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
$nombre_usuario = $_SESSION['username'];

// Validate request method and input
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id_sala'])) {
    http_response_code(400); // Bad request
    echo json_encode(['success' => false, 'message' => 'Solicitud inválida.']);
    exit;
}

$id_sala = $_POST['id_sala'];

try {
    // Check room state
    $selectEstado = $con->prepare("SELECT estado FROM salas WHERE id_sala = ?");
    $selectEstado->execute([$id_sala]);
    $infoEstado = $selectEstado->fetchColumn();

    if ($infoEstado == 2) {
        // Si la sala está desactivada, verificar cuántos jugadores hay en la sala
        $contarJugadores = $con->prepare('SELECT COUNT(*) as total FROM jugadores_en_sala WHERE id_sala = ?');
        $contarJugadores->execute([$id_sala]);
        $totalJugadores = $contarJugadores->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Si no hay jugadores, desbloquear la sala
        if ($totalJugadores == 0) {
            $desbloquearSala = $con->prepare("UPDATE salas SET estado = 1 WHERE id_sala = ?");
            if ($desbloquearSala->execute([$id_sala])) {
                // La sala ha sido desbloqueada, continuamos con el proceso
                $infoEstado = 1; // Actualizamos el estado para la ejecución posterior
            } else {
                throw new Exception('Error al desbloquear la sala.');
            }
        } else {
            // Si hay jugadores, la sala sigue bloqueada
            echo json_encode(['success' => false, 'desactivar' => true, 'message' => 'La sala está desactivada.']);
            exit;
        }
    }

    // Check if user is already in the room
    $verificar = $con->prepare('SELECT * FROM jugadores_en_sala WHERE id_sala = ? AND id_usuario = ?');
    $verificar->execute([$id_sala, $id_usuario]);

    if ($verificar->rowCount() > 0) {
        echo json_encode(['success' => false, 'message' => 'Ya estás en la sala.']);
        exit;
    }

    // Count current players in the room
    $contar = $con->prepare('SELECT COUNT(*) as total FROM jugadores_en_sala WHERE id_sala = ?');
    $contar->execute([$id_sala]);
    $total = $contar->fetch(PDO::FETCH_ASSOC)['total'];

    if ($total >= 6) {
        echo json_encode(['success' => false, 'message' => 'La sala está llena.']);
        exit;
    }

    // Generate a unique random code for the game
    $random = random_int(0, 10000000);

    // Insert user into the room
    $insert = $con->prepare('INSERT INTO jugadores_en_sala (id_sala, id_usuario, nombre_usuario, codigoPartida) VALUES (?, ?, ?, ?)');
    if ($insert->execute([$id_sala, $id_usuario, $nombre_usuario, $random])) {
        echo json_encode(['success' => true, 'message' => 'Te uniste a la sala.', 'jugadores' => $total + 1]);
    } else {
        throw new Exception('Error al unirse a la sala.');
    }
} catch (PDOException $e) {
    http_response_code(500); // Internal server error
    echo json_encode(['success' => false, 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
} catch (Exception $e) {
    http_response_code(500); // Internal server error
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>