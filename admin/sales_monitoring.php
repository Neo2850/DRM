<?php
  require 'db_connect.php';

    $qry_refresh = "UPDATE sales_monitoring SET Sales = '0'"; 
    $refresh_result = mysqli_query($conn, $qry_refresh);

    $query1 = "SELECT * FROM orders WHERE payment_status = 'completed'";
    $query_run1 = mysqli_query($conn, $query1);

  if(mysqli_num_rows($query_run1) > 0)
  {
    foreach($query_run1 as $row1)
      {
        $total_price = $row1['total_price'];
        $year = date('Y', strtotime($row1['placed_on']));
        $month = date('m', strtotime($row1['placed_on']));
        $check = false;

        // Query 3
          $query3 = "SELECT * FROM sales_monitoring WHERE `Year` = '$year' AND `Month` = '$month'";
          $query_run3 = mysqli_query($conn, $query3);
          if(mysqli_num_rows($query_run3) > 0)
          {
            foreach($query_run3 as $row3)
            {
                $last_sales = $row3['Sales'];
            }
            $check = true;
          }
        if($check){
            $total_sales = $last_sales + $total_price;
            $query4 = "UPDATE sales_monitoring SET Sales = '$total_sales' WHERE `Year` = '$year' AND `Month` = '$month'"; 
            $update_result = mysqli_query($conn, $query4);
        }
        else{
            $total_sales = $total_price;
            $query5 = "INSERT INTO sales_monitoring (`Month`, `Year`, `Sales`) VALUES ('$month', '$year', '$total_sales')";
            $insert_result = mysqli_query($conn, $query5);
        }
      }
  }
?>