<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Navigation</title>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f4f4f9;
        }

        /* Navigation Styling */
        .nav {
            background-color: #2c3e50; /* Dark navy theme */
            color: white;
            width: 250px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.2);
        }

        .nav .bx {
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #34495e; /* Slightly lighter for branding */
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .nav-container {
            padding: 20px 0;
            height: calc(100vh - 60px);
            overflow-y: auto;
        }

        .ul {
            list-style: none;
        }

        .li {
            margin: 5px 0;
        }

        .li a {
            display: flex;
            align-items: center;
            text-decoration: none;
            padding: 10px 20px;
            color: white;
            font-size: 16px;
            border-radius: 5px;
            transition: background-color 0.3s, padding-left 0.3s;
        }

        .li a:hover {
            background-color: #1abc9c; /* Aqua hover */
            padding-left: 30px;
        }

        .li a i {
            margin-right: 10px;
            font-size: 18px;
        }

        /* Responsive Styling */
        @media (max-width: 768px) {
            .nav {
                width: 100%;
                height: auto;
                position: relative;
            }

            .nav-container {
                height: auto;
            }
        }
		svg{
			margin-right: 10px;

		}
    </style>
</head>
<body>

<nav class="nav">
    <div class="bx">Admin Panel</div>
    <div class="nav-container">
        <ul class="ul">
            <?php if ($_SESSION['admin_id'] == "1") { ?>
                <li class="li"><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
                <li class="li"><a href="placed_orders.php"><i class="fas fa-shopping-cart"></i> Placed Orders</a></li>
                <li class="li"><a href="products.php"><i class="fas fa-box"></i> Products</a></li>
                <li class="li"><a href="users_accounts.php"><i class="fas fa-users"></i> Customers</a></li>
                <li class="li"><a href="view_feedback.php"><i class="fas fa-comments"></i> Feedback</a></li>
                <!-- <li class="li"><a href="messages.php"><i class="fas fa-comments"></i> Messages</a></li> -->
                <li class="li"><a href="gcash_account.php"><i class="fas fa-wallet"></i> Payment Options</a></li>
                <li class="li"><a href="inventory.php"><i class="fas fa-warehouse"></i> Inventory Management</a></li>
                <li class="li"><a href="sales_report.php"><i class="fas fa-chart-line"></i> Sales Report</a></li>
                <li class="li"><a href="file_management.php"><i class="fas fa-file-alt"></i> File Management</a></li>
                <li class="li"><a href="cms.php"><i class="fas fa-edit"></i> Content Management</a></li>
                <li class="li"><a href="announcements.php"><i class="fas fa-bullhorn"></i> Announcements</a></li>
                <li class="li"><a href="update_profile.php"><i class="fas fa-user-edit"></i> Update Profile</a></li>
                <li class="li"><a href="admin_accounts.php"><i class="fas fa-user-shield"></i> Admin Accounts</a></li>
            <?php } else { ?>
                <li class="li"><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
                <li class="li"><a href="placed_orders.php"><i class="fas fa-shopping-cart"></i> Placed Orders</a></li>
                <li class="li"><a href="products.php"><i class="fas fa-box"></i> Products</a></li>
            <?php } ?>
        </ul>
    </div>
</nav>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js"></script>
</body>
</html>