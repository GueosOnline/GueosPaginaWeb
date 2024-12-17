<?php

require 'config/config.php';
require_once 'config/database.php';
require 'clases/clienteFunciones.php';

$db = new Database();
$con = $db->conectar();


$token = generarToken();
$_SESSION['token'] = $token;
$idCliente = $_SESSION['user_cliente'];

$sql = $con->prepare("SELECT id_transaccion, fecha, status, total, medio_pago FROM compra WHERE id_cliente=? ORDER BY DATE(fecha) DESC");
$sql->execute([$idCliente]);

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
    <?php include 'menu.php'; ?>
    <!--Contenido-->
    <main>
        <div class="container">
            <h4>Mis Compras</h4>
            <hr>

            <?php while ($row = $sql->fetch(PDO::FETCH_ASSOC)) { ?>

                <div class="card mb-4">
                    <div class="card-header">
                        <?php echo $row['fecha']; ?>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Referencia: <?php echo $row['id_transaccion']; ?></h5>
                        <p class="card-text">Total: <?php echo MONEDA . number_format($row['total'], 2, '.', ','); ?></p>
                        <a href="compra_detalle.php?orden=<?php echo $row['id_transaccion'] ?>&token=<?php echo $token; ?>" class="btn btn-primary">Ver compra</a>
                    </div>
                </div>

            <?php } ?>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>

</html>