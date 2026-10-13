<?php

if (!isset($_SESSION['moneda'])) {
    $_SESSION['moneda'] = 'USD';
}

$monedaActual = $_SESSION['moneda'];

require_once 'config.php';


// Cambio de moneda
if (isset($_GET['moneda'])) {

    if ($_GET['moneda'] === 'USD' || $_GET['moneda'] === 'UYU') {
        $_SESSION['moneda'] = $_GET['moneda'];
    }

    $monedaActual = $_SESSION['moneda'];
}

?>

<header class="site-header">

    <div class="header-left">

        <h1><?= SITE_NAME ?></h1>

    </div>


    <nav class="header-nav">

        <a href="index.php?lang=<?= $_SESSION['lang'] ?>">
            <?= t('inicio') ?>
        </a>

        <a
            href="contacto.php?lang=<?= $_SESSION['lang'] ?>"
            class="<?= basename($_SERVER['PHP_SELF']) === 'contacto.php' ? 'activo' : '' ?>">

            <?= t('contacto') ?>

        </a>
        <a
            href="energia.php?lang=<?= $_SESSION['lang'] ?>"
            class="header-energia">
            ♻ <?= t('energia_menu') ?>
        </a>

    </nav>


    <?php

    $url = strtok($_SERVER['REQUEST_URI'], '?');
    $params = $_GET;

    ?>


    <div class="header-preferencias">


        <!-- IDIOMA -->

        <div class="idioma">

            <a
                href="<?= $url ?>?<?= http_build_query(array_merge($params, ['lang' => 'es'])) ?>"
                class="<?= $_SESSION['lang'] === 'es' ? 'activo' : '' ?>">

                ES

            </a>

            <span>|</span>

            <a
                href="<?= $url ?>?<?= http_build_query(array_merge($params, ['lang' => 'en'])) ?>"
                class="<?= $_SESSION['lang'] === 'en' ? 'activo' : '' ?>">

                EN

            </a>

        </div>


        <!-- MONEDA -->

        <div class="moneda">

            <a
                href="<?= $url ?>?<?= http_build_query(array_merge($params, ['moneda' => 'USD'])) ?>"
                class="<?= $monedaActual === 'USD' ? 'activo' : '' ?>">

                US$

            </a>

            <span>|</span>

            <a
                href="<?= $url ?>?<?= http_build_query(array_merge($params, ['moneda' => 'UYU'])) ?>"
                class="<?= $monedaActual === 'UYU' ? 'activo' : '' ?>">

                $U

            </a>

        </div>


    </div>

</header>