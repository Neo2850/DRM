<?php
include '../components/connect.php';
?>
<!-- Notification for critical stocks -->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

        <?php 
        // Attempt select query execution
        $is_out_of_stocks = false;
        $is_critical_stocks = false;
            $sql_critical_stocks = "SELECT * FROM products";
            if($result001 = $conn2->query($sql_critical_stocks)){
                if($result001->num_rows > 0){
                    while($row001 = $result001->fetch_array()){
                        $single_stocks = $row001['stock'];
                        $min_single_stocks = 10;
                        $prd_id = $row001['id'];

                        if($single_stocks == "0"){
                            $is_out_of_stocks = true;
                        }
                        else if($single_stocks <= "10"){
                            $is_critical_stocks = true;
                        }
                    }
                    if($is_critical_stocks){
                        if($is_out_of_stocks){
                        echo '<button id="trigger1" onclick="msg1()" hidden></button>
                        <script>
                            document.getElementById("trigger1").click();

                            function msg1(){
                                swal("Critical and Out of Stocks!", "Some of your products reached its critical status. Others are out of stocks.");
                            }
                        </script>';

                        }
                        else{
                        echo '<button id="trigger1" onclick="msg1()" hidden></button>
                        <script>
                            document.getElementById("trigger1").click();

                            function msg1(){
                                swal("Critical Stocks!", "Some of your products reached its critical status!");
                            }
                        </script>';
                        }
                    }
                    else if($is_out_of_stocks){
                        if($is_critical_stocks){
                        echo '<button id="trigger1" onclick="msg1()" hidden></button>
                        <script>
                            document.getElementById("trigger1").click();

                            function msg1(){
                                swal("Critical and Out of Stocks!", "Some of your products reached its critical status. Others are out of stocks");
                            }
                        </script>';

                        }
                        else{
                        echo '<button id="trigger1" onclick="msg1()" hidden></button>
                        <script>
                            document.getElementById("trigger1").click();

                            function msg1(){
                                swal("Out of Stocks!", "Some of your products are out of stocks.");
                            }
                        </script>';
                        }
                    }
            }
        }
    ?>
<!-- ================================================== -->