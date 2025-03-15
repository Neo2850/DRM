<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   
   // First, delete the related records from all dependent tables
   $delete_orders = $conn->prepare("DELETE FROM `orders` WHERE user_id = ?");
   $delete_orders->execute([$delete_id]);
   
   $delete_messages = $conn->prepare("DELETE FROM `messages` WHERE user_id = ?");
   $delete_messages->execute([$delete_id]);
   
   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
   $delete_cart->execute([$delete_id]);
   
   // $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE user_id = ?");
   // $delete_wishlist->execute([$delete_id]);
   
   // Then delete the user from the `users` table
   $delete_user = $conn->prepare("DELETE FROM `users` WHERE id = ?");
   $delete_user->execute([$delete_id]);
   
   // Set session variable to notify success
   $_SESSION['delete_message'] = "User and related data deleted successfully!";
   
   header('location:users_accounts.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>User Accounts</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">

   <!-- SweetAlert CDN -->
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body style="
   position: relative;
   padding: 0 0 0 220px;
   background: url(../img/background.png) no-repeat center;
   background-size: cover;
">

<div style="position: absolute; top: 0; left: 0; width: 100%; height: 100vh; background: #282828; z-index: -1; opacity: 0.6;"></div>

<style>
   .accounts-table {
   width: 100%;
   max-width: 1500px;
   margin: 0 auto;
   border-collapse: collapse;
}

.accounts-table th, .accounts-table td {
   padding: 15px;
   text-align: center;
   border: 1px solid #ddd;
   background-color: #fff;
   font-size: 16px;
}

.accounts-table th {
   background-color: #f2f2f2;
   color: #333;
}

.accounts-table tr:nth-child(even) {
   background-color: #f9f9f9;
}

.accounts-table tr:hover {
   background-color: #f1f1f1;
}

.empty {
   text-align: center;
   font-size: 18px;
   color: #666;
}
</style>

<?php include '../components/admin_header.php'; ?>
<?php include '../components/nav.php'; ?>

<section class="accounts">
   <h1 class="heading" style="margin-top: 90px; color: white; background-color: gray; padding: 10px; text-align: center;">User Accounts</h1>

   <div class="box-container" style="float: inline-start;">
      <table class="accounts-table" style="width: 360%; max-width:1500%;">
         <thead>
            <tr>
               <th>User ID</th>
               <th>Username</th>
               <th>Email</th>
               <th>Actions</th>
            </tr>
         </thead>
         <tbody>
         <?php
            $select_accounts = $conn->prepare("SELECT * FROM `users`");
            $select_accounts->execute();
            if($select_accounts->rowCount() > 0){
               while($fetch_accounts = $select_accounts->fetch(PDO::FETCH_ASSOC)){
         ?>
            <tr>
               <td><?= $fetch_accounts['id']; ?></td>
               <td><?= $fetch_accounts['name']; ?></td>
               <td><?= $fetch_accounts['email']; ?></td>
               <td>
                  <a href="javascript:void(0);" onclick="deleteUser(<?= $fetch_accounts['id']; ?>)" class="delete-btn">Delete</a>
               </td>
            </tr>
         <?php
               }
            }else{
               echo '<tr><td colspan="4" class="empty">No accounts available!</td></tr>';
            }
         ?>
         </tbody>
      </table>
   </div>
</section>

<script src="../js/admin_script.js"></script>

<script>
   function deleteUser(userId) {
      // SweetAlert2 popup for confirmation
      Swal.fire({
         title: 'Are you sure?',
         text: "This will delete the user and all related data!",
         icon: 'warning',
         showCancelButton: true,
         confirmButtonColor: '#3085d6',
         cancelButtonColor: '#d33',
         confirmButtonText: 'Yes, delete it!',
      }).then((result) => {
         if (result.isConfirmed) {
            // Redirect to delete the user if confirmed
            window.location.href = 'users_accounts.php?delete=' + userId;
         }
      })
   }

   // Check if there is a delete message in session
   <?php if(isset($_SESSION['delete_message'])): ?>
      Swal.fire({
         title: 'Success!',
         text: '<?php echo $_SESSION['delete_message']; ?>',
         icon: 'success',
         confirmButtonColor: '#3085d6'
      });
      <?php unset($_SESSION['delete_message']); ?>
   <?php endif; ?>
</script>

</body>
</html>
