<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
}

// Get the current page number from the query string, defaulting to page 1 if not set
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$records_per_page = 5; // Number of records per page
$offset = ($page - 1) * $records_per_page; // Calculate the offset for the SQL query

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Feedback</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_style.css">
    <div style="
   position: absolute;
   top: 0;left: 0;
   width: 100%;
   height: 100vh;
   background: #282828;
   z-index: -1;
   opacity: 0.6;
"></div>
    <style>
        body {
            position: relative;
            padding: 0 0 0 220px;
            background: url(../img/background.png) no-repeat center;
            background-size: cover;
            padding: 100px;
        }

        .container {
            margin: 20px auto;
            max-width: 1000px;
            margin-left: 280px;
        }

        h1 {
            text-align: center;
            color: white;
            background-color: gray;
            padding: 10px;
            color: white;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 14px;
        }

        table th, table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        table th {
            color: black;
        }

        table tr:nth-child(even) {
            background: #f9f9f9;
        }

        table tr:hover {
            background: #f1f1f1;
        }

        .empty {
            text-align: center;
            color: #888;
            margin: 20px 0;
        }

        tr {
            background: white;
            font-size: 14px;
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .pagination a {
            padding: 10px 15px;
            margin: 0 5px;
            text-decoration: none;
            background-color: #FF8105;
            color: white;
            border-radius: 5px;
        }

        .pagination a:hover {
            background-color: #333;
        }
    </style>
</head>
<body>

<?php include '../components/admin_header.php'; ?>
<?php include '../components/nav.php'; ?>

<div class="container">
    <h1>All Feedback</h1>
    
    <?php
    // Fetch feedback data with pagination
    $select_feedback = $conn->prepare("SELECT * FROM `feedback` ORDER BY id DESC LIMIT :offset, :limit");
    $select_feedback->bindParam(':offset', $offset, PDO::PARAM_INT);
    $select_feedback->bindParam(':limit', $records_per_page, PDO::PARAM_INT);
    $select_feedback->execute();

    if ($select_feedback->rowCount() > 0) {
    ?>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Rating</th>
                    <th>Message</th>
                    <th>Submitted At</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = $select_feedback->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['rating']) . " ⭐</td>";
                    echo "<td>" . htmlspecialchars($row['message']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    <?php
    } else {
        echo "<p class='empty'>No feedback available yet.</p>";
    }

    // Get the total number of feedback records to calculate the total pages
    $select_count = $conn->prepare("SELECT COUNT(*) FROM `feedback`");
    $select_count->execute();
    $total_records = $select_count->fetchColumn();
    $total_pages = ceil($total_records / $records_per_page);

    ?>
<!-- Pagination -->
<div class="pagination">
    <?php if ($page > 1): ?>
        <a href="?page=1">First</a>
        <a href="?page=<?= $page - 1; ?>">Previous</a>
    <?php endif; ?>

    <!-- Display page numbers -->
    <?php
    // Display numbers before current page
    $start = max(1, $page - 2);
    $end = min($total_pages, $page + 2);

    for ($i = $start; $i <= $end; $i++): ?>
        <a href="?page=<?= $i; ?>" class="<?= $i == $page ? 'active' : ''; ?>"><?= $i; ?></a>
    <?php endfor; ?>

    <!-- Display next and last page buttons -->
    <?php if ($page < $total_pages): ?>
        <a href="?page=<?= $page + 1; ?>">Next</a>
        <a href="?page=<?= $total_pages; ?>">Last</a>
    <?php endif; ?>
</div>



<script src="../js/admin_script.js"></script>

</body>
</html>
