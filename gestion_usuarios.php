<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

include 'php/conexion_be.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['accion'])) {
        $accion = $_POST['accion'];
        
        if ($accion == 'insertar') {
            $nombre = $_POST['nombre'];
            $apellido = $_POST['apellido'];
            $correo = $_POST['correo'];
            $contrasena = $_POST['contrasena'];
            $celular = $_POST['celular'];

            $query = "INSERT INTO usuarios (nombre, apellido, correo, contrasena, celular) VALUES ('$nombre', '$apellido', '$correo', '$contrasena', '$celular')";
            if (mysqli_query($conexion, $query)) {
                echo "<script>alert('Usuario registrado con éxito');</script>";
            } else {
                echo "<script>alert('Error al registrar usuario');</script>";
            }
        } elseif ($accion == 'actualizar') {
            $id = $_POST['id'];
            $nombre = $_POST['nombre'];
            $apellido = $_POST['apellido'];
            $correo = $_POST['correo'];
            $celular = $_POST['celular'];

            $query = "UPDATE usuarios SET nombre='$nombre', apellido='$apellido', correo='$correo', celular='$celular' WHERE id=$id";
            if (mysqli_query($conexion, $query)) {
                echo "<script>alert('Usuario actualizado con éxito');</script>";
            } else {
                echo "<script>alert('Error al actualizar usuario');</script>";
            }
        } elseif ($accion == 'eliminar') {
            $id = $_POST['id'];
            $query = "DELETE FROM usuarios WHERE id=$id";
            mysqli_query($conexion, $query);
        }
    }
}

// Obtener los usuarios de la base de datos para la carga inicial
$query = "SELECT * FROM usuarios";
$resultado = mysqli_query($conexion, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de usuarios</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container mt-4">
        <h1>Gestión de usuarios</h1>
        <a href="php/logout.php" class="btn btn-secondary">Cerrar sesión</a>
        <a href="bienvenido.php" class="btn btn-primary">Volver a Bienvenido</a>
        <div class="d-flex justify-content-between align-items-center my-3">
            <input type="text" id="searchInput" class="form-control w-75" data-table="order-table" placeholder="Buscar usuarios...">
            <button class="btn btn-success" data-toggle="modal" data-target="#insertarUsuarioModal">Insertar Usuario</button>
        </div>
        <table class="table table-bordered order-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Correo</th>
                    <th>Celular</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="usuariosTable">
                <?php while ($usuario = mysqli_fetch_assoc($resultado)) { ?>
                <tr>
                    <td><?php echo $usuario['id']; ?></td>
                    <td><?php echo $usuario['nombre']; ?></td>
                    <td><?php echo $usuario['apellido']; ?></td>
                    <td><?php echo $usuario['correo']; ?></td>
                    <td><?php echo $usuario['celular']; ?></td>
                    <td>
                        <form action="gestion_usuarios.php" method="POST" style="display:inline;">
                            <input type="hidden" name="accion" value="eliminar">
                            <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                        </form>
                        <button class="btn btn-primary" onclick="editarUsuario(<?php echo $usuario['id']; ?>, '<?php echo $usuario['nombre']; ?>', '<?php echo $usuario['apellido']; ?>', '<?php echo $usuario['correo']; ?>', '<?php echo $usuario['celular']; ?>')">Editar</button>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Modal para insertar usuario -->
    <div class="modal fade" id="insertarUsuarioModal" tabindex="-1" aria-labelledby="insertarUsuarioModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="insertarUsuarioModalLabel">Insertar Usuario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="gestion_usuarios.php" method="POST" onsubmit="return validarFormularioUsuarios()">
                    <div class="modal-body">
                        <input type="hidden" name="accion" value="insertar">
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre" required oninput="validarLetras(this)" onblur="formatearNombre(this)">
                        </div>
                        <div class="form-group">
                            <label for="apellido">Apellido</label>
                            <input type="text" class="form-control" name="apellido" id="apellido" placeholder="Apellido" required oninput="validarLetras(this)" onblur="formatearNombre(this)">
                        </div>
                        <div class="form-group">
                            <label for="correo">Correo</label>
                            <input type="email" class="form-control" name="correo" id="correo" placeholder="Correo" required>
                        </div>
                        <div class="form-group">
                            <label for="contrasena">Contraseña</label>
                            <input type="password" class="form-control" name="contrasena" id="contrasena" placeholder="Contraseña" required>
                        </div>
                        <div class="form-group">
                            <label for="celular">Celular</label>
                            <input type="text" class="form-control" id="celular" placeholder="Celular" name="celular" required oninput="validarCelular()">
                            <span id="error_celular" style="color: red; display: none;">Número de celular incorrecto</span>
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

    <!-- Modal para editar usuario -->
    <div class="modal fade" id="editarUsuarioModal" tabindex="-1" aria-labelledby="editarUsuarioModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editarUsuarioModalLabel">Editar Usuario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formActualizar" action="gestion_usuarios.php" method="POST" onsubmit="return validarFormularioUsuarioActualizar()">
                    <div class="modal-body">
                        <input type="hidden" name="accion" value="actualizar">
                        <input type="hidden" name="id" id="idActualizarUsuario">
                        <div class="form-group">
                            <label for="nombreActualizar">Nombre</label>
                            <input type="text" class="form-control" name="nombre" id="nombreActualizar" placeholder="Nombre" required oninput="validarLetras(this)" onblur="formatearNombre(this)">
                        </div>
                        <div class="form-group">
                            <label for="apellidoActualizar">Apellido</label>
                            <input type="text" class="form-control" name="apellido" id="apellidoActualizar" placeholder="Apellido" required oninput="validarLetras(this)" onblur="formatearNombre(this)">
                        </div>
                        <div class="form-group">
                            <label for="correoActualizar">Correo</label>
                            <input type="email" class="form-control" name="correo" id="correoActualizar" placeholder="Correo" required>
                        </div>
                        <div class="form-group">
                            <label for="celularActualizar">Celular</label>
                            <input type="text" class="form-control" name="celular" id="celularActualizar" placeholder="Celular" required>
                            <span id="error_celularActualizar" style="color: red; display: none;">Número de celular incorrecto</span>
                        
                            
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

    <script src="assets/js/buscador.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>
