<?php
return array(
    'default' => 'Sample',
    'theme' => 'classic',
    'db' => array(
        'server' => 'mysql:host=localhost',
        'dbname' => 'winu',
        'username' => 'root',
        'password' => '1'
    ),
    'import' => array(
        'controllers/*.php',
        'models/*.php',
        'components/*.php'
    )
);