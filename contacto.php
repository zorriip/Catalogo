<?php
require_once 'config.php';
include 'includes/header.php';
?>

<section class="contacto">

  <h1><?= t('contacto') ?></h1>

  <p><?= t('contacto_intro') ?></p>

  <ul class="contacto-datos">
    <li>
      <strong><?= t('nombre') ?>:</strong>
      <?= SITE_OWNER ?>
    </li>

    <li>
      <strong><?= t('ubicacion') ?>:</strong>
      Montevideo, Uruguay
    </li>

    <li>
      <strong><?= t('whatsapp') ?>:</strong>
      <a href="https://wa.me/<?= WHATSAPP_NUMERO ?>" target="_blank">+
        <?= t('escribir_whatsapp') ?>
      </a>
    </li>

    <li>
      <strong><?= t('email') ?>:</strong>
      <a href="mailto:<?= SITE_EMAIL ?>">
        <?= SITE_EMAIL ?>
      </a>
    </li>


    <li>
      <strong><?= t('instagram') ?>:</strong>
      <a href="<?= INSTAGRAM_URL ?>"
        onclick="abrirInstagram(event)"
        target="_blank" rel="noopener">
        @<?= INSTAGRAM_USER ?>
      </a>
    </li>





  </ul>

</section>

<?php include 'includes/footer.php'; ?>