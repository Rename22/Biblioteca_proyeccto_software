<?php
include 'php/conexion_be.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario_id = $_POST['usuario_id'];
    $libro_id = $_POST['libro_id'];
    $cantidad = $_POST['cantidad'];
    $fecha_devolucion = $_POST['fecha_devolucion'];

    // Verificar la cantidad disponible del libro
    $query = "SELECT cantidad FROM libros WHERE id = $libro_id";
    $resultado = mysqli_query($conexion, $query);
    $libro = mysqli_fetch_assoc($resultado);

    if ($libro['cantidad'] >= $cantidad) {
        // Insertar el préstamo
        $query = "INSERT INTO prestamos (usuario_id, libro_id, cantidad, fecha_devolucion) VALUES ($usuario_id, $libro_id, $cantidad, '$fecha_devolucion')";
        mysqli_query($conexion, $query);

        // Actualizar la cantidad disponible del libro
        $query = "UPDATE libros SET cantidad = cantidad - $cantidad WHERE id = $libro_id";
        mysqli_query($conexion, $query);

        echo '
        <html>
        <head>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <style>
                .custom-swal-button {
                    background-color: #4CAF50;
                    color: white;
                    border: none;
                    padding: 10px 20px;
                    font-size: 16px;
                    cursor: pointer;
                    border-radius: 5px;
                }

                .custom-swal-button:hover {
                    background-color: #45a049;
                }
            </style>
        </head>
        <body>
            <script>
                Swal.fire({
                    icon: "success",
                    title: "Préstamo registrado",
                    text: "Préstamo registrado exitosamente",
                    customClass: {
                        confirmButton: "custom-swal-button"
                    }
                }).then(function() {
                    window.location = "bienvenido.php";
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
                    background-color: #FF0000;
                    color: white;
                    border: none;
                    padding: 10px 20px;
                    font-size: 16px;
                    cursor: pointer;
                    border-radius: 5px;
                }

                .custom-swal-button:hover {
                    background-color: #FF3333;
                }
            </style>
        </head>
        <body>
            <script>
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Cantidad no disponible.",
                    customClass: {
                        confirmButton: "custom-swal-button"
                    }
                }).then(function() {
                    window.location = "bienvenido.php";
                });
            </script>
        </body>
        </html>
        ';
    }

    mysqli_close($conexion);
}
?>
