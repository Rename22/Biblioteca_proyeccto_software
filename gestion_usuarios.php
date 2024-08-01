<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

include 'php/conexion_be.php';

$errorCorreo = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['accion'])) {
        $accion = $_POST['accion'];
        
        if ($accion == 'insertar') {
            $nombre = $_POST['nombre'];
            $apellido = $_POST['apellido'];
            $correo = $_POST['correo'];
            $contrasena = $_POST['contrasena'];
            $celular = $_POST['celular'];
            $role = $_POST['role'];

            // Verificar si el correo ya existe
            $query = "SELECT * FROM usuarios WHERE correo = '$correo'";
            $result = mysqli_query($conexion, $query);

            if (mysqli_num_rows($result) > 0) {
                $errorCorreo = 'El correo ya está registrado.';
            } else {
                $query = "INSERT INTO usuarios (nombre, apellido, correo, contrasena, celular, role) VALUES ('$nombre', '$apellido', '$correo', '$contrasena', '$celular', '$role')";
                if (mysqli_query($conexion, $query)) {
                    $_SESSION['mensaje'] = 'Usuario registrado con éxito';
                    $_SESSION['tipo_mensaje'] = 'success';
                    header("Location: gestion_usuarios.php");
                    exit();
                } else {
                    echo "<script>alert('Error al registrar usuario');</script>";
                }
            }
        } elseif ($accion == 'actualizar') {
            $id = $_POST['id'];
            $nombre = $_POST['nombre'];
            $apellido = $_POST['apellido'];
            $correo = $_POST['correo'];
            $celular = $_POST['celular'];
            $role = $_POST['role'];

            $query = "UPDATE usuarios SET nombre='$nombre', apellido='$apellido', correo='$correo', celular='$celular', role='$role' WHERE id=$id";
            if (mysqli_query($conexion, $query)) {
                $_SESSION['mensaje'] = 'Usuario actualizado con éxito';
                $_SESSION['tipo_mensaje'] = 'success';
                header("Location: gestion_usuarios.php");
                exit();
            } else {
                echo "<script>alert('Error al actualizar usuario');</script>";
            }
        } elseif ($accion == 'eliminar') {
            $id = $_POST['id'];
            $query = "DELETE FROM usuarios WHERE id=$id";
            if (mysqli_query($conexion, $query)) {
                $_SESSION['mensaje'] = 'Usuario eliminado con éxito';
                $_SESSION['tipo_mensaje'] = 'success';
                header("Location: gestion_usuarios.php");
                exit();
            }
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>
<body>
    <div class="container mt-4">
        <h1>Gestión de usuarios</h1>
        <a href="php/logout.php" class="btn btn-secondary">Cerrar sesión</a>
        <a href="bienvenido.php" class="btn btn-primary">Volver a Bienvenido</a>
        <div class="d-flex justify-content-between align-items-center my-3">
            <input type="text" id="searchInput" class="form-control w-75 light-table-filter" data-table="order-table" placeholder="Buscar usuarios...">
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
                    <th>Rol</th>
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
                    <td><?php echo $usuario['role']; ?></td>
                    <td>
                        <form action="gestion_usuarios.php" method="POST" style="display:inline;">
                            <input type="hidden" name="accion" value="eliminar">
                            <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                        </form>
                        <button class="btn btn-primary" onclick="editarUsuario(<?php echo $usuario['id']; ?>, '<?php echo $usuario['nombre']; ?>', '<?php echo $usuario['apellido']; ?>', '<?php echo $usuario['correo']; ?>', '<?php echo $usuario['celular']; ?>', '<?php echo $usuario['role']; ?>')">Editar</button>
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
                            <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre" required pattern="[A-Za-z]{1,15}" oninput="validarLetras(this)" onblur="formatearNombre(this)">
                        </div>
                        <div class="form-group">
                            <label for="apellido">Apellido</label>
                            <input type="text" class="form-control" name="apellido" id="apellido" placeholder="Apellido" required pattern="[A-Za-z]{1,15}" oninput="validarLetras(this)" onblur="formatearNombre(this)">
                        </div>
                        <div class="form-group">
                            <label for="correo">Correo</label>
                            <input type="email" class="form-control" name="correo" id="correo" placeholder="Correo" required>
                            <span id="error_correo" style="color: red; display: <?php echo !empty($errorCorreo) ? 'block' : 'none'; ?>;"><?php echo $errorCorreo; ?></span>
                        </div>
                        <div class="form-group">
                            <label for="contrasena">Contraseña</label>
                            <input type="password" class="form-control" name="contrasena" id="contrasena" placeholder="Contraseña" required>
                        </div>
                        <div class="form-group">
                            <label for="celular">Celular</label>
                            <input type="text" class="form-control" id="celular" placeholder="Celular" name="celular" required pattern="^09\d{8}$" oninput="validarCelular()">
                            <span id="error_celular" style="color: red; display: none;">Número de celular incorrecto</span>
                        </div>
                        <div class="form-group">
                            <label for="role">Rol</label>
                            <select class="form-control" name="role" id="role" required>
                                <option value="admin">Administrador</option>
                                <option value="worker">Trabajador</option>
                                <option value="student">Estudiante</option>
                            </select>
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
                            <input type="text" class="form-control" name="nombre" id="nombreActualizar" placeholder="Nombre" required pattern="[A-Za-z]{1,15}" oninput="validarLetras(this)" onblur="formatearNombre(this)">
                        </div>
                        <div class="form-group">
                            <label for="apellidoActualizar">Apellido</label>
                            <input type="text" class="form-control" name="apellido" id="apellidoActualizar" placeholder="Apellido" required pattern="[A-Za-z]{1,15}" oninput="validarLetras(this)" onblur="formatearNombre(this)">
                        </div>
                        <div class="form-group">
                            <label for="correoActualizar">Correo</label>
                            <input type="email" class="form-control" name="correo" id="correoActualizar" placeholder="Correo" required>
                        </div>
                        <div class="form-group">
                            <label for="celularActualizar">Celular</label>
                            <input type="text" class="form-control" name="celular" id="celularActualizar" placeholder="Celular" required pattern="^09\d{8}$" oninput="validarCelularActualizar()">
                            <span id="error_celularActualizar" style="color: red; display: none;">Número de celular incorrecto</span>
                        </div>
                        <div class="form-group">
                            <label for="roleActualizar">Rol</label>
                            <select class="form-control" name="role" id="roleActualizar" required>
                                <option value="admin">Administrador</option>
                                <option value="worker">Trabajador</option>
                                <option value="student">Estudiante</option>
                            </select>
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

    <script>
        // Validar campos de usuarios
        function validarLetras(input) {
            input.value = input.value.replace(/[^a-zA-Z]/g, '');
        }

        function formatearNombre(input) {
            input.value = input.value.charAt(0).toUpperCase() + input.value.slice(1).toLowerCase();
        }

        function validarCorreo() {
            var correo = document.getElementById("correo").value;
            var error_correo = document.getElementById("error_correo");

            if (!correo.includes('@')) {
                error_correo.style.display = "block";
                return false;
            } else {
                error_correo.style.display = "none";
                return true;
            }
        }

        // Validar número de celular
        function validarCelular() {
            var celular = document.getElementById("celular").value;
            var error_celular = document.getElementById("error_celular");

            var regex = /^09\d{8}$/;
            if (!regex.test(celular)) {
                error_celular.style.display = "block";
                return false;
            } else {
                error_celular.style.display = "none";
                return true;
            }
        }

        // Validar número de celular al actualizar
        function validarCelularActualizar() {
            var celular = document.getElementById("celularActualizar").value;
            var error_celularActualizar = document.getElementById("error_celularActualizar");

            var regex = /^09\d{8}$/;
            if (!regex.test(celular)) {
                error_celularActualizar.style.display = "block";
                return false;
            } else {
                error_celularActualizar.style.display = "none";
                return true;
            }
        }

        // Validar todo el formulario al insertar
        function validarFormularioUsuarios() {
            var nombreValido = validarLetras(document.getElementById("nombre"));
            var apellidoValido = validarLetras(document.getElementById("apellido"));
            var correoValido = validarCorreo();
            var celularValido = validarCelular();
            return nombreValido && apellidoValido && correoValido && celularValido;
        }

        // Validar todo el formulario al actualizar
        function validarFormularioUsuarioActualizar() {
            var nombreValido = validarLetras(document.getElementById("nombreActualizar"));
            var apellidoValido = validarLetras(document.getElementById("apellidoActualizar"));
            var correoValido = validarCorreo();
            var celularValido = validarCelularActualizar();
            return nombreValido && apellidoValido && correoValido && celularValido;
        }

        $(document).ready(function() {
            // Validación en tiempo real del campo celular al insertar
            $("#celular").on("input", function() {
                validarCelular();
            });

            // Validación en tiempo real del campo celular al actualizar
            $("#celularActualizar").on("input", function() {
                validarCelularActualizar();
            });

            // Mostrar SweetAlert para mensajes de éxito o error
            <?php if (isset($_SESSION['mensaje'])): ?>
                Swal.fire({
                    icon: '<?php echo $_SESSION['tipo_mensaje']; ?>',
                    title: '<?php echo $_SESSION['mensaje']; ?>',
                    showConfirmButton: false,
                    timer: 1500
                });
                <?php unset($_SESSION['mensaje']); unset($_SESSION['tipo_mensaje']); ?>
            <?php endif; ?>
        });

        function editarUsuario(id, nombre, apellido, correo, celular, role) {
            document.getElementById('idActualizarUsuario').value = id;
            document.getElementById('nombreActualizar').value = nombre;
            document.getElementById('apellidoActualizar').value = apellido;
            document.getElementById('correoActualizar').value = correo;
            document.getElementById('celularActualizar').value = celular;
            document.getElementById('roleActualizar').value = role;
            $('#editarUsuarioModal').modal('show');
        }
    </script>
</body>
</html>
