<?php

//Datos del sistema
define("CLIENT_ID", "APR.wqc-354*");
define("TOKEN_MP", "TEST-5643175586443360-120516-eee2547feaf32253850f8c060f52cec7-192220680");

define('SITE_URL', 'http://localhost:8080/PaginaWeb');
define("KEY_TOKEN", "APR.wqc-354*");
define("MONEDA", "$");

//Datos para el envio de correo electronico
define("MAIL_HOST", "mail.gueos.com.co");
define("MAIL_USER", "pruebas@gueos.com.co");
define("MAIL_PASS", "GueosPruebas");
define("MAIL_PORT", "465");

session_start();

$num_cart = 0;
if (isset($_SESSION['carrito']['productos'])) {
    $num_cart = count($_SESSION['carrito']['productos']);
}
