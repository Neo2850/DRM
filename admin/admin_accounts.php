<?php
include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
}

if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];
   $delete_admins = $conn->prepare("DELETE FROM `admins` WHERE id = ?");
   $delete_admins->execute([$delete_id]);
   header('location:admin_accounts.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   $id = $_POST['id'];
   $name = $_POST['name'];
   $password = $_POST['password'];

   // Hash the password
   $hashed_password = md5($password);

   // Update query
   $update_admins = $conn->prepare("UPDATE `admins` SET name = ?, password = ? WHERE id = ?");
   $update_admins->execute([$name, $hashed_password, $id]);

   header('Location: admin_accounts.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Staff Accounts</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">

   <style>
      .modal-content {
         padding: 20px;
         border-radius: 8px;
      }

      .modal-header {
         border-bottom: none;
      }

      .modal-footer {
         border-top: none;
      }

      .update-btn {
         background-color: #007bff;
         color: white;
         border: none;
         border-radius: 5px;
         padding: 8px 16px;
         cursor: pointer;
      }

      .update-btn:hover {
         background-color: #0056b3;
      }

      .delete-btn {
         background-color: #dc3545;
         color: white;
         border: none;
         border-radius: 5px;
         padding: 8px 16px;
         cursor: pointer;
      }

      .delete-btn:hover {
         background-color: #c82333;
      }

      .box-container {
         margin-top: 20px;
      }

      /* Style for the table */
      .table th, .table td {
         font-size: 16px;
         padding: 12px 15px;
      }

      .table-striped tbody tr:nth-child(odd) {
         background-color: #f8f9fa;
      }

      .table-striped tbody tr:nth-child(even) {
         background-color: #e9ecef;
      }

      tr {
         background-color: white;
         font-size: 14px;
      }
   </style>
</head>
<body style="padding: 70px; padding-left: 300px; background: url(../img/background.png) no-repeat center; background-size: cover;">

<?php include '../components/admin_header.php'; ?>
<?php include '../components/nav.php'; ?>

<div class="container mt-5" style="font-size: 18px;">

   <h1 class="mb-4" style="background-color: gray; padding: 10px; color: white; text-align: center;">Staff Accounts</h1>

   <div class="mb-3">
      <a href="register_admin.php" class="btn btn-primary" style="width: 300px; background-color: #00b359; border-color: #00b359;">Add New Staff</a>
   </div>

   <div class="row">
      <?php
         $select_accounts = $conn->prepare("SELECT * FROM `admins` WHERE id!='1'");
         $select_accounts->execute();
         if ($select_accounts->rowCount() > 0) {
            echo '<table class="table table-striped">';
            echo '<thead><tr><th>ID</th><th>Username</th><th>Actions</th></tr></thead><tbody>';
            while ($fetch_accounts = $select_accounts->fetch(PDO::FETCH_ASSOC)) {
               echo '<tr>';
               echo '<td>' . $fetch_accounts['id'] . '</td>';
               echo '<td>' . $fetch_accounts['name'] . '</td>';
               echo '<td>
                        <a href="admin_accounts.php?delete=' . $fetch_accounts['id'] . '" onclick="return confirm(\'Delete this account?\')" class="btn delete-btn">Delete</a>
                        <button class="btn update-btn" onclick="openModal(' . $fetch_accounts['id'] . ', \'' . $fetch_accounts['name'] . '\')">Update</button>
                     </td>';
               echo '</tr>';
            }
            echo '</tbody></table>';
         } else {
            echo '<p class="empty">No accounts available!</p>';
         }
      ?>
   </div>

</div>

<!-- Update Modal -->
<div id="updateModal" class="modal fade" tabindex="-1" role="dialog" style="font-size: 18px;">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h3 class="modal-title">Update Account</h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <form id="updateForm" method="POST">
            <div class="modal-body">
               <input type="hidden" id="adminId" name="id">
               <div class="form-group">
                  <label for="username">Username:</label>
                  <input type="text" style="font-size: 16px;" class="form-control" id="username" name="name" required>
               </div>
               <div class="form-group">
                  <label for="password">Password:</label>
                  <input type="password" style="font-size: 16px;" class="form-control" id="password" name="password" required>
               </div>
            </div>
            <div class="modal-footer">
               <button type="submit" class="btn btn-primary">Save Changes</button>
               <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
         </form>
      </div>
   </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
   // Function to open the modal
   function openModal(id, name) {
      document.getElementById('adminId').value = id;
      document.getElementById('username').value = name;
      $('#updateModal').modal('show');
   }
</script>

</body>
</html>
