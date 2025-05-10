<?php
require_once("../../database/database.php");
$conexion = new database;
$con = $conexion->conectar();
session_start();

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rol = $_POST['rol'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $contraseña = $_POST['contraseña'];
    $estado = 2; 
   
    if (empty($rol) || empty($username) || empty($email) || empty($contraseña)) {
        $response['message'] = "Complete todos los campos";
    } else {
       
        $validar = $con->prepare("SELECT * FROM usuario WHERE username = :username OR correo = :email");
        $validar->bindParam(":username", $username, PDO::PARAM_STR);
        $validar->bindParam(":email", $email, PDO::PARAM_STR);
        $validar->execute();
        $fila = $validar->fetch(PDO::FETCH_ASSOC);

        if ($fila) {
            $response['message'] = "El usuario o correo ya están registrados";
        } else {
            $contraseñaEncripta = password_hash($contraseña, PASSWORD_DEFAULT);
            $insert = $con->prepare("INSERT INTO usuario (username, correo, contrasena, puntos, nivel, vida, estado, id_rol) 
                                    VALUES (:username, :email, :password, 100, 1, 100, :estado, :rol)");
            $insert->bindParam(':username', $username);
            $insert->bindParam(':email', $email);
            $insert->bindParam(':password', $contraseñaEncripta);
            $insert->bindParam(':estado', $estado, PDO::PARAM_INT);
            $insert->bindParam(':rol', $rol, PDO::PARAM_INT);

            if ($insert->execute()) {
                $response['success'] = true;
                $response['message'] = "Usuario creado con éxito.";
            } else {
                $response['message'] = "Hubo un error al crear el usuario.";
            }
        }
    }
    echo json_encode($response);
    exit;
}
?>
