<?php

// Mostrar errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'conexion_be.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];

    $query = "SELECT * FROM usuarios WHERE correo = '$correo' AND contrasena = '$contrasena'";
    $resultado = mysqli_query($conexion, $query);

    if (mysqli_num_rows($resultado) == 1) {
        $usuario = mysqli_fetch_assoc($resultado);
        $_SESSION['id'] = $usuario['id'];
        $_SESSION['nombre'] = $usuario['nombre'];
        $_SESSION['role'] = $usuario['role']; // Agregar esta línea para almacenar el rol en la sesión

        echo '
            <html>
            <head>
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                <style>
                    .custom-swal-button {
                        background-color: #4CAF50; /* Cambia esto al color que desees */
                        color: white;
                        border: none;
                        padding: 10px 20px;
                        font-size: 16px;
                        cursor: pointer;
                        border-radius: 5px;
                    }

                    .custom-swal-button:hover {
                        background-color: #45a049; /* Color cuando se pasa el mouse */
                    }
                </style>
            </head>
            <body>
                <script>
                    Swal.fire({
                        icon: "success",
                        title: "Inicio de sesión exitoso",
                        text: "Bienvenido ' . $usuario['nombre'] . '",
                        customClass: {
                            confirmButton: "custom-swal-button"
                        }
                    }).then(function() {
                        window.location = "../bienvenido.php"; // Redirige a una página de bienvenida o al dashboard
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
                <style>
                    .custom-swal-button {
                        background-color: #FF0000; /* Cambia esto al color que desees */
                        color: white;
                        border: none;
                        padding: 10px 20px;
                        font-size: 16px;
                        cursor: pointer;
                        border-radius: 5px;
                    }

                    .custom-swal-button:hover {
                        background-color: #FF3333; /* Color cuando se pasa el mouse */
                    }
                </style>
            </head>
            <body>
                <script>
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "Correo o contraseña incorrectos.",
                        customClass: {
                            confirmButton: "custom-swal-button"
                        }
                    }).then(function() {
                        window.location = "../index.php";
                    });
                </script>
            </body>
            </html>
        ';
    }

    mysqli_close($conexion);
}
?>
