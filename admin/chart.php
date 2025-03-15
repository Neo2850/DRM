<?php
include("sales_monitoring.php");
$con  = mysqli_connect("localhost","root","","eorcon");
 if (!$con) {
     # code...
    echo "Problem in database connection! Contact administrator!" . mysqli_error();
 }
 else{
        // STOCKS MONITORING
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $selected_year = $_POST['select_year'];
        }
        else{
            $selected_year = date("Y");
        }
        $sql002 ="SELECT * FROM sales_monitoring WHERE `Year` = '$selected_year' ORDER BY `Month` ASC";
        $result = mysqli_query($con,$sql002);
        $chart_data="";
        while ($row = mysqli_fetch_array($result)) { 
            $mnt = $row['Month'];
            if($mnt == "01"){
                $mnt_str = "January";
            }
            else if($mnt == "02"){
                $mnt_str = "February";
            }
            else if($mnt == "03"){
                $mnt_str = "March";
            }
            else if($mnt == "04"){
                $mnt_str = "April";
            }
            else if($mnt == "05"){
                $mnt_str = "May";
            }
            else if($mnt == "06"){
                $mnt_str = "June";
            }
            else if($mnt == "07"){
                $mnt_str = "July";
            }
            else if($mnt == "08"){
                $mnt_str = "August";
            }
            else if($mnt == "09"){
                $mnt_str = "September";
            }
            else if($mnt == "10"){
                $mnt_str = "October";
            }
            else if($mnt == "11"){
                $mnt_str = "November";
            }
            else if($mnt == "12"){
                $mnt_str = "December";
            }
            $month_arr[]  = $mnt_str;
            $sales[] = $row['Sales'];
       }


 }
?>
<!DOCTYPE html>
<html lang="en"> 
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Graph</title> 
    </head>
    <body>
        <center>
        <form name="year_frm" action="" method="POST">
            <span style="float: right; color: white; margin-top: 10px; margin-right: 10px;">Year: &nbsp
        <select name="select_year" onchange="this.form.submit()" style="width: 100px; float: right;">
        <?php
        $sql_yr ="SELECT DISTINCT `Year` FROM sales_monitoring";
        $result_yr = mysqli_query($con,$sql_yr);
        while ($row3 = mysqli_fetch_array($result_yr)) { 
            $list_year  = $row3['Year'];
            if( $list_year == $selected_year){
                echo '<option value="'.$list_year.'" selected>'.$list_year.'</option>';
            }
            else{
                echo '<option value="'.$list_year.'">'.$list_year.'</option>';
            }
        }
        ?>
        </select>
        </form>
        </span>
        <h2 class="page-header" style="background-color: #595959; color: white; padding: 5px; margin-left: 20px;">Analytics Reports (Sales) </h2>
        <div style="width:80%;height:15%;text-align:center">
            <canvas width="100%" height="48px" id="chartjs_bar1"></canvas>
        </div>    
        </center>
    </body>
  <script src="//code.jquery.com/jquery-1.9.1.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>

<script type="text/javascript">

var ctx = document.getElementById("chartjs_bar1").getContext('2d');
          var myChart = new Chart(ctx, {
              type: 'bar',
              data: {
                  labels:<?php echo json_encode($month_arr); ?>,
                  datasets: [{
                      backgroundColor: [
                         "#5969ff",
                          "#ff407b",
                          "#25d5f2",
                          "#ffc750",
                          "#2ec551",
                          "#7040fa",
                          "#ff004e"
                      ],
                      label:<?php echo json_encode("sales"); ?>,
                      data:<?php echo json_encode($sales); ?>,
                      
                  }]
              },
              options: {
                  scales: {
                      yAxes: [{
                          ticks: {
                              beginAtZero: true
                          }
                      }]
                  },
                     legend: {
                  display: false,
                  position: 'bottom',
 
                  labels: {
                      fontColor: '#71748d',
                      fontFamily: 'Circular Std Book',
                      fontSize: 14,
                  }
              },
 
 
          }
          });
</script>
</html>