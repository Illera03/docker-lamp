// Función de comprobar los datos del formulario de login (se ejecuta al hacer click en el botón de enviar)
function comprobarDatosLogin() {
    // Obtener el formulario usando el id
    const formularioL = document.getElementById('login_form');

    // Para comprobar si se han rellenado todos los campos
    if (!formularioL.checkValidity()) {
        alert("Por favor, completa todos los campos requeridos.");
        return;
    }
    const emailL = document.getElementById("email").value;

    // Validar el correo electrónico
    if (!validarEmail(emailL)) {
        alert("Introduce un email válido.");
        return;
    }

    // Si todo está correcto, enviar el formulario donde indica el atributo action del formulario
    formularioL.submit();
}

// Función para validar el email
function validarEmail(email) {
    // Expresión regular básica para validar el formato del email
    const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    return emailPattern.test(email);
}