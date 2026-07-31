<?php
require_once 'auth.php';
require_once '../config.php';

/* =========================
   Cabeceras para Excel
========================= */
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=macetas.csv');

/* =========================
   Salida
========================= */
$output = fopen('php://output', 'w');

/* =========================
   Encabezados de columnas
========================= */
fputcsv($output, array_map('utf8_decode', [
  'ID',
  'Código',
  'Nombre',
  'Forma',
  'Material',
  'Esmalte',
  'Precio',
  'Vendida',
  'Largo',
  'Ancho',
  'Alto',
  'Peso',
  'Capacidad'
]));

/* =========================
   Consulta
========================= */
$stmt = $pdo->query("SELECT * FROM macetas ORDER BY id DESC");

while ($m = $stmt->fetch()) {

  fputcsv($output, [
    $m['id'],
    $m['codigo'],
    $m['nombre'],
    $m['forma'],
    $m['material'],
    $m['esmalte'],
    $m['precio'],
    $m['vendida'] ? 'Sí' : 'No',
    $m['largo'],
    $m['ancho'],
    $m['alto'],
    $m['peso'],
    $m['capacidad']
  ]);
}

fclose($output);
exit;