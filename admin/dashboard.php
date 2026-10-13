<?php

declare(strict_types=1);

require_once __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| Dashboard Administración
|--------------------------------------------------------------------------
| Catálogo de Macetas Bonsái
|--------------------------------------------------------------------------
*/

const ITEMS_PER_PAGE = 25;

/*
|--------------------------------------------------------------------------
| FUNCIONES
|--------------------------------------------------------------------------


function e(?string $value): string
{
    return htmlspecialchars(
        $value ?? '',
        ENT_QUOTES,
        'UTF-8'
    );
}
*/
function formatPrecio(?float $precio): string
{
    if (!$precio) {
        return '—';
    }

    return 'US$ ' .
        number_format(
            $precio,
            2,
            ',',
            '.'
        );
}

function formatMedidas(array $m): string
{
    $medidas = [];

    foreach (['largo', 'ancho', 'alto'] as $campo) {

        if (
            isset($m[$campo]) &&
            $m[$campo] !== null &&
            $m[$campo] > 0
        ) {

            $medidas[] = number_format(
                (float)$m[$campo],
                1,
                ',',
                ''
            );
        }
    }

    return empty($medidas)
        ? '—'
        : implode(' × ', $medidas) . ' cm';
}

function badgeEstado(array $m): array
{
    if (($m['estado'] ?? 'disponible') === 'vendida') {
        return [
            'badge-danger',
            'Vendida'
        ];
    }

    return [
        'badge-success',
        'Disponible'
    ];
}

function iconCamera(): string
{
    return <<<'SVG'
<svg viewBox="0 0 24 24">
<path d="M9 4l1.5 2H14l1.5-2H20a2 2 0 012 2v12a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2h5zm3 5a5 5 0 100 10 5 5 0 000-10z"/>
</svg>
SVG;
}

function iconEdit(): string
{
    return <<<'SVG'
<svg viewBox="0 0 24 24">
<path d="M3 17.2V21h3.8L18.6 9.2l-3.8-3.8L3 17.2zm17.7-10.5a1 1 0 000-1.4L18.7 3.3a1 1 0 00-1.4 0l-1.6 1.6 3.8 3.8 1.2-1.2z"/>
</svg>
SVG;
}

function iconTrash(): string
{
    return <<<'SVG'
<svg viewBox="0 0 24 24">
<path d="M6 7h12l-1 14H7L6 7zm3-4h6l1 2h4v2H4V5h4l1-2z"/>
</svg>
SVG;
}

function iconDollar(): string
{
    return <<<'SVG'
<svg viewBox="0 0 24 24">
<path d="M11 2h2v2c2.8.3 5 2 5 4h-2c0-1.1-1.3-2-3-2s-3 .9-3 2c0 1 .8 1.6 3.4 2.2C17.3 11.1 19 12.4 19 15c0 2.3-2 4-5 4v3h-2v-3c-3-.3-5-2-5-5h2c0 1.7 1.5 3 3.5 3S16 16.2 16 15c0-1.2-.8-1.9-3.7-2.6C8.6 11.5 7 10.3 7 8c0-2.2 1.9-3.8 4-4V2z"/>
</svg>
SVG;
}

function iconUndo(): string
{
    return <<<'SVG'
<svg viewBox="0 0 24 24">
<path d="M12 5V1L5 8l7 7V9c3.3 0 6 2.7 6 6 0 .7-.1 1.3-.3 2h2.1c.1-.6.2-1.3.2-2 0-4.4-3.6-8-8-8z"/>
</svg>
SVG;
}



/*
|--------------------------------------------------------------------------
| FILTROS
|--------------------------------------------------------------------------
*/

$page = max(
    1,
    (int)($_GET['page'] ?? 1)
);

$buscar = trim(
    $_GET['buscar'] ?? ''
);

$where = [];
$params = [];

if ($buscar !== '') {

    $where[] =
        '(m.codigo LIKE :buscar
      OR
      m.nombre LIKE :buscar)';

    $params[':buscar'] =
        "%{$buscar}%";
}

$whereSql = '';

if ($where) {

    $whereSql =
        'WHERE ' . implode(
            ' AND ',
            $where
        );
}

/*
|--------------------------------------------------------------------------
| ESTADÍSTICAS
|--------------------------------------------------------------------------
|
| Una sola consulta.
|
*/

$sqlStats = "

SELECT

COUNT(*) AS total,

SUM(estado='disponible') AS disponibles,

SUM(estado='vendida') AS vendidas,

SUM(
    NOT EXISTS(
        SELECT 1
        FROM fotos f
        WHERE f.maceta_id = m.id
          AND f.es_portada = 1
    )
) AS sin_foto

FROM macetas m";

$stats = $pdo
    ->query($sqlStats)
    ->fetch(PDO::FETCH_ASSOC);

