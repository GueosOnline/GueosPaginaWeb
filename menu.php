<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-compatible" content="IEwedge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Título de la página, que aparecerá en la pestaña del navegador -->
    <title>Tienda Online</title>

    <!-- Boostrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Enlace a la hoja de estilo de Font Awesome para los íconos (por ejemplo, íconos de carrito y usuario) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Enlace a una hoja de estilos personalizada (local) -->
    <link href="css/estilos.css" rel="stylesheet">
</head>

<header>
    <!-- Barra de navegación-->
    <div class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <!-- Logo de la tienda que redirige a la página principal -->
            <a href="index.php" class="navbar-brand">
                <img src="images/Logo.png" alt="Logo" class="img-fluid" style="max-height: 60px; margin-right: 10px;">
            </a>
            <!-- Botón de menú para pantallas pequeñas (cuando la navegación es comprimida) -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarHeader">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a href="#" class="nav-link active">Catalogo </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link ">Contacto </a>
                    </li>
                </ul>
                <a href="checkout.php" class="btn btn-primary me-3"><i class="fas fa-shopping-cart"></i>
                    <span id="num_cart" class="badge bg-light text-dark rounded-pill fs-7"><?php if ($num_cart != 0) echo $num_cart; ?></span>
                </a>

                <?php if (isset($_SESSION['user_id'])) { ?>

                    <div class="dropdown ">
                        <button class="btn btn-success dropdown-toggle" type="button" id="btn-session" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user" class="btn btn-success me-3"></i> <?php echo $_SESSION['user_name']; ?>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="btn-session">
                            <li><a class="dropdown-item" href="compras.php">Mis Compras</a></li>
                            <li><a class="dropdown-item" href="logout.php">Cerrar sesión</a></li>
                        </ul>
                    </div>

                <?php } else { ?>
                    <a href="login.php" class="btn btn-success me-3"><i class="fas fa-user"></i> Ingresar</a>
                <?php } ?>
            </div>
        </div>
</header>