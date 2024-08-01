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
            $_SESSION['mensaje'] = 'Libro registrado con éxito';
            $_SESSION['tipo_mensaje'] = 'success';
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
            $_SESSION['mensaje'] = 'Libro actualizado con éxito';
            $_SESSION['tipo_mensaje'] = 'success';
        } elseif ($accion == 'eliminar') {
            $id = $_POST['id'];
            $query = "DELETE FROM libros WHERE id=$id";
            mysqli_query($conexion, $query);
            $_SESSION['mensaje'] = 'Libro eliminado con éxito';
            $_SESSION['tipo_mensaje'] = 'success';
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
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
                <form action="gestion_libros.php" method="POST" enctype="multipart/form-data" onsubmit="return validarFormularioLibros()">
                    <div class="modal-body">
                        <input type="hidden" name="accion" value="insertar">
                        <div class="form-group">
                            <label for="titulo">Título</label>
                            <input type="text" class="form-control" name="titulo" id="titulo" placeholder="Título" required oninput="formatearTitulo(this)">
                        </div>
                        <div class="form-group">
                            <label for="autor">Autor</label>
                            <input type="text" class="form-control" name="autor" id="autor" placeholder="Autor" required oninput="validarLetras(this)" onblur="formatearNombre(this)">
                        </div>
                        <div class="form-group">
                            <label for="editorial">Editorial</label>
                            <input type="text" class="form-control" name="editorial" id="editorial" placeholder="Editorial" required oninput="validarLetrasEspacios(this)" onblur="formatearNombre(this)">
                        </div>
                        <div class="form-group">
                            <label for="aniopubli">Año de Publicación</label>
                            <input type="number" class="form-control" name="aniopubli" id="aniopubli" placeholder="Año de Publicación" min="1500" max="<?php echo date('Y'); ?>" required oninput="validarAnioPublicacion(this)">
                        </div>
                        <div class="form-group">
                            <label for="categoria">Categoría</label>
                            <input type="text" class="form-control" name="categoria" id="categoria" placeholder="Categoría" required list="categoriasList" oninput="validarLetrasEspacios(this)" onblur="formatearNombre(this)">
                            <datalist id="categoriasList">
                                <?php
                                $categoriasQuery = "SELECT DISTINCT categoria FROM libros";
                                $categoriasResult = mysqli_query($conexion, $categoriasQuery);
                                while ($categoria = mysqli_fetch_assoc($categoriasResult)) {
                                    echo "<option value='".$categoria['categoria']."'>";
                                }
                                ?>
                            </datalist>
                        </div>
                        <div class="form-group">
                            <label for="cantidad">Cantidad</label>
                            <input type="number" class="form-control" name="cantidad" id="cantidad" placeholder="Cantidad" min="0" required>
                        </div>
                        <div class="form-group">
                            <label for="imagen">Imagen del libro</label>
                            <input type="file" class="form-control" name="imagen" id="imagen" required onchange="mostrarVistaPrevia(event)">
                            <div id="vistaPreviaImagen" style="display: none; margin-top: 10px;">
                                <img id="imgPreview" src="#" alt="Vista previa" width="450">
                                <br><br>
                                <button type="button" class="btn btn-warning" onclick="editarImagen()">Editar</button>
                                <button type="button" class="btn btn-danger" onclick="eliminarImagen()">Eliminar</button>
                            </div>
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
                <form id="formActualizar" action="gestion_libros.php" method="POST" enctype="multipart/form-data" onsubmit="return validarFormularioLibrosActualizar()">
                    <div class="modal-body">
                        <input type="hidden" name="accion" value="actualizar">
                        <input type="hidden" name="id" id="idActualizar">
                        <div class="form-group">
                            <label for="tituloActualizar">Título</label>
                            <input type="text" class="form-control" name="titulo" id="tituloActualizar" placeholder="Título" required oninput="formatearTitulo(this)">
                        </div>
                        <div class="form-group">
                            <label for="autorActualizar">Autor</label>
                            <input type="text" class="form-control" name="autor" id="autorActualizar" placeholder="Autor" required oninput="validarLetras(this)" onblur="formatearNombre(this)">
                        </div>
                        <div class="form-group">
                            <label for="editorialActualizar">Editorial</label>
                            <input type="text" class="form-control" name="editorial" id="editorialActualizar" placeholder="Editorial" required oninput="validarLetrasEspacios(this)" onblur="formatearNombre(this)">
                        </div>
                        <div class="form-group">
                            <label for="aniopubliActualizar">Año de Publicación</label>
                            <input type="number" class="form-control" name="aniopubli" id="aniopubliActualizar" placeholder="Año de Publicación" min="1500" max="<?php echo date('Y'); ?>" required oninput="validarAnioPublicacion(this)">
                        </div>
                        <div class="form-group">
                            <label for="categoriaActualizar">Categoría</label>
                            <input type="text" class="form-control" name="categoria" id="categoriaActualizar" placeholder="Categoría" required list="categoriasList" oninput="validarLetrasEspacios(this)" onblur="formatearNombre(this)">
                        </div>
                        <div class="form-group">
                            <label for="cantidadActualizar">Cantidad</label>
                            <input type="number" class="form-control" name="cantidad" id="cantidadActualizar" placeholder="Cantidad" min="0" required>
                        </div>
                        <div class="form-group">
                            <label for="imagenActualizar">Imagen del libro</label>
                            <input type="file" class="form-control" name="imagen" id="imagenActualizar" onchange="mostrarVistaPreviaActualizar(event)">
                            <div id="vistaPreviaImagenActualizar" style="display: none; margin-top: 10px;">
                                <img id="imgPreviewActualizar" src="#" alt="Vista previa" width="400">
                                <button type="button" class="btn btn-warning" onclick="editarImagenActualizar()">Editar</button>
                                <button type="button" class="btn btn-danger" onclick="eliminarImagenActualizar()">Eliminar</button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary" id="btnActualizar">Actualizar</button>
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
                    <div class="form-group">
                        <label for="imagenVer">Imagen</label>
                        <img id="imagenVer" src="#" alt="Imagen del libro" width="200">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Mostrar SweetAlert para mensajes de éxito o error
        $(document).ready(function() {
            <?php if (isset($_SESSION['mensaje'])): ?>
                Swal.fire({
                    icon: '<?php echo $_SESSION['tipo_mensaje']; ?>',
                    title: '<?php echo $_SESSION['mensaje']; ?>',
                    showConfirmButton: false,
                    timer: 1500
                });
                <?php unset($_SESSION['mensaje']); unset($_SESSION['tipo_mensaje']); ?>
            <?php endif; ?>

            // Función de búsqueda en tiempo real
            $("#searchInput").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#librosTable tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });

            // Mostrar vista previa de la imagen al insertar
            window.mostrarVistaPrevia = function(event) {
                var reader = new FileReader();
                reader.onload = function() {
                    var output = document.getElementById('imgPreview');
                    output.src = reader.result;
                    document.getElementById('vistaPreviaImagen').style.display = 'block';
                    document.getElementById('imagen').style.display = 'none'; // Ocultar el input de archivo
                }
                reader.readAsDataURL(event.target.files[0]);
            }

            // Editar imagen al insertar
            window.editarImagen = function() {
                document.getElementById('imagen').click();
            }

            // Eliminar imagen al insertar
            window.eliminarImagen = function() {
                document.getElementById('imagen').value = '';
                document.getElementById('vistaPreviaImagen').style.display = 'none';
                document.getElementById('imagen').style.display = 'block'; // Mostrar el input de archivo
            }

            // Mostrar vista previa de la imagen al actualizar
            window.mostrarVistaPreviaActualizar = function(event) {
                var reader = new FileReader();
                reader.onload = function() {
                    var output = document.getElementById('imgPreviewActualizar');
                    output.src = reader.result;
                    document.getElementById('vistaPreviaImagenActualizar').style.display = 'block';
                    document.getElementById('imagenActualizar').style.display = 'none'; // Ocultar el input de archivo
                }
                reader.readAsDataURL(event.target.files[0]);
            }

            // Editar imagen al actualizar
            window.editarImagenActualizar = function() {
                document.getElementById('imagenActualizar').click();
            }

            // Eliminar imagen al actualizar
            window.eliminarImagenActualizar = function() {
                document.getElementById('imagenActualizar').value = '';
                document.getElementById('vistaPreviaImagenActualizar').style.display = 'none';
                document.getElementById('imagenActualizar').style.display = 'block'; // Mostrar el input de archivo
            }

            // Validaciones del formulario
            window.formatearTitulo = function(input) {
                input.value = input.value.charAt(0).toUpperCase() + input.value.slice(1).toLowerCase();
            }

            window.validarLetras = function(input) {
                input.value = input.value.replace(/[^a-zA-Z\s]/g, '');
            }

            window.validarLetrasEspacios = function(input) {
                input.value = input.value.replace(/[^a-zA-Z\s]/g, '');
            }

            window.formatearNombre = function(input) {
                input.value = input.value.charAt(0).toUpperCase() + input.value.slice(1).toLowerCase();
            }

            window.validarAnioPublicacion = function(input) {
                if (input.validity.rangeUnderflow) {
                    input.setCustomValidity("El año admitido debe ser mayor a 1500");
                } else {
                    input.setCustomValidity("");
                }
            }

            window.validarFormularioLibros = function() {
                var titulo = document.getElementById('titulo');
                var autor = document.getElementById('autor');
                var editorial = document.getElementById('editorial');
                var aniopubli = document.getElementById('aniopubli');
                var categoria = document.getElementById('categoria');
                var cantidad = document.getElementById('cantidad');
                var imagen = document.getElementById('imagen');

                formatearTitulo(titulo);
                formatearNombre(autor);
                formatearNombre(editorial);
                formatearNombre(categoria);

                if (titulo.value === '' || autor.value === '' || editorial.value === '' || aniopubli.value === '' || categoria.value === '' || cantidad.value === '' || imagen.value === '') {
                    return false;
                }
                return true;
            }

            window.validarFormularioLibrosActualizar = function() {
                var titulo = document.getElementById('tituloActualizar');
                var autor = document.getElementById('autorActualizar');
                var editorial = document.getElementById('editorialActualizar');
                var aniopubli = document.getElementById('aniopubliActualizar');
                var categoria = document.getElementById('categoriaActualizar');
                var cantidad = document.getElementById('cantidadActualizar');

                formatearTitulo(titulo);
                formatearNombre(autor);
                formatearNombre(editorial);
                formatearNombre(categoria);

                if (titulo.value === '' || autor.value === '' || editorial.value === '' || aniopubli.value === '' || categoria.value === '' || cantidad.value === '') {
                    return false;
                }
                return true;
            }
        });

        function editarLibro(id, titulo, autor, editorial, aniopubli, categoria, cantidad, imagen) {
            document.getElementById('idActualizar').value = id;
            document.getElementById('tituloActualizar').value = titulo;
            document.getElementById('autorActualizar').value = autor;
            document.getElementById('editorialActualizar').value = editorial;
            document.getElementById('aniopubliActualizar').value = aniopubli;
            document.getElementById('categoriaActualizar').value = categoria;
            document.getElementById('cantidadActualizar').value = cantidad;
            document.getElementById('imagenActualizar').value = '';
            if (imagen) {
                var output = document.getElementById('imgPreviewActualizar');
                output.src = imagen;
                document.getElementById('vistaPreviaImagenActualizar').style.display = 'block';
            }
            $('#editarLibroModal').modal('show');
        }

        function verLibro(id, titulo, autor, editorial, aniopubli, categoria, cantidad, imagen) {
            document.getElementById('tituloVer').value = titulo;
            document.getElementById('autorVer').value = autor;
            document.getElementById('editorialVer').value = editorial;
            document.getElementById('aniopubliVer').value = aniopubli;
            document.getElementById('categoriaVer').value = categoria;
            document.getElementById('cantidadVer').value = cantidad;
            if (imagen) {
                var output = document.getElementById('imagenVer');
                output.src = imagen;
            } else {
                document.getElementById('imagenVer').style.display = 'none';
            }
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
    </script>
</body>
</html>
