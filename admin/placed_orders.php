<?php
include '../components/connect.php';

ini_set('display_errors', 0);

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
    ini_set('display_errors', 0);
}

$limit = 4; // Number of orders per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Fetch orders with pagination
$select_orders = $conn->prepare("SELECT * FROM `orders` LIMIT :limit OFFSET :offset");
$select_orders->bindParam(':limit', $limit, PDO::PARAM_INT);
$select_orders->bindParam(':offset', $offset, PDO::PARAM_INT);
$select_orders->execute();

if ($select_orders->rowCount() > 0) {
    // Fetch total number of orders for pagination
    $count_orders = $conn->query("SELECT COUNT(*) FROM `orders`")->fetchColumn();
    $total_pages = ceil($count_orders / $limit);
}

// Handle payment status update
if (isset($_POST['update_payment'])) {
    $order_id = $_POST['order_id'];
    $order_id_2 = $_POST['order_id_2'];
    $payment_status = $_POST['payment_status'];
    $current_payment_status = $_POST['current_payment_status'];
    $cust_name = $_POST['customer_name'];
    $cust_email = $_POST['customer_email'];

    // SELECT ALL DATA FROM ORDER DETAILS
    $select_order_details = $conn->prepare("SELECT * FROM `order_details` WHERE order_id = ?");
    $select_order_details->execute([$order_id_2]);
    if ($select_order_details->rowCount() > 0) {
        while ($fetch_orders1 = $select_order_details->fetch(PDO::FETCH_ASSOC)) {
            $check = 0;
            $prd_id = $fetch_orders1["pid"];
            $prd_qnty = $fetch_orders1["quantity"];

            // SELECT DATA FROM PRODUCTS
            $select_product = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
            $select_product->execute([$prd_id]);
            if ($select_product->rowCount() > 0) {
                while ($fetch_orders2 = $select_product->fetch(PDO::FETCH_ASSOC)) {
                    $current_stock = $fetch_orders2["stock"];
                    $current_stock_out = $fetch_orders2["stock_out"];
                }
            }

            // COMPUTE STOCKS
            if ($current_payment_status == "pending" && $payment_status == "completed") {
                $result_stocks = $current_stock - $prd_qnty;
                $result_stocks_out = $current_stock_out + $prd_qnty;
                $check = 1;
            } else if ($current_payment_status == "completed" && $payment_status == "pending") {
                $result_stocks = $current_stock + $prd_qnty;
                $result_stocks_out = $current_stock_out - $prd_qnty;
                $check = 2;
            }

            if ($check == 1 || $check == 2) {
                // UPDATE STOCKS
                $update_stocks = $conn->prepare("UPDATE `products` SET `stock` = ?, `stock_out` = ? WHERE id = ?");
                $update_stocks->execute([$result_stocks, $result_stocks_out, $prd_id]);
            }
        }
    }

    // UPDATE PAYMENT STATUS
    $payment_status = filter_var($payment_status, FILTER_SANITIZE_STRING);
    $update_payment = $conn->prepare("UPDATE `orders` SET payment_status = ? WHERE id = ?");
    $update_payment->execute([$payment_status, $order_id]);
    $message[] = 'Order status updated!';

    // Send notification email to customer
    $subject = "DRM Roofing Glass and Aluminum And Iron Works: Your Order Status";
    $message1 = "Good Day $cust_name! We would like to notify you that your order is now $payment_status. Thank You!";
    $sender = "From: admin@gmail.com";
    mail($cust_email, $subject, $message1, $sender);

    // echo '<script>window.location.reload();</script>';

}
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    // Delete the order and related records
    $delete_order = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
    $delete_order->execute([$delete_id]);

    // echo '<script>window.location.reload();</script>';

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Placed Orders</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_style.css">
    <style>
        /* Basic styling */
        body {
            padding: 70px 0 0 220px;
            background: url(../img/background.png) no-repeat center;
            background-size: cover;
            position: relative;
        }

        .background-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgb(0,0,0,.5);
            z-index: -1;
            pointer-events: none;
        }

        section.orders {
            padding: 20px;
        }

        h1.heading {
            color: white;
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f4f4f4;
            color: #333;
        }

        tr {
            background-color: #f1f1f1;
            font-size: 14px;
        }

        .select, .option-btn {
            padding: 5px 10px;
            font-size: 14px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }

        .delete-btn {
            padding: 5px 10px;
            background-color: #f44336;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-left: 5px;
        }

        .delete-btn:hover {
            background-color: #d32f2f;
        }

        .empty {
            text-align: center;
            color: white;
            font-size: 18px;
        }

        .pagination {
            text-align: center;
            margin-top: 20px;
        }

        .pagination a {
            padding: 8px 16px;
            margin: 0 5px;
            text-decoration: none;
            background-color: #ddd;
            color: black;
            border-radius: 4px;
        }

        .pagination a:hover {
            background-color: #ccc;
        }

        .pagination .active {
            background-color: #007bff;
            color: white;
        }

        /* Modal Styles */
        .notes-modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .notes-modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
        }

        .close-btn {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close-btn:hover,
        .close-btn:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        #notesText {
            font-size: 16px;
            white-space: pre-wrap;
            word-wrap: break-word;
        }

        @media (max-width: 768px) {
            body {
                padding: 70px 0;
            }

            table, th, td {
                display: block;
                width: 100%;
            }

            th {
                background-color: #f4f4f4;
            }

            td {
                border: none;
                padding: 10px;
                text-align: right;
            }

            td span {
                font-weight: bold;
            }

            td::before {
                content: attr(data-label);
                font-weight: bold;
                display: inline-block;
                margin-bottom: 5px;
            }

            .order-actions {
                display: flex;
                justify-content: space-between;
            }

            .flex-btn {
                display: flex;
                flex-direction: column;
            }

            .select, .option-btn, .delete-btn {
                width: 100%;
                margin-bottom: 5px;
            }
        }
    </style>
