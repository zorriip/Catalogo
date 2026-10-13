<?php
//session_start();

require_once 'config.php';

$lang = $_SESSION['lang'] ?? 'es';

/*
|--------------------------------------------------------------------------
| TEXTOS
|--------------------------------------------------------------------------
| Dejamos preparada la estructura para español e inglés.
| Más adelante estos textos pueden pasar al sistema t().
|--------------------------------------------------------------------------
*/

$textos = [

    'es' => [
        'kicker'       => 'CERÁMICA ARTESANAL · URUGUAY',
        'titulo'       => 'Cerámica artesanal en un país de energía renovable',
        'intro'        => 'Nuestro taller se encuentra en Uruguay, un país donde la generación eléctrica tiene una fuerte presencia de fuentes renovables.',
        
        'energia_titulo' => 'La energía también forma parte de la pieza',
        'energia_texto'  => 'La cocción es una de las etapas fundamentales de cualquier proceso cerámico. En nuestro taller, el fuego transforma la arcilla y convierte el trabajo artesanal en una pieza terminada.',
        
        'proceso_titulo' => 'Del barro a la maceta',
        'proceso_texto'  => 'Cada maceta es construida individualmente a partir de placas de arcilla. El proceso incluye el diseño, corte, armado, terminación, secado y finalmente la cocción en el horno.',
        
        'uruguay_titulo' => 'Uruguay: un entorno favorable',
        'uruguay_texto'  => 'Uruguay ha desarrollado una matriz eléctrica con una importante participación de fuentes renovables. Para una producción artesanal como la nuestra, esta característica forma parte del entorno energético en el que trabajamos.',
        
        'artesanal_titulo' => 'Hecho a mano',
        'artesanal_texto'  => 'No producimos macetas en serie. Cada pieza pasa por nuestras manos y conserva las pequeñas diferencias que caracterizan a un objeto verdaderamente artesanal.',
        
        'final_titulo' => 'Una maceta lleva más que arcilla',
        'final_texto'  => 'Lleva diseño, trabajo manual, materia, tiempo y fuego. Y todo ese proceso sucede en Uruguay.'
    ],

    'en' => [
        'kicker'       => 'HANDCRAFTED CERAMICS · URUGUAY',
        'titulo'       => 'Handcrafted ceramics in a country powered by renewable energy',
        'intro'        => 'Our workshop is located in Uruguay, a country where electricity generation has a strong presence of renewable energy sources.',
        
        'energia_titulo' => 'Energy is also part of the piece',
        'energia_texto'  => 'Firing is one of the fundamental stages of any ceramic process. In our workshop, fire transforms clay and turns craftsmanship into a finished piece.',
        
        'proceso_titulo' => 'From clay to bonsai pot',
        'proceso_texto'  => 'Each pot is individually built from clay slabs. The process includes design, cutting, assembly, finishing, drying and finally firing in the kiln.',
        
        'uruguay_titulo' => 'Uruguay: a favorable environment',
        'uruguay_texto'  => 'Uruguay has developed an electricity mix with a significant share of renewable sources. For an artisan production such as ours, this is part of the energy environment in which we work.',
        
        'artesanal_titulo' => 'Made by hand',
        'artesanal_texto'  => 'We do not produce pots in series. Each piece passes through our hands and retains the small differences that characterize a truly handcrafted object.',
        
        'final_titulo' => 'A bonsai pot carries more than clay',
        'final_texto'  => 'It carries design, craftsmanship, material, time and fire. And the entire process takes place in Uruguay.'
    ]
];

$t = $textos[$lang] ?? $textos['es'];

?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title><?= htmlspecialchars($t['titulo']) ?></title>

    <link
        rel="stylesheet"
        href="css/style.css">

    <style>
        .energia-page img {
            width: 70%;
            max-width: 100%;
            height: auto;
            display: block;
            margin-inline: auto;
        }
    </style>

</head>

<body>

<?php include 'includes/header.php'; ?>


<main class="energia-page">


    <!-- =====================================================
         HERO
    ====================================================== -->

    <section class="energia-hero">

        <div class="energia-hero-text">

            <p class="energia-kicker">
                <?= htmlspecialchars($t['kicker']) ?>
            </p>

            <h1>
                <?= htmlspecialchars($t['titulo']) ?>
            </h1>

            <p class="energia-intro">
                <?= htmlspecialchars($t['intro']) ?>
            </p>

        </div>

        <div class="energia-hero-image">

            <img
                src="uploads/energia/energia_02.jpg"
                alt="Ceramic artist working with a bonsai pot">

        </div>

    </section>


    <!-- =====================================================
         ENERGÍA
    ====================================================== -->

    <section class="energia-section energia-section-destacada">

        <div class="energia-section-image">

            <img
                src="uploads/energia/energia_01.jpg"
                alt="Ceramic artist working on a bonsai pot">

        </div>

        <div class="energia-section-text">

            <p class="energia-number">
                01
            </p>

            <h2>
                <?= htmlspecialchars($t['energia_titulo']) ?>
            </h2>

            <p>
                <?= htmlspecialchars($t['energia_texto']) ?>
            </p>

        </div>

    </section>


    <!-- =====================================================
         PROCESO
    ====================================================== -->

    <section class="energia-section energia-section-reverse">

        <div class="energia-section-text">

            <p class="energia-number">
                02
            </p>

            <h2>
                <?= htmlspecialchars($t['proceso_titulo']) ?>
            </h2>

            <p>
                <?= htmlspecialchars($t['proceso_texto']) ?>
            </p>

        </div>

        <div class="energia-section-image">

            <img
                src="uploads/energia/energia_03.jpg"
                alt="Building a handmade ceramic bonsai pot">

        </div>

    </section>


    <!-- =====================================================
         GALERÍA
    ====================================================== -->

    <section class="energia-galeria">

        <div class="energia-galeria-heading">

            <p class="energia-number">
                03
            </p>

            <h2>
                <?= htmlspecialchars($t['artesanal_titulo']) ?>
            </h2>

            <p>
                <?= htmlspecialchars($t['artesanal_texto']) ?>
            </p>

        </div>


        <div class="energia-galeria-grid">

            <figure>
                <img
                    src="uploads/energia/energia_04.jpg"
                    alt="Handmade ceramic work">
            </figure>

            <figure>
                <img
                    src="uploads/energia/energia_05.jpg"
                    alt="Handmade ceramic bonsai pot">
            </figure>

        </div>

    </section>


    <!-- =====================================================
         URUGUAY
    ====================================================== -->

    <section class="energia-uruguay">

        <div class="energia-uruguay-inner">

            <p class="energia-number">
                04
            </p>

            <h2>
                <?= htmlspecialchars($t['uruguay_titulo']) ?>
            </h2>

            <p>
                <?= htmlspecialchars($t['uruguay_texto']) ?>
            </p>

        </div>

    </section>


    <!-- =====================================================
         CIERRE
    ====================================================== -->

    <section class="energia-final">

        <h2>
            <?= htmlspecialchars($t['final_titulo']) ?>
        </h2>

        <p>
            <?= htmlspecialchars($t['final_texto']) ?>
        </p>

    </section>


</main>


<?php include 'includes/footer.php'; ?>

</body>
</html>
