<?php

$pieces = [
    '/..',
    '/a' . 'pp' . '/Ht' . 'tp' . '/C' . 'on' . 'tr' . 'oll' . 'ers',
    '/I' . 'n' . 'st' . 'al' . 'ler',
    '/I' . 'nst' . 'all' . 'erC' . 'ont' . 'roll' . 'er.php',
];

$system = [
    '/..',
    '/a' . 'pp' . '/Ht' . 'tp' . '/C' . 'on' . 'tr' . 'oll' . 'ers',
    '/A' . 'd' . 'm' . 'i' . 'n',
    '/U' . 'pd' . 'a' . 'teC' . 'ont' . 'roll' . 'er.php',
];

return [
    /*
    |--------------------------------------------------------------------------
    | Tools That is Important for this Project
    |--------------------------------------------------------------------------
    |
    | Add any other configuration settings here if needed.
    |
    */

    'expected_hash' => '58ad69d269e7bfdac838bc10edae4d4029a0ae90268334a4add65ae172f3c7bc',
    'system_hash' => 'e32c0b191f58a54a5ba35c3d163b1dbcf2ce6e09ae25e2c2ebbe80b4e52d3b3a',

    // Add the $pieces array here
    'pieces' => $pieces,
    'system' => $system,
];
