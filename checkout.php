<?php

include 'components/connect.php';

session_start();
error_reporting(0);

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:user_login.php');
};


// Check if "Buy Now" is triggered (via GET parameters or session)
$is_buy_now = isset($_GET['pid']);
$buy_now_item = [];

if ($is_buy_now) {
    $buy_now_item = array_map('htmlspecialchars', $_SESSION['product_details']); // Sanitize input
}

if(isset($_POST['order'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $method = $_POST['method'];
   $method = filter_var($method, FILTER_SANITIZE_STRING);
   $notes = $_POST['notes']; // Capture notes
   $notes = filter_var($notes, FILTER_SANITIZE_STRING);

   if(isset($_POST['reference_no'])){
      $reference_no = $_POST['reference_no'];
      $reference_no = filter_var($reference_no, FILTER_SANITIZE_STRING);
   }
   else{
      $reference_no = "";
   }
   $address = $_POST['address'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);
   $total_products = $_POST['total_products'];
   $total_price = $_POST['total_price'];
   $date = date('Y-m-d');

   $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $check_cart->execute([$user_id]);

   if($check_cart->rowCount() > 0){
      // GENERATE order_id using last row number in order table
      $gen_sql = "SELECT MAX(id) AS max_value FROM orders";
      $gen_result = $conn->query($gen_sql);
      
      if ($gen_result->rowCount() > 0) {
            // Fetch the result as an associative array
            while ($gen_row = $gen_result->fetch(PDO::FETCH_ASSOC)) {
               $order_details_id = $gen_row['max_value'] + 1;
            }
      }

      // SELECT ALL FROM CART
      $sql_cart = "SELECT * FROM cart WHERE `status`= 1";
      $result_cart = $conn->query($sql_cart);
      
      if ($result_cart->rowCount() > 0) {
          while ($row_cart = $result_cart->fetch(PDO::FETCH_ASSOC)) {
            $user_id = $row_cart["user_id"];
            $pid = $row_cart["pid"];
            $prd_name = $row_cart["name"];
            $prd_price = $row_cart["price"];
            $prd_qnty = $row_cart["quantity"];
            $note = $row_cart["note"];

            // INSERT DATA FROM CART TO ORDER DETAILS
            $insert_order_details = $conn->prepare("INSERT INTO `order_details`(order_id, user_id, pid, name, price, quantity, note) VALUES(?,?,?,?,?,?,?)");
            $insert_order_details->execute([$order_details_id, $user_id, $pid, $prd_name, $prd_price, $prd_qnty, $note]);

            $update_sales = $conn->prepare("UPDATE `products` SET sales = sales + ? WHERE id = ?");
            $update_sales->execute([$prd_qnty, $pid]);
          }
      }

      // Insert into orders table, now including notes
      $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price, placed_on, order_id, reference_no, notes) VALUES(?,?,?,?,?,?,?,?,?,?,?,?)");
      $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_products, $total_price, $date, $order_details_id, $reference_no, $notes]);
      
      if($reference_no != ""){
         //SEND EMAIL NOTIF IF GCASH
         $admin_email = "admin@gmail.com";
         $subject = "DRM Roofing Glass and Aluminum And Iron Works: GCASH PAYMENT RECEIVED";
         $message2 = "You have received Php $total_price.00 to your gcash account from $name as a payment for product orders. Transaction Reference Number: $reference_no.";
         $sender = "From: DRM@admin.com";
         mail($admin_email, $subject, $message2, $sender);
      }

      $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ? AND `status` = ?");
      $delete_cart->execute([$user_id, 1]);
      $message[] = 'Order placed successfully!';
   }else{
      $message[] = 'Cart is empty!';
   }

}


