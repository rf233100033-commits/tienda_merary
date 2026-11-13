
<?php
session_start();

// ======================================
// CONEXION A LA BASE DE DATOS
// ======================================

$host = "localhost";
$basedatos = "tienda_merary";
$usuarioBD = "root";
$passwordBD = "";

$error = "";

try {

    $conexion = new PDO(
        "mysql:host=$host;dbname=$basedatos;charset=utf8mb4",
        $usuarioBD,
        $passwordBD
    );

    $conexion->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

} catch (PDOException $e) {

    die("Error de conexión con MySQL: " .
        htmlspecialchars($e->getMessage()));
}

// ======================================
// VERIFICAR SESION
// ======================================

if (isset($_SESSION["id_usuario"])) {

    header("Location: dashboard.php");
    exit;

}

// ======================================
// PROCESAR INICIO DE SESION
// ======================================

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $usuario = trim($_POST["usuario"] ?? "");
    $contrasena = $_POST["contrasena"] ?? "";

    if ($usuario == "" || $contrasena == "") {

        $error = "Todos los campos son obligatorios.";

    } else {

        try {

            $sql = "SELECT * FROM usuarios
                    WHERE usuario = ?
                    AND estado = 1
                    LIMIT 1";

            $consulta = $conexion->prepare($sql);
            $consulta->execute([$usuario]);

            $datos = $consulta->fetch(PDO::FETCH_ASSOC);

            if ($datos && password_verify(
                $contrasena,
                $datos["contrasena"]
            )) {

                session_regenerate_id(true);

                $_SESSION["id_usuario"] = $datos["id_usuario"];
                $_SESSION["usuario"] = $datos["usuario"];
                $_SESSION["rol"] = $datos["rol"];

                header("Location: dashboard.php");
                exit;

            } else {

                $error = "Usuario o contraseña incorrectos.";

            }

        } catch (PDOException $e) {

            $error = "Error al procesar el inicio de sesión.";

        }

    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login | Tienda Merary</title>

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- ICONOS -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #141e30, #243b55, #0f2027);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .login-card {
            width: 100%;
            max-width: 440px;
            background: white;
            padding: 40px;
            border-radius: 22px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.4);
            animation: aparecer 0.8s ease;
        }

        @keyframes aparecer {

            from {
                opacity: 0;
                transform: translateY(40px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }

        }

        /* LOGO */

        .logo {
            width: 90px;
            height: 90px;
            background: linear-gradient(135deg, #ffb347, #ff6b35);
            color: white;
            border-radius: 25px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 45px;
            margin: 0 auto 18px;
            box-shadow: 0 8px 20px rgba(255,107,53,0.3);
        }

        /* TITULO */

        .titulo {
            text-align: center;
            font-size: 27px;
            font-weight: 800;
            color: #17243b;
            letter-spacing: 1px;
        }

        .titulo span {
            color: #f07832;
        }

        .subtitulo {
            text-align: center;
            color: #888;
            font-size: 13px;
            margin-bottom: 35px;
        }

        .bienvenida {
            color: #17243b;
            font-weight: 700;
            font-size: 23px;
        }

        .descripcion {
            color: #777;
            font-size: 14px;
            margin-bottom: 25px;
        }

        /* CAMPOS */

        .form-label {
            font-weight: 600;
            color: #333;
        }

        .input-group-text {
            background: #f5f6fa;
            color: #f07832;
            border: 1px solid #ddd;
            padding: 12px 15px;
        }

        .form-control {
            padding: 12px;
            border: 1px solid #ddd;
        }

        .form-control:focus {
            border-color: #f07832;
            box-shadow: 0 0 0 3px rgba(240,120,50,0.15);
        }

        /* BOTON LOGIN */

        .btn-login {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(135deg, #ffb347, #ff6b35);
            color: white;
            font-weight: 700;
            font-size: 15px;
            letter-spacing: 1px;
            transition: 0.3s;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(255,107,53,0.35);
        }

        /* BOTON REGISTRO */

        .registro {
            text-align: center;
            margin-top: 25px;
        }

        .registro p {
            color: #777;
            font-size: 14px;
            margin-bottom: 12px;
        }

        .btn-registro {
            display: block;
            width: 100%;
            padding: 12px;
            border: 2px solid #f07832;
            border-radius: 10px;
            color: #f07832;
            font-weight: 700;
            text-decoration: none;
            transition: 0.3s;
        }

        .btn-registro:hover {
            background: #f07832;
            color: white;
            transform: translateY(-2px);
        }

        /* PIE DE PAGINA */

        .footer {
            text-align: center;
            margin-top: 30px;
            color: #888;
            font-size: 13px;
        }

        .footer i {
            color: #20a36b;
        }

        @media(max-width: 480px) {

            .login-card {
                padding: 28px 22px;
            }

        }

    </style>

</head>

<body>

    <div class="login-card">

        <!-- LOGO -->
        <div class="logo">
            <i class="bi bi-shop"></i>
        </div>

        <!-- TITULO -->
        <h2 class="titulo">
            TIENDA <span>MERARY</span>
        </h2>

        <p class="subtitulo">
            Sistema de administración comercial
        </p>

        <!-- BIENVENIDA -->
        <h3 class="bienvenida">
            ¡Bienvenido!
        </h3>

        <p class="descripcion">
            Ingresa tus datos para acceder al sistema.
        </p>

        <!-- MENSAJE DE ERROR -->
        <?php if (!empty($error)): ?>

            <div class="alert alert-danger text-center">
                <i class="bi bi-exclamation-circle"></i>
                <?= htmlspecialchars($error) ?>
            </div>

        <?php endif; ?>

        <!-- FORMULARIO LOGIN -->
        <form method="POST" action="">

            <div class="mb-3">

                <label class="form-label">
                    Nombre de usuario
                </label>

                <div class="input-group">

                    <span class="input-group-text">
                        <i class="bi bi-person-fill"></i>
                    </span>

                    <input
                        type="text"
                        name="usuario"
                        class="form-control"
                        placeholder="Ingresa tu usuario"
                        autocomplete="username"
                        required
                    >

                </div>

            </div>

            <div class="mb-4">

                <label class="form-label">
                    Contraseña
                </label>

                <div class="input-group">

                    <span class="input-group-text">
                        <i class="bi bi-lock-fill"></i>
                    </span>

                    <input
                        type="password"
                        name="contrasena"
                        id="contrasena"
                        class="form-control"
                        placeholder="Ingresa tu contraseña"
                        autocomplete="current-password"
                        required
                    >

                    <button
                        class="btn btn-outline-secondary"
                        type="button"
                        onclick="mostrarPassword()"
                    >
                        <i class="bi bi-eye" id="icono"></i>
                    </button>

                </div>

            </div>

            <!-- BOTON INICIAR SESION -->
            <button type="submit" class="btn-login">

                INICIAR SESIÓN

                <i class="bi bi-arrow-right-circle ms-2"></i>

            </button>

        </form>

        <!-- BOTON REGISTRAR USUARIO -->
        <div class="registro">

            <p>
                ¿No tienes una cuenta?
            </p>

            <a href="registro.php" class="btn-registro">

                <i class="bi bi-person-plus-fill"></i>

                REGISTRAR NUEVO USUARIO

            </a>

        </div>

        <!-- PIE DE PAGINA -->
        <div class="footer">

            <i class="bi bi-shield-check"></i>

            Acceso seguro al sistema

            <p class="mt-3">
                © 2026 Tienda Merary
                <br>
                Todos los derechos reservados.
            </p>

        </div>

    </div>

    <!-- MOSTRAR Y OCULTAR CONTRASEÑA -->
    <script>

        function mostrarPassword() {

            const campo = document.getElementById("contrasena");
            const icono = document.getElementById("icono");

            if (campo.type === "password") {

                campo.type = "text";

                icono.classList.replace(
                    "bi-eye",
                    "bi-eye-slash"
                );

            } else {

                campo.type = "password";

                icono.classList.replace(
                    "bi-eye-slash",
                    "bi-eye"
                );

            }

        }

    </script>

</body>

</html>
