<?php 
    $prd_id = $_GET['id'];
    $prd_name = $_GET['name'];
    $prd_price= $_GET['price'];
    $prd_image= $_GET['image'];
    $disc_qnty= $_GET['disc_qnty'];
    $disc_prc= $_GET['disc_price'];

?>

<?php 
include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
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
<form name="discount-form" action="insert_discount.php" method="post">
    <br><br>
    <div class="box" style="border-color: #dedede; border-style: solid; width: 400px;"><br>
    <img src="../uploaded_img/<?= $prd_image; ?>" alt="" style="width: 280px; height: 240px;">
    <div class="name"><?= $prd_name; ?></div>
    <div class="price">₱<span><?= $prd_price ?></span></div><br>
    <input type="hidden" name="prd_name" value="<?= $prd_name; ?>">
    <input type="hidden" name="id_prd" value="<?= $prd_id; ?>">
    <span>&nbsp &nbsp Quantity: (>=)</span>&nbsp &nbsp &nbsp <input type="number" name="qnty" value="<?= $disc_qnty; ?>" style="border-style: solid; border-color: #bcbcbc; font-size: 16px;"><br>
    <span>Discounted Price:</span> &nbsp <input type="number" name="discount_price" value="<?= $disc_prc; ?>" style="border-style: solid; border-color: #bcbcbc; font-size: 16px; margin-top: 5px;">
    <br><br>
    <input type="submit" value="Update Discount" style="cursor: pointer; background-color: green; padding: 10px; color: white; border-radius: 5px; width: 87%; font-size: 16px;">
    <br><br><br>
    </div>
</form>
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