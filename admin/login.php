<?php
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $usuario = $_POST['usuario'] ?? '';
    $clave   = $_POST['password'] ?? '';

    $stmt = $pdo->prepare(
        "SELECT * FROM usuarios WHERE usuario = ?"
    );
    $stmt->execute([$usuario]);
    $user = $stmt->fetch();

    if ($user && password_verify($clave, $user['password'])) {
        $_SESSION['admin'] = true;
        $_SESSION['usuario'] = $user['usuario'];

        header('Location: dashboard.php');
        exit;
    }

    $error = "Usuario o contraseña incorrectos";
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Login</title>
</head>
<body>

<form method="post">
  <input name="usuario" placeholder="Usuario" required>
  <input name="password" type="password" placeholder="Contraseña" required>
  <button type="submit">Entrar</button>
</form>

<?php if (!empty($error)): ?>
  <p style="color:red"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

</body>
</html>
