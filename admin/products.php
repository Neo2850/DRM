<?php
include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
}

if (isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $price = $_POST['price'];
    $price = filter_var($price, FILTER_SANITIZE_STRING);
    $stock = $_POST['stock'];
    $stock = filter_var($stock, FILTER_SANITIZE_STRING);
    $details = $_POST['details'];
    $details = filter_var($details, FILTER_SANITIZE_STRING);
    $category = $_POST['category'];
    $category = filter_var($category, FILTER_SANITIZE_STRING);

    $image_01 = $_FILES['image_01']['name'];
    $image_01 = filter_var($image_01, FILTER_SANITIZE_STRING);
    $image_size_01 = $_FILES['image_01']['size'];
    $image_tmp_name_01 = $_FILES['image_01']['tmp_name'];
    $image_folder_01 = '../uploaded_img/' . $image_01;

    $image_02 = $_FILES['image_02']['name'];
    $image_02 = filter_var($image_02, FILTER_SANITIZE_STRING);
    $image_size_02 = $_FILES['image_02']['size'];
    $image_tmp_name_02 = $_FILES['image_02']['tmp_name'];
    $image_folder_02 = '../uploaded_img/' . $image_02;

    $image_03 = $_FILES['image_03']['name'];
    $image_03 = filter_var($image_03, FILTER_SANITIZE_STRING);
    $image_size_03 = $_FILES['image_03']['size'];
    $image_tmp_name_03 = $_FILES['image_03']['tmp_name'];
    $image_folder_03 = '../uploaded_img/' . $image_03;

    $select_products = $conn->prepare("SELECT * FROM `products` WHERE name = ?");
    $select_products->execute([$name]);

    if ($select_products->rowCount() > 0) {
        $message[] = 'Product name already exists!';
    } else {
        $insert_products = $conn->prepare("INSERT INTO `products`(name, details, price, stock, image_01, image_02, image_03, category) VALUES(?,?,?,?,?,?,?,?)");
        $insert_products->execute([$name, $details, $price, $stock, $image_01, $image_02, $image_03, $category]);

        if ($insert_products) {
            if ($image_size_01 > 20000000 || $image_size_02 > 20000000 || $image_size_03 > 20000000) {
                $message[] = 'Image size is too large!';
            } else {
                move_uploaded_file($image_tmp_name_01, $image_folder_01);
                move_uploaded_file($image_tmp_name_02, $image_folder_02);
                move_uploaded_file($image_tmp_name_03, $image_folder_03);
                $message[] = 'New product added!';
            }
        }
    }
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_product_image = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
    $delete_product_image->execute([$delete_id]);
    $fetch_delete_image = $delete_product_image->fetch(PDO::FETCH_ASSOC);
    unlink('../uploaded_img/' . $fetch_delete_image['image_01']);
    unlink('../uploaded_img/' . $fetch_delete_image['image_02']);
    unlink('../uploaded_img/' . $fetch_delete_image['image_03']);
    $delete_product = $conn->prepare("DELETE FROM `products` WHERE id = ?");
    $delete_product->execute([$delete_id]);
    $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE pid = ?");
    $delete_cart->execute([$delete_id]);

    header('location:products.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_style.css">
</head>

<style>
   body {
      padding: 70px 0 0 220px;
            background: url(../img/background.png) no-repeat center;
            background-size: cover;
            position: relative;
}

.heading {
    text-align: center;
    margin-top: 20px;
}

.show-products {
    padding: 20px;
}

.products-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.products-table th, .products-table td {
    padding: 10px;
    text-align: center;
    border: 1px solid #ddd;
}

.products-table th {
    background-color: #f2f2f2;
}

.products-table td img {
    max-width: 100px;
    border-radius: 5px;
}

.add-product-popup {
    background: white;
    color: black;
    padding: 20px;
    position: absolute;
    top: 60%;
    left: 55%;
    transform: translate(-50%, -50%);
    width: 80%;
    max-width: 600px;
    border-radius: 10px;
    box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.5);
    font-size: 14px;
}

input[type="file"] {
    padding: 10px;
    font-size: 16px;
}

.inputBox {
    margin-bottom: 20px;
}

.inputBox input, .inputBox select, .inputBox textarea {
    width: 100%;
    padding: 10px;
    font-size: 16px;
    border-radius: 5px;
    border: 1px solid #ddd;
}

.pagination {
    text-align: center;
    margin-top: 20px;
}

.pagination a {
    margin: 0 5px;
    padding: 10px 15px;
    background-color: #3498db;
    color: white;
    text-decoration: none;
    border-radius: 5px;
}

.pagination a:hover {
    background-color: #2980b9;
}

.page-link {
    margin: 0 5px;
    padding: 10px 15px;
    background-color: #3498db;
    color: white;
    text-decoration: none;
    border-radius: 5px;
}

.page-link:hover {
    background-color: #2980b9;
}
tr {
   background-color: white;
   font-size: 14px;
}

#close-modal {
    position: absolute;
    top: 10px;
    right: 10px;
    background: transparent;
    border: none;
    color: black;
    font-size: 24px;
    cursor: pointer;
}

</style>
<body > 

<?php include '../components/admin_header.php'; ?>
<?php include '../components/nav.php'; ?>

