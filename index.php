<?php

require 'config/config.php';

$db = new Database();
$con = $db->conectar();

$idCategoria = $_GET['cat'] ?? '';
$orden = $_GET['orden'] ?? '';
$buscar = $_GET['q'] ?? '';

$filtro = '';

$orders = [
    'asc' => 'nombre ASC',
    'desc' => 'nombre DESC',
    'precio_alto' => 'precio DESC',
    'precio_bajo' => 'precio ASC'
];

$order = $orders[$orden] ?? '';

if (!empty($order)) {
    $order = "ORDER BY $order";
}

$params = [];
$query = "SELECT id, nombre, precio, descuento FROM productos WHERE activo=1 $order";

if ($buscar != '') {
    $query .= " AND nombre LIKE ?";
    $params[] = "%$buscar%";
    //$filtro = "AND (nombre LIKE '%$buscar%' || descripcion LIKE '%$buscar%')";
}

if ($idCategoria != '') {
    $query .= " AND id_categoria = ?";
    $params[] = $idCategoria;
}

$query = $con->prepare($query);
$query->execute($params);


/*
if (!empty($idCategoria)) {
    $comando = $con->prepare("SELECT id, nombre, precio, descuento FROM productos WHERE activo=1 $filtro AND id_categoria = ? $order");
    $comando->execute([$idCategoria]);
} else {
    $comando = $con->prepare("SELECT id, nombre, precio, descuento FROM productos WHERE activo=1 $filtro $order");
    $comando->execute();
}*/

$resultado = $query->fetchAll(PDO::FETCH_ASSOC);

$sqlCategorias = $con->prepare("SELECT id, nombre FROM categorias WHERE activo=1");
$sqlCategorias->execute();
$categorias = $sqlCategorias->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-compatible" content="IEwedge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda Online</title>

    <!-- Boostrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="css/estilos.css" rel="stylesheet">
</head>

<body class="d-flex flex-column h-100">

    <?php include 'menu.php'; ?>

    <!--Contenido-->
    <main class="flex-shrink-0">
        <div class="container">
            <div class="row">
                <div class="col-3">
                    <div class="card shadow-sm">

                        <div class="card-header">
                            Categorias
                        </div>

                        <div class="list-group">
                            <a href="index.php" class="list-group-item list-group-item-action">
                                TODO
                            </a>
                            <?php foreach ($categorias as $categoria) { ?>
                                <a href="index.php?cat=<?php echo $categoria['id']; ?>" class="list-group-item list-group-item-action <?php if ($idCategoria == $categoria['id']) echo 'active' ?>">
                                    <?php echo $categoria['nombre']; ?>
                                </a>

                            <?php } ?>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-9">
                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 justify-content-end g-4">
                        <div class="col mb-2">
                            <form action="index.php" id="ordenForm" method="get">

                                <input type="hidden" name="cat" id="cat" value="<?php echo $idCategoria; ?>">

                                <select name="orden" id="orden" class="form-select form-select-sm mb-4" onchange="submitForm()">
                                    <option value="">Ordenar por:</option>
                                    <option value="precio_alto" <?php echo ($orden === 'precio_alto') ? 'selected' : ''; ?>>Precios más altos</option>
                                    <option value="precio_bajo" <?php echo ($orden === 'precio_bajo') ? 'selected' : ''; ?>>Precios más bajos</option>
                                    <option value="asc" <?php echo ($orden === 'asc') ? 'selected' : ''; ?>>Nombre A-Z</option>
                                    <option value="desc" <?php echo ($orden === 'desc') ? 'selected' : ''; ?>>Nombre Z-A</option>
                                </select>
                            </form>
                        </div>
                    </div>

                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">

                        <?php foreach ($resultado as $row) { ?>
                            <!--Caja de Producto-->
                            <div class="col mb-2">
                                <div class="card shadow-sm h-100 ">
                                    <?php

                                    $id = $row['id'];
                                    $descuento = $row['descuento'];
                                    $precio = $row['precio'];
                                    $precio_desc = $precio - (($precio * $descuento) / 100);
                                    $imagen = "images/productos/" . $id . "/principal.jpg";

                                    if (!file_exists($imagen)) {
                                        $imagen = "images/no-photo.jpg";
                                    }
                                    ?>
                                    <div class="img-thumbnail">
                                        <a href="details.php?id=<?php echo $row['id']; ?>&token=<?php echo hash_hmac('sha1', $row['id'], KEY_TOKEN); ?>">
                                            <img src="<?php echo $imagen; ?>" class="img-thumbail">
                                        </a>
                                    </div>

                                    <div class="card-body">

                                        <a style="color: inherit;text-decoration: none;" href="details.php?id=<?php echo $row['id']; ?>&token=<?php echo hash_hmac('sha1', $row['id'], KEY_TOKEN); ?>">
                                            <p class="card-title"><?php echo $row['nombre']; ?></p>
                                        </a>

                                        <?php if ($descuento > 0) { ?>
                                            <p><del><?php echo MONEDA . number_format($precio, 2, '.', ','); ?></del></p>

                                            <h3>
                                                <?php echo MONEDA . number_format($precio_desc, 2, '.', ','); ?><br>

                                            </h3>
                                            <h5 class="text-success"> <?php echo $descuento; ?>% de descuento</h5>
                                        <?php } else { ?>

                                            <h3><?php echo MONEDA . number_format($precio_desc, 2, '.', ','); ?></h3>

                                        <?php } ?>



                                    </div>

                                    <div class="card-footer bg-transparent">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="btn-group">
                                                <a href="details.php?id=<?php echo $row['id']; ?>&token=<?php echo hash_hmac('sha1', $row['id'], KEY_TOKEN); ?>" class="btn btn-primary">Detalles</a>
                                            </div>

                                            <a class="btn btn-success" type="button" onclick="addProducto(<?php echo $row['id']; ?>, '<?php echo hash_hmac('sha1', $row['id'], KEY_TOKEN); ?>')">Agregar</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
    </main>



    <script>
        function addProducto(id, token) {
            let url = 'clases/carrito.php'
            let formData = new FormData()
            formData.append('id', id)
            formData.append('token', token)

            fetch(url, {
                    method: 'POST',
                    body: formData,
                    mode: 'cors'
                }).then(response => response.json())
                .then(data => {
                    if (data.ok) {
                        let elemento = document.getElementById("num_cart")
                        elemento.innerHTML = data.numero
                    } else {
                        alert("No hay suficientes existencias");
                    }
                })
        }

        function submitForm() {
            document.getElementById('ordenForm').submit();
        }
    </script>

</body>

</html>