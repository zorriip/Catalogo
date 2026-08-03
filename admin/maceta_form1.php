<?php
require 'auth.php';

require_once __DIR__ . '/../includes/catalogos.php';

$id = $_GET['id'] ?? null;

$maceta = [
  'codigo' => '',
  'nombre' => '',
  'descripcion' => '',
  'descripcion_en' => '',
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
    <option value=""><?= t('seleccionar') ?></option>

    <?php foreach (catalogoFormas() as $forma): ?>

      <option
        value="<?= $forma ?>"
        <?= $maceta['forma'] === $forma ? 'selected' : '' ?>>

        <?= t_value('forma', $forma) ?>

      </option>

    <?php endforeach; ?>

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
  <select name="material">

<?php foreach (catalogoMateriales() as $material): ?>

<option
    value="<?= $material ?>"
    <?= $maceta['material']===$material ? 'selected' : '' ?>>

    <?= t_value('material',$material) ?>

</option>

<?php endforeach; ?>

</select><br><br>


  <label><?= t('esmalte') ?>:</label>
  <select name="esmalte">

<option value=""><?= t('seleccionar') ?></option>

<?php foreach (catalogoEsmaltes() as $esmalte): ?>

<option
    value="<?= $esmalte ?>"
    <?= $maceta['esmalte']===$esmalte ? 'selected' : '' ?>>

    <?= t_value('esmalte',$esmalte) ?>

</option>

<?php endforeach; ?>

</select>



  <label>Precio</label>
  <input type="number"
    name="precio"
    step="0.01"
    value="<?= $maceta['precio'] ?? '' ?>"><br><br>


  <label>Estado</label><br>
  <select name="estado">
    <option value="disponible" <?= $maceta['estado'] == 'disponible' ? 'selected' : '' ?>>Disponible</option>
    <option value="vendida" <?= $maceta['estado'] == 'vendida' ? 'selected' : '' ?>>Vendida</option>
  </select><br><br>

  <button>Guardar</button>
  <a href="dashboard.php">Cancelar</a>
</form>