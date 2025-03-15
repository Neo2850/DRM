<?php
include 'components/connect.php';  // Ensure the connection is correctly set

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
};

if (isset($_POST['send'])) {
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $msg = $_POST['msg'];
   $msg = filter_var($msg, FILTER_SANITIZE_STRING);

   // Check if the message has already been sent
   $select_message = $conn->prepare("SELECT * FROM `messages` WHERE name = ? AND email = ? AND number = ? AND message = ?");
   $select_message->execute([$name, $email, $number, $msg]);

   if ($select_message->rowCount() > 0) {
      $message = 'Message already sent!';
      echo "<script>
         Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Message already sent!'
         });
      </script>";
   } else {
      // Insert new message into the database
      $insert_message = $conn->prepare("INSERT INTO `messages`(user_id, name, email, number, message) VALUES(?,?,?,?,?)");
      $insert_message->execute([$user_id, $name, $email, $number, $msg]);

      $message = 'Message sent successfully!';
      echo "<script>
         Swal.fire({
            icon: 'success',
            title: 'Message Sent',
            text: 'Your message has been sent successfully!'
         });
      </script>";
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Contact Us</title>

   <!-- SweetAlert2 CDN -->
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

   <!-- Font Awesome CDN -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS -->
   <link rel="stylesheet" href="css/style.css">

   <style>
      body {
         margin: 0;
         font-family: Arial, sans-serif;
         background: url(./img/background.png) no-repeat center center/cover;
         color: #f1f1f1;
         position: relative;
      }

      .overlay {
         position: absolute;
         top: 0;
         left: 0;
         width: 100%;
         height: 120vh;
         background: rgba(0, 0, 0, 0.6);
         z-index: -1;
      }

      .contact {
         display: flex;
         justify-content: center;
         align-items: center;
         height: 100vh;
         padding: 20px;
      }

      .contact form {
         background: white;
         padding: 30px;
         border-radius: 10px;
         box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
         width: 100%;
         max-width: 500px;
      }

      .contact form h3 {
         font-size: 2rem;
         margin-bottom: 20px;
         text-align: center;
         color: black;
         text-transform: uppercase;
      }

      .contact form .box {
         width: 100%;
         padding: 10px;
         margin: 10px 0;
         background: white;
         border: 1px solid black;
         border-radius: 5px;
         color: black;
         font-size: 1rem;
      }

      .contact form .btn {
         display: block;
         width: 100%;
         padding: 10px;
         margin-top: 20px;
         background: red;
         color: #fff;
         border: none;
         border-radius: 5px;
         font-size: 1rem;
         text-transform: uppercase;
         cursor: pointer;
         transition: 0.3s;
      }

      .contact form .btn:hover {
         background: #cc0000;
      }

      .success-message {
         color: green;
         font-weight: bold;
         text-align: center;
         margin-bottom: 20px;
      }
   </style>
</head>
<body>

<?php include 'components/user_header.php'; ?>
<div class="overlay"></div>

<section class="contact">
   <form action="" method="post">
      <h3>Get In Touch</h3>

      <?php if (isset($message)): ?>
         <p class="success-message"><?= $message ?></p>
      <?php endif; ?>

      <input type="text" name="name" placeholder="Full Name" required maxlength="30" class="box">
      <input type="email" name="email" placeholder="Email Address" required maxlength="50" class="box">
      <input type="number" name="number" placeholder="Phone Number" required maxlength="11" class="box" onkeypress="if(this.value.length == 11) return false;">
      <textarea name="msg" placeholder="Your Message" rows="5" required class="box"></textarea>
      <input type="submit" value="Send Message" name="send" class="btn">
   </form>
</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
