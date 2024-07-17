//Ejecutando funciones
document.getElementById("btn__iniciar-sesion").addEventListener("click", iniciarSesion);
document.getElementById("btn__registrarse").addEventListener("click", register);
window.addEventListener("resize", anchoPage);

//Declarando variables
var formulario_login = document.querySelector(".formulario__login");
var formulario_register = document.querySelector(".formulario__register");
var contenedor_login_register = document.querySelector(".contenedor__login-register");
var caja_trasera_login = document.querySelector(".caja__trasera-login");
var caja_trasera_register = document.querySelector(".caja__trasera-register");

function anchoPage(){
    if (window.innerWidth > 850){
        caja_trasera_register.style.display = "block";
        caja_trasera_login.style.display = "block";
    } else {
        caja_trasera_register.style.display = "block";
        caja_trasera_register.style.opacity = "1";
        caja_trasera_login.style.display = "none";
        formulario_login.style.display = "block";
        contenedor_login_register.style.left = "0px";
        formulario_register.style.display = "none";   
    }
}

anchoPage();

function iniciarSesion(){
    if (window.innerWidth > 850){
        formulario_login.style.display = "block";
        contenedor_login_register.style.left = "10px";
        formulario_register.style.display = "none";
        caja_trasera_register.style.opacity = "1";
        caja_trasera_login.style.opacity = "0";
    } else {
        formulario_login.style.display = "block";
        contenedor_login_register.style.left = "0px";
        formulario_register.style.display = "none";
        caja_trasera_register.style.display = "block";
        caja_trasera_login.style.display = "none";
    }
}

function register() {
    if (window.innerWidth > 850) {
        formulario_register.style.display = "block";
        contenedor_login_register.style.left = "410px";
        formulario_login.style.display = "none";
        caja_trasera_register.style.opacity = "0";
        caja_trasera_login.style.opacity = "1";
    } else {
        formulario_register.style.display = "block";
        contenedor_login_register.style.left = "0px";
        formulario_login.style.display = "none";
        caja_trasera_register.style.display = "none";
        caja_trasera_login.style.display = "block";
        caja_trasera_login.style.opacity = "1";
    }
}

// Validar campos de usuarios
function validarLetras(input) {
    input.value = input.value.replace(/[^a-zA-Z]/g, '');
}

function formatearNombre(input) {
    input.value = input.value.charAt(0).toUpperCase() + input.value.slice(1).toLowerCase();
}

function validarCorreo() {
    var correo = document.getElementById("correo").value;
    var error_correo = document.getElementById("error_correo");

    if (!correo.includes('@')) {
        error_correo.style.display = "block";
        return false;
    } else {
        error_correo.style.display = "none";
        return true;
    }
}

// Validar número de celular
function validarCelular() {
    var celular = document.getElementById("celular").value;
    var error_celular = document.getElementById("error_celular");

    var regex = /^09\d{8}$/;
    if (!regex.test(celular)) {
        error_celular.style.display = "block";
        return false;
    } else {
        error_celular.style.display = "none";
        return true;
    }
}

// Validar número de celular al actualizar
function validarCelularActualizar() {
    var celular = document.getElementById("celularActualizar").value;
    var error_celularActualizar = document.getElementById("error_celularActualizar");

    var regex = /^09\d{8}$/;
    if (!regex.test(celular)) {
        error_celularActualizar.style.display = "block";
        return false;
    } else {
        error_celularActualizar.style.display = "none";
        return true;
    }
}

// Validar todo el formulario al insertar
function validarFormularioUsuarios() {
    var nombreValido = validarLetras(document.getElementById("nombre"));
    var apellidoValido = validarLetras(document.getElementById("apellido"));
    var correoValido = validarCorreo();
    var celularValido = validarCelular();
    return nombreValido && apellidoValido && correoValido && celularValido;
}

// Validar todo el formulario al actualizar
function validarFormularioUsuarioActualizar() {
    var nombreValido = validarLetras(document.getElementById("nombreActualizar"));
    var apellidoValido = validarLetras(document.getElementById("apellidoActualizar"));
    var correoValido = validarCorreo();
    var celularValido = validarCelularActualizar();
    return nombreValido && apellidoValido && correoValido && celularValido;
}

// Mostrar contraseña
function showPassword(fieldId) {
    var field = document.getElementById(fieldId);
    field.type = "text";
}

// Ocultar contraseña
function hidePassword(fieldId) {
    var field = document.getElementById(fieldId);
    field.type = "password";
}

// Validar contraseña
function validar_contrasena() {
    var contrasena = document.getElementById("contrasena").value;
    var confirmar_contrasena = document.getElementById("confirmar_contrasena").value;
    var mensaje_error = document.getElementById("mensaje_error");

    if (contrasena !== confirmar_contrasena) {
        mensaje_error.style.display = "block";
        return false;
    } else {
        mensaje_error.style.display = "none";
        return true;
    }
}

$(document).ready(function() {
    // Mostrar contraseña en el formulario de registro
    $("#togglePassword").mousedown(function() {
        showPassword('contrasena');
    }).mouseup(function() {
        hidePassword('contrasena');
    }).mouseout(function() {
        hidePassword('contrasena');
    });

    // Mostrar contraseña en el formulario de inicio de sesión
    $("#toggleLoginPassword").mousedown(function() {
        showPassword('loginPassword');
    }).mouseup(function() {
        hidePassword('loginPassword');
    }).mouseout(function() {
        hidePassword('loginPassword');
    });

    // Función de búsqueda en tiempo real
    $("#searchInput").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#librosTable tr, #usuariosTable tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    // Validación en tiempo real del campo celular al insertar
    $("#celular").on("input", function() {
        validarCelular();
    });

    // Validación en tiempo real del campo celular al actualizar
    $("#celularActualizar").on("input", function() {
        validarCelularActualizar();
    });
});

function editarLibro(id, titulo, autor, editorial, aniopubli, categoria, cantidad, imagen) {
    document.getElementById('idActualizar').value = id;
    document.getElementById('tituloActualizar').value = titulo;
    document.getElementById('autorActualizar').value = autor;
    document.getElementById('editorialActualizar').value = editorial;
    document.getElementById('aniopubliActualizar').value = aniopubli;
    document.getElementById('categoriaActualizar').value = categoria;
    document.getElementById('cantidadActualizar').value = cantidad;
    document.getElementById('imagenActualizar').value = imagen;
    $('#editarLibroModal').modal('show');
}

function verLibro(id, titulo, autor, editorial, aniopubli, categoria, cantidad, imagen) {
    document.getElementById('tituloVer').value = titulo;
    document.getElementById('autorVer').value = autor;
    document.getElementById('editorialVer').value = editorial;
    document.getElementById('aniopubliVer').value = aniopubli;
    document.getElementById('categoriaVer').value = categoria;
    document.getElementById('cantidadVer').value = cantidad;
    document.getElementById('imagenVer').src = imagen;
    $('#verLibroModal').modal('show');
}

function editarUsuario(id, nombre, apellido, correo, celular) {
    document.getElementById('idActualizarUsuario').value = id;
    document.getElementById('nombreActualizar').value = nombre;
    document.getElementById('apellidoActualizar').value = apellido;
    document.getElementById('correoActualizar').value = correo;
    document.getElementById('celularActualizar').value = celular;
    $('#editarUsuarioModal').modal('show');
}
