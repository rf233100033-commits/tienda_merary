
<?php
session_start();

// Verificar que el usuario haya iniciado sesión
if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit();
}

// Conexión a la base de datos
$host = "localhost";
$db = "tienda_merary";
$user = "root";
$password = "";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8mb4",
        $user,
        $password
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consultar cantidades de registros
    $totalProductos = $pdo->query(
        "SELECT COUNT(*) FROM productos"
    )->fetchColumn();

    $totalClientes = $pdo->query(
        "SELECT COUNT(*) FROM clientes"
    )->fetchColumn();

    $totalProveedores = $pdo->query(
        "SELECT COUNT(*) FROM proveedores"
    )->fetchColumn();

    $totalEmpleados = $pdo->query(
        "SELECT COUNT(*) FROM empleados"
    )->fetchColumn();

    $totalVentas = $pdo->query(
        "SELECT COUNT(*) FROM ventas"
    )->fetchColumn();

    $totalCompras = $pdo->query(
        "SELECT COUNT(*) FROM compras"
    )->fetchColumn();

} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

$nombreUsuario = htmlspecialchars($_SESSION["usuario"]);
$rolUsuario = htmlspecialchars($_SESSION["rol"]);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard | Tienda Merary</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: #f4f6f9;
            font-family: Arial, sans-serif;
        }

        .sidebar {
            min-height: 100vh;
            background: #172033;
            color: white;
            padding: 25px 15px;
        }

        .sidebar h4 {
            color: #ff9f43;
            font-weight: bold;
            text-align: center;
            margin-bottom: 35px;
        }

        .sidebar a {
            display: block;
            color: #e5e7eb;
            text-decoration: none;
            padding: 13px;
            border-radius: 8px;
            margin-bottom: 8px;
        }

        .sidebar a:hover {
            background: #ff9f43;
            color: #172033;
        }

        .topbar {
            background: white;
            padding: 18px 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,.07);
        }

        .card-resumen {
            border: none;
            border-radius: 15px;
            padding: 22px;
            box-shadow: 0 4px 15px rgba(0,0,0,.06);
            height: 100%;
        }

        .icono {
            font-size: 32px;
            color: #ff9f43;
        }

        .cantidad {
            font-size: 30px;
            font-weight: bold;
            color: #172033;
        }

        .titulo {
            color: #777;
            font-size: 15px;
        }

        .contenido {
            padding: 30px;
        }

        .bienvenida {
            background: linear-gradient(120deg, #172033, #293b5c);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
        }

        .btn-salir {
            background: #dc3545;
            color: white !important;
        }
    </style>
</head>

<body>

<div class="container-fluid">
    <div class="row">

        <!-- MENÚ LATERAL -->
        <aside class="col-md-2 sidebar">

            <h4>
                <i class="bi bi-shop"></i>
                TIENDA MERARY
            </h4>

            <a href="dashboard.php">
                <i class="bi bi-house-door me-2"></i>
                Inicio
            </a>

            <a href="productos.php">
                <i class="bi bi-box-seam me-2"></i>
                Productos
            </a>

            <a href="categorias.php">
                <i class="bi bi-tags me-2"></i>
                Categorías
            </a>

            <a href="proveedores.php">
                <i class="bi bi-truck me-2"></i>
                Proveedores
            </a>

            <a href="clientes.php">
                <i class="bi bi-people me-2"></i>
                Clientes
            </a>

            <a href="empleados.php">
                <i class="bi bi-person-badge me-2"></i>
                Empleados
            </a>

            <a href="compras.php">
                <i class="bi bi-cart-plus me-2"></i>
                Compras
            </a>

            <a href="ventas.php">
                <i class="bi bi-cart-check me-2"></i>
                Ventas
            </a>

            <hr>

            <a href="logout.php" class="btn-salir">
                <i class="bi bi-box-arrow-right me-2"></i>
                Cerrar sesión
            </a>

        </aside>

        <!-- CONTENIDO PRINCIPAL -->
        <main class="col-md-10 p-0">

            <div class="topbar d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Panel principal</h5>

                <div>
                    <i class="bi bi-person-circle me-2"></i>
                    <?php echo $nombreUsuario; ?>
                    <span class="badge bg-warning text-dark ms-2">
                        <?php echo $rolUsuario; ?>
                    </span>
                </div>
            </div>

            <div class="contenido">

                <!-- BIENVENIDA -->
                <div class="bienvenida">
                    <h2>¡Bienvenido a Tienda Merary!</h2>

                    <p class="mb-0">
                        Sistema de administración y control de inventario,
                        compras y ventas.
                    </p>
                </div>

                <h4 class="mb-4">Resumen general del sistema</h4>

                <!-- TARJETAS -->
                <div class="row g-4">

                    <div class="col-md-4">
                        <div class="card card-resumen">
                            <i class="bi bi-box-seam icono"></i>
                            <div class="titulo mt-3">Productos registrados</div>
                            <div class="cantidad">
                                <?php echo $totalProductos; ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card card-resumen">
                            <i class="bi bi-people icono"></i>
                            <div class="titulo mt-3">Clientes registrados</div>
                            <div class="cantidad">
                                <?php echo $totalClientes; ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card card-resumen">
                            <i class="bi bi-truck icono"></i>
                            <div class="titulo mt-3">Proveedores</div>
                            <div class="cantidad">
                                <?php echo $totalProveedores; ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card card-resumen">
                            <i class="bi bi-person-badge icono"></i>
                            <div class="titulo mt-3">Empleados</div>
                            <div class="cantidad">
                                <?php echo $totalEmpleados; ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card card-resumen">
                            <i class="bi bi-cart-check icono"></i>
                            <div class="titulo mt-3">Ventas registradas</div>
                            <div class="cantidad">
                                <?php echo $totalVentas; ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card card-resumen">
                            <i class="bi bi-cart-plus icono"></i>
                            <div class="titulo mt-3">Compras registradas</div>
                            <div class="cantidad">
                                <?php echo $totalCompras; ?>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="text-center mt-5 text-secondary">
                    <p>
                        © <?php echo date("Y"); ?> Tienda Merary.
                        Sistema de administración.
                    </p>
                </div>

            </div>
        </main>

    </div>
</div>

</body>
</html>
