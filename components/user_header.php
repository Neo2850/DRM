
<style type="text/css">
         nav a {
            text-transform: uppercase;
            font-size: 1.7em !important;
         }
      </style>
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<header class="header" style="height: 80px; background-color: #262626;">
   <section class="flex" style="height: 80px; background-color:;">
   <a href="index.php" class="logo"><img src="img/logo_1.png" style="width: 55px; height: 55px; border-radius: 50%; margin-right: 60px; float: right;" alt=""></a>
      <nav class="navbar">
         <a style="color: white;" href="index.php">home</a>
         <a style="color: white;" href="shop.php">shop</a>
         <a style="color: white;" href="orders.php">orders</a>
         <a style="color: white;" href="about.php">about</a>
         <?php if(isset($user_id) && !empty($user_id)): // Only show if logged in ?>
         <a style="color: white;" href="feedbackform.php">feedback</a>
         <a style="color: white;" href="contact.php">contact</a>
         <?php endif; ?>
      </nav>
      <div class="icons">
         <?php
            //$count_wishlist_items = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ?");
            //$count_wishlist_items->execute([$user_id]);
            //$total_wishlist_counts = $count_wishlist_items->rowCount();
            $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $count_cart_items->execute([$user_id]);
            $total_cart_counts = $count_cart_items->rowCount();
         ?>
         <div id="menu-btn" class="fas fa-bars"></div>
         <!-- <a href="search_page.php"><i class="fas fa-search"></i></a> -->
         <!-- <a href="wishlist.php"><i class="fas fa-heart"></i><span><?= $total_wishlist_counts; ?></span></a> -->
         <a href="cart.php"><i class="fas fa-shopping-basket" style="color: white; margin-right:5px;"></i><span style="color: white;"><?= $total_cart_counts; ?></span></a>
         <div id="user-btn" class="fas fa-user" style="color: white;"></div>
         <!-- Button to trigger page refresh -->
            <!-- <button onclick="refreshPage()"><img src="../img/refresh.jpg" style="width: 24px;" alt=""></button> -->

            <script>
            // JavaScript function to refresh the page
            function refreshPage() {
               // Reload the current page
               location.reload();
            }
            </script>
      </div>
      <div class="profile">
         <?php          
            $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $select_profile->execute([$user_id]);
            if($select_profile->rowCount() > 0){
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <p><?= $fetch_profile["name"]; ?></p>
         <a href="update_user.php" class="btn" style="background:#239ae0">update profile</a>
         <a href="insert_address.php" class="btn" style="background:#239ae0">Add Delivery Address</a>
         <!-- <div class="flex-btn">
            <a href="user_register.php" class="option-btn" style="background: #239ae0;">register</a>
            <a href="user_login.php" class="option-btn" style="background: #239ae0;">login</a>
         </div> -->
         <a href="#" class="delete-btn" id="logout-btn">logout</a> <!-- Logout trigger -->
         <?php
            }else{
         ?>
         <!-- <p>please login or register first!</p> -->
         <div class="flex-btn">
            <a href="user_register.php" class="option-btn" style="background: #239ae0;">register</a>
            <a href="user_login.php" class="option-btn" style="background: #239ae0;">login</a>
         </div>
         <?php
            }
         ?>
      </div>
   </section>
</header>

<script>
// JavaScript to trigger SweetAlert for logout confirmation
document.getElementById('logout-btn').addEventListener('click', function(event) {
    event.preventDefault();  // Prevents the default action (which is to log out immediately)
    
    // Show SweetAlert confirmation
    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to log out of the website?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, log out',
        cancelButtonText: 'Cancel',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Redirect to logout script if confirmed
            window.location.href = 'components/user_logout.php';
        }
    });
});
</script>
