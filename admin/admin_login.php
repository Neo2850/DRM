<?php

include '../components/connect.php';

session_start();

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $pass = md5($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   $select_admin = $conn->prepare("SELECT * FROM `admins` WHERE name = ? AND password = ?");
   $select_admin->execute([$name, $pass]);
   $row = $select_admin->fetch(PDO::FETCH_ASSOC);

   if($select_admin->rowCount() > 0){
      $_SESSION['admin_id'] = $row['id'];
      header('location:dashboard.php');
   }else{
      $message[] = 'incorrect username or password!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Login</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <style>
      * {
         margin: 0;
         padding: 0;
         box-sizing: border-box;
         font-family: sans-serif;
      }

      body {
         width: 100%;
         height: 100vh;
         background: url(../img/background.png);
         background-position: center center;
         background-repeat: no-repeat;
         background-size: cover;
         display: flex;
         justify-content: center;
         align-items: center;
      }

      .bg-overlay {
         position: fixed;
         top: 0;
         left: 0;
         width: 100%;
         height: 100vh;
         z-index: -1;
      }

      header {
         position: absolute;
         top: 0;
         left: 0;
         width: 100%;
         height: 70px;
         background: rgba(0, 0, 0, 0.3);
         display: flex;
         align-items: center;
         padding: 25px;
         box-shadow: 0 2px 3px 0 rgba(0, 0, 0, .2);
         z-index: 999;
      }

      header a {
         text-decoration: none;
         font-size: 1.5rem;
         font-weight: 400;
         color: #f1f1f1;
         transition: ease-in-out 0.3s;
      }

      header a:hover {
         opacity: 0.7;
      }

      p {
         color: #ffcc00;
         font-size: 1.3rem;
         font-weight: bold;
         text-align: center;
         margin-top: 10px;
      }

      section {
         position: relative;
         display: flex;
         justify-content: center;
         align-items: center;
         background: rgba(95, 95, 95, .9);
         padding: 40px;
         border-radius: 20px;
         margin-top: 40px;
         box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
      }

      form {
         padding: 40px;
         display: flex;
         justify-content: center;
         align-items: center;
         flex-direction: column;
         border-radius: 20px;
         margin-top: 40px;
      }

      form h3 {
         font-size: 1.5rem;
         font-weight: bold;
         text-transform: uppercase;
         color: #f1f1f1;
         margin-bottom: 20px;
         text-shadow: 2px 3px 3px rgba(0, 0, 0, .4);
      }

      form input {
         border: none;
         outline: none;
         width: 300px;
         padding: 12px 18px;
         margin-bottom: 13px;
         font-size: 1rem;
         border-radius: 8px;
         text-align: left;
         background: rgba(77, 77, 77, 255);
         color: #ffffff;
         box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
      }

      form input::placeholder {
         color: #ffffff;
      }

      form input[type="submit"] {
         text-align: center;
         text-transform: uppercase;
         background: rgba(236, 110, 12, 255);
         cursor: pointer;
         transition: ease-in-out 0.3s;
         margin-bottom: 20px;
         color: #ffffff;
         box-shadow: 0 3px 5px 0px rgba(0, 0, 0, .3);
      }

      form input[type="submit"]:hover {
         opacity: 0.7;
         transform: scale(0.98);
      }

      form p {
         color: #f1f1f1;
         font-size: 1rem;
      }

      form a {
   text-decoration: none;
   font-size: 1rem;
   text-transform: uppercase;
   padding: 14px;
   margin-top: 5px;
   border: 1px solid white;
   border-radius: 24px;
   color: #f1f1f1;
   transition: ease-in-out 0.3s;
   text-shadow: 2px 3px 3px rgba(0, 0, 0, .4);
}
form a:hover {
   background: #282828;
}
      .message {
         position: fixed;
         bottom: 10em;
         z-index: 100;
         padding: 8px 18px;
         background: #282828;
         color: #fff;
         border-radius: 8px;
         display: flex;
         justify-content: center;
         align-items: center;
         text-transform: uppercase;
         font-size: .8em;
         user-select: none;
         pointer-events: none;
      }
   </style>
</head>
<body>

<?php
   if(isset($message)){
      foreach($message as $message){
         echo '
         <div class="message">
            <span>'.$message.'</span>
         </div>
         ';
      }
   }
?>

<div class="bg-overlay"></div>
<header>
   <a href="../index.php" class="logo"><img src="../img/logo_1.png" style="width: 55px; height: 55px; margin-right: 60px; float: right;" alt=""></a>
   <p>DRM: ADMIN PANEL</p>
</header>

<section class="form-container">
   <form action="" method="post">
      <h3>Admin Login</h3>
      <input type="text" name="name" required placeholder="Username" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="pass" required placeholder="Password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="LOGIN" class="btn" name="submit">
      <!-- <p>Don't have an account?</p> -->
      <!-- <a href="admin_register.php" class="option-btn">register now</a> -->
      <a href="../index.php" class="option-btn" style="margin-top: 10px;">Customer's Homepage</a>
   </form>
</section>
   
</body>
</html>
