<?php
  session_start();
    include('sentiment/vendor/autoload.php');
    Use Sentiment\Analyzer;
    $analyzer = new Analyzer(); 
?>

<!DOCTYPE html>
<html lang="en" >
<head>
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      outline: none;
      text-decoration: none;
      font-family: sans-serif;
    }

    body {
      position: relative;
      padding: 0 0 0 260px;
    }
    .nav {
       position: fixed;
       top: 0;
       left: 0;
       background: #239ae0;
       width: 100%;
       max-width: 260px;
       height: 100vh;
       z-index: 100;
    }

    /*.bx {
       width: 100%;
       height: 75px;
       background: red;
    }*/

    .nav .logo {
       width: 100%;
       height: 75px;
       display: flex;
       justify-content: center;
       align-items: center;
    }

    .logo h3 a {
       font-size: 2rem;
       font-weight: 500;
       color: #f1f1f1;
    }

    .ul {
       display: block;
    }

    .ul .li {
       list-style: none;
       width: 95%;
       background: rgba(0, 0, 0, .2);
       height: 55px;
       margin-bottom: 4px;
       border-radius: 0 15px 3px 0;
       transition: 0.2s;
    }

    .ul .li:hover {
       background: rgba(0, 0, 0, 0.4);
    }

    .ul .li a {
       text-decoration: none;
       color: #f1f1f1;
       font-size: 1.2rem;
       font-weight: 400;
       text-transform: capitalize;
       width: 100%;
       height: 100%;
       display: flex;
       align-items: center;
       margin-left: 10px;
    }

    #customers {
      font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
      border-collapse: collapse;
      width: 100%;
    }

    #customers td, #customers th {
      border: 1px solid #ddd;
      padding: 8px;
    }

    #customers tr:nth-child(even){background-color: #f2f2f2;}
    #customers tr:nth-child(odd){background-color: #f2f2f2;}
    #customers tr:hover {background-color: #ddd;}

    #customers th {
      padding-top: 12px;
      padding-bottom: 12px;
      text-align: left;
      background-color: #239ae0;
      color: white;
    }
    .block {
      display: block;
      width: 100%;
      border: none;
      background-color: #4CAF50;
      color: white;
      padding: 14px 28px;
      font-size: 16px;
      cursor: pointer;
      text-align: center;
    }

    .block:hover {
      background-color: #ddd;
      color: black;
    }
</style>

<style>
	.nav-container {
    height: 530px; /* Adjust the height as per your needs */
    overflow-y: auto;
}

.ul {
    list-style-type: none;
    padding: 0;
}

.li {
    margin: 0;
    padding: 0;
}

.li a {
    text-decoration: none;
    color: #333;
}

</style>

<style>
        /* Style the container for the word cloud */
        .word-cloud {
            position: relative;
            width: 300px; /* Adjust the width as needed */
            height: 200px; /* Adjust the height as needed */
        }

        /* Style each word with different font sizes */
        .word-cloud p {
            position: absolute;
            margin: 0;
            padding: 0;
        }

        .word-cloud p:nth-child(1) {
            font-size: 15px;
            left: 20px;
            top: 20px;
        }

        .word-cloud p:nth-child(2) {
            font-size: 20px;
            left: 100px;
            top: 40px;
        }

        .word-cloud p:nth-child(3) {
            font-size: 25px;
            left: 180px;
            top: 80px;
        }

        .word-cloud p:nth-child(4) {
            font-size: 35px;
            left: 60px;
            top: 120px;
        }
        
    </style>
  <meta charset="UTF-8">
  <title>Feedback</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js" type="text/javascript"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
      <link rel="stylesheet" href="css/style.css">
</head>
<body>

