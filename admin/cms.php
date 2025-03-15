<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

// Fetch current data from `cms_tb`
$select_cms = $conn->prepare("SELECT * FROM `cms_tb` LIMIT 1");
$select_cms->execute();
$cms_data = $select_cms->fetch(PDO::FETCH_ASSOC);

$home_details = '';
$about_details = '';

if($cms_data) {
   $home_details = $cms_data['home_details'];
   $about_details = $cms_data['about_details'];
}

// Handle update form submission
if(isset($_POST['update'])){
   $home_details = $_POST['home_details'];
   $about_details = $_POST['about_details'];

   // Update `cms_tb` with new data
   $update_cms = $conn->prepare("UPDATE `cms_tb` SET home_details = ?, about_details = ? WHERE id = ?");
   $update_cms->execute([$home_details, $about_details, $cms_data['id']]);

   if($update_cms){
      $message[] = 'Content updated successfully';
   }else{
      $message[] = 'Failed to update content';
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Content Management</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body style="
   position: relative;
   padding: 0 0 0 280px;
   background: url(../img/background.png) no-repeat center;
   background-size: cover;
">

<div style="
   position: absolute;
   top: 0;left: 0;
   width: 100%;
   height: 100vh;
   background: #282828;
   z-index: -1;
   opacity: 0.6;
"></div>

<?php include '../components/admin_header.php'; ?>
<?php include '../components/nav.php'; ?>

<section class="cms-management">

   <h1 class="heading" style="margin-top: 90px; color: white;">Content Management</h1>

   <form action="" method="post" style="max-width: 1500px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px;">
      
      <div class="inputBox">
         <span style="font-size: 18px; font-weight: bold;">Home Details :</span>
         <textarea style="font-size: 18px; background-color: #f2f2f2; padding: 10px; width:100%;" name="home_details" class="box" required><?= $home_details; ?></textarea>
      </div>
      
      <div class="inputBox">
         <span style="font-size: 18px; font-weight: bold;">About Details :</span>
         <textarea style="font-size: 18px; background-color: #f2f2f2; padding: 10px; width:100%; height: 200px;" name="about_details" class="box" required><?= $about_details; ?></textarea>
      </div>

      <input type="submit" name="update" value="Update Content" class="btn">

   </form>

</section>

<script src="../js/admin_script.js"></script>
   
</body>
</html>
