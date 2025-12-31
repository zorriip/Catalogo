<?php
require 'auth.php';
require '../config.php';

$id = $_GET['id'] ?? null;

if ($id) {
  $stmt = $pdo->prepare("DELETE FROM macetas WHERE id = ?");
  $stmt->execute([$id]);
}

header('Location: dashboard.php');
exit;