/*
|--------------------------------------------------------------------------
| TOTAL REGISTROS
|--------------------------------------------------------------------------
*/

$sqlTotal = "

SELECT COUNT(*)

FROM macetas m

$whereSql

";

$stmt = $pdo->prepare($sqlTotal);

$stmt->execute($params);

$totalRegistros =
    (int)$stmt->fetchColumn();

$totalPaginas =
    max(
        1,
        (int)ceil(
            $totalRegistros /
                ITEMS_PER_PAGE
        )
    );

$offset =
    ($page - 1) *
    ITEMS_PER_PAGE;

/*
|--------------------------------------------------------------------------
| CONSULTA PRINCIPAL
|--------------------------------------------------------------------------
*/

$sql = "

SELECT

    m.id,
    m.codigo,
    m.nombre,

    m.forma,

    m.largo,
    m.ancho,
    m.alto,

    m.material,
    m.esmalte,

    m.precio,

    m.estado,

    f.archivo portada

FROM macetas m

LEFT JOIN fotos f

       ON f.maceta_id = m.id
      AND f.es_portada = 1

$whereSql

ORDER BY

    m.codigo 

LIMIT :limit

OFFSET :offset

";

$stmt = $pdo->prepare($sql);

foreach ($params as $nombre => $valor) {

    $stmt->bindValue(
        $nombre,
        $valor,
        PDO::PARAM_STR
    );
}

$stmt->bindValue(
    ':limit',
    ITEMS_PER_PAGE,
    PDO::PARAM_INT
);

$stmt->bindValue(
    ':offset',
    $offset,
    PDO::PARAM_INT
);

$stmt->execute();

$macetas = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1">

    <title>

        Panel de Administración

    </title>

    <link
        rel="stylesheet"
        href="dashboard.css">

</head>

