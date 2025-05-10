
let advertencia = document.getElementById("advertencia")
let advertencia2 = document.getElementById("advertencia2")
let advertencia3 = document.getElementById("advertencia3")


const username = document.getElementById("username")
const email = document.getElementById("email")
const contraseña = document.getElementById("contraseña")
const submit = document.getElementById("submit")


let palabrasObcenas = /\b(gonorrea|hpta|hijueputa|hp|monda|verga|caremondá|carechimba|careculo|careverga|caregüevo|caremonda|chimba|culicagado|culiar|culiao|culón|guevón|hijuepucha|hijueputa|hpta|malparido|mamahuevo|marica|maricón|mierda|mondá|ñero|pirobo|piroba|puta|putas|putica|sapo|sapa|sisas|soplamonda|tombos|tragasable|triplehijueputa|triplehp|vergas|zunga)\b/i

const regex = {
    username : /^[a-zA-Z0-9_]{5,16}$/,
    contraseña: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@#$%^&*])[A-Za-z\d@#$%^&*]{8,}$/,
    correo: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/


}
submit.disabled = true

validacionUser = false
validacionContra = false
validacionEmail = false


function comprobacion(queComprobar,campo,advertencia,mensaje){


    if(!regex[queComprobar].test(campo.value)){
        submit.disabled = true
        advertencia.style.opacity = 1
        advertencia.textContent = mensaje
        setTimeout(() => {
            advertencia.style.opacity = 0
            setTimeout(() => {
                advertencia.textContent = ""
            }, 1000);
        }, 4000);
  
    }  else if(palabrasObcenas.test(campo.value)){
        submit.disabled = true
        advertencia.style.opacity = 1
        advertencia.textContent = "NO SE PERMITEN ESAS PALABRAS"
        setTimeout(() => {
            advertencia.style.opacity = 0
            setTimeout(() => {
                advertencia.textContent = ""
            }, 1000);
        }, 4000);
    }else{
        return  true
    }

}

function activarRegistro(){
    if(validacionContra === true && validacionEmail === true && validacionUser === true ){
        submit.disabled = false
    }
}

username.addEventListener("blur", () =>{
    validacionUser = comprobacion("username",username,advertencia,"Minimo 5 a 16 caracteres")
    activarRegistro()
})
email.addEventListener("blur", () =>{
    validacionEmail = comprobacion("correo",email,advertencia2,"Ingrese Un correo valido")
    activarRegistro()
})
contraseña.addEventListener("blur", () =>{
    validacionContra = comprobacion("contraseña",contraseña,advertencia3,"contraseña segura con mínimo 8 caracteres, una letra, un número y un símbolo especial." )
    activarRegistro()
})







