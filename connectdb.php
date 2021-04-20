<?php
$user = 'root';
$password = 'root';
$db = 'spymetrix';
$host = 'localhost';

$dsn = 'mysql:host='.$host.';dbname='.$db;
$pdo = new PDO($dsn, $user, $password);
 ?>
