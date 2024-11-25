<?php

$host = '127.0.0.1';
$db = 'my_crud_app';
$user = 'root';
$pass = '';


$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db; charset=$charset";

try {

    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // echo "Connection SuccessFull";
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

?>