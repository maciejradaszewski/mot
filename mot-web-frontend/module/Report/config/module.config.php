<?php

return [
    'view_manager' => [
        'display_exceptions'  => true,
        'template_map' => [
            'table/table'             => __DIR__ . '/../view/table/default.phtml',
            'table/footer'            => __DIR__ . '/../view/table/footer.phtml',
            'table/paginator'         => __DIR__ . '/../view/table/paginator.phtml',

            'table/formatter/sub-row'       => __DIR__ . '/../view/table/formatter/sub-row.phtml',
            'table/formatter/mot-test-link' => __DIR__ . '/../view/table/formatter/mot-test-link.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
