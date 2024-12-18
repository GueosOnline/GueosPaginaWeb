<?php

require_once 'config/config.php';
require_once 'config/database.php';
require_once 'clases/clienteFunciones.php';

$db = new Database();
$con = $db->conectar();

$proceso = isset($_GET['pago']) ? 'pago' : 'login';


$errors = [];

if (!empty($_POST)) {

    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);
    $proceso = $_POST['proceso'] ?? 'login';

    if (esNulo([$usuario, $password])) {
        $errors[] = "Debe llenar todos los campos";
    }
    if (count($errors) == 0) {
        $errors[] = login($usuario, $password, $con, $proceso);
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

<body class="body-login" style="background-image: url('images/FondoLogin.jpg'); background-size: cover; background-position: center; background-attachment: fixed;">
    <!--Contenido-->

    <main class="m-auto pt-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5">

                    <div class=" mb-5 mt-3 mx-auto" style="max-width: 350px;">
                        <a href="index.php">
                            <img src="images/Logo.png" class="img-fluid">
                        </a>
                    </div>

                    <div class="card-header col-8 mx-auto">
                        <h2 class="text-center mb-5 col 8">Iniciar Sesión</h2>
                        <?php mostrarMensajes($errors); ?>
                    </div>

                    <div class="card-body">

                        <form action="login.php" method="post" autocomplete="off">

                            <input type="hidden" name="proceso" value="<?php echo $proceso; ?>">

                            <div class="form-floating mb-3 col-8 mx-auto">
                                <input class="form-control" type="text" name="usuario" id="usuario" placeholder="Usuario" required>
                                <label for="usuario">Usuario</label>
                            </div>

                            <div class="form-floating mb-3 col-8 mx-auto">
                                <input class="form-control" type="password" name="password" id="password" placeholder="Contraseña" required>
                                <label for="floating-input">Contraseña</label>
                            </div>

                            <div class="col-12 text-center mb-2">
                                <small><a href="recupera.php">¿Olvidaste tu contraseña?</a></small>
                            </div>
                            <div class="d-grid gap-6 col-5 mx-auto mb-4">
                                <button type="submit" class="btn" style="background-color:	#FF8000;">Ingresar</button>
                            </div>
                            <div class="text-center">
                                <small> ¿No tiene cuenta? <a href="registro.php">Registrate aqui.</a></small>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>

</html>