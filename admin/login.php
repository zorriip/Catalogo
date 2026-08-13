<?php
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $usuario = $_POST['usuario'] ?? '';
    $clave   = $_POST['password'] ?? '';

    $stmt = $pdo->prepare(
        "SELECT * FROM usuarios WHERE usuario = ?"
    );
    $stmt->execute([$usuario]);
    $user = $stmt->fetch();

    if ($user && password_verify($clave, $user['password'])) {
        $_SESSION['admin'] = true;
        $_SESSION['usuario'] = $user['usuario'];

        header('Location: dashboard.php');
        exit;
    }

    $error = "Usuario o contraseña incorrectos";
}
?>

<!DOCTYPE html>
<html lang="<?= htmlspecialchars($_SESSION['lang'] ?? 'es') ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Acceso | Catálogo</title>

    <link rel="stylesheet" href="../css/style.css">

    <style>
        /* ======================================================
           LOGIN ADMIN — compatible con el frontend del catálogo
        ====================================================== */

        body.login-page {
            min-height: 100vh;
            margin: 0;
            display: flex;
            flex-direction: column;
            background: #f4f2ed;
        }

        .login-header {
            min-height: 76px;
            display: flex;
            align-items: center;
            padding: 16px 32px;
            background: #151515;
            color: #fff;
        }

        .login-header-inner {
            width: min(1320px, 100%);
            margin: 0 auto;
        }

        .login-brand {
            color: #fff;
            font-family: Georgia, "Times New Roman", serif;
            font-size: 23px;
            font-weight: 400;
            letter-spacing: .4px;
            text-decoration: none;
        }

        .login-main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 50px 20px;
        }

        .login-card {
            width: min(100%, 420px);
            padding: 38px;
            background: #fff;
            border: 1px solid #dedbd4;
            border-radius: 14px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .08);
        }

        .login-kicker {
            margin: 0 0 7px;
            color: #8a6a43;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .login-card h1 {
            margin: 0;
            color: #252525;
            font-family: Georgia, "Times New Roman", serif;
            font-size: 30px;
            font-weight: 400;
            line-height: 1.2;
        }

        .login-intro {
            margin: 10px 0 28px;
            color: #777;
            font-size: 14px;
        }

        .login-field {
            margin-bottom: 18px;
        }

        .login-field label {
            display: block;
            margin-bottom: 7px;
            color: #4d4d4d;
            font-size: 13px;
            font-weight: 700;
        }

        .login-field input {
            width: 100%;
            padding: 11px 13px;
            color: #292929;
            background: #fff;
            border: 1px solid #cfcac2;
            border-radius: 7px;
            outline: none;
            transition:
                border-color .2s ease,
                box-shadow .2s ease;
        }

        .login-field input:focus {
            border-color: #8a6a43;
            box-shadow: 0 0 0 3px rgba(138, 106, 67, .12);
        }

        .login-submit {
            width: 100%;
            margin-top: 6px;
            padding: 11px 16px;
            color: #fff;
            background: #8a6a43;
            border: 1px solid #8a6a43;
            border-radius: 7px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 700;
            transition:
                background .2s ease,
                border-color .2s ease,
                transform .15s ease;
        }

        .login-submit:hover {
            background: #6e5336;
            border-color: #6e5336;
        }

        .login-submit:active {
            transform: translateY(1px);
        }

        .login-error {
            margin: 0 0 20px;
            padding: 10px 12px;
            color: #8b3f3f;
            background: #f8eeee;
            border: 1px solid #e5caca;
            border-radius: 7px;
            font-size: 13px;
        }

        .login-footer {
            padding: 18px 20px;
            color: #777;
            text-align: center;
            font-size: 12px;
        }

        @media (max-width: 560px) {

            .login-header {
                min-height: 64px;
                padding: 14px 20px;
            }

            .login-brand {
                font-size: 20px;
            }

            .login-main {
                padding: 30px 14px;
            }

            .login-card {
                padding: 28px 22px;
            }
        }
    </style>
</head>

<body class="login-page">

<header class="login-header">
    <div class="login-header-inner">
        <a class="login-brand" href="../index.php">
            Catálogo
        </a>
    </div>
</header>

<main class="login-main">

    <section class="login-card">

        <p class="login-kicker">Administración</p>

        <h1>Iniciar sesión</h1>

        <p class="login-intro">
            Accede al panel de administración del catálogo.
        </p>

        <?php if (!empty($error)): ?>

            <p class="login-error">
                <?= htmlspecialchars($error) ?>
            </p>

        <?php endif; ?>

        <form method="post">

            <div class="login-field">

                <label for="usuario">
                    Usuario
                </label>

                <input
                    id="usuario"
                    name="usuario"
                    type="text"
                    value="<?= htmlspecialchars($_POST['usuario'] ?? '') ?>"
                    autocomplete="username"
                    required
                    autofocus
                >

            </div>

            <div class="login-field">

                <label for="password">
                    Contraseña
                </label>

                <input
                    id="password"
                    name="password"
                    type="password"
                    autocomplete="current-password"
                    required
                >

            </div>

            <button
                type="submit"
                class="login-submit"
            >
                Entrar
            </button>

        </form>

    </section>

</main>

<footer class="login-footer">
    Catálogo · Administración
</footer>

</body>
</html>
