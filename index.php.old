<?php
require_once 'config.php';
require_once 'includes/functions.php';


$condiciones = [];
$params = [];

if (!empty($_GET['forma'])) {
  $condiciones[] = "forma = ?";
  $params[] = $_GET['forma'];
}

if (isset($_GET['estado']) && $_GET['estado'] !== '') {
  $condiciones[] = "vendida = ?";
  $params[] = (int) $_GET['estado'];
}

$sql = "SELECT * FROM macetas";
if ($condiciones) {
  $sql .= " WHERE " . implode(' AND ', $condiciones);
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$macetas = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title><?= t('catalogo')?></title>
  <link rel="stylesheet" href="css/style.css">
</head>

<body>
  <?php include 'includes/header.php'; ?>
  <main class="site-content catalogo">

    <h1><?= t('catalogo_b') ?></h1>


    <form method="get" style="margin-bottom:20px">

      <label><?= t('forma') ?>:</label>
      <select name="forma" onchange="this.form.submit()">

        <option value="">
          <?= t('todas') ?>
        </option>

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

      <label><?= t('estado') ?>:</label>
      <select name="estado" onchange="this.form.submit()">

        <option value="">
          <?= t('todas') ?>
        </option>

        <option value="0" <?= ($_GET['estado'] ?? '') === '0' ? 'selected' : '' ?>>
          <?= t('estado_disponible') ?>
        </option>

        <option value="1" <?= ($_GET['estado'] ?? '') === '1' ? 'selected' : '' ?>>
          <?= t('estado_vendida') ?>
        </option>

      </select>


    </form>



    <div class="catalogo-grid">
      <?php foreach ($macetas as $m):

        $fotoStmt = $pdo->prepare(
          "SELECT archivo FROM fotos WHERE maceta_id = ? AND es_portada = 1 LIMIT 1"
        );
        $fotoStmt->execute([$m['id']]);
        $portada = $fotoStmt->fetchColumn(); ?>

        <div class="maceta-card">

          <!-- IMAGEN clickeable -->
          <a href="maceta.php?id=<?= $m['id'] ?>&lang=<?= $_SESSION['lang'] ?>"
            class="maceta-link">

            <div class="maceta-img-wrapper">

              <?php if ($m['vendida']): ?>
                <span class="vendida"><?= t('vendida') ?></span>
              <?php else: ?>
                <span class="disponible"><?= t('disponible') ?></span>
              <?php endif; ?>

              <?php if (!empty($_SESSION['admin'])): ?>
                <a class="editar-maceta"
                  href="admin/dashboard.php?q=<?= urlencode($m['codigo']) ?>">
                  ✏️ Editar
                </a>
              <?php endif; ?>

              <img src="<?= $portada ?: 'sin-foto.jpg' ?>">
            </div>
          </a>

          <!-- TÍTULO clickeable (SEPARADO) -->
          <div class="maceta-titulo">
            <a href="maceta.php?id=<?= $m['id'] ?>&lang=<?= $_SESSION['lang'] ?>">
              <?= htmlspecialchars($m['codigo']) ?> — <?= htmlspecialchars($m['nombre']) ?>
            </a>
          </div>

        </div>


      <?php endforeach; ?>
    </div>

  </main>
  <?php include 'includes/footer.php'; ?>
</body>

</html>