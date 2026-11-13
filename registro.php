
<?php
session_start();

$host = "localhost";
$db = "tienda_merary";
$user = "root";
$password = "";

try {
    $conexion = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8mb4",
        $user,
        $password
    );

    $conexion->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

} catch (PDOException $e) {
    die("Error de conexión con la base de datos.");
}

$mensaje = "";
$tipo = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $usuario = trim($_POST["usuario"] ?? "");
    $contrasena = $_POST["contrasena"] ?? "";
    $confirmar = $_POST["confirmar"] ?? "";

    if ($usuario == "" || $contrasena == "" || $confirmar == "") {

        $mensaje = "Completa todos los campos.";
        $tipo = "danger";

    } elseif (strlen($usuario) < 4) {

        $mensaje = "El usuario debe tener al menos 4 caracteres.";
        $tipo = "danger";

    } elseif (strlen($contrasena) < 8) {

        $mensaje = "La contraseña debe tener al menos 8 caracteres.";
        $tipo = "danger";

    } elseif ($contrasena !== $confirmar) {

        $mensaje = "Las contraseñas no coinciden.";
        $tipo = "danger";

    } else {

        $consulta = $conexion->prepare(
            "SELECT id_usuario FROM usuarios WHERE usuario = ?"
        );

        $consulta->execute([$usuario]);

        if ($consulta->fetch()) {

            $mensaje = "Este nombre de usuario ya existe.";
            $tipo = "danger";

        } else {

            $hash = password_hash(
                $contrasena,
                PASSWORD_DEFAULT
            );

            $sql = "INSERT INTO usuarios
                    (id_empleado, usuario, contrasena, rol)
                    VALUES (NULL, ?, ?, 'Vendedor')";

            $insertar = $conexion->prepare($sql);
            $insertar->execute([$usuario, $hash]);

            $mensaje = "¡Usuario registrado correctamente!";
            $tipo = "success";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Registro | Tienda Merary</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
body {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #141e30, #243b55, #0f2027);
    font-family: 'Segoe UI', sans-serif;
    padding: 20px;
}

.registro {
    background: white;
    width: 100%;
    max-width: 440px;
    padding: 35px;
    border-radius: 20px;
    box-shadow: 0 15px 40px rgba(0,0,0,.3);
    animation: aparecer .6s ease;
}

@keyframes aparecer {
    from {
        opacity: 0;
        transform: translateY(25px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.logo {
    text-align: center;
    font-size: 50px;
    color: #f07832;
}

h2 {
    text-align: center;
    font-weight: 800;
    color: #17243b;
}

h2 span {
    color: #f07832;
}

.btn-registro {
    width: 100%;
    padding: 13px;
    background: linear-gradient(135deg, #ffb347, #ff6b35);
    color: white;
    border: none;
    border-radius: 10px;
    font-weight: bold;
}

.form-control {
    padding: 12px;
}

a {
    color: #f07832;
}
</style>
</head>

<body>

<div class="registro">

    <div class="logo">
        <i class="bi bi-person-plus-fill"></i>
    </div>

    <h2>TIENDA <span>MERARY</span></h2>

    <p class="text-center text-secondary mb-4">
        Registro de nuevos usuarios
    </p>

    <?php if ($mensaje != ""): ?>
        <div class="alert alert-<?= $tipo ?>">
            <?= htmlspecialchars($mensaje) ?>
        </div>
    <?php endif; ?>

    <form method="POST">

        <div class="mb-3">
            <label class="form-label fw-bold">Nombre de usuario</label>
            <input type="text"
                   name="usuario"
                   class="form-control"
                   placeholder="Crea tu usuario"
                   minlength="4"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Contraseña</label>
            <input type="password"
                   name="contrasena"
                   class="form-control"
                   placeholder="Mínimo 8 caracteres"
                   minlength="8"
                   required>
        </div>

        <div class="mb-4">
            <label class="form-label fw-bold">Confirmar contraseña</label>
            <input type="password"
                   name="confirmar"
                   class="form-control"
                   placeholder="Repite tu contraseña"
                   minlength="8"
                   required>
        </div>

        <button type="submit" class="btn-registro">
            <i class="bi bi-person-check"></i>
            REGISTRAR USUARIO
        </button>

    </form>

    <div class="text-center mt-4">
        <a href="login.php" class="text-decoration-none fw-bold">
            <i class="bi bi-arrow-left"></i>
            Volver al inicio de sesión
        </a>
    </div>

</div>

</body>
</html>
