<?php 
include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

// Check if the form was submitted
if (isset($_POST["submit"])) {
    // Check if a file was uploaded without errors
    if ($_FILES["image"]["error"] == 0) {
        $uploadDir = "../gcash_code/"; // Specify the directory where you want to save the uploaded images
        $uploadFile = $uploadDir . basename($_FILES["image"]["name"]);
        $imageFileName = basename($_FILES["image"]["name"]);

        // Check file type (you can add more types if needed)
        $allowedTypes = array("jpg", "jpeg", "png", "gif");
        $fileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));

        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $uploadFile)) {
                $update_image = $conn->prepare("UPDATE `admins` SET gcash_qr = ? WHERE id = ?");
                $update_image->execute([ $imageFileName, 1]);
            } else {
                echo "Error uploading file.";
            }
        } else {
            echo "Invalid file type. Allowed types: jpg, jpeg, png, gif.";
        }
    } else {
        echo "Error: " . $_FILES["image"]["error"];
    }
}

// Check if the form was submitted
if (isset($_POST["accnt_name"])) {
    $accnt_name = $_POST['accnt_name'];
    $gcash_num = $_POST['gcash_num'];

    $update_info = $conn->prepare("UPDATE `admins` SET account_name = ?, gcash_number = ? WHERE id = ?");
    $update_info->execute([$accnt_name, $gcash_num, 1]);

    echo "<script>alert('Your gcash information was updated successfully!');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Sales Report</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
   <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.2/css/buttons.dataTables.min.css">

</head>
<body style="
   padding: 70px 0 0 180px;
   font-size: 16px;
">

<?php include '../components/admin_header.php'; ?>
<?php include '../components/nav.php'; ?>
<center>
    <?php
        $select_admin = $conn->prepare("SELECT * FROM `admins` WHERE id = ?");
        $select_admin->execute([1]);
        $row = $select_admin->fetch(PDO::FETCH_ASSOC);

        if($select_admin->rowCount() > 0){
            $gcash_qr = $row['gcash_qr'];
            $account_name = $row['account_name'];
            $gcash_number = $row['gcash_number'];
        }
    ?>
    <br>        <h1 style="background-color: gray; padding: 10px; color: white; text-align: center; width: 1080px;
    margin-bottom: 20px;">GCASH QR CODE</h1>
    <div style="background-color: #565656a8; margin-left: 100px; margin-right: 100px; border-radius: 10px; width: 80%; padding: 20px;">

        <br>
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div style="flex: 0 0 40%;">
                <img src="../gcash_code/<?php echo $gcash_qr; ?>" style="width: 100%; height: auto;" alt="">
            </div>
            <div style="flex: 1; padding-left: 20px;">
                <form action="" name="form-image" method="post" enctype="multipart/form-data">
                    <input type="file" name="image" id="image" style="font-size: 16px; margin-top: 10px;" required><br>
                    <input type="submit" value="Update Image" name="submit" style="width: 100%; padding: 10px; border-radius: 3px; background-color: orange; color: white; font-size: 14px; margin-top: 10px;">
                </form>

                <form action="" name="form-info" method="post" enctype="multipart/form-data">
                    <br>
                    <span style="font-size: 14px; color: white; font-weight: normal;">Account Name: &nbsp &nbsp</span>
                    <input name="accnt_name" type="text" style="font-weight: bold; font-size: 16px; padding: 2px;" value="<?php echo $account_name; ?>" required><br>
                    <span style="font-size: 14px; color: white; font-weight: normal;">Gcash Number:  &nbsp &nbsp</span>
                    <input name="gcash_num" type="text" style="font-weight: bold; font-size: 16px; padding: 2px; margin-top: 10px;" value="<?php echo $gcash_number; ?>" required><br>
                    <input type="submit" value="Update Info" name="submit_info" style="width: 100%; margin-top: 10px; padding: 10px; border-radius: 3px; background-color: #24A0AD; color: white; font-weight: ;">
                </form>
            </div>
        </div>
        <br><br>
    </div>
</center>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap4.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.print.min.js"></script>

    <!-- <script>
    $(document).ready( function () {
        $('#myTable').DataTable();
    } );
    </script> -->

    <script>
        $(document).ready(function() {
    $('#myTable').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'print'
        ]
    } );
} );
    </script>

<script src="../js/admin_script.js"></script>
   
</body>
</html>