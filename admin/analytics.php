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
<iframe src="chart.php" style="width: 100%; height: 600px;"></iframe>
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