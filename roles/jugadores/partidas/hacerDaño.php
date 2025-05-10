
<?php
include("../../../database/database.php");
$conexion = new database;
$con = $conexion->conectar();
session_start();

header('Content-Type: application/json');

try {
    if (!isset($_SESSION['id_usuario'])) {
        throw new Exception("User is not logged in.");
    }

    $data = json_decode(file_get_contents('php://input'), true);

    $idUsuarioAtacado = intval($data['idUsuarioAtacado']);
    $daño = intval($data['daño']);
    $idArma = intval($data['idArma']);
    $idZonaAtaque = intval($data['idZonaAtaque']);

    if ($idZonaAtaque == 1) {
        $dañoFinal = 20;
        $mensajeAtaque = "¡Golpe en la cabeza!";
    } elseif ($idZonaAtaque == 2) {
        $dañoFinal = 15;
        $mensajeAtaque = "¡Golpe en el torso!";
    } elseif ($idZonaAtaque == 3) {
        $dañoFinal = 5;
        $mensajeAtaque = "¡Golpe en las piernas!";
    } else {
        $dañoFinal = 10;
        $mensajeAtaque = "¡Golpe en los brazos!";
    }

    // Update vida
    $atacar = $con->prepare("UPDATE usuario SET vida = GREATEST(vida - ?, 0) WHERE id_usuario = ?");
    $atacar->execute([$dañoFinal, $idUsuarioAtacado]);

    // Update puntos del atacante
    $actualizarPuntos = $con->prepare("UPDATE usuario SET puntos = puntos + ? WHERE id_usuario = ?");
    $actualizarPuntos->execute([$dañoFinal, $_SESSION['id_usuario']]);

    // Update puntos del atacado
    $actualizarPuntosAtacado = $con->prepare("UPDATE usuario SET puntos = GREATEST(puntos - ?, 0) WHERE id_usuario = ?");
    $actualizarPuntosAtacado->execute([$dañoFinal, $idUsuarioAtacado]);

    // Guardar datos de los atacques en ataques
    $insertarAtaque = $con->prepare("INSERT INTO ataque (id_usuario, codigoPartida, id_arma, id_atacado, id_zonaAtaque) VALUES (?, ?, ?, ?, ?)");
    $insertarAtaque->execute([$_SESSION['id_usuario'], $_SESSION['codigo'], $idArma, $idUsuarioAtacado, $idZonaAtaque]);

    echo json_encode([
        'success' => true,
        'message' => $mensajeAtaque,
        'dañoInfligido' => $dañoFinal,
        'idZonaAtaque' => $idZonaAtaque
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>