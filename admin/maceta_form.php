<?php
//V3001A

require 'auth.php';

require_once __DIR__ . '/../includes/catalogos.php';
require_once __DIR__ . '/includes/admin_functions.php';



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

$stmt = $pdo->prepare("
    SELECT *
    FROM fotos
    WHERE maceta_id = ?
    ORDER BY es_portada DESC, id ASC
");

$stmt->execute([$id]);

$fotos = $stmt->fetchAll();



?>
<?php require_once __DIR__ . '/includes/admin_header.php'; ?>

<div class="page-header">

  <div>

    <h1>
      <?= $id ? 'Editar maceta' : 'Nueva maceta' ?>
    </h1>

    <p class="page-subtitle">

      <?= $id
        ? 'Modificar la información de la maceta'
        : 'Ingresar una nueva maceta al catálogo'; ?>

    </p>

  </div>

</div>
<form
  action="maceta_save.php"
  method="post"
  enctype="multipart/form-data"
  class="admin-form">

  <input
    type="hidden"
    name="id"
    value="<?= (int)$id ?>">

  <!-- ==========================================================
     TARJETA 1
     DATOS GENERALES
=========================================================== -->

  <div class="form-card">

    <h2>Datos generales</h2>

    <div class="datos-grid">

      <!-- ================= Código ================= -->

      <div class="field span-1">

        <label>Código</label>

        <input
          type="text"
          name="codigo"
          value="<?= htmlspecialchars($maceta['codigo']) ?>"
          required>

      </div>


      <!-- ================= Nombre ================= -->

      <div class="field span-11">
        <label>Nombre</label>

        <input
          type="text"
          name="nombre"
          value="<?= htmlspecialchars($maceta['nombre']) ?>"
          required>

      </div>


      <!-- ================= Forma ================= -->

      <div class="field span-4">

        <label>Forma</label>

        <select name="forma">

          <option value="">
            <?= t('seleccionar') ?>
          </option>

          <?php foreach (catalogoFormas() as $forma): ?>

            <option
              value="<?= $forma ?>"
              <?= ($maceta['forma'] ?? '') == $forma ? 'selected' : '' ?>>

              <?= t_value('forma', $forma) ?>

            </option>

          <?php endforeach; ?>

        </select>

      </div>


      <!-- ================= Material ================= -->

      <div class="field span-4">

        <label>Material</label>

        <select name="material">

          <?php foreach (catalogoMateriales() as $material): ?>

            <option
              value="<?= $material ?>"
              <?= ($maceta['material'] ?? '') == $material ? 'selected' : '' ?>>

              <?= t_value('material', $material) ?>

            </option>

          <?php endforeach; ?>

        </select>

      </div>


      <!-- ================= Esmalte ================= -->

      <div class="field span-4">

        <label><?= t('esmalte') ?></label>

        <select name="esmalte">

          <option value="">
            <?= t('seleccionar') ?>
          </option>

          <?php foreach (catalogoEsmaltes() as $esmalte): ?>

            <option
              value="<?= $esmalte ?>"
              <?= ($maceta['esmalte'] ?? '') == $esmalte ? 'selected' : '' ?>>

              <?= t_value('esmalte', $esmalte) ?>

            </option>

          <?php endforeach; ?>

        </select>

      </div>


      <!-- ================= Largo ================= -->

      <div class="field span-2">

        <label>Largo (cm)</label>

        <input
          type="number"
          step="0.01"
          name="largo"
          value="<?= $maceta['largo'] ?>">

      </div>


      <!-- ================= Ancho ================= -->

      <div class="field span-2">

        <label>Ancho (cm)</label>

        <input
          type="number"
          step="0.01"
          name="ancho"
          value="<?= $maceta['ancho'] ?>">

      </div>


      <!-- ================= Alto ================= -->

      <div class="field span-2">

        <label>Alto (cm)</label>

        <input
          type="number"
          step="0.01"
          name="alto"
          value="<?= $maceta['alto'] ?>">

      </div>


      <!-- ================= Capacidad ================= -->

      <div class="field span-3">

        <label>Capacidad (L)</label>

        <input
          type="number"
          step="0.001"
          name="capacidad"
          value="<?= $maceta['capacidad'] ?>">

      </div>


      <!-- ================= Peso ================= -->

      <div class="field span-3">

        <label>Peso (kg)</label>

        <input
          type="number"
          step="0.001"
          name="peso"
          value="<?= $maceta['peso'] ?>">

      </div>


      <!-- ================= Precio ================= -->

      <div class="field span-8">

        <label>Precio</label>

        <input
          type="number"
          step="0.01"
          name="precio"
          value="<?= $maceta['precio'] ?>">

      </div>


      <!-- ================= Estado ================= -->

      <div class="field span-4">

        <label>Estado</label>

        <select name="estado">

          <option value="disponible"
            <?= $maceta['estado'] == 'disponible' ? 'selected' : '' ?>>
            Disponible
          </option>

          <option value="vendida"
            <?= $maceta['estado'] == 'vendida' ? 'selected' : '' ?>>
            Vendida
          </option>

        </select>

      </div>

    </div>

  </div>
  <!-- ==========================================================
     DESCRIPCIÓN EN ESPAÑOL
=========================================================== -->
  <div class="descripcion-grid">
    <div class="form-card">

      <h2>Descripción (Español)</h2>

      <div class="field">

        <textarea
          name="descripcion"
          rows="8"><?= htmlspecialchars($maceta['descripcion'] ?? '') ?></textarea>

      </div>

    </div>
    <!-- ==========================================================
     DESCRIPTION ENGLISH
=========================================================== -->

    <div class="form-card">

      <h2>Description (English)</h2>

      <div class="field">

        <textarea
          name="descripcion_en"
          rows="8"><?= htmlspecialchars($maceta['descripcion_en'] ?? '') ?></textarea>

      </div>

    </div>
  </div>
  <!-- ==========================================================
     FOTOGRAFÍAS
=========================================================== -->

  <?php include __DIR__ . '/includes/fotos_panel.php'; ?>


</form>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>