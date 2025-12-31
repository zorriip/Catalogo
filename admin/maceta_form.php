<?php
require 'auth.php';

require_once '../config.php';

if (empty($_SESSION['admin'])) {
  header('Location: ../index.php');
  exit;
}


$id = $_GET['id'] ?? null;
$maceta = [
  'codigo' => '',
  'nombre' => '',
  'descripcion' => '',
  'forma' => '',
  'largo' => '',
  'ancho' => '',
  'alto' => '',
  'esmalte' => '',
  'material' => '',
  'precio' => '',
  'estado' => 'disponible',
  'peso' => '',
  'capacidad' => ''
  
];

if ($id) {
  $stmt = $pdo->prepare("SELECT * FROM macetas WHERE id = ?");
  $stmt->execute([$id]);
  $maceta = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  if ($id) {
    // EDITAR
    $sql = "UPDATE macetas SET
codigo = ?, nombre = ?, descripcion = ?, descripcion_en = ?, forma = ?,
largo = ?, ancho = ?, alto = ?, esmalte = ?, material = ?, precio = ?, estado = ?, peso = ?, capacidad = ?
WHERE id = ?";
    $params = [
      $_POST['codigo'],
      $_POST['nombre'],
      $_POST['descripcion'],
      $_POST['descripcion_en'],
      $_POST['forma'],
      $_POST['largo'],
      $_POST['ancho'],
      $_POST['alto'],
      $_POST['esmalte'],
      $_POST['material'],
      $_POST['precio'],
      $_POST['estado'],
      $_POST['peso'],
      $_POST['capacidad'],
      $id
    ];
  } else {
    // CREAR
    $sql = "INSERT INTO macetas
(codigo, nombre, descripcion, descripcion_en, forma, largo, ancho, alto, esmalte, material, precio, estado, peso, capacidad)
VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $params = [
      $_POST['codigo'],
      $_POST['nombre'],
      $_POST['descripcion'],
      $_POST['descripcion_en'],
      $_POST['forma'],
      $_POST['largo'],
      $_POST['ancho'],
      $_POST['alto'],
      $_POST['esmalte'],
      $_POST['material'],
      $_POST['precio'],
      $_POST['estado'],
      $_POST['peso'],
      $_POST['capacidad']
    ];
  }

  $stmt = $pdo->prepare($sql);
  $stmt->execute($params);

  header('Location: dashboard.php');
  exit;
}
?>

<h1><?= $id ? 'Editar maceta' : 'Nueva maceta' ?></h1>

<form method="post">
  <label>Código</label><br>
  <input name="codigo" value="<?= htmlspecialchars($maceta['codigo']) ?>" required><br><br>

  <label>Nombre</label><br>
  <input name="nombre" value="<?= htmlspecialchars($maceta['nombre']) ?>" required><br><br>

  
<label>Descripción:</label><br>
<textarea name="descripcion" rows="4">
<?= htmlspecialchars($maceta['descripcion'] ?? '') ?>
</textarea>


<label><?= t('descripcion_en') ?>:</label>
<textarea name="descripcion_en" rows="4" placeholder="Description in English">
<?= htmlspecialchars($maceta['descripcion_en'] ?? '') ?>
</textarea><br>




  <label>Forma</label><br>
  <select name="forma">
    <option value="">--</option>
    <option value="oval" <?= $maceta['forma']=='oval'?'selected':'' ?>>Oval</option>
    <option value="rectangular" <?= $maceta['forma']=='rectangular'?'selected':'' ?>>Rectangular</option>
    <option value="redonda" <?= $maceta['forma']=='redonda'?'selected':'' ?>>Redonda</option>
    <option value="cuadrada" <?= $maceta['forma']=='cuadrada'?'selected':'' ?>>Cuadrada</option>
    <option value="flor" <?= $maceta['forma']=='flor'?'selected':'' ?>>Flor</option>
    <option value="kurama" <?= $maceta['forma']=='kurama'?'selected':'' ?>>Kurama</option>
    <option value="cascada" <?= $maceta['forma']=='cascada'?'selected':'' ?>>Cascada</option>
    <option value="kusamono" <?= $maceta['forma']=='kusamono'?'selected':'' ?>>Kusamon</option>
  </select><br><br>

  <label>Medidas (cm)</label><br>
  Largo <input name="largo" size="4" value="<?= $maceta['largo'] ?>">
  Ancho <input name="ancho" size="4" value="<?= $maceta['ancho'] ?>">
  Alto <input name="alto" size="4" value="<?= $maceta['alto'] ?>"><br><br>

  <label>Peso (kg)</label><br>
<input type="number" name="peso"
       step="0.001"
       value="<?= $maceta['peso'] ?? '' ?>"><br><br>

<label>Capacidad (litros)</label><br>
<input type="number" name="capacidad"
       step="0.001"
       value="<?= $maceta['capacidad'] ?? '' ?>"><br><br>


<label>Material</label><br>
<select name="material" required>
  <?php
  $materiales = ['gres','terracota','ceramica','raku','barro','plastico'];
  foreach ($materiales as $mat):
  ?>
    <option value="<?= $mat ?>"
      <?= ($maceta['material'] ?? '') === $mat ? 'selected' : '' ?>>
      <?= $mat ?>
    </option>
  <?php endforeach; ?>
</select><br><br>


<label><?= t('esmalte') ?>:</label>
<select name="esmalte">

  <option value=""><?= t('seleccionar') ?></option>

  <option value="brillante" <?= ($maceta['esmalte'] ?? '') === 'brillante' ? 'selected' : '' ?>>
    <?= t('esmalte_brillante') ?>
  </option>

  <option value="satinado" <?= ($maceta['esmalte'] ?? '') === 'satinado' ? 'selected' : '' ?>>
    <?= t('esmalte_satinado') ?>
  </option>

  <option value="mate" <?= ($maceta['esmalte'] ?? '') === 'mate' ? 'selected' : '' ?>>
    <?= t('esmalte_mate') ?>
  </option>

  <option value="sin_esmalte" <?= ($maceta['esmalte'] ?? '') === 'sin_esmalte' ? 'selected' : '' ?>>
    <?= t('esmalte_sin') ?>
  </option>

  <option value="mixto" <?= ($maceta['esmalte'] ?? '') === 'mixto' ? 'selected' : '' ?>>
    <?= t('esmalte_mixto') ?>
  </option>

</select>

 

<label>Precio</label>
<input type="number"
       name="precio"
       step="0.01"
       value="<?= $maceta['precio'] ?? '' ?>"><br><br>


  <label>Estado</label><br>
  <select name="estado">
    <option value="disponible" <?= $maceta['estado']=='disponible'?'selected':'' ?>>Disponible</option>
    <option value="vendida" <?= $maceta['estado']=='vendida'?'selected':'' ?>>Vendida</option>
  </select><br><br>

  <button>Guardar</button>
  <a href="dashboard.php">Cancelar</a>
</form>
