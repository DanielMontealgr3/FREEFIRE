<?php 
include_once("./database/database.php");
$conexion = new database;
$con = $conexion->conectar();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $response = array('success' => false, 'message' => '');
    
    $username = ($_POST['username']);
    $email = ($_POST['email']);
    $contraseña = ($_POST['contraseña']);
    $estado = 2;
    $rol = 2;

    $validar = $con->prepare("SELECT * FROM usuario WHERE username = :username OR correo = :email");
    $validar->bindParam(":username", $username, PDO::PARAM_STR);
    $validar->bindParam(":email", $email, PDO::PARAM_STR);
    $validar->execute();
    $fila = $validar->fetch(PDO::FETCH_ASSOC);
    
    if ($fila) {
        $response['message'] = "El usuario o correo ya están registrados";
    } else if(empty($username) || empty($email) || empty($contraseña)) {
        $response['message'] = "Complete todos los campos";
    } else {
        $contraseñaEncripta = password_hash($contraseña, PASSWORD_DEFAULT);
        
        $insert = $con->prepare("INSERT INTO usuario (username, correo, contrasena, puntos, nivel, vida, estado, id_rol) 
                                VALUES (:username, :email, :password, 100, 1, 100, :estado, :rol)");
        $insert->bindParam(":username", $username, PDO::PARAM_STR);
        $insert->bindParam(":email", $email, PDO::PARAM_STR);
        $insert->bindParam(":password", $contraseñaEncripta, PDO::PARAM_STR);
        $insert->bindParam(":estado", $estado, PDO::PARAM_INT);
        $insert->bindParam(":rol", $rol, PDO::PARAM_INT);

        if ($insert->execute()) {
            $response['success'] = true;
            $response['message'] = "Registro exitoso";
        } else {
            $response['message'] = "Hubo un error en el registro";
        }
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?>