<nav class="nav">
    <div class="bx"></div>
    <div class="logo" style="background-color: white; height: 80px;">
    <a href="../admin/dashboard.php" class="logo"><img src="../images/logo.JPG" style="width: 200px; height: 90px; margin-top:-10px; margin-left: -60px; float: left;" alt=""></a>
    </div>
    <div class="nav-container">
    <ul class="ul">
    <li class="li"><a href="dashboard.php">dashboard</a></li>
    <li class="li"><a href="placed_orders.php">placed orders</a></li>
    <li class="li"><a href="products.php">products added</a></li>
    <li class="li"><a href="users_accounts.php">user</a></li>
    <li class="li"><a href="admin_accounts.php">admin user</a></li>
    <li class="li"><a href="messages.php">messages</a></li>
    <li class="li"><a href="mainpage.php">feedback</a></li>
    <li class="li"><a href="gcash_account.php">Payment Management</a></li>
    <li class="li"><a href="inventory.php">Inventory Management</a></li>
    <li class="li"><a href="sales_report.php">Sales Report</a></li>
    <li class="li"><a href="analytics.php">Data Analytics</a></li>
    <li class="li"><a href="update_profile.php">update profile</a></li>
    </ul>
    </div>
  </nav>

  <?php 
  require 'config.php';
  $ex = 0;
  $gd = 0;
  $neu = 0;
  $poor = 0;

  $p1 = $p2 = $p3 = $p4 = "";

   if (isset($_SESSION['admin_id'])) {
          $userLoggedIn = $_SESSION['admin_id'];
          $result = mysqli_query($con,"SELECT * FROM poll");

            echo "<table border='1' id='customers'>
            <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Ratings</th>
            <th>Feedback</th>
            <th>Sentiment Analysis</th>
            <th>Action</th>
            </tr>";

            while($row = mysqli_fetch_array($result))
            {           
              if($row['feedback'] == "excellent"){
                $ex += 1;

              }
              else if($row['feedback'] == "good"){
                $gd += 1;
              }
              else if($row['feedback'] == "neutral"){
                $neu += 1;
              }
              else if($row['feedback'] == "poor"){
                $poor += 1;
              }

              $output_text = $analyzer->getSentiment($row['suggestions']);
              // Access the sentiment values from the output
              $positive = $output_text['pos'];
              $negative = $output_text['neg'];
              $neutral = $output_text['neu'] - 0.2;
              if($positive >= 0.4){
                $positive += 0.2;
                  $pic = '<img src="../project images/smile.png" alt="" style="width: 30px; height: 30px;">';
              }
              else if($negative >= 0.4){
                  $pic = '<img src="../project images/sad.png" alt="" style="width: 30px; height: 30px;">';
                  $negative += 0.2;
              }
              else if($neutral >= 0.3){
                  $pic = '<img src="../project images/neutral.png" alt="" style="width: 30px; height: 30px;">';
              }

            echo "<tr>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "<td>" . $row['phone'] . "</td>";
            echo "<td>" . $row['feedback'] . "</td>";
            echo "<td>" . $row['suggestions'] . "</td>";
            echo "<td style='text-align: center;'>" . $pic . "<br> positive " .$positive. "<br> neutral " .$neutral. "<br> negative " .$negative."</td>";
            echo "<td><a href='delete_feedback.php?id=".$row['id']."' class='del-btn' style='background-color: red; color: white; padding: 10px; border-radius: 4px;'>Remove</a></td>";
            echo "</tr>";
            }
            echo "</table>";

  }
  else {
  //header("Location: index.php");
  }

  $a = $ex;
  $b = $gd;
  $c = $neu;
  $d = $poor;

  $num_rate = [$a, $b, $c, $d];
  $numberCounts = array_count_values($num_rate);
  $similarNumbers = array_filter($numberCounts, function($count){
    return $count > 1;
  });

  if(!empty($similarNumbers)){
    if($a == $c && $a == $b && $c == $d){
      $p1 = "30px";
      $p2 = "30px";
      $p3 = "30px";
      $p4 = "30px";
    }
    if($a == $b){
      $p1 = "30px";
      $p2 = "30px";
    }
    if($a == $c){
      $p1 = "30px";
      $p3 = "30px";
    }
    if($a == $d){
      $p1 = "30px";
      $p4 = "30px";
    }

    if($b == $c){
      $p2 = "30px";
      $p3 = "30px";
    }
    if($b == $d){
      $p2 = "30px";
      $p4 = "30px";
    }
    if($c == $d){
      $p3 = "30px";
      $p4 = "30px";
    }

    if($a == $b && $b == $c){
      $p1 = "30px";
      $p2 = "30px";
      $p3 = "30px";
    }


    if($a == $b && $a > $c && $a > $d){
      $p1 = "30px";
      $p2 = "30px";
      if($c > $d){
        $p3 = "25px";
        $p4 = "15px";
      }
      else{
        $p3 = "15px";
        $p4 = "25px";
      }
    }
    if($a == $c && $a > $b && $a > $d){
      $p1 = "30px";
      $p3 = "30px";
      if($b > $d){
        $p2 = "25px";
        $p4 = "15px";
      }
      else{
        $p2 = "15px";
        $p4 = "25px";
      }
    }
    if($a == $d && $a > $b && $a > $c){
      $p1 = "30px";
      $p4 = "30px";
      if($c > $b){
        $p3 = "25px";
        $p2 = "15px";
      }
      else{
        $p3 = "15px";
        $p2 = "25px";
      }
    }
//---------------------------------------------------
    if($b == $c && $b > $a && $b > $d){
      $p2 = "30px";
      $p3 = "30px";
    }
    if($b == $d && $b > $a && $b > $c){
      $p2 = "30px";
      $p4 = "30px";
    }
//---------------------------------------------------
    if($c == $b && $c > $a && $c > $d){
      $p3 = "30px";
      $p2 = "30px";
    }
    if($c == $d && $c > $a && $c > $b){
      $p3 = "30px";
      $p4 = "30px";
    }
    
  }

  if($a > $b && $a > $c && $a > $d){
    $p1 = "35px";
    if($b > $c && $b > $d){
      $p2 = "30px";
      if($c > $d){
        $p3 = "25px";
        $p4 = "15px";
      }
      else{
        $p3 = "15px";
        $p4 = "25px";
      }
    }
    else{
      $p2 = "15px";
      if($c > $d){
        $p3 = "25px";
        $p4 = "15px";
      }
      else{
        $p3 = "15px";
        $p4 = "25px";
      }
    }
  }
  else if($b > $a && $b > $c && $b > $d){
    $p2 = "35px";
    if($a > $c && $a > $d){
      $p1 = "30px";
      if($c > $d){
        $p3 = "25px";
        $p4 = "15px";
      }
      else{
        $p3 = "15px";
        $p4 = "25px";
      }
    }
    else{
      $p1 = "15px";
      if($c > $d){
        $p3 = "25px";
        $p4 = "15px";
      }
      else{
        $p3 = "15px";
        $p4 = "25px";
      }
    }
  }
  else if($c > $a && $c > $b && $c > $d){
    $p3 = "35px";
    if($a > $b && $a > $d){
      $p1 = "30px";
      if($b > $d){
        $p2 = "25px";
        $p4 = "15px";
      }
      else{
        $p2 = "15px";
        $p4 = "25px";
      }
    }
    else{
      $p1 = "15px";
      if($b > $d){
        $p2 = "25px";
        $p4 = "15px";
      }
      else{
        $p2 = "15px";
        $p4 = "25px";
      }
    }
  }
  else if($d> $a && $d > $b && $d > $c){
    $p4 = "35px";
    if($a > $b && $a > $c){
      $p1 = "30px";
      if($b > $c){
        $p2 = "25px";
        $p3 = "15px";
      }
      else{
        $p2 = "15px";
        $p3 = "25px";
      }
    }
    else{
      $p1 = "15px";
      if($b > $c){
        $p2 = "25px";
        $p3 = "15px";
      }
      else{
        $p2 = "15px";
        $p3 = "25px";
      }
    }

  }

  if($a == 0){
    $p1 = "0px";
  }
  if($b == 0){
    $p2 = "0px";
  }
  if($c == 0){
    $p3 = "0px";
  }
  if($d == 0){
    $p4 = "0px";
  }



