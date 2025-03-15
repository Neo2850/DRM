<?php
   if(isset($message)){
      foreach($message as $message){
         echo '
         <div class="message">
            <span>'.$message.'</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
         </div>
         ';
      }
   }
?>

<style>
   .svg-inline--fa {
    display: var(--fa-display, inline-block);
    height: 1em;
    overflow: visible;
    vertical-align: -.125em;
    font-size: 24px;
}
</style>

<header class="header" style="position: fixed; background-color: #333333;">
<a href="../admin/dashboard.php" class="logo"><img src="../img/logo_1.png" style="width: 70px; height: 70px; margin-left: 30px; float: left;" alt=""></a>
   <section class="flex" style="height: 80px;">
   <center><span style="font-size: 20px; font-weight: bold; margin-left: -10px; color: white;">ADMIN DASHBOARD</span></center>
      <!-- <nav class="navbar">
         <a href="../admin/dashboard.php">home</a>
         <a href="../admin/products.php">products</a>
         <a href="../admin/placed_orders.php">orders</a>
         <a href="../admin/admin_accounts.php">admins</a>
         <a href="../admin/users_accounts.php">users</a>
         <a href="../admin/messages.php">messages</a>
      </nav> -->

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="user-btn" class="fas fa-user" style="color: white;"></div>
      </div>

      <div class="profile">
         <?php
            $select_profile = $conn->prepare("SELECT * FROM `admins` WHERE id = ?");
            $select_profile->execute([$admin_id]);
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <p><?= $fetch_profile['name']; ?></p>
         <a href="../admin/update_profile.php" class="btn">update profile</a>
         <!-- <div class="flex-btn">
            <a href="../admin/register_admin.php" class="option-btn">register</a>
            <a href="../admin/admin_login.php" class="option-btn">login</a>
         </div> -->
         <a href="../components/admin_logout.php" class="delete-btn" onclick="return confirm('logout from the website?');">logout</a> 
      </div>

   </section>

</header>