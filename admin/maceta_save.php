<?php

declare(strict_types=1);

require_once __DIR__ . '/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: dashboard.php');
    exit;
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

$data = [
    'codigo' => trim($_POST['codigo'] ?? ''),
    'nombre' => trim($_POST['nombre'] ?? ''),
    'descripcion' => trim($_POST['descripcion'] ?? ''),
    'descripcion_en' => trim($_POST['descripcion_en'] ?? ''),
    'forma' => trim($_POST['forma'] ?? ''),
    'largo' => $_POST['largo'] !== '' ? (float)$_POST['largo'] : null,
    'ancho' => $_POST['ancho'] !== '' ? (float)$_POST['ancho'] : null,
    'alto' => $_POST['alto'] !== '' ? (float)$_POST['alto'] : null,
    'material' => trim($_POST['material'] ?? ''),
    'esmalte' => trim($_POST['esmalte'] ?? ''),
    'precio' => $_POST['precio'] !== '' ? (float)$_POST['precio'] : null,
    'peso' => $_POST['peso'] !== '' ? (float)$_POST['peso'] : null,
    'capacidad' => $_POST['capacidad'] !== '' ? (float)$_POST['capacidad'] : null,
    'estado' => ($_POST['estado'] ?? 'disponible') === 'vendida' ? 'vendida' : 'disponible'
];

if ($data['codigo'] === '' || $data['nombre'] === '') {
    die('Debe indicar código y nombre.');
}

try {
    $pdo->beginTransaction();

    if ($id > 0) {
        $sql = "UPDATE macetas SET
            codigo=:codigo,
            nombre=:nombre,
            descripcion=:descripcion,
            descripcion_en=:descripcion_en,
            forma=:forma,
            largo=:largo,
            ancho=:ancho,
            alto=:alto,
            material=:material,
            esmalte=:esmalte,
            precio=:precio,
            peso=:peso,
            capacidad=:capacidad,
            estado=:estado
            WHERE id=:id";
        $st = $pdo->prepare($sql);
        $data['id'] = $id;
        $st->execute($data);
    } else {
        $sql = "INSERT INTO macetas
        (codigo,nombre,descripcion,descripcion_en,forma,largo,ancho,alto,material,esmalte,precio,peso,capacidad,estado)
        VALUES
        (:codigo,:nombre,:descripcion,:descripcion_en,:forma,:largo,:ancho,:alto,:material,:esmalte,:precio,:peso,:capacidad,:estado)";
        $st = $pdo->prepare($sql);
        $st->execute($data);
        $id = (int)$pdo->lastInsertId();
    }

    $pdo->commit();

     header("Location: maceta_form.php?id=".$id."&ok=1");
    exit;
    
} catch (Throwable $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    die("Error al guardar: " . $e->getMessage());
}
