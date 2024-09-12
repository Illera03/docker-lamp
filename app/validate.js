// FICHERO DE VALIDACIONES

// Añadir un evento al formulario con id "register_form" que se ejecuta al enviar el formulario
document.getElementById("register_form").addEventListener("submit", function(event) {
    // Obtener el valor del campo de entrada con id "dni"
    const dniInput = document.getElementById("dni").value;
    // Validar si el DNI es correcto con la función validarDNI
    if (!validarDNI(dniInput)) {
        alert("El DNI es incorrecto.");
        event.preventDefault(); // Evita el envío del formulario si hay error
    }
});

function validarDNI(dni) {
    const letras = "ABCDEFGHJKLMNPQRSTVWXYZ";
    // Extraer la parte numérica del DNI (todos los caracteres excepto Los dos últimos)
    const numero = dni.substr(0, dni.length - 2);
    // Extraer la letra del DNI (el último carácter) y convertirla a mayúsculas
    const letra = dni.substr(dni.length - 1).toUpperCase();
    // Comprobar si la letra es correcta en función del número introducido
    return letras[numero % 23] === letra;
}