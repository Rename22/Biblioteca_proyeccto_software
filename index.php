<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/estilos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <main>
        <div class="contenedor__todo">
            <div class="caja__trasera">
                <div class="caja__trasera-login">
                    <h3>¿Ya tienes una cuenta?</h3>
                    <p>Inicia sesión para entrar en la página</p>
                    <button id="btn__iniciar-sesion">Iniciar Sesión</button>
                </div>
                <div class="caja__trasera-register">
                    <h3>¿Aún no tienes una cuenta?</h3>
                    <p>Regístrate para que puedas iniciar sesión</p>
                    <button id="btn__registrarse">Regístrarse</button>
                </div>
            </div>

            <!--Formulario de Login y registro-->
            <div class="contenedor__login-register">
                <!--Login-->
                <form action="php/verificar_login.php" method="POST" class="formulario__login">
                    <h2>Iniciar Sesión</h2>
                    <input type="text" placeholder="Correo Electronico" name="correo" required>
                    <div class="contenedor_contrasena">
                        <input type="password" placeholder="Contraseña" name="contrasena" id="loginPassword" required>
                        <i class="fa fa-eye" id="toggleLoginPassword" onmousedown="showPassword('loginPassword')" onmouseup="hidePassword('loginPassword')" onmouseout="hidePassword('loginPassword')"></i>
                    </div>
                    <button type="submit">Entrar</button>
                </form>

                <!--Register-->
                <form action="php/registro_usuario_be.php" method="POST" class="formulario__register" onsubmit="return validarFormulario()">
                    <h2>Regístrarse</h2>
                    <input type="text" id="nombre" placeholder="Nombre" name="nombre" required oninput="validarLetras(this)" onblur="formatearNombre(this)">
                    <input type="text" id="apellido" placeholder="Apellido" name="apellido" required oninput="validarLetras(this)" onblur="formatearNombre(this)">
                    <div class="contenedor_input">
                        <input type="text" id="correo" placeholder="Correo Electronico" name="correo" required oninput="validarCorreo()">
                        <span id="error_correo" style="color: red; display: none;">Por favor, ingrese un correo válido.</span>
                    </div>
                    
                    <!-- Campo de contraseña -->
                    <div class="contenedor_contrasena">
                        <input type="password" id="contrasena" placeholder="Contraseña" name="contrasena" required>
                        <i class="fas fa-eye" id="toggleContrasena" onmousedown="showPassword('contrasena')" onmouseup="hidePassword('contrasena')" onmouseleave="hidePassword('contrasena')"></i>
                    </div>

                    <!-- Campo de confirmar contraseña -->
                    <div class="contenedor_contrasena">
                        <input type="password" id="confirmar_contrasena" placeholder="Confirmar contraseña" name="confirmar_contrasena" required>
                        <i class="fas fa-eye" id="toggleConfirmarContrasena" onmousedown="showPassword('confirmar_contrasena')" onmouseup="hidePassword('confirmar_contrasena')" onmouseleave="hidePassword('confirmar_contrasena')"></i>
                    </div>
                    
                    <span id="mensaje_error" style="color: red; display: none;">Las contraseñas no coinciden.</span>
                    <div class="contenedor_input">
                        <input type="text" id="celular" placeholder="Celular" name="celular" required oninput="validarCelular()">
                        <span id="error_celular" style="color: red; display: none;">Ingesa un numero de celular correcto</span>
                    </div>
                    <button>Regístrarse</button>
                </form>
            </div>
        </div>
    </main>

    
    <script src="assets/js/script.js"></script>
</body>
</html>
