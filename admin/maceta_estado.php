<?php
require 'auth.php';
require '../config.php';


$id = $_GET['id'] ?? null;
$v = $_GET['v'] ?? null;


if ($id === null || $v === null) {
header('Location: dashboard.php'); exit;
}


$stmt = $pdo->prepare("UPDATE macetas SET vendida = ? WHERE id = ?");
$stmt->execute([(int)$v, $id]);


header('Location: dashboard.php');