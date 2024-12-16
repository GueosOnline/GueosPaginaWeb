<?php

// Se incluyen los archivos de configuración y conexión a la base de datos
require '../config/config.php';
require_once '../config/database.php';

// Verifica si la solicitud POST contiene la acción a realizar
if (isset($_POST['action'])) {

    // Obtiene la acción y el id del producto
    $action = $_POST['action'];
    $id = isset($_POST['id']) ? $_POST['id'] : 0;

    // Si la acción es 'agregar', realiza el proceso de agregar al carrito
    if ($action == 'agregar') {
        $cantidad = isset($_POST['cantidad']) ? $_POST['cantidad'] : 0;

        // Llama a la función agregar y obtiene el resultado (subtotal)
        $respuesta = agregar($id, $cantidad);

        // Si la operación fue exitosa, indica que está bien
        if ($respuesta > 0) {
            $datos['ok'] = true;
        } else {
            // Si no fue exitosa, indica que falló
            $datos['ok'] = false;
        }
        // Muestra el subtotal con el formato de moneda
        $datos['sub'] = MONEDA . number_format($respuesta, 2, '.', ',');

        // Si la acción es 'eliminar', realiza el proceso de eliminar del carrito
    } else if ($action == 'eliminar') {
        $datos['ok'] = eliminar($id);
    } else {
        // Si la acción no es válida, marca como fallo
        $datos['ok'] = false;
    }
} else {
    // Si no se envió ninguna acción, marca como fallo
    $datos['ok'] = false;
}
// Responde con un JSON que indica el estado de la operación
echo json_encode($datos);


// Función para agregar productos al carrito
function agregar($id, $cantidad)
{
    $res = 0;

    // Verifica que el id y la cantidad sean válidos
    if ($id > 0 && $cantidad > 0 && is_numeric(($cantidad))) {
        // Si el producto ya existe en el carrito, actualiza la cantidad
        if (isset($_SESSION['carrito']['productos'][$id])) {
            $_SESSION['carrito']['productos'][$id] = $cantidad;

            // Conecta a la base de datos y consulta el precio y descuento del producto
            $db = new Database();
            $con = $db->conectar();
            $sql = $con->prepare("SELECT precio, descuento FROM productos WHERE id=? AND activo=1 LIMIT 1");
            $sql->execute([$id]);
            $row = $sql->fetch(PDO::FETCH_ASSOC);
            // Calcula el precio con el descuento
            $precio = $row['precio'];
            $descuento = $row['descuento'];
            $precio_desc = $precio - (($precio * $descuento) / 100);
            // Calcula el total de la compra
            $res = $cantidad * $precio_desc;

            return $res;
        }
    } else {
        return $res;
    }
}

// Función para eliminar un producto del carrito
function eliminar($id)
{
    // Verifica que el id sea válido
    if ($id > 0) {
        // Si el producto existe en el carrito, lo elimina
        if (isset($_SESSION['carrito']['productos'][$id])) {
            unset($_SESSION['carrito']['productos'][$id]);
            return true;
        }
    } else {
        return false; // Si el id no es válido, no elimina
    }
}
