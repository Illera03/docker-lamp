document.getElementById("register_form").addEventListener("submit", function(event) {
    const dniInput = document.getElementById("dni").value;
    if (!validarDNI(dniInput)) {
        alert("El DNI es incorrecto.");
        event.preventDefault(); // Evita el envío del formulario si hay error
    }
});

function validarDNI(dni) {
    const letras = "TRWAGMYFPDXBNJZSQVHLCKE";
    const numero = dni.substr(0, dni.length - 2);
    const letra = dni.substr(dni.length - 1).toUpperCase();
    return letras[numero % 23] === letra;
}