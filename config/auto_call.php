<?php

return [
    'setting' => [
        'api' => [
            'list_campaigns' => 'http://203.137.178.244/api/v1/campaigns',
            'list_contacts' => 'http://203.137.178.244/api/v1/contacts',
            'add_contact' => 'http://203.137.178.244/api/v1/contacts',
            'operate' => 'http://203.137.178.244/api/contacts/%d/operate?command=start'
        ],
        'add_contact' => [
            'csv_dir' => 'tmp/file/auto_call'
        ]
    ]
];
