<?php
require 'auth.php';
//require '../config.php';

if (empty($_SESSION['admin'])) {
  header('Location: ../index.php');
  exit;
}


$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: dashboard.php');
    exit;
}


// Obtener maceta
$stmt = $pdo->prepare("SELECT * FROM macetas WHERE id = ?");
$stmt->execute([$id]);
$maceta = $stmt->fetch();


$codigo = $maceta['codigo'];
$dir = "../uploads/$codigo";
if (!is_dir($dir)) mkdir($dir, 0777, true);


// Subir fotos
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_FILES['fotos']['name'][0])) {

  foreach ($_FILES['fotos']['tmp_name'] as $i => $tmp) {

    if ($_FILES['fotos']['error'][$i] !== UPLOAD_ERR_OK) {
      continue;
    }

    $nombreOriginal = basename($_FILES['fotos']['name'][$i]);
    $ext = pathinfo($nombreOriginal, PATHINFO_EXTENSION);

    // nombre único para evitar pisar archivos
    $nombre = uniqid('foto_') . '.' . $ext;
    $destino = "$dir/$nombre";

    if (move_uploaded_file($tmp, $destino)) {
      $stmt = $pdo->prepare(
        "INSERT INTO fotos (maceta_id, archivo, es_portada)
         VALUES (?, ?, 0)"
      );
      $stmt->execute([$id, "uploads/$codigo/$nombre"]);
    }
  }
}
$check = $pdo->prepare(
  "SELECT COUNT(*) FROM fotos WHERE maceta_id = ? AND es_portada = 1"
);
$check->execute([$id]);

if ($check->fetchColumn() == 0) {
  $pdo->prepare(
    "UPDATE fotos
     SET es_portada = 1
     WHERE maceta_id = ?
     ORDER BY id ASC
     LIMIT 1"
  )->execute([$id]);
}


// Marcar portada
if (isset($_GET['portada'])) {
    $pdo->prepare("UPDATE fotos SET es_portada = 0 WHERE maceta_id = ?")
        ->execute([$id]);
    $pdo->prepare("UPDATE fotos SET es_portada = 1 WHERE id = ?")
        ->execute([$_GET['portada']]);
}


// Borrar foto
if (isset($_GET['borrar'])) {
    $stmt = $pdo->prepare("SELECT archivo FROM fotos WHERE id = ?");
    $stmt->execute([$_GET['borrar']]);
    $foto = $stmt->fetch();


    if ($foto && file_exists('../' . $foto['archivo'])) {
        unlink('../' . $foto['archivo']);
    }


    $pdo->prepare("DELETE FROM fotos WHERE id = ?")
        ->execute([$_GET['borrar']]);
}


$fotos = $pdo->prepare("SELECT * FROM fotos WHERE maceta_id = ?");
$fotos->execute([$id]);
?>


<h1>Fotos – <?= htmlspecialchars($maceta['codigo']) ?> - <?= htmlspecialchars($maceta['nombre']) ?></h1>


<form method="post" enctype="multipart/form-data">
    <input type="file" name="fotos[]" multiple accept="image/*" required>
    <button>Subir fotos</button>
    <p>Podés seleccionar varias fotos a la vez</p>

</form>


<hr>


<?php foreach ($fotos as $f): ?>
    <div style="display:inline-block; margin:10px; text-align:center;">
        <img src="../<?= $f['archivo'] ?>" style="max-width:150px"><br>
        <?php if (!$f['es_portada']): ?>
            <a href="?id=<?= $id ?>&portada=<?= $f['id'] ?>">Hacer portada</a>
        <?php else: ?>
            <strong>Portada</strong>
        <?php endif; ?> |
        <a href="?id=<?= $id ?>&borrar=<?= $f['id'] ?>" onclick="return confirm('¿Borrar foto?')">Borrar</a>
    </div>
<?php endforeach; ?>


<br><br>
<a href="dashboard.php">← Volver</a>