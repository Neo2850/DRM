<?php
include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}

include 'components/wishlist_cart.php';

// Handle "Add to Cart" action
if (isset($_POST['add_to_cart'])) {
    // Your add to cart logic goes here (e.g., saving to session or database)
    $_SESSION['swal_message'] = "Item added to cart!";
    $_SESSION['swal_type'] = "success";  // You can use 'success', 'error', etc.
    header("Location: shop.php");
    exit();
}

// Handle "Buy Now" action
if (isset($_POST['buy_now'])) {
    $product_id = $_POST['pid'];
    $product_name = $_POST['name'];
    $product_price = $_POST['price'];
    $product_qty = $_POST['qty'];
    $product_image = $_POST['image'];
    $product_note = $_POST['note'];

    // Storing product details in session for checkout
    $_SESSION['product_details'] = [
        'id' => $product_id,
        'name' => $product_name,
        'price' => $product_price,
        'qty' => $product_qty,
        'image' => $product_image,
        'note' => $product_note,
        'pmethod' => "",
    ];

    // Redirect to checkout page
    header("Location: checkout.php?pid=$product_id");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop</title>

    <!-- font awesome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- custom css file link -->
    <link rel="stylesheet" href="css/style.css">

    <!-- SweetAlert CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
<?php include 'components/user_header.php'; ?>

<section class="shop-container">

    <!-- Search Bar and Category Dropdown -->
    <section class="search-container">
        <form action="shop.php" method="get" style="display: flex; margin-bottom: 20px;">
            <input 
                type="text" 
                name="search" 
                placeholder="Search by product name..." 
                value="<?= isset($_GET['search']) ? $_GET['search'] : ''; ?>" 
                style="flex: 1; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
            <button 
                type="submit" 
                style="background-color: #FF8105; color: white; padding: 10px 20px; border: none; border-radius: 5px; margin-left: 10px;" 
                onmouseover="this.style.backgroundColor='#333';" 
                onmouseout="this.style.backgroundColor='#FF8105';">
                Search
            </button>
        </form>
    </section>

    <!-- Products Section -->
    <section class="products">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h1 class="heading" style="padding:10px; border-radius:14px; background:gray; color:white;">Products</h1>
            <!-- Category Dropdown -->
            <div class="category-dropdown">
                <form action="shop.php" method="get">
                    <select 
                        name="category" 
                        id="category" 
                        onchange="this.form.submit()" 
                        style="padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
                        <option value="">All Categories</option>
                        <?php
                        $select_categories = $conn->prepare("SELECT DISTINCT category FROM `products`");
                        $select_categories->execute();
                        if ($select_categories->rowCount() > 0) {
                            while ($category = $select_categories->fetch(PDO::FETCH_ASSOC)) {
                                $selected = isset($_GET['category']) && $_GET['category'] == $category['category'] ? 'selected' : '';
                                echo "<option value='{$category['category']}' $selected>{$category['category']}</option>";
                            }
                        } else {
                            echo '<option value="">No Categories Available</option>';
                        }
                        ?>
                    </select>
                </form>
            </div>
        </div>

        <div class="box-container" style="padding: 30px 20px; border-radius: 20px; background-color: rgba(0, 0, 0, .5);">
            <?php
            // Build query for filtering products
            $filter_query = "SELECT * FROM `products` WHERE 1";
            $params = [];

            // Handle category filtering
            if (isset($_GET['category']) && !empty($_GET['category'])) {
                $filter_query .= " AND category = :category";
                $params[':category'] = $_GET['category'];
            }

            // Handle search filtering
            if (isset($_GET['search']) && !empty($_GET['search'])) {
                $filter_query .= " AND name LIKE :search";
                $params[':search'] = '%' . $_GET['search'] . '%';
            }

            $select_products = $conn->prepare($filter_query);
            $select_products->execute($params);

            if ($select_products->rowCount() > 0) {
                while ($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)) {
            ?>
            <form action="" method="post" class="box" style="height: 433px;">
                <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
                <input type="hidden" name="name" value="<?= substr($fetch_product['name'], 0, 25); ?>">
                <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
                <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">
                <a href="quick_view.php?pid=<?= $fetch_product['id']; ?>" class="fas fa-eye"></a>
                <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">

                <div class="name"><?= substr($fetch_product['name'], 0, 25); ?></div>
                <div class="flex">
                    <div class="price"><span>₱ </span><?= $fetch_product['price']; ?><span>.00</span></div>
                    <?php if ($fetch_product['stock'] > 0): ?>
                        <span style="font-size: 16px;">Qty: </span>
                        <input type="number" name="qty" class="qty" min="1" max="<?= $fetch_product['stock']; ?>" value="1">
                    <?php endif; ?>
                </div>
                <?php if ($fetch_product['discount_qnty'] > 0): ?>
                    <p style="color: green; font-size: 12px;">Discount Available</p>
                <?php endif; ?>
                <div class="price" style="color:orange; font-weight: bold; font-size:14px;"><span>Stock: </span><?= $fetch_product['stock']; ?></div>

                <!-- Add to Cart and Buy Now Buttons -->
                <?php if ($fetch_product['stock'] <= 0): ?>
                    <input type="submit" value="Out of Stock" class="btn" disabled style="background-color: #f2f2f2; color: #666666;">
                <?php else: ?>
                    <div>
                        <input type="submit" value="Buy Now" name="buy_now" class="btn">
                        <input type="submit" value="Add to Cart" name="add_to_cart" class="btn">
                    </div>
                <?php endif; ?>
            </form>
            <?php
                }
            } else {
                echo '<p class="empty">No products found!</p>';
            }
            ?>
        </div>
    </section>
</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

<script>
    // Display SweetAlert after action
    <?php
    if (isset($_SESSION['swal_message'])) {
        echo "
        Swal.fire({
            icon: '{$_SESSION['swal_type']}',
            title: '{$_SESSION['swal_message']}',
            showConfirmButton: false,
            timer: 1500
        });
        ";
        unset($_SESSION['swal_message']);
        unset($_SESSION['swal_type']);
    }
    ?>
</script>

</body>
</html>
