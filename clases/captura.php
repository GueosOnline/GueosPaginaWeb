<?php

require '../config/config.php';
require_once '../config/database.php';

$db = new Database();
$con = $db->conectar();

$idTransaccion = isset($_GET['payment_id']) ? $_GET['payment_id'] : '';
$status = isset($_GET['status']) ? $_GET['status'] : '';

if ($idTransaccion != '') {

    $fecha = date("Y-m-d H:i:s");
    $total = isset($_SESSION['carrito']['total']) ? $_SESSION['carrito']['total'] : 0;
    $idCliente = $_SESSION['user_cliente'];
    $sqlProd = $con->prepare("SELECT email FROM clientes WHERE id=? AND estatus=1");
    $sqlProd->execute([$idCliente]);
    $row_cliente = $sqlProd->fetch(PDO::FETCH_ASSOC);
    $email = $row_cliente['email'];

    $comando = $con->prepare("INSERT INTO compra (fecha, status, email, id_cliente, total, id_transaccion, medio_pago) VALUES(?,?,?,?,?,?,?)");
    $comando->execute([$fecha, $status, $email, $idCliente, $total, $idTransaccion, 'MP']);
    $id = $con->lastInsertId();

    if ($id > 0) {
        $productos = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos'] : null;

        if ($productos != null) {
            foreach ($productos as $clave => $cantidad) {
                $sqlProd = $con->prepare("SELECT id, nombre, precio, descuento FROM productos WHERE id=? AND activo=1");
                $sqlProd->execute([$clave]);
                $row_prod = $sqlProd->fetch(PDO::FETCH_ASSOC);

                $precio = $row_prod['precio'];
                $descuento = $row_prod['descuento'];
                $precio_desc = $precio - (($precio * $descuento) / 100);

                $sql = $con->prepare("INSERT INTO detalle_compra (id_compra, id_producto, nombre, cantidad, precio) VALUES(?,?,?,?,?)");
                $sql->execute([$id, $row_prod['id'], $row_prod['nombre'], $cantidad, $precio_desc]);
            }

            require 'Mailer.php';

            $asunto = "Detalles de su pedido";

            $cuerpo = '<p><b>ID de la compra: </b>' . $idTransaccion . '</p>';
            $cuerpo .= '<p><b>Fecha: </b>' . date("d/m/Y h:i A") . '</b></p>';
            $cuerpo .= '<p><b>Total: </b>' . $total . '</b></p><br>';
            $cuerpo .= '<p>Si desea ver mas detalles visite la pagina: https://www.gueos.com.co/</p>';

            $cuerpo .= "<h4>Gracias por su compra y escoger a Representaciones Gueos Ltda.</h4>";

            $mailer = new Mailer();
            $mailer->enviarEmail($email, $asunto, $cuerpo);
        }

        unset($_SESSION['carrito']);
        header("Location: " . SITE_URL . "/completado.php?key=" . $idTransaccion);
    }
}
