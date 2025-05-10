

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/alerta.css">
</head>
<body>
    
    <section class="mainGrid">
        <div class="logoFreeFire">
            <img src="./assets/img/ff-logo.png" class="logo" alt="">
        </div>

        <video class="video-fondo" autoplay loop muted playsinline>
    <source src="./assets/fondos/fondoAnimado.mp4" type="video/mp4">
    Tu navegador no soporta la reproducción de videos.
</video>
        

        <!-- <button onclick="mostrarMensaje()"></button> -->
        <div class="mainLogin">
           
                <form class="login" id="registroForm" >
                    <h1 class="titleLogin">Registrate!</h1>
                    


                    <div class="datos">
                        <p class="datoP">Username</p>
                        <input type="text" name="username" id="username" class="inputs">
                        <p style="color:red" id="advertencia"></p>
                    </div>
                    
                    <div class="datos">
                        <p class="datoP">Email</p>
                        <input type="email" id="email" name="email" class="inputs">
                        <p style="color:red" id="advertencia2"></p>
                    </div>

                    <div class="datos">
                        <p class="datoP">Contraseña</p>
                        <input type="password" id="contraseña" name="contraseña" class="inputs">
                        <p style="color:red" id="advertencia3"></p>
                    </div>

                    <div class="olvidasteContraseñaDiv">
                        <a class="olvidasteContraseña" href="">¿Olvidaste Tu Contraseña?</a>
                    </div>
                    <input type="submit" id="submit" name="submit" value="Registrarme" class="inicioSesion" >




                    <hr style="color:white; width:90%; height:5px; background:white;margin-top:10px;">
                    
                    <div class="sinCuenta">
                        <p style="color:gray; margin-top:15px">¿Ya tienes cuenta? <a href="./index.php" class="registrate">Inicia Sesion</a></p>
                </form>
                
            </div>


        </div>
    </section>





    

<script src="./js/registro.js"></script>



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

document.querySelector("form").addEventListener("submit", async function (event) {
    event.preventDefault();


    

    try {
        let formData = new FormData(this);
            
            let response = await fetch("procesarRegistro.php", {
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
                window.location.href = 'registro.php';
            }, 2000);
        }
    } catch (error) {
        mostrarMensaje("Error en el proceso de registro: " + error.message );
    }
});
</script>

<script src="./js/alerta.js"></script>    


</body>
</html>