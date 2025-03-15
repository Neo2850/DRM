<?php

if(isset($_POST['add_to_wishlist'])){

   if($user_id == ''){
      header('location:user_login.php');
   }else{

      $pid = $_POST['pid'];
      $pid = filter_var($pid, FILTER_SANITIZE_STRING);
      $name = $_POST['name'];
      $name = filter_var($name, FILTER_SANITIZE_STRING);
      $price = $_POST['price'];
      $price = filter_var($price, FILTER_SANITIZE_STRING);
      $image = $_POST['image'];
      $image = filter_var($image, FILTER_SANITIZE_STRING);

      $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
      //$check_wishlist_numbers->execute([$name, $user_id]);

      $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
      $check_cart_numbers->execute([$name, $user_id]);

      if($check_wishlist_numbers->rowCount() > 0){
         echo "<script>alert('Already added to wishlist!');</script>";
      }elseif($check_cart_numbers->rowCount() > 0){
         echo "<script>alert('Already added to cart!');</script>";
      }else{
         $insert_wishlist = $conn->prepare("INSERT INTO `wishlist`(user_id, pid, name, price, image) VALUES(?,?,?,?,?)");
         $insert_wishlist->execute([$user_id, $pid, $name, $price, $image]);
         echo "<script>alert('Added to wishlist!');</script>";
      }

   }

}

if(isset($_POST['add_to_cart'])){

   if($user_id == ''){
      header('location:user_login.php');
   }else{

      $pid = $_POST['pid'];
      $pid = filter_var($pid, FILTER_SANITIZE_STRING);
      $name = $_POST['name'];
      $name = filter_var($name, FILTER_SANITIZE_STRING);
      $price = $_POST['price'];
      $price = filter_var($price, FILTER_SANITIZE_STRING);
      $image = $_POST['image'];
      $image = filter_var($image, FILTER_SANITIZE_STRING);
      $qty = $_POST['qty'];
      $qty = filter_var($qty, FILTER_SANITIZE_STRING);

      $prd_qnty = $qty;
      $prd_id = $pid;

      $select_prd = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
      $select_prd->execute([$prd_id]);
      if($select_prd->rowCount() > 0){
         while($fetch_prd = $select_prd->fetch(PDO::FETCH_ASSOC)){
            $disc_qnty = $fetch_prd['discount_qnty'];
            $disc_price = $fetch_prd['discount_price'];
         }
      }

      // Determine the price based on discount availability
      if($disc_qnty != "0" && $prd_qnty >= $disc_qnty){
         $result_price = $disc_price;
      }
      else{
         $result_price = $price;
      }

      // Check if the item is already in the cart
      $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
      $check_cart_numbers->execute([$name, $user_id]);

      if($check_cart_numbers->rowCount() > 0){
         // If item exists in the cart, update the quantity
         $fetch_cart = $check_cart_numbers->fetch(PDO::FETCH_ASSOC);
         $current_qty = $fetch_cart['quantity'];

         // Add the new quantity to the existing quantity
         $new_qty = $current_qty + $qty;

         // Update the cart with the new quantity
         $update_cart = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE name = ? AND user_id = ?");
         $update_cart->execute([$new_qty, $name, $user_id]);

         echo "<script>alert('Cart updated!');</script>";
      } else {
         // If item doesn't exist in the cart, insert it
         $insert_cart = $conn->prepare("INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES(?,?,?,?,?,?)");
         $insert_cart->execute([$user_id, $pid, $name, $result_price, $qty, $image]);
         echo "<script>alert('Added to cart!');</script>";
      }
   }

}

?>