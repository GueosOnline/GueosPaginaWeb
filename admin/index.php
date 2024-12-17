<?php

require 'config/config.php';
require 'config/database.php';
require 'clases/adminFunciones.php';

$db = new Database();
$con = $db->conectar();
/*
$password = password_hash('admin', PASSWORD_DEFAULT);
$sql = "INSERT INTO admin (usuario, password, nombre, email, activo, fecha_alta) VALUES('admin', '$password', 'Administrador', 'mianrobe321@gmail.com','1',NOW())";
$con->query($sql);*/

$errors = [];

if (!empty($_POST)) {
    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);

    if (esNulo([$usuario, $password])) {
        $errors[] = "Debe llenar todos los datos";
    }

    if (count($errors) == 0) {
        $errors[] = login($usuario, $password, $con);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <title>Login Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="../css/estilos.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>

<body>
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container bg-gray">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <div class="card-secondary border-0 rounded-lg mt-5 ">
                                <div class="text-center m-auto mb-5 mt-5">
                                    <img src="../images/Logo.png" class="img-fluid">
                                </div>
                                <div class="card-header">
                                    <h3 class="text-center">Iniciar sesion</h3>
                                    <p class="text-center mb-4">Administrador</p>
                                </div>
                                <div class="card-body text-center align-items-center ">
                                    <form action="index.php" method="post" autocomplete="off">
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="usuario" name="usuario" type="text" placeholder="usuario" autofocus required />
                                            <label for="usuario">Usuario</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="password" name="password" type="password" placeholder="password" required />
                                            <label for="password">Contraseña</label>
                                        </div>

                                        <?php mostrarMensajes($errors); ?>

                                        <div class="text-center mt-4 mb-2">
                                            <a class="small" href="password.html">Forgot Password?</a>

                                        </div>
                                        <div class="d-grid align-center col-12">
                                            <button type="submit" class="btn btn-primary">Ingresar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
</body>

</html>