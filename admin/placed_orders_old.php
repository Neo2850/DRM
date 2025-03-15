<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_POST['update_payment'])){
   $order_id = $_POST['order_id'];
   $order_id_2 = $_POST['order_id_2'];
   $payment_status = $_POST['payment_status'];
   $current_payment_status = $_POST['current_payment_status'];
   $cust_name = $_POST['customer_name'];
   $cust_email = $_POST['customer_email'];

   // SELECT ALL DATA FROM ORDER DETAILS
   $select_order_details = $conn->prepare("SELECT * FROM `order_details` WHERE order_id = ?");
      $select_order_details->execute([$order_id_2]);
      if($select_order_details->rowCount() > 0){
         while($fetch_orders1 = $select_order_details->fetch(PDO::FETCH_ASSOC)){
            $check = 0;
            $prd_id = $fetch_orders1["pid"];
            $prd_qnty = $fetch_orders1["quantity"];

            // SELECT DATA FROM PRODUCTS
            $select_product = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
            $select_product->execute([$prd_id]);
            if($select_product->rowCount() > 0){
               while($fetch_orders2 = $select_product->fetch(PDO::FETCH_ASSOC)){
                  $current_stock = $fetch_orders2["stock"];
                  $current_stock_out = $fetch_orders2["stock_out"];
               }
            }
               // COMPUTE STOCKS
            if($current_payment_status == "pending" && $payment_status == "completed"){
               $result_stocks = $current_stock - $prd_qnty;
               $result_stocks_out = $current_stock_out + $prd_qnty;
               $check = 1;
            }
            else if($current_payment_status == "completed" && $payment_status == "pending"){
               $result_stocks = $current_stock + $prd_qnty;
               $result_stocks_out = $current_stock_out - $prd_qnty;
               $check = 2;
            }

            if($check == 1 || $check == 2){
               // UPDATE STOCKS
               $update_stocks = $conn->prepare("UPDATE `products` SET `stock` = ?, `stock_out` = ? WHERE id = ?");
               $update_stocks->execute([$result_stocks, $result_stocks_out, $prd_id]);
            }

         }
      }

   // UPDATE PAYMENT STATUS
   $payment_status = filter_var($payment_status, FILTER_SANITIZE_STRING);
   $update_payment = $conn->prepare("UPDATE `orders` SET payment_status = ? WHERE id = ?");
   $update_payment->execute([$payment_status, $order_id]);
   $message[] = 'Order status updated!';

   $subject = "AJB's Bakery: Your Order Status";
   $message1 = "Good Day $cust_name ! We would like to notify you that your order is now $payment_status. Thank You!";
   $sender = "From: admin@gmail.com";
   mail($cust_email, $subject, $message1, $sender);
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_order = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
   $delete_order->execute([$delete_id]);
   header('location:placed_orders.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>placed orders</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body style="
   padding: 70px 0 0 180px;
">

<?php include '../components/admin_header.php'; ?>
<?php include '../components/nav.php'; ?>

<section class="orders">

<h1 class="heading">placed orders</h1>

<div class="box-container" 
   style="

   ">

   <?php
      $select_orders = $conn->prepare("SELECT * FROM `orders`");
      $select_orders->execute();
      if($select_orders->rowCount() > 0){
         while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="box">
      <p> Placed On : <span><?= $fetch_orders['placed_on']; ?></span> </p>
      <p> Name : <span><?= $fetch_orders['name']; ?></span> </p>
      <p> Number : <span><?= $fetch_orders['number']; ?></span> </p>
      <p> Address : <span><?= $fetch_orders['address']; ?></span> </p>
      <p> Total Products : <span><?= $fetch_orders['total_products']; ?></span> </p>
      <p> Total Price : <span>₱ <?= $fetch_orders['total_price']; ?>/-</span> </p>
      <p> Payment Method : <span><?= $fetch_orders['method']; ?></span> </p>
      <?php
         if($fetch_orders['method'] == 'credit'){
            $placed_on = new DateTime($fetch_orders['placed_on']);
            $due_date = $placed_on->modify('+3 days')->format('Y-m-d');
            echo '<p>Due Date : <span>' . $due_date . '</span></p>';
         }
      ?>

      <p> Reference No. : <span><?= $fetch_orders['reference_no']; ?></span> </p>
      <form action="" method="post">
         <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
         <input type="hidden" name="order_id_2" value="<?= $fetch_orders['order_id']; ?>">
         <input type="hidden" name="current_payment_status" value="<?= $fetch_orders['payment_status']; ?>">
         <input type="hidden" name="customer_name" value="<?= $fetch_orders['name']; ?>">
         <input type="hidden" name="customer_email" value="<?= $fetch_orders['email']; ?>">

         <?php
         if($fetch_orders['payment_status'] == "completed")
         {
            $completed = "disabled";
         }
         else
         {
            $completed = "";
         }
         ?>
            <select name="payment_status" class="select" <?php echo $completed;?>>
               <option selected disabled><?= $fetch_orders['payment_status']; ?></option>
               <option value="pending">pending</option>
               <option value="preparing">preparing your item</option>
               <option value="out for delivery">out for delivery</option>
               <option value="completed">completed</option>
            </select>

        <div class="flex-btn">
         <input type="submit" value="update" class="option-btn" name="update_payment" style="filter: invert(1);color: #000;">
         <a href="placed_orders.php?delete=<?= $fetch_orders['id']; ?>" class="delete-btn" onclick="return confirm('delete this order?');">delete</a>
        </div>
      </form>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty" style="border:none">no orders placed yet!</p>';
      }
   ?>

</div>

</section>

</section>

<script src="../js/admin_script.js"></script>
   
</body>
</html>