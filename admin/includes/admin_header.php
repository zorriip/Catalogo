<?php
/*
|--------------------------------------------------------------------------
| ADMIN HEADER
|--------------------------------------------------------------------------
| Cabecera común para todas las páginas del administrador.
|--------------------------------------------------------------------------
*/

//require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/admin_functions.php';

$pageTitle = $pageTitle ?? 'Administración';

$currentPage = basename($_SERVER['PHP_SELF']);

function navActive(array $pages, string $current): string
{
    return in_array($current, $pages, true) ? 'active' : '';
}
?>
<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0">

<title><?= htmlspecialchars($pageTitle) ?> | <?= SITE_NAME ?></title>

<link
    rel="stylesheet"
    href="css/admin.css?v=1">

<script
    src="js/admin.js"
    defer></script>

</head>

<body>

<header class="admin-header">

    <div class="brand">

        <div class="brand-title">

            🏺 <?= SITE_NAME ?>

        </div>

        <div class="brand-subtitle">

            Panel de Administración

        </div>

    </div>

    <nav class="admin-nav">

        <a
            href="dashboard.php"
            class="<?= navActive(['dashboard.php'], $currentPage) ?>">
            Dashboard
        </a>

        <a
            href="maceta_form.php"
            class="<?= navActive(['maceta_form.php'], $currentPage) ?>">
            Nueva maceta
        </a>

        <a
            href="export_excel.php">
            Exportar Excel
        </a>

        <a
            href="../index.php"
            target="_blank">
            Sitio web
        </a>

        <a
            class="logout"
            href="logout.php">
            Cerrar sesión
        </a>

    </nav>

</header>

<div class="admin-wrapper">

<div class="page-title">

<h1><?= htmlspecialchars($pageTitle) ?></h1>

</div>