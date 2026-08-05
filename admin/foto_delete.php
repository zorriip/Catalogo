<?php

declare(strict_types=1);

require_once __DIR__ . '/auth.php';

$idFoto = isset($_GET['foto']) ? (int)$_GET['foto'] : 0;
$idMaceta = isset($_GET['maceta']) ? (int)$_GET['maceta'] : 0;

if ($idFoto <= 0 || $idMaceta <= 0) {
    header('Location: dashboard.php');
    exit;
}

try {

    $pdo->beginTransaction();

    // Obtener datos de la fotografía

    $stmt = $pdo->prepare("
        SELECT archivo, es_portada
        FROM fotos
        WHERE id = ?
          AND maceta_id = ?
    ");

    $stmt->execute([$idFoto, $idMaceta]);

    $foto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$foto) {
        throw new Exception('Fotografía inexistente.');
    }

    // Borrar archivo físico

    $ruta = dirname(__DIR__) . '/' . $foto['archivo'];

    if (is_file($ruta)) {
        unlink($ruta);
    }

    // Eliminar registro

    $stmt = $pdo->prepare("
        DELETE FROM fotos
        WHERE id = ?
    ");

    $stmt->execute([$idFoto]);

    // Si era portada, asignar otra

    if ((int)$foto['es_portada'] === 1) {

        $stmt = $pdo->prepare("
            SELECT id
            FROM fotos
            WHERE maceta_id = ?
            ORDER BY id
            LIMIT 1
        ");

        $stmt->execute([$idMaceta]);

        $nueva = $stmt->fetchColumn();

        if ($nueva) {

            $stmt = $pdo->prepare("
                UPDATE fotos
                SET es_portada = 1
                WHERE id = ?
            ");

            $stmt->execute([$nueva]);

        }
    }

    $pdo->commit();

} catch (Throwable $e) {

    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

}

header("Location: maceta_form.php?id=".$idMaceta."#panel-fotos");

exit;