</head>
<body>

<div class="background-overlay"></div>

<?php include '../components/admin_header.php'; ?>
<?php include '../components/nav.php'; ?>

<section class="orders">
    <h1 class="heading" style="background-color: gray; padding: 10px; color: white; text-align: center;">Placed Orders</h1>

    <div class="box-container" style="display:flex;">
        <?php
        if ($select_orders->rowCount() > 0) {
        ?>
        <table>
            <thead>
                <tr>
                    <th>Placed On</th>
                    <th>Name</th>
                    <th>Number</th>
                    <th>Address</th>
                    <th>Total Products</th>
                    <th>Total Price</th>
                    <th>Payment Method</th>
                    <th>Notes</th>
                    <th>Reference No.</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <tr>
                    <td data-label="Placed On"><?= $fetch_orders['placed_on']; ?></td>
                    <td data-label="Name"><?= $fetch_orders['name']; ?></td>
                    <td data-label="Number"><?= $fetch_orders['number']; ?></td>
                    <td data-label="Address"><?= $fetch_orders['address']; ?></td>
                    <td data-label="Total Products"><?= $fetch_orders['total_products']; ?></td>
                    <td data-label="Total Price">₱ <?= $fetch_orders['total_price']; ?></td>
                    <td data-label="Payment Method"><?= $fetch_orders['method']; ?></td>
                    <td data-label="Notes"><button style="padding: 10px; background: #46ed78; border-radius: 10px;" class="view-notes-btn" data-notes="<?= htmlspecialchars($fetch_orders['notes']); ?>">View Notes</button></td>
                    <td data-label="Reference No."><?= $fetch_orders['reference_no']; ?></td>
                    <td class="order-actions">
                        <form action="" method="post" style="display: inline;">
                            <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
                            <input type="hidden" name="order_id_2" value="<?= $fetch_orders['order_id']; ?>">
                            <input type="hidden" name="current_payment_status" value="<?= $fetch_orders['payment_status']; ?>">
                            <input type="hidden" name="customer_name" value="<?= $fetch_orders['name']; ?>">
                            <input type="hidden" name="customer_email" value="<?= $fetch_orders['email']; ?>">

                            <?php
                            $completed = $fetch_orders['payment_status'] == "completed" ? "disabled" : "";
                            ?>
                            <select name="payment_status" class="select" <?= $completed; ?>>
                                <option selected disabled><?= $fetch_orders['payment_status']; ?></option>
                                <option value="pending">pending</option>
                                <option value="preparing">preparing your item</option>
                                <option value="out for delivery">out for delivery</option>
                                <option value="completed">completed</option>
                            </select>

                            <div class="flex-btn">
                                <input type="submit" value="update" class="option-btn" name="update_payment" style="filter: invert(1);color: #000;">
                                <a href="placed_orders.php?delete=<?= $fetch_orders['id']; ?>" class="delete-btn" onclick="return confirm('delete this order?');">delete</a>
                            </div>
                        </form>
                    </td>
                </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
        
        <!-- Notes Modal -->
        <div id="notesModal" class="notes-modal">
            <div class="notes-modal-content">
                <span class="close-btn">&times;</span>
                <h2>Order Notes</h2>
                <p id="notesText"></p>
            </div>
        </div>

        <?php
        } else {
            echo '<p class="empty">No orders placed yet!</p>';
        }
        ?>
    </div>

    <!-- Pagination -->
    <div class="pagination">
        <?php
        for ($i = 1; $i <= $total_pages; $i++) {
            $active = ($i == $page) ? 'active' : '';
            echo "<a href='placed_orders.php?page=$i' class='$active'>$i</a>";
        }
        ?>
    </div>
</section>

<script>
// Get the modal
var modal = document.getElementById("notesModal");

// Get the button that opens the modal (View Notes)
var viewNotesBtns = document.querySelectorAll(".view-notes-btn");

// Get the element that closes the modal (×)
var closeBtn = document.getElementsByClassName("close-btn")[0];

// When a "View Notes" button is clicked, open the modal and set the notes text
viewNotesBtns.forEach(function(btn) {
    btn.onclick = function() {
        var notes = btn.getAttribute("data-notes");
        document.getElementById("notesText").textContent = notes;
        modal.style.display = "block"; // Show the modal
    };
});

// When the user clicks on the close button, close the modal
closeBtn.onclick = function() {
    modal.style.display = "none";
};

// When the user clicks anywhere outside the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
};
</script>

<script src="../js/admin_script.js"></script>

</body>
</html>
