<?php
require_once("../../database/database.php");
$conexion = new database;
$con = $conexion->conectar();
session_start();
include('menu.html');

if (isset($_POST['update'])) {
    $usuarios = $_POST['usuarios'];
    $puntos = $_POST['puntos'];  
    $niveles = $_POST['nivel'];
    $vidas = $_POST['vida']; 
    $estados = $_POST['estado'];   
    $roles = $_POST['rol'];  
    $correos = $_POST['correo']; 

    $correos_a_enviar = [];
    $hay_actualizaciones = false; // VARIABLE PARA VERIFICAR SI HAY ACTUALIZACIONES

    for ($i = 0; $i < count($usuarios); $i++) {
        $id_usuario = $usuarios[$i];
        $puntos_usuario = $puntos[$i];
        $nivel_usuario = $niveles[$i];
        $vida_usuario = $vidas[$i];
        $nuevo_estado = $estados[$i];
        $rol = $roles[$i];
        $correo = $correos[$i];

        // OBTENGO EL ESTADO ACTUAL DE LA BASE DE DATOS
        $sql_actual = $con->prepare("SELECT puntos, nivel, vida, estado FROM usuario WHERE id_usuario = :id_usuario");
        $sql_actual->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $sql_actual->execute();
        $fila_actual = $sql_actual->fetch(PDO::FETCH_ASSOC);

        if ($fila_actual) {

            // VERIFICAR SI HAY CAMBIOS EN EL ESTADO
            $estado_actual = (int) $fila_actual['estado'];
            $nuevo_estado = (int) $nuevo_estado;

            // VERIFICAR SI HAY CAMBIOS EN LOS DATOS
            if ($fila_actual['puntos'] != $puntos_usuario || 
                $fila_actual['nivel'] != $nivel_usuario || 
                $fila_actual['vida'] != $vida_usuario || 
                $estado_actual !== $nuevo_estado) {
                
                $hay_actualizaciones = true; // SI HAY CAMBIOS SE HACE LA ACTUALIZACION

                // CONSICIONAL SOLO PARA CUANDO SE MODIFIQUE EL ESTADO SE AGGREGUE EL CORREO
                if ($nuevo_estado === 1) {
                    $correos_a_enviar[] = $correo; // 
                }

                // ACTUALIZAR EN LA BASE DE DATOS
                $update = $con->prepare("UPDATE usuario SET puntos = :puntos, nivel = :nivel, vida = :vida, estado = :estado, id_rol = :rol WHERE id_usuario = :id_usuario");
                $update->bindParam(':puntos', $puntos_usuario, PDO::PARAM_INT); 
                $update->bindParam(':nivel', $nivel_usuario, PDO::PARAM_INT); 
                $update->bindParam(':vida', $vida_usuario, PDO::PARAM_INT); 
                $update->bindParam(':estado', $nuevo_estado, PDO::PARAM_INT);
                $update->bindParam(':rol', $rol, PDO::PARAM_INT);
                $update->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                $update->execute();
            }
        }
    }

    // MeENSAJE DE ALERTA SI NO HAY ACTULIZACIONES
    if (!$hay_actualizaciones) {
        echo '<script>alert("No hay nada para actualizar.");</script>';
        echo '<script>window.location.href = "lista_usu.php";</script>';
    } else {
        echo '<script>alert("Actualización exitosa");</script>';
        
        // SE ENVIAN CORREOS SOLO si correos_a_enviar TIENE REGISTROS
        if (!empty($correos_a_enviar)) {
            $correos_str = implode(',', $correos_a_enviar);  
            echo '<script>window.location.href = "enviar_correo.php?correos=' . $correos_str . '";</script>';
        } else {
            echo '<script>window.location.href = "lista_usu.php";</script>';
        }
    }
}
?>

<!DOCTYPE html
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
        <h1 class="titulo">LISTA USUARIOS</h1>
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
                INNER JOIN roles ON usuario.id_rol = roles.id_rol;");
            $sql->execute();
            $fila = $sql->fetchAll(PDO::FETCH_ASSOC);

            foreach ($fila as $resu) {
            ?>

            <tr>
                <td><input class="texto_registros" type="text" readonly value="<?php echo htmlspecialchars($resu['id_usuario']); ?>"></td>
                <td><input class="texto_registros" type="text" readonly value="<?php echo htmlspecialchars($resu['username']); ?>"></td>
                <td><input class="texto_registros" type="text" name="correo[]" value="<?php echo htmlspecialchars($resu['correo']); ?>"></td>
                <td><input class="texto_registros" type="text" readonly value="<?php echo htmlspecialchars($resu['contrasena']); ?>"></td>
                <td><input class="texto_registros" type="text" name="puntos[]" value="<?php echo htmlspecialchars($resu['puntos']); ?>"></td>
                <td><input class="texto_registros" type="text" name="nivel[]" value="<?php echo htmlspecialchars($resu['nivel']); ?>"></td>
                <td><input class="texto_registros" type="text" name="vida[]" value="<?php echo htmlspecialchars($resu['vida']); ?>"></td>
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
                    <select name="rol[]">
                        <option value="<?php echo htmlspecialchars($resu['id_rol']); ?>">
                            <?php echo htmlspecialchars($resu['rol']); ?>
                        </option>
                        <?php
                        $sql_roles = $con->prepare("SELECT * FROM roles");
                        if ($sql_roles->execute()) {
                            while ($fila_roles = $sql_roles->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value='" . htmlspecialchars($fila_roles['id_rol']) . "'>" . htmlspecialchars($fila_roles['rol']) . "</option>";
                            }
                        }
                        ?>
                    </select>
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
        <h1 class="titulo">LISTA USUARIOS</h1>
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
                INNER JOIN roles ON usuario.id_rol = roles.id_rol;");
            $sql->execute();
            $fila = $sql->fetchAll(PDO::FETCH_ASSOC);

            foreach ($fila as $resu) {
            ?>

            <tr>
                <td><input class="texto_registros" type="text" readonly value="<?php echo htmlspecialchars($resu['id_usuario']); ?>"></td>
                <td><input class="texto_registros" type="text" readonly value="<?php echo htmlspecialchars($resu['username']); ?>"></td>
                <td><input class="texto_registros" type="text" name="correo[]" value="<?php echo htmlspecialchars($resu['correo']); ?>"></td>
                <td><input class="texto_registros" type="text" readonly value="<?php echo htmlspecialchars($resu['contrasena']); ?>"></td>
                <td><input class="texto_registros" type="text" name="puntos[]" value="<?php echo htmlspecialchars($resu['puntos']); ?>"></td>
                <td><input class="texto_registros" type="text" name="nivel[]" value="<?php echo htmlspecialchars($resu['nivel']); ?>"></td>
                <td><input class="texto_registros" type="text" name="vida[]" value="<?php echo htmlspecialchars($resu['vida']); ?>"></td>
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
                    <select name="rol[]">
                        <option value="<?php echo htmlspecialchars($resu['id_rol']); ?>">
                            <?php echo htmlspecialchars($resu['rol']); ?>
                        </option>
                        <?php
                        $sql_roles = $con->prepare("SELECT * FROM roles");
                        if ($sql_roles->execute()) {
                            while ($fila_roles = $sql_roles->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value='" . htmlspecialchars($fila_roles['id_rol']) . "'>" . htmlspecialchars($fila_roles['rol']) . "</option>";
                            }
                        }
                        ?>
                    </select>
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