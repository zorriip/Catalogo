<?php
//V3001A

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

$stmt = $pdo->prepare("
    SELECT *
    FROM fotos
    WHERE maceta_id = ?
    ORDER BY es_portada DESC, id ASC
");

$stmt->execute([$id]);

$fotos = $stmt->fetchAll();

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


<form method="post" class="admin-form">

<!-- ==========================================================
     TARJETA 1
     DATOS GENERALES
=========================================================== -->

<div class="card">

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
                        <?= ($maceta['forma'] ?? '')==$forma ? 'selected':'' ?>>

                        <?= t_value('forma',$forma) ?>

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
                        <?= ($maceta['material'] ?? '')==$material ? 'selected':'' ?>>

                        <?= t_value('material',$material) ?>

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
                        <?= ($maceta['esmalte'] ?? '')==$esmalte ? 'selected':'' ?>>

                        <?= t_value('esmalte',$esmalte) ?>

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
                    <?= $maceta['estado']=='disponible'?'selected':'' ?>>
                    Disponible
                </option>

                <option value="vendida"
                    <?= $maceta['estado']=='vendida'?'selected':'' ?>>
                    Vendida
                </option>

            </select>

        </div>

    </div>

</div>
<!-- ==========================================================
     DESCRIPCIÓN EN ESPAÑOL
=========================================================== -->

<div class="card">

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

<div class="card">

    <h2>Description (English)</h2>

    <div class="field">

        <textarea
            name="descripcion_en"
            rows="8"><?= htmlspecialchars($maceta['descripcion_en'] ?? '') ?></textarea>

    </div>

</div>

<!-- ==========================================================
     FOTOGRAFÍAS
=========================================================== -->
<!--
<div class="card">

    <h2>Fotografías</h2>

    <div class="photo-placeholder">

        <svg width="48" height="48" viewBox="0 0 24 24"
             fill="none" stroke="#888" stroke-width="1.5">

            <rect x="3" y="5" width="18" height="14" rx="2"></rect>

            <circle cx="8.5" cy="10" r="1.5"></circle>

            <path d="M21 16l-5-5-4 4-2-2-4 3"></path>

        </svg>

        <h3>Gestión de fotografías</h3>

        <p>

            En la próxima versión aquí aparecerá la galería de la maceta,
            la carga múltiple de fotografías, selección de portada y
            eliminación de imágenes.

        </p>

    </div>

</div>-->
<?php include __DIR__ . '/includes/fotos_panel.php'; ?>

<div class="form-actions">

    <a
        href="dashboard.php"
        class="btn btn-secondary">

        Cancelar

    </a>

    <button
        type="submit"
        class="btn btn-primary">

        Guardar Maceta

    </button>

</div>

</form>

<?php require_once __DIR__.'/includes/admin_footer.php'; ?>




