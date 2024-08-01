<?php
include 'php/conexion_be.php';

if (isset($_GET['libro_id'])) {
    $libro_id = $_GET['libro_id'];
    $query = "SELECT cantidad FROM libros WHERE id = $libro_id";
    $resultado = mysqli_query($cantidad, $query);

    if ($resultado) {
        $libro = mysqli_fetch_assoc($resultado);
        echo json_encode(['cantidad' => $libro['cantidad']]);
    } else {
        echo json_encode(['cantidad' => 0]);
    }
}
?>
