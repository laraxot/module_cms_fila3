<?php

declare(strict_types=1);

return [
    'navigation' => [
        'name' => 'Pagina',
        'plural' => 'Pagine',
        'group' => [
            'name' => 'Site',
        ],
    ],
    'fields' => [
        'title' => [
            'label' => 'Titolo',
        ],
        'name' => [
            'label' => 'Nome',
        ],
        'slug' => [
            'label' => 'Slug',
        ],
        'guard_name' => 'Guard',
        'permissions' => 'Permessi',
        'roles' => 'Ruoli',
        'updated_at' => 'Aggiornato il',
        'first_name' => 'Nome',
        'last_name' => 'Cognome',
    ],
    'actions' => [
        'create' => [
            'label' => 'create',
        ],
        'activeLocale' => [
            'label' => 'activeLocale',
        ],
    ],
];
