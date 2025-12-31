<?php

require_once 'auth.php';
//require '../config.php';


if (empty($_SESSION['admin'])) {
  header('Location: ../index.php');
  exit;
}

$where = [];
$params = [];

if (!empty($_GET['q'])) {
    $where[] = "(codigo LIKE ? OR nombre LIKE ?)";
    $params[] = '%' . $_GET['q'] . '%';
    $params[] = '%' . $_GET['q'] . '%';
}

$sql = "SELECT * FROM macetas";

if ($where) {
    $sql .= " WHERE " . implode(" AND ", $where);
}

$sql .= " ORDER BY id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$macetas = $stmt->fetchAll();



?>
<HTML>
<TITLE> Mantenimiento</TITLE>
<body>
    
<h1>Mantenimiento</h1>

<form method="get" class="admin-search">
    <input
        type="text"
        name="q"
        value="<?= htmlspecialchars($_GET['q'] ?? '') ?>"
        placeholder="Buscar por código o nombre">

    <button>Buscar</button>
</form>

<p><?= count($macetas) ?> macetas encontradas</p>

<a href="maceta_form.php">+ Nueva maceta</a>

<a href="logout.php" class="logout">Cerrar sesión</a>
<ul>
    <?php foreach ($macetas as $m): ?>

        <li>
            <?= $m['codigo'] ?> – <?= $m['nombre'] ?>
            <a href="maceta_fotos.php?id=<?= $m['id'] ?>">Fotos</a>
            <a href="maceta_form.php?id=<?= $m['id'] ?>">Editar</a>
            <a href="maceta_delete.php?id=<?= $m['id'] ?>">Borrar</a>
        </li>
        <?php if ($m['vendida']): ?>
            <strong style="color:red">VENDIDA</strong> |
            <a href="maceta_estado.php?id=<?= $m['id'] ?>&v=0">Reactivar</a>
        <?php else: ?>
            <a href="maceta_estado.php?id=<?= $m['id'] ?>&v=1">Marcar vendida</a>
        <?php endif; ?>
    <?php endforeach; ?>
</ul>

</body>
</HTML>