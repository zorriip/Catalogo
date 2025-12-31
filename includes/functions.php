<?php
/**
 * Helpers globales del sistema
 * Se cargan desde config.php
 */

/* =========================
   Seguridad básica
========================= */

/**
 * Escapar HTML de forma segura
 */
function e($string): string {
    return htmlspecialchars((string)$string, ENT_QUOTES, 'UTF-8');
}


/* =========================
   Idiomas
========================= */

/**
 * Traducción simple por clave
 
 */

function t(string $key): string
{
  static $tr = null;

  if ($tr === null) {
    $lang = $_SESSION['lang'] ?? 'es';
    $file = __DIR__ . "/../lang/$lang.php";

    $tr = file_exists($file) ? require $file : [];
  }

  return $tr[$key] ?? $key;
}


/**
 * Traducciones de valores (forma, material, esmalte, etc)
 */
function t_value(string $grupo, string $valor): string
{
  if ($valor === '') {
    return '';
  }

  return t($grupo . '_' . $valor);
}


/* =========================
   Sesión / permisos
========================= */

/**
 * Verifica si el usuario es admin
 */
function is_admin(): bool {
    return !empty($_SESSION['admin']);
}


/**
 * Redirige si no es admin
 */
function require_admin(): void {
    if (!is_admin()) {
        header('Location: index.php');
        exit;
    }
}


/* =========================
   Utilidades
========================= */

/**
 * Construye URL preservando idioma
 */
function url(string $path, array $params = []): string {
    if (!isset($params['lang']) && isset($_SESSION['lang'])) {
        $params['lang'] = $_SESSION['lang'];
    }

    return $path . '?' . http_build_query($params);
}


/**
 * Formatea precio
 */
function precio($valor): string {
    return number_format((float)$valor, 0, ',', '.');
}
