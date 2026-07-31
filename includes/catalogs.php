<?php

/*
|--------------------------------------------------------------------------
| Catálogos de valores fijos
|--------------------------------------------------------------------------
*/

function catalogoFormas(): array
{
    return [
        'oval',
        'rectangular',
        'redonda',
        'cuadrada',
        'flor',
        'kurama',
        'cascada',
        'kusamono'
    ];
}

function catalogoMateriales(): array
{
    return [
        'gres',
        'terracota',
        'ceramica',
        'raku',
        'barro',
        'plastico'
    ];
}

function catalogoEsmaltes(): array
{
    return [
        'brillante',
        'satinado',
        'mate',
        'sin_esmalte',
        'mixto'
    ];
}

function catalogoEstados(): array
{
    return [
        'disponible',
        'vendida'
    ];
}