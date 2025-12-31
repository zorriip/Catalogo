<?php
require '../config.php';

$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = ?");
$stmt->execute(['admin']);
$user = $stmt->fetch();

var_dump($user);

echo '<br><br>';

if ($user) {
  var_dump(password_verify('Catalogo212524', $user['password']));
}