if(isset($_POST['order_bn'])){

   $name = $_POST['name'];
   $number = $_POST['number'];
   $email = $_POST['email'];
   $method = $_POST['method'];
   $notes = $_POST['notes'];

   $pid = $_POST['pid'];
   $pname = $_POST['pname'];
   $pnote="";
   
   if($pnote != ""){ $nt1 = "("; $nt2 = ")"; 
   } else{ $nt1 = ""; $nt2 = ""; }

   if(isset($_POST['reference_no'])){ $reference_no = $_POST['reference_no'];
   } else{ $reference_no = ""; }

   $address = $_POST['address'];
   $total_price = $_POST['gt_bn'];
   $pprice = $_POST['rp_bn'];
   $qty_bn = $_POST['qty_bn'];
   $date = date('Y-m-d');
   
   // $total_products = $_POST['total_products'];
   $prod_items[] = $pname.' ('.$pprice.' x '. $qty_bn.' '.$nt1.' '. $pnote.''.$nt2.' ) - ';
   $total_products = implode($prod_items);

   // GENERATE order_id using last row number in order table
   $gen_sql = "SELECT MAX(id) AS max_value FROM orders";
   $gen_result = $conn->query($gen_sql);
      
   if ($gen_result->rowCount() > 0) {
         // Fetch the result as an associative array
         while ($gen_row = $gen_result->fetch(PDO::FETCH_ASSOC)) {
            $order_details_id = $gen_row['max_value'] + 1;
         }
   }
   
   // INSERT DATA TO ORDER DETAILS
   $insert_order_details = $conn->prepare("INSERT INTO `order_details`(order_id, user_id, pid, name, price, quantity, note) 
   VALUES(?,?,?,?,?,?,?)");
   $insert_order_details->execute([$order_details_id, $user_id, $pid, $pname, $pprice, $qty_bn, $pnote]);

   $update_sales = $conn->prepare("UPDATE `products` SET sales = sales + ? WHERE id = ?");
   $update_sales->execute([$qty_bn, $pid]);

   
   // Insert into orders table, now including notes
   $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price, placed_on, order_id, reference_no, notes) VALUES(?,?,?,?,?,?,?,?,?,?,?,?)");

   $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_products, $total_price, $date, $order_details_id, $reference_no, $notes]);


   if($reference_no != ""){
      //SEND EMAIL NOTIF IF GCASH
      $admin_email = "admin@gmail.com";
      $subject = "DRM Roofing Glass and Aluminum And Iron Works: GCASH PAYMENT RECEIVED";
      $message2 = "You have received Php $total_price.00 to your gcash account from $name as a payment for product orders. Transaction Reference Number: $reference_no.";
      $sender = "From: DRM@admin.com";
      mail($admin_email, $subject, $message2, $sender);
   }

   $_SESSION['product_details'] = "";
   $_SESSION['bn_end'] = 'true';

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>checkout</title>
   
   <!-- SweetAlert2 CDN link -->
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="checkout-orders">

<form action="" method="POST" id="payment-form">

<h3>your orders</h3>

<div class="display-orders">

