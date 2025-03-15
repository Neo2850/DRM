<?php
    include '../components/connect.php';

    session_start();

    $admin_id = $_SESSION['admin_id'];

    if(!isset($admin_id)){
    header('location:admin_login.php');
    };

    $id = $_GET['id'];
    $delete_feedback = $conn->prepare("DELETE FROM `poll` WHERE id = ?");
    $delete_feedback->execute([$id]);

    echo '<script>alert("Deleted successfully!");</script>';
    echo '<script>window.location.href = "mainpage.php";</script>';
?>