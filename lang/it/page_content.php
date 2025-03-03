<?php

declare(strict_types=1);

return [
    'navigation' => [
        'name' => 'Contenuto Pagina',
        'plural' => 'Contenuti Pagina',
        'group' => [
            'name' => 'Site',
        ],
        'label' => 'page content.navigation',
        'sort' => 87,
        'icon' => 'page content.navigation',
    ],
    'fields' => [
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
        'content_blocks' => [
            'label' => 'blocco componente',
        ],
        'delete' => [
            'label' => 'delete',
        ],
        'edit' => [
            'label' => 'edit',
        ],
        'view' => [
            'label' => 'view',
        ],
        'openFilters' => [
            'label' => 'openFilters',
        ],
        'applyFilters' => [
            'label' => 'applyFilters',
        ],
        'resetFilters' => [
            'label' => 'resetFilters',
        ],
        'reorderRecords' => [
            'label' => 'reorderRecords',
        ],
        'toggleColumns' => [
            'label' => 'toggleColumns',
        ],
    ],
    'rating' => [
        'no_import' => 'Nessuna cifra inserita',
        'import_zero' => 'Nessuna cifra inserita',
        'import_min' => 'Hai superato la cifra di :credits: crediti',
        'no_choice' => 'Nessuna opzione scelta',
    ],
    'single_expired' => 'Scaduto',
    'expired' => 'Articolo scaduto, non si possono fare più scommesse',
    'no_vote' => 'Siamo spiacenti, ma questa votazione è chiusa da :TIME, per favore prova a fare un altra previsione',
    'your_bet' => 'La tua previsione',
    'your_amount' => 'Previsione',
    'if_win' => 'Se vinci',
    'actions' => [
        'import' => [
            'fields' => [
                'import_file' => 'Seleziona un file XLS o CSV da caricare',
            ],
        ],
        'export' => [
            'filename_prefix' => 'Aree al',
            'columns' => [
                'name' => 'Nome area',
                'parent_name' => 'Nome area livello superiore',
            ],
        ],
        'activeLocale' => [
            'label' => 'activeLocale',
        ],
        'create' => [
            'label' => 'create',
        ],
    ],
    'plural' => [
        'model' => [
            'label' => 'page content.plural.model',
        ],
    ],
    'model' => [
        'label' => 'page content.model',
    ],
];