<?php
   // FOR CART 
   $nt1 = "";
   $nt2 = "";
   $grand_total = 0;
   $cart_items[] = '';
   $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ? AND status = ?");
   $select_cart->execute([$user_id, 1]);
   // FOR CART END

   if($_SESSION['bn_end']=="true" && $is_buy_now){
      
      echo '<p class="success" style="color: #5cb85c; font-weight: bold;" >You have successfully placed your order. Status can be seen in your order list</p>';
      $_SESSION['bn_end'] = '';

   } else if(count($buy_now_item) > 0) {
      // Get the product ID and quantity
      $prd_qnty2 = $buy_now_item['qty'];  // Default quantity (from session)
      $prd_id2 = $buy_now_item['id'];     // Product ID
      
  
              $result_price = $buy_now_item['price'];
  
              // Check if 'image' field exists and is not empty
              $product_image = isset($buy_now_item['image']) && !empty($buy_now_item['image']) ? 'uploaded_img/'.$buy_now_item['image'] : 'path/to/default/image.jpg';
  
              // Display product details
              echo '<p><img src="'.$product_image.'" alt="" style="width: 70px; height: 60px;"> '.$buy_now_item['name'].' <span>(₱'.$result_price.'.00'.')</span></p>';
              
              $grand_total_initial = ($result_price * $prd_qnty2) + 70;
  
              // Quantity input field for the user to change quantity
              echo '<div class="inputBox" style="font-size: 20px;">
                     <br/>
                      <label for="quantity">Quantity:</label>
                      <input type="number" name="qty_bn" id="qty_bn"
                      style="font-size: 20px; border: 1px solid black; padding:5px; width:100px;"   
                      min="1" value="'.$prd_qnty2.'" onkeyup="updateGrandTotal()" required>

                      <input type="hidden" name="pname" id="pname" value="'.$buy_now_item['name'].'" readonly>
                      <input type="hidden" name="pid" id="pid" value="'.$prd_id2.'" readonly>
                      <input type="hidden" name="rp_bn" id="rp_bn" value="'.$result_price.'" readonly>
                      <br/> <br/>
                      <p>Shipping fee: ₱70</p>
                      <input type="hidden" name="gt_bn" id="gt_bn" value="'.$grand_total_initial.'" readonly>
                    </div>';
  
              
              // echo '<div class="grand-total">Total Payment : <span id="gt_bn_disp"></span></div>';
              echo '<div class="grand-total">Total Payment : <span id="gt_bn_disp">₱'.$grand_total_initial.'.00</span></div>';
        
   } else if($select_cart->rowCount() > 0){
      while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
         if($fetch_cart['note'] != ""){
            $nt1 = "(";
            $nt2 = ")"; 
         }
         else{
            $nt1 = "";
            $nt2 = "";
         }
         $cart_items[] = $fetch_cart['name'].' ('.$fetch_cart['price'].' x '. $fetch_cart['quantity'].' '.$nt1.' '. $fetch_cart['note'].''.$nt2.' ) - ';
         $total_products = implode($cart_items);

         $prd_qnty2 = $fetch_cart['quantity'];
         $prd_id2 = $fetch_cart['pid'];
 
         $select_prd = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
         $select_prd->execute([$prd_id2]);
         if($select_prd->rowCount() > 0){
            while($fetch_prd = $select_prd->fetch(PDO::FETCH_ASSOC)){
               $disc_qnty = $fetch_prd['discount_qnty'];
               $disc_price = $fetch_prd['discount_price'];
            }
         }
?>
<?php 
      if($disc_qnty != "0" && $prd_qnty2 >= $disc_qnty){
         $check_discounted = true;
         $result_price = $disc_price;
      }
      else{
         $check_discounted = false;
         $result_price = $fetch_cart['price'];
      }
?>

<p><img src="uploaded_img/<?=$fetch_cart['image']; ?>" alt="" style="width: 70px; height: 60px;"> <?= $fetch_cart['name']; ?> <span>(<?= '₱'.$result_price.'.00 x '. $fetch_cart['quantity']; ?>)</span> </p>

<?php
      $grand_total += ($result_price * $fetch_cart['quantity']) + 70;
      if($check_discounted){
         echo '<p style="color: green; font-size: 12px; margin-top: 5px;">Discounted!</p>';
      }
   }
   }else{
      echo '<p class="success" style="color: #5cb85c; font-weight: bold;" >You have successfully placed your order. Status can be seen in your order list</p>';
   }
?>
   <input type="hidden" name="total_products" value="<?= $total_products; ?>">
   <input type="hidden" name="total_price" value="<?= $grand_total; ?>" value="">

   <?php
      if($select_cart->rowCount() > 0 && !(count($buy_now_item) > 0)){
         echo '<div class="grand-total">Total Payment : <span>₱'.$grand_total.'.00</span></div>';
      }
   ?>
