<?php
ini_set('display_errors', 0);
include 'components/connect.php'; 

   if(isset($feedbackMessage)){
      foreach($feedbackMessage as $feedbackMessage){
         echo '
         <div class="message">
            <span>'.$feedbackMessage.'</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
         </div>
         ';
      }
   }
session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
};

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $user_id = $_SESSION['user_id'];
    $email = $_POST['email'];
    $rating = $_POST['rating'];
    $message = $_POST['message'];

    // Insert feedback into database
    $query = "INSERT INTO feedback (name, user_id, email, rating, message) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$name, $user_id, $email, $rating, $message]);
    $feedbackMessage = "Thank you for your feedback!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Feedback</title>

   <!-- font awesome cdn link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css -->
	   <!-- custom css file link  -->
	   <link rel="stylesheet" href="css/style.css">
   <style>


      .container {
         max-width: 600px;
         margin: 0 auto;
         background: #fff;
         padding: 20px;
         border-radius: 8px;
         box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      }

      h2 {
         margin-bottom: 20px;
         text-align: center;
         color: #333;
      }

      .rating {
         direction: rtl;
         unicode-bidi: bidi-override;
         display: inline-block;
         font-size: 2em;
         color: #ddd;
         cursor: pointer;
         text-align: center;
         margin-bottom: 20px;
      }

      .rating input[type="radio"] {
         display: none;
      }

      .rating label {
         display: inline-block;
         padding: 0 0.1em;
         color: #ddd;
      }

      .rating label:hover,
      .rating label:hover ~ label,
      .rating input[type="radio"]:checked ~ label {
         color: #ffc107;
      }

      label {
         display: block;
         margin-bottom: 5px;
         font-weight: bold;
      }

      input[type="text"],
      input[type="email"],
      textarea {
         width: 100%;
         padding: 10px;
         margin-bottom: 15px;
         border: 1px solid #ccc;
         border-radius: 4px;
         font-size: 1em;
      }

      textarea {
         resize: none;
         height: 100px;
      }

      .btn {
         display: block;
         width: 100%;
         background: #007bff;
         color: #fff;
         border: none;
         padding: 10px;
         font-size: 1.2em;
         border-radius: 4px;
         cursor: pointer;
      }

      .btn:hover {
         background: #0056b3;
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

<section class="feedback-form">
   <div class="container">
      <h2>We Value Your Feedback</h2>

      <?php if (isset($feedbackMessage)): ?>
         <p class="success-message"><?= $feedbackMessage ?></p>
      <?php endif; ?>

      <form action="" method="POST">
         <label for="name">Full Name</label>
         <input type="text" name="name" id="name" placeholder="Enter your name" value="<?= htmlspecialchars($name ?? '') ?>" required>

         <label for="email">Email</label>
         <input type="email" name="email" id="email" placeholder="Enter your email" required>

         <label for="rating">Rating</label>
         <div class="rating">
            <input type="radio" id="star5" name="rating" value="5"><label for="star5" title="5 stars">&#9733;</label>
            <input type="radio" id="star4" name="rating" value="4"><label for="star4" title="4 stars">&#9733;</label>
            <input type="radio" id="star3" name="rating" value="3"><label for="star3" title="3 stars">&#9733;</label>
            <input type="radio" id="star2" name="rating" value="2"><label for="star2" title="2 stars">&#9733;</label>
            <input type="radio" id="star1" name="rating" value="1"><label for="star1" title="1 star">&#9733;</label>
         </div>

         <label for="message">Message</label>
         <textarea name="message" id="message" rows="5" placeholder="Enter your feedback" required></textarea>

         <button type="submit" class="btn">Submit Feedback</button>
      </form>
   </div>
</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