//   $a = $ex;
//   $b = $gd;
//   $c = $neu;
//   $d = $poor;
  
//   // Create an associative array with variable names as keys and values as values
//   $variables = array(
//       'a' => $a,
//       'b' => $b,
//       'c' => $c,
//       'd' => $d
//   );
  
//   // Sort the array by values in ascending order
//   asort($variables);
//   $i = 0;
//   // Loop through the sorted array and display variable names and values
//   foreach ($variables as $varName => $varValue) {
//     $i++;
//     if($i == 1){
//       if($varName == "a"){
//         $p1 = "15px";
//       }
//       else if($varName == "b"){
//         $p2 = "15px";
//       }
//       else if($varName == "c"){
//         $p3 = "15px";
//       }
//       else if($varName == "d"){
//         $p4 = "15px";
//       }
//     }

//     if($i == 2){
//       if($varName == "a"){
//         $p1 = "20px";
//       }
//       else if($varName == "b"){
//         $p2 = "20px";
//       }
//       else if($varName == "c"){
//         $p3 = "20px";
//       }
//       else if($varName == "d"){
//         $p4 = "20px";
//       }
//     }

//     if($i == 3){
//       if($varName == "a"){
//         $p1 = "25px";
//       }
//       else if($varName == "b"){
//         $p2 = "25px";
//       }
//       else if($varName == "c"){
//         $p3 = "25px";
//       }
//       else if($varName == "d"){
//         $p4 = "25px";
//       }
//     }

