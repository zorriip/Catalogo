<?php

declare(strict_types=1);

require_once __DIR__ . '/auth.php';

$idFoto = isset($_GET['foto']) ? (int)$_GET['foto'] : 0;

$idMaceta = isset($_GET['maceta']) ? (int)$_GET['maceta'] : 0;

if ($idFoto <= 0 || $idMaceta <= 0) {

    header("Location: dashboard.php");

    exit;

}


    $pdo->beginTransaction();

    // Quitar portada actual

    $stmt = $pdo->prepare("
        UPDATE fotos
        SET es_portada = 0
        WHERE maceta_id = ?
    ");

    $stmt->execute([$idMaceta]);

    // Asignar nueva portada

 /*   $stmt = $pdo->prepare("
        UPDATE fotos
        SET es_portada = 1
        WHERE id = ?
          AND maceta_id = ?
    ");
*/

$stmt = $pdo->prepare("
    UPDATE fotos
    SET es_portada = 1
    WHERE id = ?
      AND maceta_id = ?
");

$stmt->execute([$idFoto, $idMaceta]);

echo "<pre>";
echo "Foto: $idFoto\n";
echo "Maceta: $idMaceta\n";
echo "Filas modificadas: ".$stmt->rowCount()."\n\n";

$stmt = $pdo->prepare("
    SELECT id, maceta_id, es_portada
    FROM fotos
    WHERE maceta_id = ?
    ORDER BY id
");

$stmt->execute([$idMaceta]);

 
    $pdo->commit();



header("Location: maceta_form.php?id=".$idMaceta."#panel-fotos");
exit;