<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:user_login.php');
};

if(isset($_POST['delete'])){
   $cart_id = $_POST['cart_id'];
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE id = ?");
   $delete_cart_item->execute([$cart_id]);
}

if(isset($_GET['delete_all'])){
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
   $delete_cart_item->execute([$user_id]);
   header('location:cart.php');
}

if(isset($_POST['update_qty'])){
   $cart_id = $_POST['cart_id'];
   $qty = $_POST['qty'];
   $qty = filter_var($qty, FILTER_SANITIZE_STRING);
   $update_qty = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE id = ?");
   $update_qty->execute([$qty, $cart_id]);
}

if(isset($_POST['update_qty'])){
   $cart_id = $_POST['cart_id'];
   $qty = $_POST['qty'];
   $qty = filter_var($qty, FILTER_SANITIZE_STRING);
   $update_qty = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE id = ?");
   $update_qty->execute([$qty, $cart_id]);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>shopping cart</title>

   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="products shopping-cart">
   <h3 class="heading" style="color: #fff;">shopping cart</h3>
   <div class="box-container" style="background-color: rgba(255, 255, 255); padding: 100px 40px; border-radius: 20px;">
   <?php
      $grand_total = 0;
      $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
      $select_cart->execute([$user_id]);
      if($select_cart->rowCount() > 0){
         while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
            $prd_qnty = $fetch_cart['quantity'];
            $prd_id = $fetch_cart['pid'];

            $select_prd = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
            $select_prd->execute([$prd_id]);
            if($select_prd->rowCount() > 0){
               while($fetch_prd = $select_prd->fetch(PDO::FETCH_ASSOC)){
                  $disc_qnty = $fetch_prd['discount_qnty'];
                  $disc_price = $fetch_prd['discount_price'];
                  $curr_stock = $fetch_prd['stock'];
               }
            }

            if($fetch_cart['status'] == "1"){
            $stat = "checked";
            }
            else{
               $stat = "";
            }

            if($disc_qnty != "0" && $prd_qnty >= $disc_qnty){
               $result_price = $disc_price;
            }
            else{
               $result_price = $fetch_cart['price'];
            }
   ?>
   <center>
      <div class="box-container">
   <form action="" method="post" class="box" style="width: 420%; margin-left: -530px; height: 100px; border-radius: 20px;">
      <input type="hidden" name="cart_id" value="<?= $fetch_cart['id']; ?>">
      <a href="quick_view.php?pid=<?= $fetch_cart['pid']; ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= $fetch_cart['image']; ?>" alt="" style="width: 100px; margin-top: -70px; float: left;">
      <li style="margin-left: 50px; float: left; margin-top:30px; list-style: none;" ><input type="checkbox" class="item-checkbox" data-item-id="<?= $fetch_cart['pid']; ?>" <?php echo $stat; ?>></li>
      <span class="name" style="margin-left: 50px; float: left; margin-top:20px; max-width: 150px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
  <?= $fetch_cart['name']; ?>
</span>
      <div class="flex" style="margin-left: 50px; float: left; margin-top:10px;">
      <span STYLE="font-size: 20px; color: red;">₱ <?= $sub_total = ($result_price * $fetch_cart['quantity']); ?>.00</span>     
         <!-- <div class="price" style="margin-top:;" >₱ <?= $fetch_cart['price']; ?>.00</div> -->
         <span style="font-size: 16px; margin-top:; margin-left: 10px;">Qty: </span><input style="" type="number" name="qty" class="qty" min="1" max="<?php echo $curr_stock; ?>" onkeypress="if(this.value.length == 2) return false;" value="<?= $fetch_cart['quantity']; ?>">

         
      </div>
      <?php 

         if($disc_qnty != "0" && $prd_qnty >= $disc_qnty){
?>
         <p style="color: green; font-size: 12px; margin-top: 5px; margin-left: -250px; float: left;">Discounted!</p>
<?php
         }
         if($fetch_cart['note'] != ""){
            echo '<span style="font-size: 12px;" margin-left: 50px; float: left;>Note: <?= $fetch_cart["note"]; ?></span>';
         }
?>
      <div style="float: right; gap: 10px; display: flex;">
      <button type="submit" class="fas fa-edit" name="update_qty" style="filter: invert(1);color: #000; margin-top: 13px;"><span style="font-size: 12px;">Edit</span></button>
      <!-- <div class="sub-total" style="margin-left: 50px; float: left;"> Sub Total : <span>₱ <?= $sub_total = ($result_price * $fetch_cart['quantity']); ?>.00</span>     </div> -->
      <input type="submit" style=" float: right; width: 150px;" value="delete item" onclick="return confirm('delete this from cart?');" class="delete-btn" name="delete">
      </div>
   </form>

   <?php
   if($stat == "checked")
      {
         $grand_total += $sub_total;
      }
   }
   ?>
  <div class="cart-total" style="width: 2000px; margin-left: -80px; float: left; ">
   <label style="font-size: 15px;">Standard Delivery Fee: <span>₱ 70.00</span></label><br><br>
      <p>Total Payment: <span>₱ <?= $grand_total + 70; ?>.00</span></p>
      <a href="shop.php" class="option-btn" style="background: #239ae0;">continue shopping</a>
      <a href="cart.php?delete_all" class="delete-btn <?= ($grand_total > 1)?'':'disabled'; ?>" onclick="return confirm('delete all from cart?');">delete all item</a>
      <a href="checkout.php?" style="background: #239ae0;" class="btn <?= ($grand_total > 1)?'':'disabled'; ?>">proceed to checkout</a>
   </div>
   </div>
      </center>
   <?php
   }else{
      echo '<p class="empty">your cart is empty</p>';
   }
   ?>
</section>













<?php include 'components/footer.php'; ?>

<script>
        $(document).ready(function () {
            // Attach a click event handler to checkboxes
            $('.item-checkbox').on('click', function () {
                var itemId = $(this).data('item-id');
                var isChecked = $(this).is(':checked') ? 1 : 0; // Check if the checkbox is checked

                // Send an AJAX request to update.php
                $.ajax({
                    type: 'POST',
                    url: 'update_place_order.php',
                    data: { itemId: itemId, isChecked: isChecked }, // Include the isChecked value
                    success: function (response) {
                        // alert(response); // Display a success message
                        // Reload the page after the AJAX request is complete
                        location.reload();
                    },
                    error: function (xhr, status, error) {
                        console.error(error); // Handle errors
                    }
                });
            });
        });

         // SweetAlert delete confirmation
         $('input[name="delete"]').on('click', function (event) {
            event.preventDefault(); // Prevent the default form submission
            var form = $(this).closest('form'); // Get the form element that contains the delete button
            var cartId = form.find('input[name="cart_id"]').val(); // Get the cart item ID
            
            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to delete this item from your cart?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, keep it'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send the request to delete the item from the cart
                    $.post('cart.php', { delete: true, cart_id: cartId }, function(response) {
                        Swal.fire(
                            'Deleted!',
                            'Your item has been deleted.',
                            'success'
                        ).then(() => {
                            location.reload(); // Reload the page after deletion
                        });
                    });
                }
            });
        });
    </script>

<script src="js/script.js"></script>

</body>
</html>