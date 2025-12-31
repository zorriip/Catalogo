<?php
require_once 'config.php';


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
  "SELECT * FROM fotos WHERE maceta_id = ? ORDER BY es_portada DESC, id"
);
$fotos->execute([$id]);
?>

<?php include 'includes/header.php'; ?>

<?php if (!empty($_SESSION['admin'])): ?>
  <div class="admin-actions">
    <a href="admin/maceta_form.php?id=<?= $maceta['id'] ?>">✏️ Editar</a>
    <a href="admin/maceta_fotos.php?id=<?= $maceta['id'] ?>">📷 Fotos</a>
    <a href="admin/dashboard.php">📋 Dashboard</a>
  </div>
<?php endif; ?>




<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($maceta['nombre']) ?></title>
  <link rel="stylesheet" href="css/style.css">
</head>

<body>



  <a href="index.php?lang=<?= $_SESSION['lang'] ?>">
    <?= t('volver') ?>
  </a>


  <h1><?= htmlspecialchars($maceta['codigo']) ?> - <?= htmlspecialchars($maceta['nombre']) ?></h1>

  <?php if (!$maceta['vendida']): ?>
    <?php
    $mensaje_es = "Hola, me interesa la maceta {$maceta['codigo']} – {$maceta['nombre']}";
    $mensaje_en = "Hello, I am interested in the pot {$maceta['codigo']} – {$maceta['nombre']}";

    $mensaje = $_SESSION['lang'] === 'en' ? $mensaje_en : $mensaje_es;
    $mensaje = urlencode($mensaje);?>


    <a class="btn-whatsapp"
   href="https://wa.me/<?= WHATSAPP_NUMERO ?>?text=<?= $mensaje ?>"
   target="_blank">
   <?= t('consultar_wp') ?>
   </a>

  

   
  <?php else: ?>
    <p class="vendida-texto"><?= t('vendida_txt') ?>:</p>
  <?php endif; ?>


  <li><strong><?= t('forma') ?>:</strong><?= t_value('forma', $maceta['forma']) ?></li>

  <li>
    <strong><?= t('material') ?>:</strong><?= t_value('material', $maceta['material']) ?>
  </li>






  <li><strong><?= t('descripcion') ?>:</strong>
    <?php $descripcion = ($_SESSION['lang'] === 'en' && !empty($maceta['descripcion_en']))
      ? $maceta['descripcion_en']
      : $maceta['descripcion'];

    echo nl2br(htmlspecialchars($descripcion)); ?>




  <li>
    <strong><?= t('esmalte') ?>:</strong>
    <?= t_value('esmalte', $maceta['esmalte']) ?>
  </li>



  <?php if (!empty($maceta['precio']) && !$maceta['vendida']): ?>
    <li class="precio">
      <strong><?= t('precio') ?>:</strong> $ <?= number_format($maceta['precio'], 0) ?>
    </li>
  <?php endif; ?>


  <li>
    <strong><?= t('dimensiones') ?>:</strong>
    <?= t('lah') ?> =
    <?= number_format($maceta['largo'], 1) ?> ×
    <?= number_format($maceta['ancho'], 1) ?> ×
    <?= number_format($maceta['alto'], 1) ?> cm
  </li>
  <?php if ((float)$maceta['peso'] > 0): ?>
    <li><strong><?= t('peso') ?>:</strong>
      <?= number_format($maceta['peso'], 3, ',', '.') ?> kg
    </li>
  <?php endif; ?>

  <?php if ((float)$maceta['capacidad'] > 0): ?>
    <li><strong><?= t('capacidad') ?>:</strong>
      <?= number_format($maceta['capacidad'], 3, ',', '.') ?> l
    </li>
  <?php endif; ?>

  <div class="galeria">
    <?php foreach ($fotos as $i => $f): ?>
      <img src="<?= htmlspecialchars($f['archivo']) ?>"
        onclick="abrirZoom(<?= $i ?>)">
    <?php endforeach; ?>
  </div>


  <div id="zoom">
    <span class="zoom-close" onclick="cerrarZoom()">✕</span>
    <span class="zoom-prev" onclick="anterior()">❮</span>
    <img id="zoom-img">
    <span class="zoom-next" onclick="siguiente()">❯</span>
  </div>






  <script src="js/zoom.js"></script>

</body>

</html>
<?php include 'includes/footer.php'; ?>