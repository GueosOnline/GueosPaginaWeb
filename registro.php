<?php

require 'config/config.php';
require 'config/database.php';
require 'clases/clienteFunciones.php';

$db = new Database();
$con = $db->conectar();

$errors = [];

if (!empty($_POST)) {

    $nombres = trim($_POST['nombres']);
    $apellidos = trim($_POST['apellidos']);
    $email = trim($_POST['email']);
    $telefono = trim($_POST['telefono']);
    $cedula = trim($_POST['cedula']);
    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);
    $repassword = trim($_POST['repassword']);

    if (esNulo([$nombres, $apellidos, $email, $telefono, $cedula, $usuario, $password, $repassword])) {
        $errors[] = "Debe llenar todos los campos";
    }

    if (!esEmail($email)) {
        $errors[] = "La dirección de correo electronico no es valida";
    }

    if (!validaPassword($password, $repassword)) {
        $errors[] = "Las contraseñas no coinciden";
    }

    if (usuarioExiste($usuario, $con)) {
        $errors[] = "El nombre de usuario $usuario ya existe";
    }

    if (emailExiste($email, $con)) {
        $errors[] = "El correo electronico $email ya existe";
    }

    if (count($errors) == 0) {

        $id = registraCliente([$nombres, $apellidos, $email, $telefono, $cedula], $con);

        if ($id > 0) {

            require 'clases/Mailer.php';
            $mailer = new Mailer();
            $token = generarToken();
            $pass_hash = password_hash($password, PASSWORD_DEFAULT);

            $idUsuario = registraUsuario([$usuario, $pass_hash, $token, $id], $con);

            if ($idUsuario > 0) {

                $url = SITE_URL . '/activa_cliente.php?id=' . $idUsuario . '&token=' . $token;
                //http://localhost:8080/PaginaWeb/activa_cliente.php?id=3&token=fb40b14311c73c05e9ae0beb8341e2e4

                $asunto = "Activar cuenta - Tienda";
                $cuerpo = "Estimado $nombres: <br> Para continuar con el proceso de registro es indispensable la confirmacion de su correo electronico de click en el siguiente link: <a href='$url'>Activar cuenta</a>";

                if ($mailer->enviarEmail($email, $asunto, $cuerpo)) {
                    echo "Para terminar el proceso de registro siga las instrucciones que le hemos enviado a la direccion de correo electronico: $email";

                    exit;
                }
            } else {
                $errors[] = "Error al registrar usuario";
            }
        } else {
            $errors[] = "Error al registrar cliente";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-compatible" content="IEwedge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda Online</title>

    <!-- Boostrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="css/estilos.css" rel="stylesheet">
</head>

<body>
    <!--Barra de Navegación-->
    <header>
        <div class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a href="#" class="navbar-brand">
                    <img src="images/productos/1/Logo.png" alt="Logo" class="img-fluid" style="max-height: 60px; margin-right: 10px;">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarHeader">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a href="#" class="nav-link active">Catalogo </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link ">Contacto </a>
                        </li>
                    </ul>
                    <a href="checkout.php" class="btn btn-primary">
                        Carrito <span id="num_cart" class="badge bg-secondary"><?php echo $num_cart; ?></span>
                    </a>
                </div>
            </div>
    </header>

    <!--Contenido-->
    <main>
        <div class="container">
            <h2>Datos del Cliente</h2>

            <?php mostrarMensajes($errors); ?>

            <form class="row g-3" action="registro.php" method="post" autocomplete="off">
                <div class="col-md-6">
                    <label for="nombres"><span class="text-danger">*</span>Nombres</label>
                    <input type="text" name="nombres" id="nombres" class="form-control" requireda>
                </div>
                <div class="col-md-6">
                    <label for="apellidos"><span class="text-danger">*</span>Apellidos</label>
                    <input type="text" name="apellidos" id="apellidos" class="form-control" requireda>
                </div>
                <div class="col-md-6">
                    <label for="email"><span class="text-danger">*</span>Correo Electronico</label>
                    <input type="email" name="email" id="email" class="form-control" requireda>
                    <span id="validaEmail" class="text-danger"></span>
                </div>
                <div class="col-md-6">
                    <label for="telefono"><span class="text-danger">*</span>Telefono</label>
                    <input type="tel" name="telefono" id="telefono" class="form-control" requireda>
                </div>
                <div class="col-md-6">
                    <label for="cedula"><span class="text-danger">*</span>Cedula</label>
                    <input type="text" name="cedula" id="cedula" class="form-control" requireda>
                </div>
                <div class="col-md-6">
                    <label for="usuario"><span class="text-danger">*</span>Usuario</label>
                    <input type="text" name="usuario" id="usuario" class="form-control" requireda>
                    <span id="validaUsuario" class="text-danger"></span>
                </div>
                <div class="col-md-6">
                    <label for="password"><span class="text-danger">*</span>Contraseña</label>
                    <input type="password" name="password" id="password" class="form-control" requireda>
                </div>
                <div class="col-md-6">
                    <label for="repassword"><span class="text-danger">*</span>Repetir Contraseña</label>
                    <input type="password" name="repassword" id="repassword" class="form-control" requireda>
                </div>

                <i><b>Nota: </b>Los campos con asterisco son obligatorios</i>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Registrar</button>
                </div>
            </form>

        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script>
        let textUsuario = document.getElementById('usuario')
        textUsuario.addEventListener("blur", function() {
            existeUsuario(textUsuario.value)
        }, false)


        let textEmail = document.getElementById('email')
        textEmail.addEventListener("blur", function() {
            existeEmail(textEmail.value)
        }, false)

        function existeUsuario(usuario) {
            let url = "clases/clienteAjax.php"
            let formData = new FormData()
            formData.append("action", "existeUsuario")
            formData.append("usuario", usuario)

            fetch(url, {
                    method: 'POST',
                    body: formData
                }).then(response => response.json())
                .then(data => {

                    if (data.ok) {
                        document.getElementById('usuario').value = ''
                        document.getElementById('validaUsuario').innerHTML = 'Usuario no disponible'
                    } else {
                        document.getElementById('validaUsuario').innerHTML = ''
                    }
                })
        }


        function existeEmail(email) {
            let url = "clases/clienteAjax.php"
            let formData = new FormData()
            formData.append("action", "existeEmail")
            formData.append("email", email)

            fetch(url, {
                    method: 'POST',
                    body: formData
                }).then(response => response.json())
                .then(data => {
                    if (data.ok) {
                        document.getElementById('email').value = ''
                        document.getElementById('validaEmail').innerHTML = 'Correo Electronico en Uso'
                    } else {
                        document.getElementById('validaEmail').innerHTML = ''
                    }
                })
        }
    </script>


</body>

</html>