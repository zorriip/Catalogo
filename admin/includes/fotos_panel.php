<?php
/*
|--------------------------------------------------------------------------
| PANEL DE FOTOGRAFÍAS
|--------------------------------------------------------------------------
| Variables disponibles:
|   $id
|   $maceta
|   $fotos
|--------------------------------------------------------------------------
*/
?>

<div class="card">

    <h2>Fotografías</h2>

    <div class="photo-toolbar">

        <button
            type="button"
            class="btn btn-primary"
            disabled>

            + Agregar fotografías

        </button>

        <span>

            La carga múltiple se habilitará en la siguiente etapa.

        </span>

    </div>


    <?php if (empty($fotos)): ?>

        <div class="photo-empty">

            <svg viewBox="0 0 24 24"
                width="70"
                height="70"
                fill="none"
                stroke="currentColor"
                stroke-width="1.4">

                <rect x="3" y="5"
                    width="18"
                    height="14"
                    rx="2" />

                <circle cx="8.5"
                    cy="10"
                    r="1.5" />

                <path d="M21 16l-5-5-4 4-2-2-4 3" />

            </svg>

            <h3>No hay fotografías</h3>

            <p>

                Esta maceta todavía no tiene fotografías asociadas.

            </p>

        </div>

    <?php else: ?>

        <div class="photo-gallery">

            <?php foreach ($fotos as $foto): ?>

                <div class="photo-card">

                    <div class="photo-thumb">

                        <img
                            src="../<?= htmlspecialchars($foto['archivo']) ?>"
                            alt="">

                    </div>

                    <div class="photo-actions">

                        ...

                    </div>

                </div>
            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</div>