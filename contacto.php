<?php
require_once 'config.php';
include 'includes/header.php';
?>

<main class="site-content contacto-page">

    <section class="contacto-header">

        <p class="contacto-kicker">
            <?= t('contacto') ?>
        </p>

        <h1>
            <?= t('contacto') ?>
        </h1>

        <p class="contacto-intro">
            <?= t('contacto_intro') ?>
        </p>

    </section>

    <section class="contacto-grid">

        <!-- ==================================================
             WHATSAPP
        =================================================== -->

        <article class="contacto-card contacto-whatsapp">

            <div class="contacto-icono">
                WhatsApp
            </div>

            <div class="contacto-card-content">

                <p class="contacto-tipo">
                    <?= t('whatsapp') ?>
                </p>

                <h2>
                    <?= t('escribir_whatsapp') ?>
                </h2>

                <p>
                    <?= t('contacto_whatsapp_descripcion') ?? 'Contacto directo y rápido para consultas sobre las macetas.' ?>
                </p>

                <a
                    class="contacto-btn"
                    href="https://wa.me/<?= WHATSAPP_NUMERO ?>"
                    target="_blank"
                    rel="noopener"
                >
                    <?= t('whatsapp') ?>
                </a>

            </div>

        </article>

        <!-- ==================================================
             EMAIL
        =================================================== -->

        <article class="contacto-card contacto-email">

            <div class="contacto-icono">
                Email
            </div>

            <div class="contacto-card-content">

                <p class="contacto-tipo">
                    <?= t('email') ?>
                </p>

                <h2>
                    <?= SITE_EMAIL ?>
                </h2>

                <p>
                    <?= t('contacto_email_descripcion') ?? 'Para consultas detalladas, información o solicitudes.' ?>
                </p>

                <a
                    class="contacto-btn"
                    href="mailto:<?= SITE_EMAIL ?>"
                >
                    <?= t('email') ?>
                </a>

            </div>

        </article>

        <!-- ==================================================
             INSTAGRAM
        =================================================== -->

        <article class="contacto-card contacto-instagram">

            <div class="contacto-icono">
                Instagram
            </div>

            <div class="contacto-card-content">

                <p class="contacto-tipo">
                    <?= t('instagram') ?>
                </p>

                <h2>
                    @<?= INSTAGRAM_USER ?>
                </h2>

                <p>
                    <?= t('contacto_instagram_descripcion') ?? 'Puedes ver novedades, trabajos y nuevas piezas.' ?>
                </p>

                <a
                    class="contacto-btn"
                    href="<?= INSTAGRAM_URL ?>"
                    onclick="abrirInstagram(event)"
                    target="_blank"
                    rel="noopener"
                >
                    <?= t('instagram') ?>
                </a>

            </div>

        </article>

    </section>

    <!-- ======================================================
         DATOS GENERALES
    ======================================================= -->

    <section class="contacto-datos-card">

        <div class="contacto-datos-heading">

            <p class="contacto-tipo">
                Información
            </p>

            <!--<h2>
                <?= t('nombre') ?>
            </h2>-->

        </div>

        <div class="contacto-datos-list">

            <div class="contacto-dato">
                <span><?= t('nombre') ?></span>
                <h2><strong><?= SITE_OWNER ?></strong></h2>
            </div>

            <div class="contacto-dato">
                <span><?= t('ubicacion') ?></span>
                <h2><strong>Montevideo, Uruguay</strong></h2>
            </div>

        </div>

    </section>

</main>

<?php include 'includes/footer.php'; ?>
