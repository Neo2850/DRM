<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['submit'])){
   $caloocan = $_POST['caloocan'];
   $barangay = $_POST['barangay'];
   $blk_lot = $_POST['blk_lot'];
   $postal_code = $_POST['postal_code'];
   
   // Sanitize the input data
   $caloocan = filter_var($caloocan, FILTER_SANITIZE_STRING);
   $barangay = filter_var($barangay, FILTER_SANITIZE_STRING);
   $blk_lot = filter_var($blk_lot, FILTER_SANITIZE_STRING);
   $postal_code = filter_var($postal_code, FILTER_SANITIZE_NUMBER_INT);

   // Construct address as a one-liner
   $address = $caloocan . ', ' . $barangay . ' - ' . $blk_lot . ' ZIP ' . $postal_code;

   // Insert data into the database
   $insert_order_details = $conn->prepare("INSERT INTO `address`(user_id, `address`) VALUES(?, ?)");
   $insert_order_details->execute([$user_id, $address]);

   $_SESSION['address_added'] = "Your delivery address has been added successfully.";

   // Redirect to index.php after setting the message
   header("Location: index.php");
   exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Add Delivery Address</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

   <script>
      // Function to show barangay dropdown based on Caloocan selection
      function showBarangays() {
         var caloocan = document.getElementById("caloocan").value;
         var barangaySelect = document.getElementById("barangay");
         
         // Clear current barangay options
         barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
         
         var barangays = [];
         if(caloocan == "North Caloocan") {
            barangays = ["Bagumbong", "Camarin", "Deparo", "Kaligayahan", "Longos", "Minuyan", "Pag-asa", "Talipapa"];
         } else if(caloocan == "South Caloocan") {
            barangays = ["Bagong Silang", "Baño", "Balintawak", "Balingasa", "Camarin", "Daang Bakal", "Dulong Bayan", "Grace Park", "Kaingin", "Loma de Gato", "Maligaya", "Pag-asa", "Sampaloc", "San Isidro", "San Jose", "San Juan", "San Juan de Dios", "San Pedro", "San Rafael", "Sauyo", "Tandang Sora"];
         }

         // Add the barangays to the dropdown
         for(var i = 0; i < barangays.length; i++) {
            var option = document.createElement("option");
            option.value = barangays[i];
            option.text = barangays[i];
            barangaySelect.appendChild(option);
         }
      }
   </script>
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="form-container">
   <form action="" method="post">
      <h3>Add Delivery Address</h3>
      
      <!-- Caloocan Selection -->
      <select name="caloocan" id="caloocan" class="box" onchange="showBarangays()">
         <option value="">Select Caloocan Area</option>
         <option value="North Caloocan">North Caloocan</option>
         <option value="South Caloocan">South Caloocan</option>
      </select>
      
      <!-- Barangay Dropdown (Initially Empty) -->
      <select name="barangay" id="barangay" class="box">
         <option value="">Select Barangay</option>
      </select>
      
      <!-- Blk/Lot -->
      <input type="text" name="blk_lot" placeholder="Blk/Lot" class="box">

       <!-- Postal Code -->
       <input type="number" name="postal_code" placeholder="Postal Code" class="box">
      
      <!-- Submit Button -->
      <input type="submit" value="Submit" class="btn" name="submit" style="background: #239ae0;">
   </form>
</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>
</body>
</html>
