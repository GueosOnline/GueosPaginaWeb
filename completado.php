<?php

require "config/config.php";
require_once 'config/database.php';

$db = new Database();
$con = $db->conectar();

$id_transaccion = isset($_GET['key']) ? $_GET['key'] : '0';

$error = '';
if ($id_transaccion == '') {
    $error = 'Error al procesar la petición';
} else {

    $sql = $con->prepare("SELECT count(id) FROM compra WHERE id_transaccion=? AND status=?");
    $sql->execute([$id_transaccion, 'approved']);
    if ($sql->fetchColumn() > 0) {

        $sql = $con->prepare("SELECT id, fecha, email, total FROM compra WHERE id_transaccion=? AND status=? LIMIT 1");
        $sql->execute([$id_transaccion, 'approved']);
        $row = $sql->fetch(PDO::FETCH_ASSOC);

        $idCompra = $row['id'];
        $total = $row['total'];
        $fecha = $row['fecha'];

        $sqlDet = $con->prepare("SELECT nombre, precio, cantidad FROM detalle_compra WHERE id_compra =?");
        $sqlDet->execute([$idCompra]);
    } else {
        $error = 'Error al comprobar la compra';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="css/estilos.css" rel="stylesheet">

</head>

<body>
    <?php include 'menu.php'; ?>

    <main>
        <div class="container">

            <?php if (strlen($error) > 0) { ?>
                <div class="row">
                    <div class="col">
                        <h3><?php echo $error; ?></h3>
                    </div>
                </div>

            <?php } else { ?>

                <div class="row">
                    <div class="col">
                        <b>Folio de la compra: </b><?php echo $id_transaccion; ?><br>
                        <b>Fecha de compra: </b><?php echo $fecha; ?><br>
                        <b>Total: </b><?php echo MONEDA . number_format($total, 2, '.', ','); ?><br>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th style="text-align: center">Cantidad</th>
                                    <th>Producto</th>
                                    <th>Precio</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php while ($row_det = $sqlDet->fetch(PDO::FETCH_ASSOC)) {
                                    $importe = $row_det['precio'] * $row_det['cantidad'];  ?>
                                    <tr>
                                        <td style="text-align: center"><?php echo $row_det['cantidad']; ?></td>
                                        <td><?php echo $row_det['nombre']; ?></td>
                                        <td><?php echo MONEDA . number_format($importe, 2, '.', ','); ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php } ?>
        </div>
    </main>
</body>

</html>