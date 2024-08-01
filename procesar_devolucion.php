<?php
include 'php/conexion_be.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario_id = $_POST['usuario_id'];
    $libro_id = $_POST['libro_id'];
    $cantidad = $_POST['cantidad'];

    // Insertar la devolución
    $query = "INSERT INTO devoluciones (usuario_id, libro_id, cantidad) VALUES ($usuario_id, $libro_id, $cantidad)";
    mysqli_query($conexion, $query);

    // Actualizar la cantidad disponible del libro
    $query = "UPDATE libros SET cantidad = cantidad + $cantidad WHERE id = $libro_id";
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
                title:
