<?php
require_once 'config.php';
require_once 'includes/functions.php';

if (!isset($_SESSION['moneda'])) {
    $_SESSION['moneda'] = 'USD';
}

$monedaActual = $_SESSION['moneda'];


// Rótulo de envíos
$textoEnvios = t('envios_todo_el_mundo');

$condiciones = [];
$params = [];

if (!empty($_GET['forma'])) {
    $condiciones[] = "forma = ?";
    $params[] = $_GET['forma'];
}

if (isset($_GET['estado']) && $_GET['estado'] !== '') {

    if ($_GET['estado'] === '0') {
        $condiciones[] = "estado = ?";
        $params[] = 'disponible';
    }

    if ($_GET['estado'] === '1') {
        $condiciones[] = "estado = ?";
        $params[] = 'vendida';
    }
}

$sql = "SELECT * FROM macetas";

if ($condiciones) {
    $sql .= " WHERE " . implode(' AND ', $condiciones);
}

$sql .= " ORDER BY id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$macetas = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="<?= htmlspecialchars($_SESSION['lang'] ?? 'es') ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= t('catalogo') ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <?php include 'includes/header.php'; ?>

    <main class="site-content catalogo">

        <section class="catalogo-intro">

            <div class="catalogo-heading-row">

                <div>
                    <p class="catalogo-kicker"><?= t('catalogo') ?></p>
                    <h1><?= t('catalogo_b') ?></h1>
                </div>

                <?php if (!empty($_SESSION['admin'])): ?>

                    <a
                        class="admin-login-link"
                        href="admin/dashboard.php">
                        ⚙ Administración
                    </a>

                <?php else: ?>

                    <a
                        class="admin-login-link"
                        href="admin/login.php">
                        Acceso administrador
                    </a>

                <?php endif; ?>

            </div>

        </section>

        <section class="envios-banner">
            <span class="envios-icono">✈</span>
            <span><?= htmlspecialchars($textoEnvios) ?></span>
        </section>

        <section class="catalogo-filtros">

            <form method="get" class="filtros-form">

                <div class="filtro">
                    <label for="forma"><?= t('forma') ?></label>

                    <select id="forma" name="forma" onchange="this.form.submit()">
                        <option value=""><?= t('todas') ?></option>

                        <option value="rectangular" <?= ($_GET['forma'] ?? '') === 'rectangular' ? 'selected' : '' ?>>
                            <?= t('forma_rectangular') ?>
                        </option>

                        <option value="oval" <?= ($_GET['forma'] ?? '') === 'oval' ? 'selected' : '' ?>>
                            <?= t('forma_oval') ?>
                        </option>

                        <option value="redonda" <?= ($_GET['forma'] ?? '') === 'redonda' ? 'selected' : '' ?>>
                            <?= t('forma_redonda') ?>
                        </option>

                        <option value="cuadrada" <?= ($_GET['forma'] ?? '') === 'cuadrada' ? 'selected' : '' ?>>
                            <?= t('forma_cuadrada') ?>
                        </option>

                        <option value="flor" <?= ($_GET['forma'] ?? '') === 'flor' ? 'selected' : '' ?>>
                            <?= t('forma_flor') ?>
                        </option>

                        <option value="kurama" <?= ($_GET['forma'] ?? '') === 'kurama' ? 'selected' : '' ?>>
                            <?= t('forma_kurama') ?>
                        </option>

                        <option value="cascada" <?= ($_GET['forma'] ?? '') === 'cascada' ? 'selected' : '' ?>>
                            <?= t('forma_cascada') ?>
                        </option>

                        <option value="kusamono" <?= ($_GET['forma'] ?? '') === 'kusamono' ? 'selected' : '' ?>>
                            <?= t('forma_kusamono') ?>
                        </option>
                    </select>
                </div>

                <div class="filtro">
                    <label for="estado"><?= t('estado') ?></label>

                    <select id="estado" name="estado" onchange="this.form.submit()">
                        <option value=""><?= t('todas') ?></option>

                        <option value="0" <?= ($_GET['estado'] ?? '') === '0' ? 'selected' : '' ?>>
                            <?= t('estado_disponible') ?>
                        </option>

                        <option value="1" <?= ($_GET['estado'] ?? '') === '1' ? 'selected' : '' ?>>
                            <?= t('estado_vendida') ?>
                        </option>
                    </select>
                </div>

            </form>

        </section>

        <section class="catalogo-grid">

            <?php foreach ($macetas as $m): ?>

                <?php
                $fotoStmt = $pdo->prepare(
                    "SELECT archivo
                 FROM fotos
                 WHERE maceta_id = ?
                   AND es_portada = 1
                 LIMIT 1"
                );

                $fotoStmt->execute([$m['id']]);
                $portada = $fotoStmt->fetchColumn();
                ?>

                <article class="maceta-card">

                    <div class="maceta-img-wrapper">

                        <a
                            href="maceta.php?id=<?= (int) $m['id'] ?>&lang=<?= urlencode($_SESSION['lang']) ?>"
                            class="maceta-link">

                            <?php if ($m['estado'] === 'vendida'): ?>
                                <span class="estado vendida"><?= t('vendida') ?></span>
                            <?php else: ?>
                                <span class="estado disponible"><?= t('disponible') ?></span>
                            <?php endif; ?>

                            <img
                                src="<?= htmlspecialchars($portada ?: 'sin-foto.jpg') ?>"
                                alt="<?= htmlspecialchars($m['codigo'] . ' — ' . $m['nombre']) ?>"
                                loading="lazy">

                            <span class="ver-maceta">
                                Ver maceta
                            </span>

                        </a>

                        <?php if (!empty($_SESSION['admin'])): ?>
                            <a
                                class="editar-maceta"
                                href="admin/dashboard.php?q=<?= urlencode($m['codigo']) ?>">
                                ✏️ Editar
                            </a>
                        <?php endif; ?>

                    </div>

                    <div class="maceta-info">

                        <a
                            href="maceta.php?id=<?= (int) $m['id'] ?>&lang=<?= urlencode($_SESSION['lang']) ?>"
                            class="maceta-titulo">
                            <span class="maceta-codigo">
                                <?= htmlspecialchars($m['codigo']) ?>
                            </span>

                            <span class="maceta-nombre">
                                <?= htmlspecialchars($m['nombre']) ?>
                            </span>
                        </a>

                        <?php if (!empty($m['precio']) && $m['estado'] !== 'vendida'): ?>

                            <div class="maceta-precio">
                                <?= htmlspecialchars(mostrarPrecio($m['precio'], $monedaActual)) ?>
                            </div>

                        <?php endif; ?>

                    </div>

                </article>

            <?php endforeach; ?>

        </section>

    </main>

    <?php include 'includes/footer.php'; ?>

</body>

</html>