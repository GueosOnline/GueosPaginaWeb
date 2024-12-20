<?php
require '../config/database.php';
require '../config/config.php';


if (!isset($_SESSION['user_type'])) {
    header('Location: ../index.php');
    exit;
}

if ($_SESSION['user_type'] != 'admin') {
    header('Location: ../../index.php');
    exit;
}

require '../header.php';

$db = new Database();
$con = $db->conectar();

$id = $_GET['id'];

$sql = $con->prepare("SELECT id, nombre, descripcion, precio, stock, descuento, id_categoria FROM productos WHERE id=? AND activo =1");
$sql->execute([$id]);
$producto = $sql->fetch(PDO::FETCH_ASSOC);

$sql = "SELECT id, nombre FROM categorias WHERE activo =1";
$resultado = $con->query($sql);
$categorias = $resultado->fetchAll(PDO::FETCH_ASSOC);

$rutaImagenes = '../../images/productos/' . $id . '/';
$imagenPrincipal = $rutaImagenes . 'principal.jpg';

$imagenes = [];
$dirIinit = dir($rutaImagenes);

while (($archivo = $dirIinit->read()) !== false) {
    if ($archivo != 'principal.jpg' && (strpos($archivo, 'jpg') || strpos($archivo, 'jpeg'))) {
        $image = $rutaImagenes . $archivo;
        $imagenes[] = $image;
    }
}
$dirIinit->close();


?>

<link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/44.1.0/ckeditor5.css" />

<script src="https://cdn.ckeditor.com/ckeditor5/44.1.0/ckeditor5.umd.js"></script>
<link href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<!-- include summernote css/js -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/
summernote.min.js"></script>

<style>
    .ck-editor__editable[role="textbox"] {
        min-height: 250px;
    }
</style>

<main>

    <div class="container-fluid px-4">
        <h2 class="mt-3">Modifica producto</h2>

        <form action="actualiza.php" method="post" enctype="multipart/form-data" autocomplete="off">
            <input type="hidden" name="id" value="<?php echo $producto['id']; ?>">

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" name="nombre" id="nombre" value="<?php echo htmlspecialchars($producto['nombre'], ENT_QUOTES); ?>" required autofocus>

            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripcion:</label>
                <textarea class="form-control" name="descripcion" id="summernote" required> <?php echo $producto['descripcion']; ?></textarea>
            </div>

            <div class="row mb-2">
                <div class="col-12 col-md-6">
                    <label for="imagen_principal" class="form-label">Imagen principal:</label>
                    <input type="file" class="form-control" name="imagen_principal" id="imagen_principal" accept="image/jpeg">
                </div>

                <div class="col-12 col-md-6">
                    <label for="otras_imagenes" class="form-label">Otras imagenes:</label>
                    <input type="file" class="form-control" name="otras_imagenes[]" id="otras_imagenes" accept="image/jpeg" multiple>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-12 col-md-6">
                    <?php if (file_exists($imagenPrincipal)) { ?>
                        <img src="<?php echo $imagenPrincipal . '?id=' . time(); ?>" class="img-thumbnail my-3"><br>
                        <button class="btn btn-danger btn-sm" onclick="eliminaImagen('<?php echo $imagenPrincipal; ?>')">Eliminar</button>
                    <?php } ?>
                </div>

                <div class="col-12 col-md-6">
                    <div class="row">
                        <?php foreach ($imagenes as $imagen) { ?>
                            <div class="col-4">
                                <img src="<?php echo $imagen . '?id=' . time(); ?>" class="img-thumbnail my-3 img-fluid"><br>
                                <button class="btn btn-danger btn-sm" onclick="eliminaImagen('<?php echo $imagen; ?>')">Eliminar</button>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col mb-3">
                    <label for="precio" class="form-label">Precio</label>
                    <input type="number" class="form-control" name="precio" id="precio" value="<?php echo $producto['precio']; ?>" required>
                </div>

                <div class="col mb-3">
                    <label for="descuento" class="form-label">Descuento</label>
                    <input type="number" class="form-control" name="descuento" id="descuento" value="<?php echo $producto['descuento']; ?>" required>
                </div>

                <div class="col mb-3">
                    <label for="stock" class="form-label">Stock</label>
                    <input type="number" class="form-control" name="stock" id="stock" value="<?php echo $producto['stock']; ?>" required>
                </div>
            </div>

            <div class="row">
                <div class="col-4 mb-3">
                    <label for="categoria" class="form-label">Categoria</label>
                    <select class="form-select" name="categoria" id="categoria" required>
                        <option value="" selected>Seleccionar</option>
                        <?php foreach ($categorias as $categoria) { ?>
                            <option value="<?php echo $categoria['id']; ?>" <?php if ($categoria['id'] == $producto['id_categoria']) echo 'selected'; ?>><?php echo $categoria['nombre']; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Guardar</button>

        </form>
    </div>

</main>

<script>
    $(document).ready(function() {
        $('#summernote').summernote();
    });

    function eliminaImagen(urlImagen) {
        let url = 'eliminar_imagen.php'
        let formData = new FormData()
        formData.append('urlImagen', urlImagen)

        fetch(url, {
            method: 'POST',
            body: formData
        }).then((response) => {
            if (response.ok) {
                location.reload()
            }
        })
    }
</script>


<?php require '../footer.php'; ?>