<?php
/*
|--------------------------------------------------------------------------
| ADMIN FUNCTIONS
|--------------------------------------------------------------------------
| Funciones comunes del administrador
|--------------------------------------------------------------------------
*/

/**
 * Escapar texto para HTML.
 */

/*function e(?string $value): string  Duplicada en/includes/functions.php
{
    return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
*/
/**
 * Devuelve la clase CSS y el texto del estado.
 */
function badgeEstado(array $maceta): array
{
    if (($maceta['estado'] ?? 'disponible') === 'vendida') {
        return [
            'badge badge-danger',
            'Vendida'
        ];
    }

    return [
        'badge badge-success',
        'Disponible'
    ];
}

/**
 * Formatea importes.
 */
function money($value): string
{
    if ($value === null || $value === '') {
        return '-';
    }

    return number_format((float)$value, 2, ',', '.');
}

/**
 * Formatea medidas.
 */
function medida($value): string
{
    if ($value === null || $value === '') {
        return '-';
    }

    return number_format((float)$value, 1, ',', '.');
}

/**
 * Formatea pesos/capacidades.
 */
function decimal3($value): string
{
    if ($value === null || $value === '') {
        return '-';
    }

    return number_format((float)$value, 3, ',', '.');
}

/**
 * Icono editar
 */
function iconEdit(): string
{
    return <<<SVG
<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
<path d="M3 17.25V21h3.75L19.81 7.94l-3.75-3.75L3 17.25z"/>
<path d="M14.06 4.19l3.75 3.75"/>
</svg>
SVG;
}

/**
 * Icono fotografías
 */
function iconCamera(): string
{
    return <<<SVG
<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
<path d="M4 7h3l2-2h6l2 2h3v12H4z"/>
<circle cx="12" cy="13" r="4"/>
</svg>
SVG;
}

/**
 * Icono eliminar
 */
function iconTrash(): string
{
    return <<<SVG
<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
<polyline points="3 6 5 6 21 6"/>
<path d="M8 6V4h8v2"/>
<path d="M19 6l-1 14H6L5 6"/>
</svg>
SVG;
}

/**
 * Icono vender
 */
function iconDollar(): string
{
    return <<<SVG
<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
<line x1="12" y1="2" x2="12" y2="22"/>
<path d="M17 7c0-2-2-3-5-3s-5 1-5 3 2 3 5 3 5 1 5 3-2 3-5 3-5-1-5-3"/>
</svg>
SVG;
}

/**
 * Icono reactivar
 */
function iconUndo(): string
{
    return <<<SVG
<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
<polyline points="9 14 4 9 9 4"/>
<path d="M20 20a8 8 0 0 0-8-8H4"/>
</svg>
SVG;
}

/**
 * Nuevo: duplicar maceta.
 */
function iconDuplicate(): string
{
    return <<<SVG
<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
<rect x="9" y="9" width="11" height="11" rx="2"/>
<rect x="4" y="4" width="11" height="11" rx="2"/>
</svg>
SVG;
}

function subirFotosMaceta(PDO $pdo, int $macetaId, array $files): void
{
    if (
        empty($files['name']) ||
        empty($files['name'][0])
    ) {
        return;
    }

    // Obtener el código de la maceta
    $stmt = $pdo->prepare("
        SELECT codigo
        FROM macetas
        WHERE id = ?
    ");

    $stmt->execute([$macetaId]);

    $codigo = $stmt->fetchColumn();

    if (!$codigo) {
        return;
    }

    // Crear carpeta si no existe
    $dir = __DIR__ . "/../../uploads/$codigo";

    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }

    foreach ($files['tmp_name'] as $i => $tmp) {

        if ($files['error'][$i] !== UPLOAD_ERR_OK) {
            continue;
        }

        $ext = strtolower(
            pathinfo(
                $files['name'][$i],
                PATHINFO_EXTENSION
            )
        );

        $nombre = uniqid('foto_') . '.' . $ext;

        $destino = $dir . '/' . $nombre;

        if (!move_uploaded_file($tmp, $destino)) {
            continue;
        }

        $stmt = $pdo->prepare("
            INSERT INTO fotos
            (maceta_id, archivo, es_portada)
            VALUES (?, ?, 0)
        ");

        $stmt->execute([
            $macetaId,
            "uploads/$codigo/$nombre"
        ]);
    }

    // Si no existe portada, asignar la primera
    $stmt = $pdo->prepare("
        SELECT COUNT(*)
        FROM fotos
        WHERE maceta_id = ?
          AND es_portada = 1
    ");

    $stmt->execute([$macetaId]);

    if (!$stmt->fetchColumn()) {

        $stmt = $pdo->prepare("
            UPDATE fotos
            SET es_portada = 1
            WHERE id = (
                SELECT id
                FROM fotos
                WHERE maceta_id = ?
                ORDER BY id
                LIMIT 1
            )
        ");

        $stmt->execute([$macetaId]);
    }
}