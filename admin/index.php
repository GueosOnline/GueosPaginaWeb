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
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>

<body style="background-image: url('../images/FondoLoginAdmin.jpg'); background-size: cover; background-position: center; background-attachment: fixed;">

    <main class="m-auto pt-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5">

                    <div class=" mb-5 mt-5 mx-auto" style="max-width: 350px;">
                        <img src="../images/Logo.png" class="img-fluid">
                    </div>

                    <div class="card-header">
                        <h3 class="text-center text-light">Iniciar sesion</h3>
                        <p class="text-center mb-4 text-light">Administrador</p>
                    </div>

                    <div class="card-body">

                        <form action="index.php" method="post" autocomplete="off">

                            <div class="form-floating mb-3 col-6 mx-auto">
                                <input class="form-control text-light" id="usuario" name="usuario" type="text" placeholder="usuario" autofocus style="background-color: rgba(0, 0, 0, 0.7); color:white;" required />
                                <label for="password" style="color: black;">Usuario</label>
                            </div>

                            <div class="form-floating mb-3 col-6 mx-auto">
                                <input class="form-control text-light" id="password" name="password" type="password" placeholder="password" style="background-color: rgba(0, 0, 0, 0.7);" required />
                                <label for="password" style="color: black;">Contraseña</label>
                            </div>

                            <?php mostrarMensajes($errors); ?>

                            <div class="text-center mt-4 mb-2">
                                <a class="small" href="password.html">¿Olvidaste tu contraseña?</a>
                            </div>
                            <div class="d-grid col-3 mx-auto">
                                <button type="submit" class="btn" style="background-color:	#FF8000;">Ingresar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
</body>

</html>