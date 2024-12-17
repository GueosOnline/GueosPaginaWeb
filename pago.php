<?php

use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\MercadoPagoConfig;

require 'config/config.php';
require_once 'config/database.php';
require 'vendor/autoload.php';

MercadoPagoConfig::setAccessToken(TOKEN_MP);
$client = new PreferenceClient();
$productos_mp = array();

$db = new Database();
$con = $db->conectar();

$productos = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos'] : null;


$lista_carrito = array();

if ($productos != null) {
    foreach ($productos as $clave => $cantidad) {

        $sql = $con->prepare("SELECT id, nombre, precio, descuento, $cantidad AS cantidad FROM productos WHERE id=? AND activo=1");
        $sql->execute([$clave]);
        $lista_carrito[] = $sql->fetch(PDO::FETCH_ASSOC);
    }
} else {
    header("Location: index.php");
    exit;
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
    <!-- CSS -->
    <link href="css/estilos.css" rel="stylesheet">
    <!-- SDK de Mercado Pago -->
    <script src="https://sdk.mercadopago.com/js/v2"></script>
</head>

<body>
    <!--Barra de Navegación-->
    <?php include 'menu.php'; ?>

    <main>
        <div class="container ">

            <div class="row">
                <div class="col-6">
                    <h4>Detalles del Pago</h4>
                    <div class="col-3 text-center" id="wallet_container"></div>
                </div>
                <div class="col-6">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Subtotal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($lista_carrito == null) {
                                    echo '<tr><td colspan="5" class="text-center"><b>Lista vacia</b></td></tr>';
                                } else {

                                    $total = 0;
                                    foreach ($lista_carrito as $producto) {
                                        $_id = $producto['id'];
                                        $nombre = $producto['nombre'];
                                        $precio = $producto['precio'];
                                        $descuento = $producto['descuento'];
                                        $cantidad = $producto['cantidad'];
                                        $precio_desc = $precio - (($precio * $descuento) / 100);
                                        $subtotal = $cantidad * $precio_desc;
                                        $total += $subtotal;

                                        $productos_mp[] = [
                                            "id" => $_id,
                                            "title" => $nombre,
                                            "quantity" => $cantidad,
                                            "unit_price" => round($precio_desc),  // Redondeamos el precio
                                            "currency_id" => "COP"
                                        ];
                                ?>

                                        <tr>
                                            <td><?php echo $nombre; ?> </td>
                                            <td> <?php echo $cantidad . ' x ' . MONEDA . '<b>' . number_format($subtotal, 2, '.', ',') . '</b>'; ?> </td>
                                        </tr>
                                    <?php } ?>

                                    <tr>
                                        <td>
                                            <h4>Pagas</h4>
                                        </td>
                                        <td>
                                            <p class="h3" id="total"><?php echo MONEDA . number_format($total, 2, '.', ','); ?></p>
                                        </td>
                                    </tr>


                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php

    $_SESSION['carrito']['total'] = $total;

    $preference = $client->create([
        "items" => $productos_mp,  // Usamos el array completo de productos
        "back_urls" => [
            "success" => "http://localhost:8080/PaginaWeb/clases/captura.php",  // URL de éxito
            "failure" => "http://localhost:8080/PaginaWeb/Fallo.php",    // URL de fracaso
        ],
        "auto_return" => "approved",
        "binary_mode" => true,
    ]);

    ?>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <script>
        const mp = new MercadoPago("TEST-4027ccea-7466-4fb9-b495-351e0cd11ad8", {
            locale: 'es-CO'
        });

        mp.bricks().create("wallet", "wallet_container", {
            initialization: {
                preferenceId: '<?php echo $preference->id; ?>'
            },
        });
    </script>

</body>

</html>