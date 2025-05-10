const advertencia = document.getElementById("advertencia")
const advertencia2 = document.getElementById("advertencia2")


const username = document.getElementById("username")
const contraseña = document.getElementById("contraseña")
const submit = document.getElementById("submit")


regex = {
    username : /^[a-zA-Z0-9_]{5,16}$/,
    contraseña: /^(?=.*[a-zA-Z])(?=.*\d)(?=.*[@#$%^&*])[a-zA-Z\d@#$%^&*]{8,}$/,
}
