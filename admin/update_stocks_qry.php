<?php
    include '../components/connect.php';

    session_start();

    $admin_id = $_SESSION['admin_id'];

    if(!isset($admin_id)){
    header('location:admin_login.php');
    };

    $id = $_POST['id_prd'];
    $updated_stocks = $_POST['stocks'];
    $update_stocks = $conn->prepare("UPDATE `products` SET stock = ? WHERE id = ?");
    $update_stocks->execute([$updated_stocks, $id]);
    echo '<script>alert("Updated successfully!");</script>';
    echo '<script>window.location.href = "inventory.php";</script>';
?>