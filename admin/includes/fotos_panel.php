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

<div class="form-card" id="panel-fotos">


    <div class="card-header">

    <h2>
        📷 Fotografías (<?= count($fotos) ?>)
    </h2>

    <div class="card-header-actions">

        <button
            type="button"
            class="btn btn-primary"
            id="btnAgregarFotos">

            + Agregar fotografías

        </button>

        <a
            href="dashboard.php"
            class="btn btn-secondary">

            Cancelar

        </a>

        <button
            type="submit"
            class="btn btn-primary">

            Guardar

        </button>

    </div>

</div>

    <div class="photo-toolbar">

        <input
            type="file"
            id="fotos-input"
            name="fotos[]"
            multiple
            accept="image/*"
            style="display:none;">

        <span>

            Puede seleccionar una o varias fotografías.
            Se cargarán al pulsar <strong>Guardar</strong>.

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

                        <?php if ((int)$foto['es_portada'] === 1): ?>

                            <span class="btn btn-success btn-sm btn-disabled">

                                ★ Portada

                            </span>

                        <?php else: ?>

                            <a
                                class="btn btn-light btn-sm"
                                href="foto_portada.php?foto=<?= (int)$foto['id'] ?>&maceta=<?= (int)$id ?>">

                                Hacer portada

                            </a>

                        <?php endif; ?>


                        <a
                            class="btn btn-danger btn-sm"
                            href="foto_delete.php?foto=<?= (int)$foto['id'] ?>&maceta=<?= (int)$id ?>"
                            onclick="return confirm('¿Está seguro de eliminar esta fotografía?');">

                            🗑 Eliminar

                        </a>

                    </div>
                </div>
            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        const boton = document.getElementById('btnAgregarFotos');
        const selector = document.getElementById('fotos-input');

        if (boton && selector) {

            boton.addEventListener('click', function() {

                selector.click();

            });

            selector.addEventListener('change', function() {

                if (this.files.length > 0) {

                    this.form.submit();

                }

            });

        }

    });
</script>