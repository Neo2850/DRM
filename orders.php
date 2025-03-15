<?php
include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>

    <!-- Font Awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">

    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .heading {
            text-align: center;
            font-size: 2em;
            margin-top: 30px;
            margin-bottom: 20px;
            color: #2c3e50;
        }

        .box-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            padding: 20px;
            justify-items: center;
        }

        .box {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 100%;
            max-width: 350px;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .box:hover {
            transform: translateY(-10px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .box p {
            margin: 10px 0;
            font-size: 1em;
            line-height: 1.6;
        }

        .box p span {
            font-weight: bold;
            color: #34495e;
        }

        .box p span.payment-status {
            font-weight: normal;
            color: #e74c3c;
        }

        .payment-status.completed {
            color: #27ae60;
        }

        .payment-status.pending {
            color: #e74c3c;
        }

        .payment-status.processing {
            color: #f39c12;
        }

        .empty {
            text-align: center;
            font-size: 1.2em;
            color: #7f8c8d;
            margin-top: 50px;
        }

        .btn {
            display: block;
            width: 100%;
            background: #2980b9;
            color: #fff;
            padding: 10px;
            font-size: 1.1em;
            border-radius: 4px;
            text-align: center;
            text-decoration: none;
            margin-top: 20px;
        }

        .btn:hover {
            background: #1abc9c;
        }

        .box p {
            font-size: 0.95em;
        }

        .button-container {
    display: flex;
    justify-content: space-between;
    gap: 10px;
    margin-top: 20px;
}

.return-btn {
    background: #27ae60;
}

.return-btn:hover {
    background: #2ecc71;
}

.cancel-btn {
    background: #e74c3c;
}

.cancel-btn:hover {
    background: #c0392b;
}

    </style>

</head>

<body>

    <?php include 'components/user_header.php'; ?>

    <section class="orders">
        <h1 class="heading">Placed Orders</h1>

        <div class="box-container" style="display: grid
;">
            <?php
            if ($user_id == '') {
                echo '<p class="empty">Please login to see your orders.</p>';
            } else {
                $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?");
                $select_orders->execute([$user_id]);
                if ($select_orders->rowCount() > 0) {
                    while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
                        // Calculate the due date if the payment method is 'credit'
                        if ($fetch_orders['method'] == 'credit') {
                            $placed_on = new DateTime($fetch_orders['placed_on']);
                            $due_date = $placed_on->modify('+3 days')->format('Y-m-d');
                        }
            ?>
                        <div class="box" style="min-height: 560px; display: flex; flex-direction: column; justify-content: space-between;">
                        <div>
                            <p>Order ID #: <span><?= $fetch_orders['order_id']; ?></span></p>
                            <p>Placed On: <span><?= $fetch_orders['placed_on']; ?></span></p>
                            <p>Name: <span><?= $fetch_orders['name']; ?></span></p>
                            <p>Email: <span><?= $fetch_orders['email']; ?></span></p>
                            <p>Number: <span><?= $fetch_orders['number']; ?></span></p>
                            <p>Address: <span><?= $fetch_orders['address']; ?></span></p>
                            <p>Payment Method: <span><?= $fetch_orders['method']; ?></span></p>
                            <?php if ($fetch_orders['method'] == 'credit') { ?>
                                <p>Due Date: <span><?= $due_date; ?></span></p>
                            <?php } ?>
                            <p>Your Orders: <span><?= $fetch_orders['total_products']; ?></span></p>
                            <p>Total Price: <span>₱ <?= $fetch_orders['total_price']; ?>/-</span></p>
                            <p>Payment Status: <span class="payment-status <?= strtolower($fetch_orders['payment_status']); ?>"><?= ucfirst($fetch_orders['payment_status']); ?></span></p>
                        </div>
                        <div class="button-container">
                            <a href="#" class="btn return-btn">Return Item</a>
                            <a href="#" class="btn cancel-btn">Cancel Item</a>
                        </div>
                    </div>

            <?php
                    }
                } else {
                    echo '<p class="empty">No orders placed yet!</p>';
                }
            }
            ?>

        </div>
    </section>

    <?php include 'components/footer.php'; ?>

    <script src="js/script.js"></script>

</body>

</html>
