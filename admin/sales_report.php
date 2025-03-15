<?php 
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

   <style>
      body {
         padding: 70px 0 0 180px;
         font-size: 16px;
      }
      .container-fluid {
         padding-left: 100px;
         padding-right: 20px;
         padding-top: 20px;
      }
      h2 {
         background-color: gray;
         color: white;
         padding: 5px;
         text-align: center;
         border-radius: 5px;
         font-size: 22px;
         margin-bottom: 30px;
      }
      .search-form {
         display: flex;
         justify-content: center;
         gap: 20px;
         margin-bottom: 20px;
      }
      .search-form input {
         font-size: 18px;
         padding: 5px;
         border-radius: 5px;
         border: 1px solid #ccc;
      }
      .search-form input[type="submit"] {
         background-color: #595959;
         border-color: white;
         color: white;
         width: 100px;
      }
      table {
         width: 100%;
         border-collapse: collapse;
         margin-top: 20px;
      }
      table th, table td {
         padding: 10px;
         text-align: center;
         border: 1px solid #ddd;
      }
      table th {
         background-color: #f4f4f4;
      }
      table tr:hover {
         background-color: #f1f1f1;
      }
      .stats-box {
         display: flex;
         justify-content: space-evenly;
         gap: 10px;
         margin-top: 30px;
      }
      .stats-box .box {
         padding: 20px;
         text-align: center;
         border-radius: 8px;
         color: white;
         font-size: 20px;
         font-weight: bold;
      }
      .stats-box .box .amount {
         font-size: 24px;
         margin-bottom: 10px;
      }
      .stats-box .box.total-sales {
         background-color: lightgreen;
      }
      .stats-box .box.today-sales {
         background-color: skyblue;
      }
      tr {
        background-color: white;
        color: black;
      }
   </style>
</head>

<body>

<?php include '../components/admin_header.php'; ?>
<?php include '../components/nav.php'; ?>

<div class="container-fluid">
    <h2>Sales Report</h2>

    <div class="card">
        <div class="card-body">
            <!-- Search Filter -->
            <form method='post' action='' class="search-form">
                <div>
                    <label style="font-size: 14px; color: black;" for="fromDate">From:</label>
                    <input type='date' class='dateFilter' name='fromDate' value='<?php if(isset($_POST['fromDate'])) echo $_POST['fromDate']; ?>' required>
                </div>
                <div>
                    <label for="endDate">To:</label>
                    <input type='date' class='dateFilter' name='endDate' value='<?php if(isset($_POST['endDate'])) echo $_POST['endDate']; ?>' required>
                </div>
                <input type='submit' name='but_search' value='Generate'>
            </form>

            <br><br>

            <?php
            // Attempt select query execution
            $sql = "SELECT * FROM orders WHERE payment_status = 'completed' $add_qry";
            if($result = mysqli_query($conn2, $sql)){
                if(mysqli_num_rows($result) > 0){
                    echo "<table class='table table-bordered table-striped' id='myTable'>";
                        echo "<thead>";
                            echo "<tr style='background-color: white; color: black;'>";
                                echo "<th>ID</th>";
                                echo "<th>Total Products</th>";
                                echo "<th>Amount</th>";
                                echo "<th>Method</th>";
                                echo "<th>Date</th>";
                            echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        while($row = mysqli_fetch_array($result)){
                            echo "<tr style='background-color: white; color: black;'>";
                                echo "<td>" . $row['id'] . "</td>";
                                echo "<td>" . $row['total_products'] . "</td>";
                                echo "<td>" . $row['total_price'] . "</td>";
                                echo "<td>" . $row['method'] . "</td>";
                                echo "<td>" . $row['placed_on'] . "</td>";
                            echo "</tr>";
                        }
                        echo "</tbody>";                            
                    echo "</table>";
                    // Free result set
                    mysqli_free_result($result);
                } else{
                    echo "<p class='lead' style='text-align:center; font-size: 24px;'><em>No records were found.</em></p>";
                }
            } else{
                echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn2);
            }

            // Close connection
            mysqli_close($conn2);
            ?>

            <div class="stats-box">
                <div class="box total-sales">
                    <?php
                        $total_completes = 0;
                        $select_completes = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
                        $select_completes->execute(['completed']);
                        if($select_completes->rowCount() > 0){
                            while($fetch_completes = $select_completes->fetch(PDO::FETCH_ASSOC)){
                                $total_completes += $fetch_completes['total_price'];
                            }
                        }
                    ?>
                    <div class="amount">₱ <?= number_format($total_completes, 2); ?></div>
                    <p>Total Sales</p>
                </div>

                <div class="box today-sales">
                    <?php
                        $total_completes2 = 0;
                        $select_completes2 = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ? and placed_on = ?");
                        $select_completes2->execute(['completed', date("Y-m-d")]);
                        if($select_completes2->rowCount() > 0){
                            while($fetch_completes2 = $select_completes2->fetch(PDO::FETCH_ASSOC)){
                                $total_completes2 += $fetch_completes2['total_price'];
                            }
                        }
                    ?>
                    <div class="amount">₱ <?= number_format($total_completes2, 2); ?></div>
                    <p>Today's Sales</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap4.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.print.min.js"></script>

<script>
    $(document).ready(function() {
        $('#myTable').DataTable({
            dom: 'Bfrtip',
            buttons: ['print']
        });
    });
</script>

<script src="../js/admin_script.js"></script>
   
</body>
</html>