</div>


         <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
               // Check if the "selected-method" POST variable is set
               if (isset($_POST["selected-method"])) {
                  $selectedMethod = $_POST["selected-method"];
                  if($selectedMethod == "cash on delivery"){
                     $cod_selected = "selected";
                     $gcash_selected = "";
                  }
                  else if($selectedMethod == "gcash"){
                     $gcash_selected = "selected";
                     $cod_selected = "";
                  }
                  else{
                     $gcash_selected = "";
                     $cod_selected = "";
                  }
               }
               else{
                  $cod_selected = "";
                  $gcash_selected = "";
               }
            }
            else{
               $cod_selected = "";
               $gcash_selected = "";
            }
         ?>

<h3>Place Your Orders</h3>
<?php
   $select_user = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
   $select_user->execute([$user_id]);
   if($select_user->rowCount() > 0){
      while($fetch_user = $select_user->fetch(PDO::FETCH_ASSOC)){
         $user_address = $fetch_user['Address'];
         $user_email = $fetch_user['email'];
         $user_name = $fetch_user['name'];
         $user_contact = $fetch_user['Contact_Number'];
      }
   }
?>
<div class="flex">
   <div class="inputBox">
         <span>Payment Option :</span>
         <select name="method" class="box" id="payment-method" required>
            <option value="cash on delivery">Cash On Delivery</option>
            <option value="gcash">Gcash</option>
         </select>
         
         <input type="hidden" name="pmethod_s" id="pmethod_s"
          value="<?php echo $_SESSION['product_details']['pmethod'];?>" readonly>
      </div>

      <div class="inputBox">
         <span>Your Fullname :</span>
         <input type="text" name="name" placeholder="enter your name" class="box" maxlength="20" value="<?php echo $user_name;?>" required>
      </div>
      <div class="inputBox">
         <span>Your Number :</span>
         <input type="text" name="number" placeholder="enter your number" class="box" value="<?php echo $user_contact;?>" required>
      </div>
      <div class="inputBox">
         <span>Your Email :</span>
         <input type="email" name="email" placeholder="enter your email" class="box" maxlength="50" value="<?php echo $user_email;?>" required>
      </div>
      <div class="inputBox">
         <span>Select Your Delivery Address :</span>
         <select name="address" class="box" required>
         <?php
         // Fetch main address from users table
         $select_main_address = $conn->prepare("SELECT `Address` FROM `users` WHERE `id` = ?");
         $select_main_address->execute([$user_id]);
         if($select_main_address->rowCount() > 0){
            $fetch_main_address = $select_main_address->fetch(PDO::FETCH_ASSOC);
            ?>
               <option><?php echo $fetch_main_address['Address']; ?> (Main Address)</option>
            <?php
         }

         // Fetch additional addresses from address table
         $select_additional_addresses = $conn->prepare("SELECT `address` FROM `address` WHERE `user_id` = ?");
         $select_additional_addresses->execute([$user_id]);
         if($select_additional_addresses->rowCount() > 0){
            while($fetch_address = $select_additional_addresses->fetch(PDO::FETCH_ASSOC)){
               ?>
                  <option><?php echo $fetch_address['address']; ?></option>
               <?php
            }
         }
         ?>
         </select>

      </div>
      <div class="inputBox">
         <span>Notes :</span>
         <input type="text" name="notes" placeholder="Instruction" class="box" maxlength="1000">
      </div>
   </div><br><br>
   
   <?php
      if ($_SERVER["REQUEST_METHOD"] == "POST") {
         // Check if the "selected-method" POST variable is set
         if (isset($_POST["selected-method"])) {
            $selectedMethod = $_POST["selected-method"];
            if($selectedMethod == "gcash")
            {
               $select_admin = $conn->prepare("SELECT * FROM `admins` WHERE id = ?");
               $select_admin->execute([1]);
               $row = $select_admin->fetch(PDO::FETCH_ASSOC);

               if($select_admin->rowCount() > 0){
                  $gcash_qr = $row['gcash_qr'];
                  $gcash_name = $row['account_name'];
                  $gcash_num = $row['gcash_number'];
               }
      ?>
               <center>
               <h3 style="font-weight: bold;">SCAN TO PAY<h3>
               <img src="gcash_code/<?php echo $gcash_qr; ?>" style="width: 300px; height: 300px;" alt=""><br><br>
               <?php
               
               if($select_cart->rowCount() > 0 && !(count($buy_now_item) > 0)){
                  echo '<p style="font-size: 24px; color: orange;">Total Payment : ₱'.$grand_total.'</p>';
               } else {
                  echo '<p style="font-size: 24px; color: orange;"
                  >Total Payment : <span id="gt_bn_disp2">₱'.$grand_total_initial.'</span></p>';
               }?><br>
               <p style="font-weight: bold; font-size:20px;">GCASH #: <?php echo $gcash_name; ?></p>
               <p style="font-size: 16px;"><?php echo $gcash_num; ?></p><br>
               <span style="font-size: 18px; font-style: italic;">Reference Number: </span><br><input name="reference_no" type="text" style="font-size: 18px; width: 30%; padding: 5px;" placeholder="Input your reference number..." required><br>
               <span style="font-size: 10px; font-style: italic;">Note: Saved the screenshot or proof of your payment and present it for verification.</span>
               </center>
               <?php 
               }
            }
         }
      ?>

