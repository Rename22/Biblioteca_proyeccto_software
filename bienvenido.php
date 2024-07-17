<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

include 'php/conexion_be.php';

// Obtener los libros de la base de datos
$query = "SELECT * FROM libros";
$resultado = mysqli_query($conexion, $query);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card {
            height: 450px; /* Ajusta la altura según tus necesidades */
        }

        .card-img-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 60%; /* Ajusta la altura según tu necesidad */
        }

        .card-img-top {
            max-width: 50%;
            max-height: 100%;
            object-fit: cover;
        }

        .card-body {
            height: 40%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .card-title {
            text-align: center;
        }

        .availability {
            display: inline-block;
            padding: 0.25em 0.4em;
            font-size: 75%;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.25rem;
            color: #fff;
        }

        .available {
            background-color: #28a745;
        }

        .not-available {
            background-color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1>Bienvenido, <?php echo $_SESSION['nombre']; ?></h1>
        <a href="php/logout.php" class="btn btn-secondary">Cerrar sesión</a>
        <a href="gestion_libros.php" class="btn btn-primary">Gestionar Libros</a>
        <a href="gestion_usuarios.php" class="btn btn-primary">Gestionar Usuarios</a>
        <div class="row mt-4">
            <?php while ($libro = mysqli_fetch_assoc($resultado)) { 
                $disponibilidad = $libro['cantidad'] > 0 ? 'Disponible' : 'No disponible';
                $disponibilidadClase = $libro['cantidad'] > 0 ? 'available' : 'not-available';
            ?>
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-img-container">
                            <img src="<?php echo $libro['imagen']; ?>" class="card-img-top" alt="<?php echo $libro['titulo']; ?>">
                        </div>
                        
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $libro['titulo']; ?></h5>
                            <p class="card-text">Autor: <?php echo $libro['autor']; ?></p>
                            <p class="card-text">Categoría: <?php echo $libro['categoria']; ?></p>
                            <span class="availability <?php echo $disponibilidadClase; ?>"><?php echo $disponibilidad; ?></span>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</body>
</html>
