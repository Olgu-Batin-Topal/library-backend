<?php

$connection = [
    'host' => '127.0.0.1',
    'user' => 'root',
    'password' => '',
    'database' => 'api_library_com',
];

try {
    $db = new PDO(
        "mysql:host={$connection['host']};dbname={$connection['database']}",
        $connection['user'],
        $connection['password']
    );

    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
