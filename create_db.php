<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$servername", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "CREATE DATABASE IF NOT EXISTS comr_mini CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    $conn->exec($sql);
    echo "Database created successfully\n";
} catch(PDOException $e) {
    echo $sql . "\n" . $e->getMessage();
}
$conn = null;
?>
