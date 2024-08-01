<?php
include 'php/conexion_be.php';

if (isset($_POST['correo'])) {
    $correo = $_POST['correo'];
    $id = isset($_POST['id']) ? $_POST['id'] : 0;

    $query = "SELECT * FROM usuarios WHERE correo = '$correo' AND id != $id";
    $result = mysqli_query($conexion, $query);

    if (mysqli_num_rows($result) > 0) {
        echo "exist";
    } else {
        echo "not_exist";
    }
}
?>
