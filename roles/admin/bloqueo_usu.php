<?php
require_once("../../database/database.php");
$conexion = new database;
$con = $conexion->conectar();
session_start();
include('menu.html');  
?>

<?php
if (isset($_POST['update'])) {
    $usuarios = $_POST['usuarios'];
    $estados = $_POST['estado'];   
    $correos = $_POST['correo']; 

    $correos_a_enviar = [];
    $hay_actualizaciones = false;

    // BUCLE PARA RECORRER CADA USUARIO

    for ($i = 0; $i < count($usuarios); $i++) {
        $id_usuario = $usuarios[$i];
        $nuevo_estado = $estados[$i];
        $correo = $correos[$i];

        // OBTENGO EL ESTADO ACTUAL DE LA BASE DE DATOS
        $sql_actual = $con->prepare("SELECT estado FROM usuario WHERE id_usuario = :id_usuario");
        $sql_actual->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $sql_actual->execute();
        $fila_actual = $sql_actual->fetch(PDO::FETCH_ASSOC);
        
        if ($fila_actual) {
            $estado_actual = (int) $fila_actual['estado'];
            $nuevo_estado = (int) $nuevo_estado;

            //CONDICIONAL PARA QUE SOLO SE ENVIEN CORREOS CUANDO EL ESTADO SEA CAMBIADO A =1
            if ($estado_actual !== $nuevo_estado) {
                if ($nuevo_estado === 1) {
                    $correos_a_enviar[] = $correo; 
                }
                $hay_actualizaciones = true; // Hay al menos una actualización
            }

            // ACTUALIZAR EN LA BASE DE DATOS
            $update = $con->prepare("UPDATE usuario SET estado = :estado WHERE id_usuario = :id_usuario"); 
            $update->bindParam(':estado', $nuevo_estado, PDO::PARAM_INT);
            $update->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $update->execute();
        }
    }

    // MENSAJE DE ALERTA SI NO HAY ACTUALIZACIONES
    if (!$hay_actualizaciones) {
        echo '<script>alert("No hay nada para actualizar.");</script>';
        echo '<script>window.location.href = "bloqueo_usu.php";</script>';
    } else {
        echo '<script>alert("Actualización exitosa");</script>';
        
        // SE ENVIAN CORREOS SOLO si correos_a_enviar TIENE REGISTROS
        if (!empty($correos_a_enviar)) {
            $correos_str = implode(',', $correos_a_enviar);  
            echo '<script>window.location.href = "enviar_correo.php?correos=' . $correos_str . '";</script>';
        } else {
            echo '<script>window.location.href = "bloqueo_usu.php";</script>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ver Usuarios Bloqueados</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<div class="contenedor_principal">
    <div class="contenedor_body">
        <form method="POST">
        <h1 class="titulo">USUARIOS BLOQUEADOS</h1>
        <table border="1">
            <tr>
                <th>ID_USUARIO</th>
                <th>USERNAME</th>
                <th>CORREO</th>
                <th>CONTRASEÑA</th>
                <th>PUNTOS</th>
                <th>NIVEL</th>
                <th>VIDA</th>
                <th>U.INGRESO</th>
                <th>ESTADO</th>
                <th>PERSONAJE</th>
                <th>ID_ROL</th>
            </tr>

            <?php
            $sql = $con->prepare("SELECT * FROM usuario 
                INNER JOIN estado ON usuario.estado = estado.id_estado 
                INNER JOIN roles ON usuario.id_rol = roles.id_rol 
                WHERE estado.id_estado = 2;");
            $sql->execute();
            $fila = $sql->fetchAll(PDO::FETCH_ASSOC);
    
            foreach ($fila as $resu) {
            ?>

            <tr>
                <td><input class="texto_registros" type="text" readonly value="<?php echo htmlspecialchars($resu['id_usuario']); ?>"></td>
                <td><input class="texto_registros" type="text" readonly value="<?php echo htmlspecialchars($resu['username']); ?>"></td>
                <td><input class="texto_registros" type="text" name="correo[]" value="<?php echo htmlspecialchars($resu['correo']); ?>"></td>
                <td><input class="texto_registros" type="text" readonly value="<?php echo htmlspecialchars($resu['contrasena']); ?>"></td>
                <td><input class="texto_registros" type="text" readonly value="<?php echo htmlspecialchars($resu['puntos']); ?>"></td>
                <td><input class="texto_registros" type="text" readonly value="<?php echo htmlspecialchars($resu['nivel']); ?>"></td>
                <td><input class="texto_registros" type="text" readonly value="<?php echo htmlspecialchars($resu['vida']); ?>"></td>
                <td><input class="texto_registros" type="text" readonly value="<?php echo htmlspecialchars($resu['ultimoIngreso']); ?>"></td>

                <td>
                    <select name="estado[]">
                        <option value="<?php echo htmlspecialchars($resu['id_estado']); ?>"><?php echo htmlspecialchars($resu['estado']); ?></option>
                        <?php
                        $sql_estado = $con->prepare("SELECT * FROM estado");
                        if ($sql_estado->execute()) {
                            while ($fila_estado = $sql_estado->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value='" . htmlspecialchars($fila_estado['id_estado']) . "'>" . htmlspecialchars($fila_estado['estado']) . "</option>";
                            }
                        }
                        ?>
                    </select>
                </td>

                <td><input class="texto_registros" type="text" readonly value="<?php echo htmlspecialchars($resu['personaje']); ?>"></td>

                <td>
                    <input class="texto_registros" type="text" readonly value="<?php echo htmlspecialchars($resu['rol']); ?>">
                </td>
                
                <!-- CAMPO OCULTO PARA GUARDAR LOS ID_USUARIO-->
                <input type="hidden" name="usuarios[]" value="<?php echo htmlspecialchars($resu['id_usuario']); ?>">

            </tr>

            <?php
            }
            ?>
            </table>
    
            <tr><td><input type="submit" name="update" value="Actualizar"></td></tr>
            </form>
            </div>
        </div>
        
        </body>
        </html>