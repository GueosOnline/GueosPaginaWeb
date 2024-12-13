<?php

require_once 'config/config.php';
require_once 'config/database.php';
require_once 'clases/clienteFunciones.php';

$db = new Database();
$con = $db->conectar();

$errors = [];

if (!empty($_POST)) {

    $email = trim($_POST['email']);

    if (esNulo([$email])) {
        $errors[] = "Debe llenar todos los campos";
    }

    if (!esEmail($email)) {
        $errors[] = "La dirección de correo electronico no es valida";
    }

    if (count($errors) == 0) {
        if (emailExiste($email, $con)) {
            $sql = $con->prepare("SELECT usuarios.id, clientes.nombres FROM usuarios INNER JOIN clientes on usuarios.id_cliente=clientes.id WHERE clientes.email LIKE ? LIMIT 1");
            $sql->execute(([$email]));
            $row = $sql->fetch(PDO::FETCH_ASSOC);
            $user_id = $row['id'];
            $nombres = $row['nombres'];

            $token = solicitaPassword($user_id, $con);

            if ($token !== null) {
                require 'clases/Mailer.php';
                $mailer = new Mailer();

                $url = SITE_URL . '/reset_password.php?id=' . $user_id . '&token=' . $token;

                $asunto = "Recuperar Contraseña - Representaciones Gueos LTDA.";
                $cuerpo = "Estimado $nombres: <br> Si usted ha solicitado el cambio de contraseña de click en el siguiente link <a href='$url'>$url</a>.";

                $cuerpo .= "<br>Si no realizo esta petición ignore este correo.";

                if ($mailer->enviarEmail($email, $asunto, $cuerpo)) {
                    echo "<p><b>Correo enviado</b><p>";
                    echo "<p>Hemos enviado un correo electronico al la dirección $email para reestablecer la contraseña</p>";

                    exit;
                }
            }
        } else {
            $errors[] = "No existe una cuenta asociada a la direccion de correo electronico escrita";
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
    </header>

    <!--Contenido-->
    <main class="form-login m-auto pt-4">
        <div class="text-center m-auto mb-5 mt-5">
            <img src="images/productos/1/Logo.png" class="img-fluid">
        </div>
        <h3>Recuperar Contraseña</h3>
        <?php mostrarMensajes($errors); ?>
        <form action="recupera.php" method="post" class="row g-3" autocomplete="off">

            <div class="form-floating">
                <input class="form-control" type="email" name="email" id="email" placeholder="Correo Electronico" required>
                <label for="email">Correo Electronico</label>
            </div>
            <div class="d-grid gap-3 col-12">
                <button type="submit" class="btn btn-primary">Continuar</button>
            </div>
            <div>
                ¿No tiene cuenta? <a href="registro.php">Registrate aqui.</a>
            </div>
        </form>

        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>

</html>