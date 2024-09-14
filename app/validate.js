// FICHERO DE VALIDACIONES


// Función de comprobar los datos del formulario ( se ejecuta al hacer click en el botón de enviar)
function comprobarDatos() {
    // Obtener el formulario usando el id
    const formulario = document.getElementById('register_form');


    // Para comprobar si se han rellenado todos los campos
    if (!formulario.checkValidity()) {
        alert("Por favor, completa todos los campos requeridos.");
        return;
    }

    // Validar el nombre (solo letras y espacios)
    const nameInput = document.getElementById("name").value;
    if (!validarNombre(nameInput)) {
        alert("El nombre solo puede contener letras y espacios.");
        return; // Detener la ejecución si el nombre no es válido
    }
    //--------------------------------------------------------------------------------

    // Validar el DNI
    const dniInput = document.getElementById('dni').value;
    if (!validarDNI(dniInput)) {
        window.alert("Introduce un DNI válido. Formato: 12345678-A");
        return; // Detener la ejecución si el DNI no es válido
    }
    //--------------------------------------------------------------------------------

    // Validar la fecha de nacimiento (no puede ser posterior a hoy)
    const birthdateInput = document.getElementById("birthdate").value;
    const today = new Date().toISOString().split('T')[0]; // Obtener la fecha de hoy en formato YYYY-MM-DD
 
    if (birthdateInput > today) {
        alert("La fecha de nacimiento no puede ser posterior a hoy.");
        return;
    }
    //--------------------------------------------------------------------------------

     // Validar el teléfono (debe tener 9 dígitos)
    const phoneInput = document.getElementById("phone").value;
    if (phoneInput.length !== 9 || !/^\d{9}$/.test(phoneInput)) {
        alert("El teléfono debe tener exactamente 9 dígitos.");
        return;
    }
    //--------------------------------------------------------------------------------

    // Validar el correo electrónico
    const emailInput = document.getElementById("email").value;
    if (!validarEmail(emailInput)) {
        alert("Introduce un email válido.");
        return;
    }
    //--------------------------------------------------------------------------------

    // Si todos los datos son correctos, enviar el formulario
    formulario.submit();
}

// Función para validar el nombre (solo letras y espacios)
function validarNombre(nombre) {
    const nombrePattern = /^[A-Za-z\s]+$/; // Solo letras (mayúsculas o minúsculas) y espacios
    return nombrePattern.test(nombre);
}


function validarDNI(dni) {
    const letras = "TRWAGMYFPDXBNJZSQVHLCKE";
    
    // Extraer la parte numérica (8 dígitos) y la letra
    const numero = dni.substr(0, 8);
    const letra = dni.substr(9, 1).toUpperCase();

    // Validar si la letra es la correcta
    return letras[numero % 23] === letra;
}

// Función para validar el email
function validarEmail(email) {
    // Expresión regular básica para validar el formato del email
    const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    return emailPattern.test(email);
}
