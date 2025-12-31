<?php
require_once 'config.php';

?>


<!DOCTYPE html>



<html lang="<?= $_SESSION['lang'] ?>">

<head>
  <meta charset="UTF-8">
  <title><?= t('titulo_sitio') ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="/Catalogo/css/style.css">
</head>

<body>

  <header class="site-header">

    <div class="header-left">
      <h1><?= SITE_NAME ?></h1>
      
    </div>

    <nav class="header-nav">
      <a href="index.php?lang=<?= $_SESSION['lang'] ?>">
        <?= t('inicio') ?>
      </a>
      <a href="contacto.php?lang=<?= $_SESSION['lang'] ?>"
        class="<?= basename($_SERVER['PHP_SELF']) === 'contacto.php' ? 'activo' : '' ?>">
        <?= t('contacto') ?>
      </a>

    </nav>

    <?php
    $url = strtok($_SERVER['REQUEST_URI'], '?');
    $params = $_GET;
    ?>

    <div class="idioma">
      <a href="<?= $url ?>?<?= http_build_query(array_merge($params, ['lang' => 'es'])) ?>"
        class="<?= $_SESSION['lang'] === 'es' ? 'activo' : '' ?>">
        ES
      </a>
      |
      <a href="<?= $url ?>?<?= http_build_query(array_merge($params, ['lang' => 'en'])) ?>"
        class="<?= $_SESSION['lang'] === 'en' ? 'activo' : '' ?>">
        EN
      </a>
    </div>


  </header>

  <main class="site-content">