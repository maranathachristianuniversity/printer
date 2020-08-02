<?php return [
    'method'     => 'AES-256-CBC',
    'key'        => $_SERVER['SECRET_KEY'],
    'identifier' => 'printer',
    'cookies'    => 'printer',
    'session'    => 'printer',
    'expired'    => 30,
    'expiredText'=> 'Login untuk melanjutkan',
    'errorText'  => 'Anda tidak memiliki hak akses',
];