<?php
require_once 'config.php';
require_once 'includes/functions.php';

if (!isset($_SESSION['moneda'])) {
    $_SESSION['moneda'] = 'USD';
}

$monedaActual = $_SESSION['moneda'];


$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: index.php');
    exit;
}

// Maceta
$stmt = $pdo->prepare("SELECT * FROM macetas WHERE id = ?");
$stmt->execute([$id]);
$maceta = $stmt->fetch();

if (!$maceta) {
    header('Location: index.php');
    exit;
}

// Fotos
$fotos = $pdo->prepare(
    "SELECT * FROM fotos
     WHERE maceta_id = ?
     ORDER BY es_portada DESC, id"
);
$fotos->execute([$id]);
$fotos = $fotos->fetchAll();

$descripcion = (
    $_SESSION['lang'] === 'en' && !empty($maceta['descripcion_en'])
)
    ? $maceta['descripcion_en']
    : $maceta['descripcion'];
?>

<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] === 'en' ? 'en' : 'es' ?>">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        <?= htmlspecialchars($maceta['nombre']) ?>
        | <?= SITE_NAME ?>
    </title>

    <link
        rel="stylesheet"
        href="css/style.css?v=2">

    <link
        rel="icon"
        href="favicon.ico"
        type="image/x-icon">

</head>

<body>

    <?php include 'includes/header.php'; ?>

    <main class="site-content ficha-maceta">

        <div class="ficha-topbar">

            <a
                class="ficha-volver"
                href="index.php?lang=<?= urlencode($_SESSION['lang']) ?>">
                ← <?= t('volver') ?>
            </a>

            <?php if (!empty($_SESSION['admin'])): ?>

                <div class="ficha-admin-actions">

                    <a
                        href="admin/maceta_form.php?id=<?= (int)$maceta['id'] ?>"
                        class="ficha-admin-btn">
                        ✏️ Editar
                    </a>

                    <a
                        href="admin/maceta_fotos.php?id=<?= (int)$maceta['id'] ?>"
                        class="ficha-admin-btn">
                        📷 Fotos
                    </a>

                    <a
                        href="admin/dashboard.php"
                        class="ficha-admin-btn">
                        📋 Dashboard
                    </a>

                </div>

            <?php endif; ?>

        </div>

        <header class="ficha-header">

            <p class="ficha-kicker">
                <?= htmlspecialchars($maceta['codigo']) ?>
            </p>

            <h1>
                <?= htmlspecialchars($maceta['nombre']) ?>
            </h1>

            <?php if ($maceta['estado'] !== 'vendida'): ?>

                <?php
                $mensaje_es = "Hola, me interesa la maceta {$maceta['codigo']} – {$maceta['nombre']}";
                $mensaje_en = "Hello, I am interested in the pot {$maceta['codigo']} – {$maceta['nombre']}";
                $mensaje = $_SESSION['lang'] === 'en' ? $mensaje_en : $mensaje_es;
                $mensaje = urlencode($mensaje);
                ?>

                <a
                    class="btn-whatsapp ficha-whatsapp"
                    href="https://wa.me/<?= WHATSAPP_NUMERO ?>?text=<?= $mensaje ?>"
                    target="_blank"
                    rel="noopener">
                    <?= t('consultar_wp') ?>
                </a>

            <?php else: ?>

                <p class="vendida-texto ficha-vendida">
                    <?= t('vendida_txt') ?>
                </p>

            <?php endif; ?>

        </header>

        <section class="ficha-layout">

            <div class="ficha-galeria">

                <div class="galeria">

                    <?php foreach ($fotos as $i => $f): ?>

                        <button
                            type="button"
                            class="galeria-item"
                            onclick="abrirZoom(<?= $i ?>)"
                            aria-label="Ver fotografía <?= $i + 1 ?>">
                            <img
                                src="<?= htmlspecialchars($f['archivo']) ?>"
                                alt="<?= htmlspecialchars($maceta['codigo'] . ' - ' . $maceta['nombre']) ?>"
                                loading="<?= $i === 0 ? 'eager' : 'lazy' ?>">
                        </button>

                    <?php endforeach; ?>

                </div>

            </div>

            <aside class="ficha-info">

                <div class="ficha-datos">

                    <div class="ficha-dato">
                        <span><?= t('forma') ?></span>
                        <strong><?= t_value('forma', $maceta['forma']) ?></strong>
                    </div>

                    <div class="ficha-dato">
                        <span><?= t('material') ?></span>
                        <strong><?= t_value('material', $maceta['material']) ?></strong>
                    </div>

                    <div class="ficha-dato">
                        <span><?= t('esmalte') ?></span>
                        <strong><?= t_value('esmalte', $maceta['esmalte']) ?></strong>
                    </div>

                    <div class="ficha-dato">
                        <span><?= t('dimensiones') ?></span>
                        <strong>
                            <?= t('lah') ?> =
                            <?= number_format($maceta['largo'], 1) ?> ×
                            <?= number_format($maceta['ancho'], 1) ?> ×
                            <?= number_format($maceta['alto'], 1) ?> cm
                        </strong>
                    </div>

                    <?php if ((float)$maceta['peso'] > 0): ?>
                        <div class="ficha-dato">
                            <span><?= t('peso') ?></span>
                            <strong>
                                <?= number_format($maceta['peso'], 3, ',', '.') ?> kg
                            </strong>
                        </div>
                    <?php endif; ?>

                    <?php if ((float)$maceta['capacidad'] > 0): ?>
                        <div class="ficha-dato">
                            <span><?= t('capacidad') ?></span>
                            <strong>
                                <?= number_format($maceta['capacidad'], 3, ',', '.') ?> l
                            </strong>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($maceta['precio']) && $maceta['estado'] !== 'vendida'): ?>
                        <div class="ficha-dato ficha-precio">

                            <span><?= t('precio') ?></span>

                            <strong>
                                <?= htmlspecialchars(
                                    mostrarPrecio($maceta['precio'], $monedaActual)
                                ) ?>
                            </strong>

                        </div>

                    <?php endif; ?>

                </div>

                <?php if (!empty($descripcion)): ?>

                    <div class="ficha-descripcion">

                        <h2><?= t('descripcion') ?></h2>

                        <div class="ficha-descripcion-texto">
                            <?= nl2br(htmlspecialchars($descripcion)) ?>
                        </div>

                    </div>

                <?php endif; ?>

            </aside>

        </section>

    </main>

    <div id="zoom">

        <span
            class="zoom-close"
            onclick="cerrarZoom()"
            aria-label="Cerrar">✕</span>

        <span
            class="zoom-prev"
            onclick="anterior()"
            aria-label="Fotografía anterior">❮</span>

        <img id="zoom-img" alt="">

        <span
            class="zoom-next"
            onclick="siguiente()"
            aria-label="Fotografía siguiente">❯</span>

    </div>

    <script src="js/zoom.js"></script>

    <?php include 'includes/footer.php'; ?>

</body>

</html>