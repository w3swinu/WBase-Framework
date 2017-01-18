<?php
return array(
    'default' => 'Sample',
    'theme' => 'classic',
    'error' => 'Sample/Error',
    'db' => array(
        'server' => 'localhost',
        'dbname' => 'wbase',
        'username' => 'root',
        'password' => ''
    ),
    'import' => array(
        'controllers/*.php',
        'models/*.php',
        'components/*.php'
    ),
    'URLmanager' => [
        'routes' => [
            'about' => 'sample/about',
        ]
    ]
);
