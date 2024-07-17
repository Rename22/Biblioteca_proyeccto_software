<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

include 'php/conexion_be.php';

// Manejar la inserción, actualización y eliminación de libros
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['accion'])) {
        $accion = $_POST['accion'];
        
        if ($accion == 'insertar') {
            $titulo = $_POST['titulo'];
            $autor = $_POST['autor'];
            $editorial = $_POST['editorial'];
            $aniopubli = $_POST['aniopubli'];
            $categoria = $_POST['categoria'];
            $cantidad = $_POST['cantidad'];
            
            // Manejar la subida de la imagen
            $imagen = $_FILES['imagen']['name'];
            $rutaImagen = "imagenes/" . basename($imagen);
            move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaImagen);

            $query = "INSERT INTO libros (titulo, autor, editorial, aniopubli, categoria, cantidad, imagen) VALUES ('$titulo', '$autor', '$editorial', $aniopubli, '$categoria', $cantidad, '$rutaImagen')";
            mysqli_query($conexion, $query);
        } elseif ($accion == 'actualizar') {
            $id = $_POST['id'];
            $titulo = $_POST['titulo'];
            $autor = $_POST['autor'];
            $editorial = $_POST['editorial'];
            $aniopubli = $_POST['aniopubli'];
            $categoria = $_POST['categoria'];
            $cantidad = $_POST['cantidad'];
            
            // Manejar la subida de la imagen
            if ($_FILES['imagen']['name']) {
                $imagen = $_FILES['imagen']['name'];
                $rutaImagen = "imagenes/" . basename($imagen);
                move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaImagen);
                $query = "UPDATE libros SET titulo='$titulo', autor='$autor', editorial='$editorial', aniopubli=$aniopubli, categoria='$categoria', cantidad=$cantidad, imagen='$rutaImagen' WHERE id=$id";
            } else {
                $query = "UPDATE libros SET titulo='$titulo', autor='$autor', editorial='$editorial', aniopubli=$aniopubli, categoria='$categoria', cantidad=$cantidad WHERE id=$id";
            }

            mysqli_query($conexion, $query);
        } elseif ($accion == 'eliminar') {
            $id = $_POST['id'];
            $query = "DELETE FROM libros WHERE id=$id";
            mysqli_query($conexion, $query);
        }
    }
}

