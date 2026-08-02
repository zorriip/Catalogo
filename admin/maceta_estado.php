<?php

require 'auth.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: dashboard.php');
    exit;
}


/*
|--------------------------------------------------------------------------
| Obtener estado actual
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
    SELECT estado
    FROM macetas
    WHERE id = ?
");

$stmt->execute([$id]);

$estado = $stmt->fetchColumn();

if ($estado === false) {
    header('Location: dashboard.php');
    exit;
}

$nuevoEstado =
    ($estado === 'vendida')
    ? 'disponible'
    : 'vendida';

/*
|--------------------------------------------------------------------------
| Actualizar
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
    UPDATE macetas
    SET estado = ?
    WHERE id = ?
");

$stmt->execute([
    $nuevoEstado,
    $id
]);

// Conservar búsqueda, página y demás parámetros
$query = $_GET;
unset($query['id']);

header('Location: dashboard.php?' . http_build_query($query));
exit;