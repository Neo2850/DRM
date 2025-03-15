<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

// Handle form submission
if(isset($_POST['submit'])){
   $filename = $_POST['filename'];
   $description = $_POST['description'];
   $date_upload = date('Y-m-d');
   
   // Handle file upload
   $file = $_FILES['file']['name'];
   $file_tmp_name = $_FILES['file']['tmp_name'];
   $file_folder = '../uploaded_files/'.$file;
   
   if(empty($filename) || empty($description) || empty($file)){
      $message[] = 'Please fill out all fields';
   }else{
      // Insert file details into file_tb
      $insert_file = $conn->prepare("INSERT INTO `file_tb` (filename, description, file, date_upload) VALUES (?, ?, ?, ?)");
      $insert_file->execute([$filename, $description, $file, $date_upload]);

      // Move file to the uploads directory
      if($insert_file){
         move_uploaded_file($file_tmp_name, $file_folder);
         $message[] = 'File uploaded successfully';
      }else{
         $message[] = 'Failed to upload file';
      }
   }
}

// Handle file deletion
if(isset($_GET['delete'])){
    $delete_id = $_GET['delete'];
 
    // Fetch the file details from the database to delete the file from the server
    $select_file = $conn->prepare("SELECT * FROM `file_tb` WHERE id = ?");
    $select_file->execute([$delete_id]);
    $fetch_file = $select_file->fetch(PDO::FETCH_ASSOC);
 
    if($fetch_file){
       // Delete the file from the directory
       $file_path = '../uploaded_files/'.$fetch_file['file'];
       if(file_exists($file_path)){
          unlink($file_path);  // Delete file from the server
       }
 
       // Delete the record from the database
       $delete_file = $conn->prepare("DELETE FROM `file_tb` WHERE id = ?");
       $delete_file->execute([$delete_id]);
 
       header('location: file_management.php'); // Change to your actual page name
    }
 }

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>File Upload</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">

   <style>
      /* Initially hide the file upload form */
      .file-upload-form {
         display: none;
         max-width: 600px;
         margin: 0 auto;
         background: white;
         padding: 20px;
         border-radius: 10px;
         font-size: 18px;
      }
   </style>
</head>
<body style="
   position: relative;
   padding: 0 0 0 280px;
   background: url(../img/background.png) no-repeat center;
   background-size: cover;
">

<div style="position: absolute; top: 0; left: 0; width: 100%; height: 150vh; background: #282828; z-index: -1; opacity: 0.6;"></div>

<?php include '../components/admin_header.php'; ?>
<?php include '../components/nav.php'; ?>

<section class="file-upload">

   <h1 class="heading" style="margin-top: 90px; color: white;"></h1>

   <!-- Button to toggle file upload form visibility -->
   <button onclick="toggleForm()" style="font-size: 18px; padding: 10px 20px; background-color: #4CAF50; color: white; border: none; cursor: pointer; border-radius: 5px;">Upload File</button>

   <!-- The file upload form -->
   <form action="" method="post" enctype="multipart/form-data" class="file-upload-form">
      
      <div class="inputBox">
         <span>File Name :</span>
         <input style="font-size: 18px; background-color: #f2f2f2; padding: 10px; width:100%;" type="text" name="filename" class="box" required>
      </div><hr><br>
      
      <div class="inputBox">
         <span>Description :</span>
         <input style="font-size: 18px; background-color: #f2f2f2; padding: 10px; width:100%;" type="text" name="description" class="box" required>
      </div><hr><br>

      <div class="inputBox">
         <span>Upload File :</span>
         <input style="font-size: 18px; background-color: #f2f2f2; padding: 10px; width:100%;" type="file" name="file" class="box" required>
      </div><hr><br>

      <div class="inputBox">
         <span>Date Upload :</span>
         <input style="font-size: 18px; background-color: #f2f2f2; padding: 10px; width:100%;" type="text" name="date_upload" class="box" value="<?= date('Y-m-d'); ?>" readonly>
      </div>

      <input type="submit" name="submit" value="Upload File" class="btn">

   </form>

</section>

<section class="uploaded-files">

   <h1 class="heading" style="margin-top: 50px; color: white;">Uploaded Files</h1>

   <table style="width: 90%; margin: 20px auto; border-collapse: collapse; background: white; font-size: 18px;">
      <thead>
         <tr style="background-color: #333; color: white;">
            <th style="padding: 10px;">Filename</th>
            <th style="padding: 10px;">Description</th>
            <th style="padding: 10px;">Date Uploaded</th>
            <th style="padding: 10px;">Action</th>
         </tr>
      </thead>
      <tbody>
      <?php
         $select_files = $conn->prepare("SELECT * FROM `file_tb` ORDER BY id DESC");
         $select_files->execute();
         if($select_files->rowCount() > 0){
            while($fetch_files = $select_files->fetch(PDO::FETCH_ASSOC)){
      ?>
         <tr style="text-align: center;">
            <td style="padding: 10px;"><?= $fetch_files['filename']; ?></td>
            <td style="padding: 10px;"><?= $fetch_files['description']; ?></td>
            <td style="padding: 10px;"><?= $fetch_files['date_upload']; ?></td>
            <td style="padding: 10px;">
                <a href="../uploaded_files/<?= $fetch_files['file']; ?>" download="<?= $fetch_files['file']; ?>" class="btn" style="color: white; background-color: #28a745; padding: 5px 10px; text-decoration: none; border-radius: 5px;">Download</a>
                <a href="?delete=<?= $fetch_files['id']; ?>" onclick="return confirm('Are you sure you want to delete this file?')" class="btn" style="color: white; background-color: #dc3545; padding: 5px 10px; text-decoration: none; border-radius: 5px;">Delete</a>
            </td>

         </tr>
      <?php
            }
         }else{
            echo '<tr><td colspan="4" style="padding: 10px; text-align: center;">No files uploaded yet!</td></tr>';
         }
      ?>
      </tbody>
   </table>

</section>

<script>
   // Function to toggle the visibility of the file upload form
   function toggleForm() {
      var form = document.querySelector('.file-upload-form');
      if (form.style.display === 'none' || form.style.display === '') {
         form.style.display = 'block';
      } else {
         form.style.display = 'none';
      }
   }
</script>

<script src="../js/admin_script.js"></script>
   
</body>
</html>
