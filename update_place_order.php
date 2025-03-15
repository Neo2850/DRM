<?php
include 'components/connect.php';
session_start();
if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:user_login.php');
};

// Check if the request was sent via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $isChecked = $_POST['isChecked'];
    $itemId = $_POST['itemId'];
    
    try {
        if ($isChecked == 1) {
            // Update cart item status to checked (selected)
            $update_cart = $conn->prepare("UPDATE `cart` SET status = ? WHERE user_id = ? AND pid = ?");
            $update_cart->execute([1, $user_id, $itemId]);

            // Show success SweetAlert
            echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Item Updated',
                        text: 'Item has been added to your cart!'
                    });
                  </script>";
        } else {
            // Update cart item status to unchecked (deselected)
            $update_cart = $conn->prepare("UPDATE `cart` SET status = ? WHERE user_id = ? AND pid = ?");
            $update_cart->execute([0, $user_id, $itemId]);

            // Show success SweetAlert
            echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Item Removed',
                        text: 'Item has been removed from your cart!'
                    });
                  </script>";
        }
    } catch (Exception $e) {
        // Show error SweetAlert in case of any issue
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'There was an issue updating your cart. Please try again.'
                });
              </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Cart Update</title>

   <!-- SweetAlert2 CDN -->
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<!-- Your content goes here -->

</body>
</html>
