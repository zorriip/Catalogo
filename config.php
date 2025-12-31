<?php
session_start();

require_once __DIR__ . '/includes/functions.php';

/* =========================
   Idioma
========================= */

if (isset($_GET['lang']) && in_array($_GET['lang'], ['es', 'en'])) {
  $_SESSION['lang'] = $_GET['lang'];
}

if (empty($_SESSION['lang'])) {
  $_SESSION['lang'] = 'es';
}

// ===============================
// BASE DE DATOS
// ===============================
$host = 'localhost';
$db   = 'catalogo';
$user = 'root';
$pass = '';

$pdo = new PDO(
  "mysql:host=$host;dbname=$db;charset=utf8",
  $user,
  $pass,
  [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

// ===============================
// CONFIGURACIÓN GENERAL
// ===============================
date_default_timezone_set('America/Montevideo');

// ===============================
// DATOS DEL SITIO / CONTACTO
// ===============================
define('SITE_OWNER', 'Pablo Zorrilla de San Martin');
define('SITE_EMAIL', 'zorrip@gmail.com');
define('SITE_NAME', 'Zorrilla Cerámica Bonsai');


// Instagram
define('INSTAGRAM_USER', 'zorrilla_ceramica_bonsai');
define('INSTAGRAM_URL', 'https://www.instagram.com/' . INSTAGRAM_USER);

// WhatsApp
define('WHATSAPP_NUMERO', '59894372669'); // sin + ni espacios
?>