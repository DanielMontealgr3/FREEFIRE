function mostrarMensaje(mensajeError){


    const tarjeta = document.createElement("section")
    
    tarjeta.classList.add("tarjetaAviso")
    tarjeta.innerHTML = `
    
            <div class="aviso" id="aviso">
                <div class="error">
                    <p id="error"></p>
                </div>
            </div>
    `
    document.body.appendChild(tarjeta)
    
        const error = document.getElementById("error")
        const aviso = document.getElementById("aviso") 
    
        error.innerHTML = mensajeError
        aviso.classList.add("animacion")
    
    
    }