// Obtener los libros de la base de datos para la carga inicial
$query = "SELECT * FROM libros";
$resultado = mysqli_query($conexion, $query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de libros</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container mt-4">
        <h1>Gestión de libros</h1>
        
        <a href="php/logout.php" class="btn btn-secondary">Cerrar sesión</a>
        <a href="bienvenido.php" class="btn btn-primary">Volver a Bienvenido</a>
        <div class="d-flex justify-content-between align-items-center my-3">
            <input type="text" id="searchInput" class="form-control w-75 light-table-filter" data-table="order-table" placeholder="Buscar libros...">
            <button class="btn btn-success" data-toggle="modal" data-target="#insertarLibroModal">Insertar Libro</button>
        </div>

        <table class="table table-bordered order-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Autor</th>
                    <th>Editorial</th>
                    <th>Año de Publicación</th>
                    <th>Categoría</th>
                    <th>Cantidad</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="librosTable">
                <?php while ($libro = mysqli_fetch_assoc($resultado)) { ?>
                <tr>
                    <td><?php echo $libro['id']; ?></td>
                    <td><?php echo $libro['titulo']; ?></td>
                    <td><?php echo $libro['autor']; ?></td>
                    <td><?php echo $libro['editorial']; ?></td>
                    <td><?php echo $libro['aniopubli']; ?></td>
                    <td><?php echo $libro['categoria']; ?></td>
                    <td><?php echo $libro['cantidad']; ?></td>
                    <td>
                        <form action="gestion_libros.php" method="POST" style="display:inline;">
                            <input type="hidden" name="accion" value="eliminar">
                            <input type="hidden" name="id" value="<?php echo $libro['id']; ?>">
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                        </form>
                        <button class="btn btn-primary" onclick="editarLibro(<?php echo $libro['id']; ?>, '<?php echo $libro['titulo']; ?>', '<?php echo $libro['autor']; ?>', '<?php echo $libro['editorial']; ?>', <?php echo $libro['aniopubli']; ?>, '<?php echo $libro['categoria']; ?>', <?php echo $libro['cantidad']; ?>, '<?php echo $libro['imagen']; ?>')">Editar</button>
                        <button class="btn btn-info" onclick="verLibro(<?php echo $libro['id']; ?>, '<?php echo $libro['titulo']; ?>', '<?php echo $libro['autor']; ?>', '<?php echo $libro['editorial']; ?>', <?php echo $libro['aniopubli']; ?>, '<?php echo $libro['categoria']; ?>', <?php echo $libro['cantidad']; ?>', '<?php echo $libro['imagen']; ?>')">Ver</button>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Modal para insertar libro -->
    <div class="modal fade" id="insertarLibroModal" tabindex="-1" aria-labelledby="insertarLibroModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="insertarLibroModalLabel">Insertar Libro</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="gestion_libros.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="accion" value="insertar">
                        <div class="form-group">
                            <label for="titulo">Título</label>
                            <input type="text" class="form-control" name="titulo" placeholder="Título" required>
                        </div>
                        <div class="form-group">
                            <label for="autor">Autor</label>
                            <input type="text" class="form-control" name="autor" placeholder="Autor" required>
                        </div>
                        <div class="form-group">
                            <label for="editorial">Editorial</label>
                            <input type="text" class="form-control" name="editorial" placeholder="Editorial">
                        </div>
                        <div class="form-group">
                            <label for="aniopubli">Año de Publicación</label>
                            <input type="number" class="form-control" name="aniopubli" placeholder="Año de Publicación">
                        </div>
                        <div class="form-group">
                            <label for="categoria">Categoría</label>
                            <input type="text" class="form-control" name="categoria" placeholder="Categoría">
                        </div>
                        <div class="form-group">
                            <label for="cantidad">Cantidad</label>
                            <input type="number" class="form-control" name="cantidad" placeholder="Cantidad" required>
                        </div>
                        <div class="form-group">
                            <label for="imagen">Imagen del libro</label>
                            <input type="file" class="form-control" name="imagen" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-success">Insertar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para editar libro -->
    <div class="modal fade" id="editarLibroModal" tabindex="-1" aria-labelledby="editarLibroModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editarLibroModalLabel">Editar Libro</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formActualizar" action="gestion_libros.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="accion" value="actualizar">
                        <input type="hidden" name="id" id="idActualizar">
                        <div class="form-group">
                            <label for="tituloActualizar">Título</label>
                            <input type="text" class="form-control" name="titulo" id="tituloActualizar" placeholder="Título" required>
                        </div>
                        <div class="form-group">
                            <label for="autorActualizar">Autor</label>
                            <input type="text" class="form-control" name="autor" id="autorActualizar" placeholder="Autor" required>
                        </div>
                        <div class="form-group">
                            <label for="editorialActualizar">Editorial</label>
                            <input type="text" class="form-control" name="editorial" id="editorialActualizar" placeholder="Editorial">
                        </div>
                        <div class="form-group">
                            <label for="aniopubliActualizar">Año de Publicación</label>
                            <input type="number" class="form-control" name="aniopubli" id="aniopubliActualizar" placeholder="Año de Publicación">
                        </div>
                        <div class="form-group">
                            <label for="categoriaActualizar">Categoría</label>
                            <input type="text" class="form-control" name="categoria" id="categoriaActualizar" placeholder="Categoría">
                        </div>
                        <div class="form-group">
                            <label for="cantidadActualizar">Cantidad</label>
                            <input type="number" class="form-control" name="cantidad" id="cantidadActualizar" placeholder="Cantidad" required>
                        </div>
                        <div class="form-group">
                            <label for="imagenActualizar">Imagen del libro</label>
                            <input type="file" class="form-control" name="imagen" id="imagenActualizar">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para ver libro -->
    <div class="modal fade" id="verLibroModal" tabindex="-1" aria-labelledby="verLibroModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="verLibroModalLabel">Ver Libro</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="tituloVer">Título</label>
                        <input type="text" class="form-control" id="tituloVer" disabled>
                    </div>
                    <div class="form-group">
                        <label for="autorVer">Autor</label>
                        <input type="text" class="form-control" id="autorVer" disabled>
                    </div>
                    <div class="form-group">
                        <label for="editorialVer">Editorial</label>
                        <input type="text" class="form-control" id="editorialVer" disabled>
                    </div>
                    <div class="form-group">
                        <label for="aniopubliVer">Año de Publicación</label>
                        <input type="number" class="form-control" id="aniopubliVer" disabled>
                    </div>
                    <div class="form-group">
                        <label for="categoriaVer">Categoría</label>
                        <input type="text" class="form-control" id="categoriaVer" disabled>
                    </div>
                    <div class="form-group">
                        <label for="cantidadVer">Cantidad</label>
                        <input type="number" class="form-control" id="cantidadVer" disabled>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/buscador.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>