//   if($i == 4){
//     if($varName == "a"){
//       $p1 = "35px";
//     }
//     else if($varName == "b"){
//       $p2 = "35px";
//     }
//     else if($varName == "c"){
//       $p3 = "35px";
//     }
//     else if($varName == "d"){
//       $p4 = "35px";
//     }
//   }
// }

  ?>
    <iframe src="chart_sentiment.php" style="width: 100%; height: 600px;"></iframe>
    <center><br><br>
    <div style="margin-top: -120px;">
      <img src="../project images/smile.png" alt="" style="width: 50px; height: 50px; margin-right: 160px; margin-left: 210px;">
      <img src="../project images/neutral.png" alt="" style="width: 50px; height: 50px; margin-right: 180px; margin-left: 60px;">
      <img src="../project images/sad.png" alt="" style="width: 50px; height: 50px; margin-right: 180px; margin-left: 50px;">
    </div>
<br><br>
  <h2 class="page-header" style="background-color: #595959; color: white; padding: 5px; margin-left: 20px; font-size: 26px; font-family: times new roman;">Sentiment Analysis (Word Cloud) </h2>
  <br><br>
  </center>
  <img src="cloud.png" alt="" style="position: absolute; width:1100px;">
  <center>
<div class="word-cloud"><br><br><br><br><br><br><br>
    <p style="font-size: <?php echo $p1; ?>; color: #00CA4E; font-weight: bold;">Excellent</p><br><br>
    <p style="font-size: <?php echo $p2; ?>; color: white; font-weight: bold; margin-top: 50px;">Good</p><br><br>
    <p style="font-size: <?php echo $p3; ?>; color: yellow; font-weight: bold; margin-top: 50px;">Neutral</p><br><br>
    <p style="font-size: <?php echo $p4; ?>; color: red; font-weight: bold; float: left; margin-top: 100px; margin-left: 150px;">Poor</p>
</div>
</center>

  <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
</body>
</html>