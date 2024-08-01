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

// Obtener el rol del usuario desde la sesión
$role = $_SESSION['role'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        <?php if ($role !== 'student'): ?>
            <a href="gestion_libros.php" class="btn btn-primary">Gestionar Libros</a>
            <a href="gestion_usuarios.php" class="btn btn-primary">Gestionar Usuarios</a>
            <button class="btn btn-success" data-toggle="modal" data-target="#prestamoModal">Registrar Préstamo</button>
            <button class="btn btn-info" data-toggle="modal" data-target="#devolucionModal">Registrar Devolución</button>
        <?php else: ?>
            <button class="btn btn-success" data-toggle="modal" data-target="#reservaModal">Reservar Libro</button>
        <?php endif; ?>
        <div class="d-flex justify-content-between align-items-center my-3">
            <input type="text" id="searchInput" class="form-control w-75" placeholder="Buscar libros...">
        </div>
        <div class="row mt-4" id="librosContainer">
            <?php while ($libro = mysqli_fetch_assoc($resultado)) { 
                $disponibilidad = $libro['cantidad'] > 0 ? "Disponible {$libro['cantidad_disponible']}/{$libro['cantidad']}" : "No disponible {$libro['cantidad_disponible']}/{$libro['cantidad']}";
                $disponibilidadClase = $libro['cantidad'] > 0 ? 'available' : 'not-available';
            ?>
                <div class="col-md-4 libro">
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

    <!-- Modal para registrar reserva -->
    <div class="modal fade" id="reservaModal" tabindex="-1" aria-labelledby="reservaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reservaModalLabel">Reservar Libro</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="reservaForm" action="procesar_reserva.php" method="post">
                    <div class="modal-body">
                        <input type="hidden" name="accion" value="reservar">
                        <div class="form-group">
                            <label for="libro">Libro</label>
                            <select name="libro_id" id="libro" class="form-control" required>
                                <?php
                                $query_libros = "SELECT * FROM libros";
                                $resultado_libros = mysqli_query($conexion, $query_libros);
                                while ($libro = mysqli_fetch_assoc($resultado_libros)) { ?>
                                    <option value="<?php echo $libro['id']; ?>"><?php echo $libro['titulo']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="cantidad">Cantidad</label>
                            <input type="number" name="cantidad" id="cantidad" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="fecha_devolucion">Fecha de Devolución</label>
                            <input type="date" name="fecha_devolucion" id="fecha_devolucion" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-success">Reservar Libro</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para registrar préstamo -->
    <div class="modal fade" id="prestamoModal" tabindex="-1" aria-labelledby="prestamoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="prestamoModalLabel">Registrar Préstamo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="prestamoForm" action="procesar_prestamo.php" method="post">
                    <div class="modal-body">
                        <input type="hidden" name="accion" value="insertar">
                        <div class="form-group">
                            <label for="usuario">Usuario</label>
                            <select name="usuario_id" id="usuario" class="form-control" required>
                                <?php
                                $query_usuarios = "SELECT * FROM usuarios";
                                $resultado_usuarios = mysqli_query($conexion, $query_usuarios);
                                while ($usuario = mysqli_fetch_assoc($resultado_usuarios)) { ?>
                                    <option value="<?php echo $usuario['id']; ?>"><?php echo $usuario['nombre']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="libro">Libro</label>
                            <select name="libro_id" id="libro" class="form-control" required>
                                <?php
                                $query_libros = "SELECT * FROM libros";
                                $resultado_libros = mysqli_query($conexion, $query_libros);
                                while ($libro = mysqli_fetch_assoc($resultado_libros)) { ?>
                                    <option value="<?php echo $libro['id']; ?>"><?php echo $libro['titulo']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="cantidad">Cantidad</label>
                            <input type="number" name="cantidad" id="cantidad" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="fecha_devolucion">Fecha de Devolución</label>
                            <input type="date" name="fecha_devolucion" id="fecha_devolucion" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-success">Registrar Préstamo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para registrar devolución -->
    <div class="modal fade" id="devolucionModal" tabindex="-1" aria-labelledby="devolucionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="devolucionModalLabel">Registrar Devolución</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="devolucionForm" action="procesar_devolucion.php" method="post">
                    <div class="modal-body">
                        <input type="hidden" name="accion" value="devolucion">
                        <div class="form-group">
                            <label for="usuario">Usuario</label>
                            <select name="usuario_id" id="usuario" class="form-control" required>
                                <?php
                                $query_usuarios = "SELECT * FROM usuarios";
                                $resultado_usuarios = mysqli_query($conexion, $query_usuarios);
                                while ($usuario = mysqli_fetch_assoc($resultado_usuarios)) { ?>
                                    <option value="<?php echo $usuario['id']; ?>"><?php echo $usuario['nombre']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="libro">Libro</label>
                            <select name="libro_id" id="libro" class="form-control" required>
                                <?php
                                $query_libros = "SELECT * FROM libros";
                                $resultado_libros = mysqli_query($conexion, $query_libros);
                                while ($libro = mysqli_fetch_assoc($resultado_libros)) { ?>
                                    <option value="<?php echo $libro['id']; ?>"><?php echo $libro['titulo']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="cantidad">Cantidad</label>
                            <input type="number" name="cantidad" id="cantidad" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-info">Registrar Devolución</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const searchInput = document.getElementById("searchInput");
            const libros = document.querySelectorAll(".libro");

            searchInput.addEventListener("keyup", function() {
                const searchText = searchInput.value.toLowerCase();
                libros.forEach(function(libro) {
                    const libroText = libro.textContent.toLowerCase();
                    if (libroText.indexOf(searchText) > -1) {
                        libro.style.display = "";
                    } else {
                        libro.style.display = "none";
                    }
                });
            });
        });
    </script>
</body>
</html>
