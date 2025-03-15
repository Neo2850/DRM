<?php 
include 'notif_for_stocks.php';
include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_POST['but_search'])){
    $fromDate = $_POST['fromDate'];
    $endDate = $_POST['endDate'];

    $final_from = date('Y-m-d', strtotime($fromDate));
    $final_to= date('Y-m-d', strtotime($endDate));

    $add_qry = "and placed_on between '".$final_from."' and '".$final_to."' ";
}
else{
    $add_qry = "";
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
<style>
    table {
        background: white;
    font-size: 14px;
    }
    button.dt-button:first-child {
        background: #008ae6;
        color: white;
    }

    .dataTables_wrapper .dataTables_paginate {
    float: right;
    text-align: right;
    padding-top: .25em;
    color: black;
    background: white;
}
.dataTables_wrapper .dataTables_filter {
    float: right;
    text-align: right;
    color: black;
    background-color: white;
}
</style>

<?php include '../components/admin_header.php'; ?>
<?php include '../components/nav.php'; ?>
<center>
<div class="container-fluid" style="padding-left: 100px; padding-right: 20px; padding-top: 20px;">
<h2 style="background-color: gray; padding: 10px; color: white; text-align: center; width: 100%;">Inventory Management</h2><br>
	<div class="col-lg-12">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-body">
                    <br>
                    <?php
                    // Attempt select query execution
                    $sql = "SELECT * FROM products";
                    if($result = mysqli_query($conn2, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo "<table class='table table-bordered table-striped' id='myTable'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>ID</th>";
                                        echo "<th>Product Name</th>";
                                        echo "<th>Image</th>";
                                        echo "<th>Details</th>";
                                        echo "<th>Price</th>";
                                        echo "<th>Stock In</th>";
                                        echo "<th>Stock Out</th>";
                                        echo "<th>Action</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" . $row['id'] . "</td>";
                                        echo "<td>" . $row['name'] . "</td>";
                                        echo "<td><img src='../uploaded_img/".$row['image_01']."' alt='' style='width: 50px; height: 50px; border-radius: 3px;'></td>";
                                        echo "<td>" . $row['details'] . "</td>";
                                        echo "<td>" . $row['price'] . "</td>";
                                        if($row['stock'] == "0" && $row['stock'] != "N/A"){
                                            $txt_color = "red";
                                        }
                                        else if($row['stock'] <= 10 && $row['stock'] != "0"){
                                            $txt_color = "orange";
                                        }
                                        else{
                                            $txt_color = "green";
                                        }
                                        echo "<td style='color: $txt_color; font-weight: bold;'>" . $row['stock'] . "</td>";
                                        echo "<td>" . $row['stock_out'] . "</td>";
                                        echo "<td><a href='update_stocks.php?id=".$row['id']." && name=".$row['name']." && price=".$row['price']." && image=".$row['image_01']."&& current_stocks=".$row['stock']."&& disc_price=".$row['discount_price']."' style='background-color: #008ae6; color: white; padding: 10px; border-radius: 4px;'>Edit</a></td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            // Free result set
                            mysqli_free_result($result);
                        } else{
                            echo "<p class='lead'><em>No records were found.</em></p>";
                        }
                    } else{
                        echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn2);
                    }
 
                    // Close connection
                    mysqli_close($conn2);
                    ?>
                </div>
            </div>        
        </div>
    </div>
</div>
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