function formsValidate() {
    const formEstudiante = document.getElementById('formEstudiante');
    const formAcudiente = document.getElementById('formAcudiente');
    let validador;

    /* Datos del estudiante */
    let tipodoc = document.getElementById('tipodoc').value.trim(); 
    let doc = document.getElementById('doc').value.trim();
    let nombre = document.getElementById('nombre').value.trim();
    let direccion = document.getElementById('direccion').value.trim();
    let telefono = document.getElementById('telefono').value.trim();
    let correo = document.getElementById('correo').value.trim();
    let fechaNac = document.getElementById('fechaNac').value.trim();
    let curso = document.getElementById('curso').value.trim();
    let contrasena = document.getElementById('contrasena').value.trim();

    // Validación de campos vacíos
    if (!tipodoc || !doc || !nombre || !direccion || !telefono || !correo || !fechaNac || !curso || !contrasena ) {
        alert("Faltan campos por llenar");
        return false;
        validador = false;
    }

    // Validación de documento (solo números)
  

    if (!/^\d+$/.test(doc)) {
        alert("El documento debe contener solo números");
        validador = false;
        return false;
    }

    // Validación de nombre (solo letras y espacios)
    if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(nombre)) { 
        alert("El nombre debe contener solo letras");
        validador = false;
        return false;
    }

    // Validación de teléfono (solo números y mínimo 7 dígitos)
    if (!/^\d{7,}$/.test(telefono)) {
        alert("El teléfono debe contener solo números y mínimo 7 dígitos");
        validador = false;
        return false;
    }

    // Validación de correo electrónico
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(correo)) {
        alert("Por favor, ingrese un correo electrónico válido");
        validador = false;
        return false;
    }

    // Validación de fecha de nacimiento
    const fechaNacDate = new Date(fechaNac);
    const fechaActual = new Date();
    
    if (fechaNacDate >= fechaActual) {
        alert("La fecha de nacimiento debe ser menor a la fecha actual");
        validador = false;
        return false;
    }

    // Validación de edad mínima (por ejemplo, 5 años)
    const edadMinima = 5;
    const diferenciaMilisegundos = fechaActual - fechaNacDate;
    const edadEnAnos = diferenciaMilisegundos / (1000 * 60 * 60 * 24 * 365.25);
    
    if (edadEnAnos < edadMinima) {
        alert(`El estudiante debe tener al menos ${edadMinima} años de edad`);
        validador = false;
        return false;
    }

    // Validación de contraseña (mínimo 8 caracteres, al menos una mayúscula, una minúscula y un número)
    if (!/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/.test(contrasena)) {
        alert("La contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula y un número");
        validador = false;
        return false;
    }

    validador = true;
    
    if (validador == true) {
        formEstudiante.classList.add('d-none');
        formAcudiente.classList.remove('d-none');
    }

    return true;

}

function asd(){
    console.log(r_doc);
}
