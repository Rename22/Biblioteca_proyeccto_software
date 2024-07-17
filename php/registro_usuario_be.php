<?php

include 'conexion_be.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtiene las contraseñas desde el formulario
    $contrasena = $_POST['contrasena'];
    $confirmar_contrasena = $_POST['confirmar_contrasena'];

    // Verifica que las contraseñas coincidan
    if ($contrasena !== $confirmar_contrasena) {
        echo '
            <html>
            <head>
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            </head>
            <body>
                <script>
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "Las contraseñas no coinciden."
                    }).then(function() {
                        window.location = "../index.php";
                    });
                </script>
            </body>
            </html>
        ';
        exit();
    }
}

$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$correo = $_POST['correo'];
$contrasena = $_POST['contrasena'];
$celular = $_POST['celular'];

$query = "INSERT INTO usuarios(nombre, apellido, correo, contrasena, celular)
        VALUES('$nombre', '$apellido', '$correo', '$contrasena', '$celular')";

// Verificar que el correo no se repita en la bdd
$verificar_correo = mysqli_query($conexion, "SELECT * FROM usuarios WHERE correo = '$correo' ");

if (mysqli_num_rows($verificar_correo) > 0) {
    echo '
        <html>
        <head>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        </head>
        <body>
            <script>
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Correo ya registrado, intenta nuevamente."
                }).then(function() {
                    window.location = "../index.php";
                });
            </script>
        </body>
        </html>
    ';
    exit();
}

// Verificar que el apellido no se repita en la bdd
$verificar_apellido = mysqli_query($conexion, "SELECT * FROM usuarios WHERE apellido = '$apellido' ");

if (mysqli_num_rows($verificar_apellido) > 0) {
    echo '
        <html>
        <head>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        </head>
        <body>
            <script>
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Apellido ya registrado, intenta nuevamente."
                }).then(function() {
                    window.location = "../index.php";
                });
            </script>
        </body>
        </html>
    ';
    exit();
}

$ejecutar = mysqli_query($conexion, $query);

if ($ejecutar) {
    echo '
    <html>
    <head>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body>
        <script>
            Swal.fire({
                icon: "success",
                title: "Registro exitoso",
                text: "Usuario registrado con éxito."
            }).then(function() {
                window.location = "../index.php";
            });
        </script>
    </body>
    </html>
    ';
} else {
    echo '
    <html>
    <head>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body>
        <script>
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Inténtalo de nuevo."
            }).then(function() {
                window.location = "../index.php";
            });
        </script>
    </body>
    </html>
    ';
}

mysqli_close($conexion);
?>
