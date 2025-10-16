<?php

//autoloadのパスを指定
require_once dirname(__DIR__,4) . '/Users/s30769/vendor/autoload.php';

// .envを配置しているパスを設定
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$user = $_ENV["USER"];
$pass = $_ENV["PASS"];


try {
    $pdo = new PDO('mysql:host=localhost;dbname=librarymanagement', $user, $pass);
} catch (PDOException $error){
    echo $error->getMessage();
}