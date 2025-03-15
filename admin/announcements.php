<?php
include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
}

// Handle add announcement
if (isset($_POST['add_announcement'])) {
   $announcement_title = $_POST['announcement_title'];
   $announcement_content = $_POST['announcement_content'];

   $add_announcement = $conn->prepare("INSERT INTO `announcements_tb` (title, content) VALUES (?, ?)");
   $add_announcement->execute([$announcement_title, $announcement_content]);

   if ($add_announcement) {
      $message[] = 'Announcement added successfully';
   } else {
      $message[] = 'Failed to add announcement';
   }
}

// Handle edit announcement
if (isset($_POST['edit_announcement'])) {
   $announcement_id = $_POST['announcement_id'];
   $announcement_title = $_POST['announcement_title'];
   $announcement_content = $_POST['announcement_content'];

   $update_announcement = $conn->prepare("UPDATE `announcements_tb` SET title = ?, content = ? WHERE id = ?");
   $update_announcement->execute([$announcement_title, $announcement_content, $announcement_id]);

   if ($update_announcement) {
      $message[] = 'Announcement updated successfully';
   } else {
      $message[] = 'Failed to update announcement';
   }
}

// Handle delete announcement
if (isset($_POST['delete_announcement'])) {
   $announcement_id = $_POST['announcement_id'];

   $delete_announcement = $conn->prepare("DELETE FROM `announcements_tb` WHERE id = ?");
   $delete_announcement->execute([$announcement_id]);

   if ($delete_announcement) {
      $message[] = 'Announcement deleted successfully';
   } else {
      $message[] = 'Failed to delete announcement';
   }
}

// Fetch all announcements
$select_announcements = $conn->prepare("SELECT * FROM `announcements_tb`");
$select_announcements->execute();
$announcements = $select_announcements->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Announcements Management</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">

   <style>
      /* Initially hide the form */
      #announcementForm {
         display: none;
      }
   </style>
</head>
<body style="position: relative; padding: 0 0 0 280px; background: url(../img/background.png) no-repeat center; background-size: cover;">

<div style="position: absolute; top: 0; left: 0; width: 100%; height: 100vh; background: #282828; z-index: -1; opacity: 0.6;"></div>

<?php include '../components/admin_header.php'; ?>
<?php include '../components/nav.php'; ?>

<section class="announcement-management">

   <h1 class="heading" style="margin-top: 90px; color: white;">Announcements Management</h1>

   <!-- Button to toggle the form -->
   <button type="button" class="btn btn-success" style="margin-bottom: 20px; width:auto;" onclick="toggleForm()">Add Announcement</button>

   <!-- Add Announcement Form (Initially Hidden) -->
   <form action="" method="post" id="announcementForm" style="max-width: 1500px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px;">
      <div class="inputBox">
         <span style="font-size: 18px; font-weight: bold;">Title :</span>
         <input type="text" name="announcement_title" class="box" style="background-color: #f2f2f2; font-size: 18px; width: 100%; padding: 10px;" required>
      </div><br>
      
      <div class="inputBox">
         <span style="font-size: 18px; font-weight: bold;">Content :</span>
         <textarea style="font-size: 18px; background-color: #f2f2f2; padding: 10px; width:100%; height: 150px;" name="announcement_content" class="box" required></textarea>
      </div>

      <input type="submit" name="add_announcement" value="Add Announcement" class="btn">
   </form>

   <div class="announcement-list" style="max-width: 1500px; margin: 50px auto; background: white; padding: 20px; border-radius: 10px;">
      <h2 style="text-align: center;">CREATED ANNOUNCEMENTS</h2><br>

      <table class="table table-striped table-bordered">
         <thead>
            <tr>
               <th style="width: 30%; font-size: 18px">Title</th>
               <th style="width: 50%; font-size: 18px">Content</th>
               <th style="width: 20%; font-size: 18px">Actions</th>
            </tr>
         </thead>
         <tbody>
            <?php foreach ($announcements as $announcement) { ?>
               <tr>
                  <td style="font-size: 15px"><?= $announcement['title']; ?></td>
                  <td style="font-size: 15px"><?= $announcement['content']; ?></td>
                  <td>
                     <!-- Edit Button -->
                     <button class="btn btn-primary" style="background-color: green; border-color: green; font-size: 13px;" data-toggle="modal" data-target="#editModal<?= $announcement['id']; ?>">Edit</button>
                  </td> 
                  <td>  
                     <!-- Delete Form -->
                     <form action="" method="post" style="display:inline-block;">
                        <input type="hidden" name="announcement_id" value="<?= $announcement['id']; ?>">
                        <button type="submit" style="background-color: red; border-color: red; width: 150px; font-size: 13px;" name="delete_announcement" class="btn btn-danger">Delete</button>
                     </form>
                  </td>
               </tr>

               <!-- Edit Modal -->
               <div class="modal fade" id="editModal<?= $announcement['id']; ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                     <div class="modal-content">
                        <div class="modal-header">
                           <h5 class="modal-title" id="editModalLabel" style="font-size: 18px;">Edit Announcement</h5>
                           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                           </button>
                        </div>
                        <form action="" method="post">
                           <div class="modal-body">
                              <input type="hidden" name="announcement_id" value="<?= $announcement['id']; ?>">
                              <div class="form-group">
                                 <label style="font-size: 18px;">Title</label>
                                 <input style="font-size: 18px;" type="text" name="announcement_title" class="form-control" value="<?= $announcement['title']; ?>" required>
                              </div>
                              <div class="form-group">
                                 <label style="font-size: 18px;">Content</label>
                                 <textarea style="font-size: 18px;" name="announcement_content" class="form-control" rows="4" required><?= $announcement['content']; ?></textarea>
                              </div>
                           </div>
                           <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                              <button type="submit" name="edit_announcement" class="btn btn-primary">Save changes</button>
                           </div>
                        </form>
                     </div>
                  </div>
               </div>
            <?php } ?>
         </tbody>
      </table>
   </div>

</section>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
   function toggleForm() {
      var form = document.getElementById("announcementForm");
      if (form.style.display === "none" || form.style.display === "") {
         form.style.display = "block";
      } else {
         form.style.display = "none";
      }
   }
</script>

<script src="../js/admin_script.js"></script>
   
</body>
</html>
