<?php

$path = dirname(__FILE__);

require_once $path . '/database.php';
require_once $path . '/../admin/clases/cifrado.php';

$db = new Database();
$con = $db->conectar();

$sql = "SELECT nombre, valor  FROM configuracion";
$resultado = $con->query($sql);
$datosConfig = $resultado->fetchAll(PDO::FETCH_ASSOC);

$config = [];

foreach ($datosConfig as $datoConfig) {
    $config[$datoConfig['nombre']] = $datoConfig['valor'];
}

//Datos del sistema
define("CLIENT_ID", "APR.wqc-354*");
define("TOKEN_MP", "TEST-5643175586443360-120516-eee2547feaf32253850f8c060f52cec7-192220680");

define('SITE_URL', 'http://localhost:8080/PaginaWeb');
define("KEY_TOKEN", "APR.wqc-354*");
define("MONEDA", "$");

//Datos para el envio de correo electronico
define("MAIL_HOST", $config['correo_smtp']);
define("MAIL_USER", $config['correo_email']);
define("MAIL_PASS", descifrar($config['correo_password']));
define("MAIL_PORT", $config['correo_puerto']);

session_start();

$num_cart = 0;
if (isset($_SESSION['carrito']['productos'])) {
    $num_cart = count($_SESSION['carrito']['productos']);
}