<section class="show-products">

    <h1 class="heading" style="color: white; background-color: gray;
    padding: 10px;
    color: white;
    text-align: center;">Products</h1>
 
    <button class="btn" style="width: auto;" id="add-product-btn" onclick="toggleAddProductForm()">Add Product</button>

    <div id="add-product-form" class="add-product-popup" style="display:none;">
    <button type="button" id="close-modal" onclick="toggleAddProductForm()" style="position: absolute; top: 10px; right: 10px; background: transparent; border: none; color: black; font-size: 24px; cursor: pointer;">&times;</button>
    <form action="" method="post" enctype="multipart/form-data"
   style="
      border: none;
   ">
      <div class="flex">
         <div class="inputBox">
            <span>Product Name <span style="color:red">*</span></span>
            <input type="text" class="box" required maxlength="100" placeholder="Enter product name" name="name">
         </div>
         <div class="inputBox">
            <span>Product Price <span style="color:red">*</span></span>
            <input type="number" min="0" class="box" required max="9999999999" placeholder="Enter product price" onkeypress="if(this.value.length == 10) return false;" name="price">
         </div>
         <div class="inputBox">
            <span>Product Stock <span style="color:red">*</span></span>
            <input type="number" min="0" class="box" required max="9999999999" placeholder="Enter product stock" onkeypress="if(this.value.length == 10) return false;" name="stock">
         </div>
        <div class="inputBox">
            <span>Image 01 <span style="color:red">*</span></span>
            <input type="file" name="image_01" accept="image/jpg, image/jpeg, image/png, image/webp" class="box" required>
        </div>
        <div class="inputBox">
            <span>Image 02 <span style="color:red">*</span></span>
            <input type="file" name="image_02" accept="image/jpg, image/jpeg, image/png, image/webp" class="box">
        </div>
        <div class="inputBox">
            <span>Image 03 <span style="color:red">*</span></span>
            <input type="file" name="image_03" accept="image/jpg, image/jpeg, image/png, image/webp" class="box">
        </div>
        <div class="inputBox">
   <span>Product Category <span style="color:red">*</span></span>
   <select name="category" style="font-size: 18px; width: 100%; height: 50px; background-color: #f2f2f2;" required>
      <option selected>Hardware</option>
      <option>Wires</option>
      <option>Glass</option>
      <option>Kitchen hardware</option>
      <option>Locks</option>
      <option>Plumbing</option>
      <option>Wood products</option>
      <option>Curtain hardware</option>
      <option>Electrical</option>
      <option>Hooks</option>
      <option>Keys</option>
      <option>Nails and screws</option>
      <option>Fasteners</option>
      <option>Hand tools</option>
      <option>Cabinet hardware</option>
      <option>Door hardware</option>
      <option>Paints</option>
      <option>Aluminum hardware</option>
      <option>Steel Bar</option>
      <option>Roofing</option>
   </select>
</div>

         <div class="inputBox">
            <span>Product Details <span style="color:red">*</span></span>
            <textarea name="details" placeholder="Enter product details" class="box" required maxlength="500" cols="30" rows="10"></textarea>
         </div>
      </div>
      
      <input type="submit" value="add product" class="btn" name="add_product">
   </form>
    </div>

    <table class="products-table">
        <thead>
        <tr>
            <th>Image</th>
            <th>Name</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Category</th>
            <th>Details</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php
        // Pagination logic
        $limit = 4; // Products per page
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $start = ($page - 1) * $limit;

        $select_products = $conn->prepare("SELECT * FROM `products` LIMIT ?, ?");
        $select_products->bindParam(1, $start, PDO::PARAM_INT);
        $select_products->bindParam(2, $limit, PDO::PARAM_INT);
        $select_products->execute();

        if ($select_products->rowCount() > 0) {
            while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <tr>
                    <td><img src="../uploaded_img/<?= $fetch_products['image_01']; ?>" alt="Product Image" width="100"></td>
                    <td><?= $fetch_products['name']; ?></td>
                    <td>₱<?= $fetch_products['price']; ?></td>
                    <td><?= $fetch_products['stock']; ?></td>
                    <td><?= $fetch_products['category']; ?></td>
                    <td><?= $fetch_products['details']; ?></td>
                    <td>
                        <a href="update_product.php?update=<?= $fetch_products['id']; ?>" class="option-btn">Update</a>
                        <a href="products.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('Delete this product?');">Delete</a>
                    </td>
                </tr>
                <?php
            }
        } else {
            echo '<tr><td colspan="7" class="empty">No products added yet!</td></tr>';
        }
        ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="pagination">
        <?php
        // Calculate total number of pages
        $total_products = $conn->query("SELECT COUNT(*) FROM `products`")->fetchColumn();
        $total_pages = ceil($total_products / $limit);

        // Display pagination links
        for ($i = 1; $i <= $total_pages; $i++) {
            echo '<a href="products.php?page=' . $i . '" class="page-link">' . $i . '</a>';
        }
        ?>
    </div>

</section>

<script>
    function toggleAddProductForm() {
    const form = document.getElementById('add-product-form');
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
}

// Close modal if clicked outside of the form
window.onclick = function(event) {
    const form = document.getElementById('add-product-form');
    if (event.target === form) {
        form.style.display = 'none';
    }
}

</script>

<script src="../js/admin_script.js"></script>

</body>
</html>
