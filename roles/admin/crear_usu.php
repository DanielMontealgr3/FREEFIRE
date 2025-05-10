<?php

    require_once("../../database/database.php");
    $conexion = new database;
    $con = $conexion->conectar();
    session_start();
    
    $userId = $_SESSION['user_id'];
    $username = $_SESSION['username'];
    $id_usuario = $_SESSION['id_usuario'];
    include('menu.html');  


?>

<body>
    <div class="contenedor_principal">
        <div class="contenedor_body">
            <div class="contenedor_form">
                <h1 class="titulo">CREAR USUARIO</h1>
                <form id="crearusu" class="opciones">
                    <div class="iconos">
                        <i class="bi bi-person-plus-fill"></i>
                    </div>

                    <label for="roles" class="nav-link">Rol</label>
                    <div class="container_select">
                        <select class="opciones" name="rol" id="rol" required>
                            <option value="" selected>Seleccionar rol</option>  
                            <?php
                            $statement = $con->prepare('SELECT * FROM roles ORDER BY rol ASC');
                            $statement->execute();
                            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value='" . ($row['id_rol']) . "'>" . ($row['rol']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                        
                    <label>Username</label>
                    <input type="text" name="username" id="username" class="opciones">
                        
                    <label>Email</label>
                    <input type="email" id="email" name="email" class="opciones">
                        
                    <label>Password</label>
                    <input type="password" id="contraseña" name="contraseña" class="opciones">
                        
                    <input type="submit" id="crear" name="crear" value="Crear usuario">
                </form>

            </div>
        </div>
    </div>
</body>




<script src="../js/registro.js"></script>

<script>
function mostrarMensaje(mensajeError) {
    const existingTarjeta = document.querySelector('.tarjetaAviso');
    if (existingTarjeta) {
        existingTarjeta.remove();
    }

    const tarjeta = document.createElement("section");
    tarjeta.classList.add("tarjetaAviso");
    tarjeta.innerHTML = `
        <div class="aviso" id="aviso">
            <div class="error">
                <p id="error"></p>
            </div>
        </div>
    `;
    document.body.appendChild(tarjeta);

    const error = document.getElementById("error");
    const aviso = document.getElementById("aviso");

    error.textContent = mensajeError;
    aviso.classList.add("animacion");

    setTimeout(() => {
        tarjeta.remove();
    }, 5000);
}

document.querySelector("#crearusu").addEventListener("submit", async function (event) {
    event.preventDefault(); // Evita que el formulario se envíe de forma tradicional

    try {
        let formData = new FormData(this); // Captura todos los datos del formulario

        let response = await fetch("proceso_crear.php", {
            method: "POST",
            body: formData
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        let result = await response.json();

        mostrarMensaje(result.message);

        if (result.success) {
            setTimeout(() => {
                window.location.href = 'crear_usu.php'; // Redirigir después de 2 segundos
            }, 2000);
        }
    } catch (error) {
        mostrarMensaje("Error en el proceso de registro: " + error.message);
    }
});

</script>

<script src="./js/alerta.js"></script>



