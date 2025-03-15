<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_POST['discount_price'])){
    $prd_id = $_POST['id_prd'];
    $prd_name = $_POST['prd_name'];
    $disc_qnty = $_POST['qnty'];
    $disc_price = $_POST['discount_price'];

    $update_product = $conn->prepare("UPDATE `products` SET discount_qnty = ?, discount_price = ? WHERE id = ?");
    $update_product->execute([$disc_qnty, $disc_price, $prd_id]);

    $select_users = $conn->prepare("SELECT * FROM `users`");
    $select_users->execute();
    if($select_users->rowCount() > 0){
       while($fetch_users = $select_users->fetch(PDO::FETCH_ASSOC)){
          $name = $fetch_users['name'];
          $email = $fetch_users['email'];
       }
    }

    $subject = "AJB's Bakery: Your Order Status";
    $message = "Good Day $name ! We would like to notify you that our store offers new special promo. Buy $disc_qnty pcs. of $prd_name and get a discount for as low as Php $disc_price per item.";
    $sender = "From: admin@gmail.com";
    mail($email, $subject, $message, $sender);

    echo "<script>alert('Discount updated successfully!');</script>";
    echo "<script>window.location.href = 'products.php'</script>";

}

?>