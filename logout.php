<?php
require_once '../config.php';

/* 1. Vaciar la sesión */
$_SESSION = [];

/* 2. Borrar cookie de sesión */
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

/* 3. Destruir sesión */
session_destroy();

/* 4. Volver al login */
header('Location: login.php');
exit;