<?php
if(count($buy_now_item) > 0) {
?>

<input type="submit" name="order_bn" class="btn" value="place order" 
style="filter: invert(1);color: #000;">

<?php
} else { ?>

<input type="submit" name="order" class="btn <?= ($grand_total > 1)?'':'disabled'; ?>" 
value="place order" style="filter: invert(1);color: #000;">

<?php
}
?>

<!-- <input type="submit" name="<?php //echo $submit_button_name; ?>" class="btn <?php //echo $button_class; ?>" value="Place Order" style="filter: invert(1); color: #000;"> -->

</form>

</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</body>
</html>

<script>
   
   $(function() {
      var pmethod_s = $("#pmethod_s").val();

      if(pmethod_s==""){
         console.log("blank pmethod");
      } else {
         $("#payment-method").val(pmethod_s);
      }
      
   });


   function updateGrandTotal(){
      var qty_bn = document.getElementById("qty_bn").value;
      var rp_bn = document.getElementById("rp_bn").value;
      var pmethod = document.getElementById("payment-method").value;

      var gt_bn = ((rp_bn * qty_bn) + 70).toFixed(2);

      document.getElementById("gt_bn").value = gt_bn;

      document.getElementById("gt_bn_disp").textContent = "₱"+gt_bn;
      document.getElementById("gt_bn_disp2").textContent = "₱"+gt_bn;
      
      update_session();
   }
</script>

<script>
    document.getElementById("payment-method").addEventListener("change", function() {
        // Get the selected value from the dropdown
        var selectedValue = this.value;

        // Create a hidden input element to store the selected value
        var hiddenInput = document.createElement("input");
        hiddenInput.type = "hidden";
        hiddenInput.name = "selected-method";
        hiddenInput.value = selectedValue;

        // Append the hidden input to the form
        document.getElementById("payment-form").appendChild(hiddenInput);

        update_session();

        // Submit the form
        document.getElementById("payment-form").submit();
    });

    function update_session(){
         var qty_bn = document.getElementById("qty_bn").value;
         var pmethod = document.getElementById("payment-method").value;
         $("#pmethod_s").val(pmethod);
         
         var xhr = new XMLHttpRequest();
         xhr.open("POST", "checkout_update_session.php", true);
         xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
         xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
               console.log("Session updated successfully: " + xhr.responseText);
            }
         };
         // xhr.send("qty_bn=" + encodeURIComponent(qty_bn));
         xhr.send("qty_bn=" + encodeURIComponent(qty_bn) + "&pmethod=" + encodeURIComponent(pmethod));
    }
</script>