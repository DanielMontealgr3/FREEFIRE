<?php 
 include("../../../database/database.php");
 $conexion = new database;
 $con = $conexion->conectar();
 session_start();


 $idUsuario = json_decode(file_get_contents('php://input',true));


    $select = $con->prepare("SELECT * FROM usuario INNER JOIN personaje on personaje.id_personaje = usuario.personaje WHERE id_usuario = $idUsuario");
    $select->execute();
    $usuario = $select->fetch(PDO::FETCH_ASSOC);


    echo json_encode(['success' => true, "usuario" => $usuario]);

?>