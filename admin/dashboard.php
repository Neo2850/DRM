<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Dashboard</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">

   <style>
      .box-container {
   display: flex;
   flex-wrap: wrap;
   gap: 20px;
}

.box {
   color: #333;
   border-radius: 10px;
   padding: 20px;
   margin: 10px;
   text-align: center;
   box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
   transition: background-color 0.3s ease;
   flex: 1 1 calc(33.333% - 40px); /* Responsive layout */
}

.box h3 {
   font-size: 24px;
   margin: 10px 0;
}

.box p {
   font-size: 16px;
}

.box i {
   font-size: 36px;
   margin-bottom: 10px;
   display: block;
}

/* Example hover effect */
.box:hover {
   background-color: #e0e0e0;
}

   </style>
</head>
<body style="
   position: relative;
   padding: 0 0 0 280px;
   background: url(../img/background.png) no-repeat center;
   background-size: cover;
">

<div style="
   position: absolute;
   top: 0;left: 0;
   width: 100%;
   height: 125vh;
   background: #282828;
   z-index: -1;
   opacity: 0.6;
"></div>

<?php include '../components/admin_header.php'; ?>
<?php include '../components/nav.php'; ?>

<section class="dashboard" style="
   margin-top: 70px;
">
   <br><br>
   <h1 class="heading" style="color: #f1f1f1; background-color: gray;
    padding: 10px;
    color: white;
    text-align: center;">DRM: ONLINE ORDERING SYSTEM</h1>

   <div class="box-container" style="">

      <div class="box" style="background-color: # #33a0ff; color:">
         <i class="fas fa-user" style="padding-left: 15px; font-size:24px;"></i>
         <h3>welcome!</h3>
         <p><?= $fetch_profile['name']; ?></p>
      </div>

      <div class="box" style="background-color:  #ff9966;">
         <?php
            $total_pendings = 0;
            $select_pendings = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
            $select_pendings->execute(['pending']);
            if($select_pendings->rowCount() > 0){
               while($fetch_pendings = $select_pendings->fetch(PDO::FETCH_ASSOC)){
                  $total_pendings += $fetch_pendings['total_price'];
               }
            }
         ?>
         <i class="fas fa-money-bill-wave" style="padding-left: 15px; font-size:24px;"></i>
         <h3><span>₱ </span><?= $total_pendings; ?></h3>
         <p>total pendings</p>
      </div>

      <div class="box" style="background-color: #33cc33;">
         <?php
            $total_completes = 0;
            $select_completes = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
            $select_completes->execute(['completed']);
            if($select_completes->rowCount() > 0){
               while($fetch_completes = $select_completes->fetch(PDO::FETCH_ASSOC)){
                  $total_completes += $fetch_completes['total_price'];
               }
            }
         ?>
         <i class="fas fa-check-circle" style="padding-left: 15px; font-size:24px;"></i>
         <h3><span>₱ </span><?= $total_completes; ?></h3>
         <p>completed orders</p>
      </div>

      <div class="box" style="background-color: #00ccff;">
         <?php
            $select_orders = $conn->prepare("SELECT * FROM `orders`");
            $select_orders->execute();
            $number_of_orders = $select_orders->rowCount()
         ?>
         <i class="fas fa-list-alt" style="padding-left: 15px; font-size:24px;"></i>
         <h3><?= $number_of_orders; ?></h3>
         <p>orders placed</p>
      </div>

      <div class="box" style="background-color: #669900;">
         <?php
            $select_products = $conn->prepare("SELECT * FROM `products`");
            $select_products->execute();
            $number_of_products = $select_products->rowCount()
         ?>
         <i class="fas fa-box" style="padding-left: 15px; font-size:24px;"></i>
         <h3><?= $number_of_products; ?></h3>
         <p>products added</p>
      </div>

      <div class="box" style="background-color:  #666699;">
         <?php
            $select_users = $conn->prepare("SELECT * FROM `users`");
            $select_users->execute();
            $number_of_users = $select_users->rowCount()
         ?>
         <i class="fas fa-users" style="padding-left: 15px; font-size:24px;"></i>
         <h3><?= $number_of_users; ?></h3>
         <p>users</p>
      </div>

      <div class="box" style="background-color: #cc4400;">
         <?php
            $select_admins = $conn->prepare("SELECT * FROM `admins`");
            $select_admins->execute();
            $number_of_admins = $select_admins->rowCount()
         ?>
         <i class="fas fa-user-shield" style="padding-left: 15px; font-size:24px;"></i>
         <h3><?= $number_of_admins; ?></h3>
         <p>admin users</p>
      </div>

      <div class="box" style="background-color: #ffcc00;">
      <?php
         $select_feedback = $conn->prepare("SELECT * FROM `feedback`");
         $select_feedback->execute();
         $number_of_feedback = $select_feedback->rowCount();
      ?>
      <i class="fas fa-comment" style="padding-left: 15px; font-size:24px;"></i>
      <h3><?= $number_of_feedback; ?></h3>
      <p>feedbacks</p>
   </div>

   </div>
</section>

<script src="../js/admin_script.js"></script>
   
</body>
</html>
