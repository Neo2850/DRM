<?php

$db_name = 'mysql:host=localhost;dbname=eorcon';
$user_name = 'root';
$user_password = '';

$conn = new PDO($db_name, $user_name, $user_password);
$conn2= new mysqli('localhost','root','','eorcon')or die("Could not connect to mysql".mysqli_error($conn2));

try {
    // Initialize the PDO connection
    $pdo = new PDO($db_name, $user_name, $user_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Optional: Set error mode to exception for better debugging
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>