<body>

    <div class="dashboard">

        <header class="topbar">

            <div class="brand">

                <h1>

                    Catálogo de Macetas

                </h1>

                <p>

                    Panel de administración

                </p>

            </div>

            <nav class="toolbar">

                <a
                    href="maceta_form.php"
                    class="btn btn-primary">

                    Nueva maceta

                </a>

                <a
                    href="export_excel.php"
                    class="btn">

                    Exportar Excel

                </a>

                <a
                    href="logout.php"
                    class="btn btn-danger">

                    Cerrar sesión

                </a>

            </nav>

        </header>

        <section class="stats-grid">

            <article class="stat-card">

                <span>Total</span>

                <strong>

                    <?= (int)$stats['total'] ?>

                </strong>

            </article>

            <article class="stat-card">

                <span>Disponibles</span>

                <strong>

                    <?= (int)$stats['disponibles'] ?>

                </strong>

            </article>

            <article class="stat-card">

                <span>Vendidas</span>

                <strong>

                    <?= (int)$stats['vendidas'] ?>

                </strong>

            </article>

            <article class="stat-card">

                <span>Sin foto</span>

                <strong>

                    <?= (int)$stats['sin_foto'] ?>

                </strong>

            </article>

        </section>


        <section class="search-panel">

            <form
                method="get"
                class="search-form">

                <input

                    type="search"

                    name="buscar"

                    value="<?= e($buscar) ?>"

                    placeholder="Código o nombre">

                <button
                    class="btn"
                    type="submit">

                    Buscar

                </button>

                <?php if ($buscar != ''): ?>

                    <a
                        href="dashboard.php"
                        class="btn">

                        Limpiar

                    </a>

                <?php endif; ?>

            </form>

            <div class="results-info">

                Mostrando

                <strong>

                    <?= count($macetas) ?>

                </strong>

                de

                <strong>

                    <?= $totalRegistros ?>

                </strong>

                macetas

            </div>

        </section>


        <section class="table-wrapper">

            <table class="macetas">

                <thead>

                    <tr>

                        <th class="col-foto">

                            Foto

                        </th>

                        <th>

                            Código

                        </th>

                        <th>

                            Nombre

                        </th>

                        <th>

                            Medidas

                        </th>

                        <th>

                            Material

                        </th>

                        <th>

                            Precio

                        </th>

                        <th>

                            Estado

                        </th>

                        <th class="acciones">

                            Acciones

                        </th>

                    </tr>

                </thead>

                <tbody>

                    <?php if (empty($macetas)): ?>

                        <tr>

                            <td colspan="8" class="empty-table">

                                No se encontraron macetas.

                            </td>

                        </tr>

                    <?php else: ?>

                        <?php foreach ($macetas as $maceta): ?>

                            <?php

                            [$badgeClass, $badgeText] = badgeEstado($maceta);

                            ?>

                            <tr>

                                <!-- ==========================================
         FOTO
    =========================================== -->

                                <td class="foto">

                                    <a
                                        <a
                                        href="maceta_form.php?id=<?= (int)$maceta['id'] ?>"
                                        title="Editar maceta">

                                        <?php if (!empty($maceta['portada'])): ?>

                                            <img
                                                src="../<?= e($maceta['portada']) ?>"
                                                alt="<?= e($maceta['codigo']) ?>"
                                                loading="lazy">

                                        <?php else: ?>

                                            <div class="no-photo">

                                                Sin foto

                                            </div>

                                        <?php endif; ?>

                                    </a>

                                </td>


                                <!-- ==========================================
         CÓDIGO
    =========================================== -->

                                <td>

                                    <span class="codigo">

                                        <?= e($maceta['codigo']) ?>

                                    </span>

                                </td>


                                <!-- ==========================================
         NOMBRE
    =========================================== -->

                                <td class="nombre">

                                    <?= e($maceta['nombre']) ?>

                                </td>


                                <!-- ==========================================
         MEDIDAS
    =========================================== -->

                                <td class="medidas">

                                    <?= formatMedidas($maceta) ?>

                                </td>


                                <!-- ==========================================
         MATERIAL
    =========================================== -->

                                <td>

                                    <?= e($maceta['material']) ?>

                                </td>


                                <!-- ==========================================
         PRECIO
    =========================================== -->

                                <td class="precio">

                                    <?= formatPrecio(
                                        $maceta['precio'] !== null
                                            ? (float)$maceta['precio']
                                            : null
                                    ) ?>

                                </td>


                                <!-- ==========================================
         ESTADO
    =========================================== -->

                                <td>

                                    <span class="badge <?= $badgeClass ?>">

                                        <?= $badgeText ?>

                                    </span>

                                </td>


 <!-- ==========================================
         ACCIONES
    =========================================== -->

                                <td class="acciones">

                                    <a
                                        class="icon-btn editar"
                                        href="maceta_form.php?id=<?= (int)$maceta['id'] ?>"
                                        title="Editar">

                                        <?= iconEdit() ?>

                                    </a>

                                    <a
                                        class="icon-btn estado"
                                        href="maceta_estado.php?id=<?= (int)$maceta['id'] ?>"
                                        title="<?= $maceta['estado'] === 'vendida'
                                                    ? 'Reactivar'
                                                    : 'Marcar como vendida' ?>">

                                        <?= $maceta['estado'] === 'vendida'
                                            ? iconUndo()
                                            : iconDollar() ?>

                                    </a>

                                    <a
                                        class="icon-btn eliminar"
                                        href="maceta_delete.php?id=<?= (int)$maceta['id'] ?>"
                                        onclick="return confirm('¿Eliminar esta maceta?');"
                                        title="Eliminar">

                                        <?= iconTrash() ?>

                                    </a>

                                </td>
                            </tr>

                        <?php endforeach; ?>

                    <?php endif; ?>

                </tbody>

            </table>

        </section>


        <!-- ==========================================================
         PAGINACIÓN
    =========================================================== -->

        <?php if ($totalPaginas > 1): ?>

            <nav class="pagination">

                <?php

                $query = $_GET;

                ?>

                <!-- Anterior -->

                <?php if ($page > 1): ?>

                    <?php
                    $query['page'] = $page - 1;
                    ?>

                    <a
                        class="page-link"
                        href="?<?= http_build_query($query) ?>">

                        &laquo; Anterior

                    </a>

                <?php endif; ?>


                <!-- Números -->

                <?php

                $inicio = max(1, $page - 2);
                $fin    = min($totalPaginas, $page + 2);

                ?>

                <?php for ($i = $inicio; $i <= $fin; $i++): ?>

                    <?php
                    $query['page'] = $i;
                    ?>

                    <a
                        href="?<?= http_build_query($query) ?>"
                        class="page-link <?= $i == $page ? 'active' : '' ?>">

                        <?= $i ?>

                    </a>

                <?php endfor; ?>


                <!-- Siguiente -->

                <?php if ($page < $totalPaginas): ?>

                    <?php
                    $query['page'] = $page + 1;
                    ?>

                    <a
                        class="page-link"
                        href="?<?= http_build_query($query) ?>">

                        Siguiente &raquo;

                    </a>

                <?php endif; ?>

            </nav>

        <?php endif; ?>


        <!-- ==========================================================
         PIE
    =========================================================== -->

        <footer class="dashboard-footer">

            <div>

                <strong>

                    <?= $totalRegistros ?>

                </strong>

                macetas registradas

            </div>

            <div>

                Página

                <strong>

                    <?= $page ?>

                </strong>

                de

                <strong>

                    <?= $totalPaginas ?>

                </strong>

            </div>

        </footer>

    </div>

    <script src="dashboard.js"></script>

</body>

</html>