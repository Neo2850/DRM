<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

include 'components/wishlist_cart.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>home</title>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Toastify/1.12.0/Toastify.min.css">
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style4.css">

   <?php
// Check if session variable is set for the address success message
if(isset($_SESSION['address_added'])){
   // Display JavaScript alert for the success message
   echo '<script>
            alert("'.$_SESSION['address_added'].'");
         </script>';
   
   // Clear the session variable to prevent it from showing again
   unset($_SESSION['address_added']);
}
?>
   
   <style type="text/css">

      .heading {
         font-size: 3rem;
         text-align: left;
      }

      .home-bg{
         /* filter: invert(1); */
      }

      .home-bg img {
         /* filter: invert(100%); */
      }

      .home-bg .content {
         /* filter: invert(100%); */
      }

      .announcements {
   padding: 20px;
   background-color: #f9f9f9;
}

.announcements .heading {
   font-size: 2.5rem;
   margin-bottom: 20px;
   text-align: center;
}

.announcement-list {
   display: flex;
   flex-direction: column;
   gap: 20px;
}

.announcement-item {
   background-color: #fff;
   padding: 15px;
   border-radius: 10px;
   box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
}

.announcement-item h3 {
   font-size: 1.8rem;
   margin-bottom: 10px;
}

.announcement-item p {
   font-size: 1.2rem;
   color: #666;
}

   </style>
   

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<div class="home-bg">

<section class="home">

   <div class="swiper home-slider" >
   
   <div class="swiper-wrapper">

      <div class="swiper-slide slide">

         <?php
            // Fetch current data from `cms_tb`
            $select_cms = $conn->prepare("SELECT * FROM `cms_tb` LIMIT 1");
            $select_cms->execute();
            $cms_data = $select_cms->fetch(PDO::FETCH_ASSOC);

            $home_details = '';
            $about_details = '';

            if($cms_data) {
               $home_details = $cms_data['home_details'];
               $about_details = $cms_data['about_details'];
            }
         ?>
         
         <div class="image" style="height: 500px;">       

         <div class="content" style="background-color: rgba(255, 255, 255, 0.7); width: 420px; text-align: center; padding: 30px; border-radius: 20px;">
         <img src="img/logo_1.PNG" alt="" style="margin-top: -100px; width: 280px; border-radius: 100%;"> 
         <p style="color: #ffaa00; margin-top: -70px; font-size: 45px; font-weight: bold;"><?php echo $home_details;?> </p>
            <!-- <span style="color: black; font-weight: bold;">"Fill Your Thirsty."</span><br> -->
            <a href="shop.php" class="btn" >order now</a>
         </div>
         </div>
      </div>


   </div>

      <div class="swiper-pagination"></div>

   </div>

</section>

</div>

<!-- <section class="category">

   <h1 class="heading">Shop by Category</h1>

   <div class="swiper category-slider">

      <div class="swipper swiper-wrapper">

         <a href="category.php?category=laptop" class="swiper-slide slide">
            <img src="images/icon-1.png" alt="">
            <img src="images/concreting-and-masonry.jpg">
            <h3>Concreting and Masonry</h3>
         </a>

         <a href="category.php?category=tv" class="swiper-slide slide">
            <img src="images/marine-plywood-local.jpg" alt="">
            <h3>Marine Plywood Local</h3>
         </a>

         <a href="category.php?category=camera" class="swiper-slide slide">
            <img src="images/PAINTING-SUPPLIES.jpg" alt="">
            <h3>Painting Supplies</h3>
         </a>

         <a href="category.php?category=mouse" class="swiper-slide slide">
            <img src="images/pvc-pipe-1.jpg" alt="">
            <h3>PVC Pipe</h3>
         </a>

         <a href="category.php?category=fridge" class="swiper-slide slide">
            <img src="images/rebars.jpg" alt="">
            <h3>Rebars</h3>
         </a>

         <a href="category.php?category=washing" class="swiper-slide slide">
            <img src="images/ROOFING.jpg" alt="">
            <h3>Roofing</h3>
         </a>

         <a href="category.php?category=smartphone" class="swiper-slide slide">
            <img src="images/STEEL.jpg" alt="">
            <h3>Steel</h3>
         </a>

         <a href="category.php?category=watch" class="swiper-slide slide">
            <img src="images/hafele-door-knob.jpg" alt="">
            <h3>Door Knob</h3>
         </a>

      </div>

   <div class="swiper-pagination"></div>

   </div>

</section> -->

<section class="home-products">

   <h1 class="heading"> Top Products</h1>

   <div class="swiper products-slider">

   <div class="swiper-wrapper">

   <?php
     // Update the query to sort by added_date in descending order
     $select_products = $conn->prepare("SELECT * FROM `products` ORDER BY sales DESC LIMIT 3"); 
     $select_products->execute();
     if($select_products->rowCount() > 0){
      while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="swiper-slide slide" style="height: 383px;">
      <a href="shop.php?pid=<?= $fetch_product['id']; ?>" class="product-link">
         <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
         <div class="name"><?= $fetch_product['name']; ?></div>
         <div class="flex">
            <div class="price"><span>₱ </span><?= $fetch_product['price']; ?><span>.00</span></div>
            <div class="price" style="color:orange; font-weight: bold; font-size:14px;"><span style="color:">Stock: </span><?= $fetch_product['stock']; ?></div>
         </div>
      </a>
      <a href="shop.php?pid=<?= $fetch_product['id']; ?>" class="btn">Shop Now</a>
   </div>
<?php
      }
   } else {
      echo '<p class="empty">No products available!</p>';
   }
?>

   </div>

   <div class="swiper-pagination"></div>

   </div>

</section>

<section class="home-products">

   <h1 class="heading">latest products</h1>

   <div class="swiper products-slider">

   <div class="swiper-wrapper">

   <?php
     // Update the query to sort by added_date in descending order
     $select_products = $conn->prepare("SELECT * FROM `products` ORDER BY added_date DESC LIMIT 3"); 
     $select_products->execute();
     if($select_products->rowCount() > 0){
      while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="swiper-slide slide" style="height: 383px;">
      <a href="shop.php?pid=<?= $fetch_product['id']; ?>" class="product-link">
         <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
         <div class="name"><?= $fetch_product['name']; ?></div>
         <div class="flex">
            <div class="price"><span>₱ </span><?= $fetch_product['price']; ?><span>.00</span></div>
            <div class="price" style="color:orange; font-weight: bold; font-size:14px;"><span style="color:">Stock: </span><?= $fetch_product['stock']; ?></div>
         </div>
      </a>
      <a href="shop.php?pid=<?= $fetch_product['id']; ?>" class="btn">Shop Now</a>
   </div>
<?php
      }
   } else {
      echo '<p class="empty">No products available!</p>';
   }
?>

   </div>

   <div class="swiper-pagination"></div>

   </div>

</section>





<section class="announcements">
   <h1 class="heading">Announcements</h1>

   <div class="announcement-list">
      <?php
      // Fetch announcements from `announcements_tb`
      $select_announcements = $conn->prepare("SELECT * FROM `announcements_tb` ORDER BY created_at DESC");
      $select_announcements->execute();
      if($select_announcements->rowCount() > 0){
         while($fetch_announcement = $select_announcements->fetch(PDO::FETCH_ASSOC)){
      ?>
      <div class="announcement-item">
         <h3><?php echo htmlspecialchars($fetch_announcement['title']); ?></h3>
         <p><?php echo htmlspecialchars($fetch_announcement['content']); ?></p>
      </div>
      <?php
         }
      } else {
         echo '<p class="empty">No announcements available at the moment!</p>';
      }
      ?>
   </div>
</section>


<br><hr><br>

<?php include 'components/footer.php'; ?>

<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<script src="js/script.js"></script>

<script>

var swiper = new Swiper(".home-slider", {
   loop:true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
    },
});

 var swiper = new Swiper(".category-slider", {
   loop:true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   },
   breakpoints: {
      0: {
         slidesPerView: 2,
       },
      650: {
        slidesPerView: 3,
      },
      768: {
        slidesPerView: 4,
      },
      1024: {
        slidesPerView: 5,
      },
   },
});

var swiper = new Swiper(".products-slider", {
   loop:true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   },
   breakpoints: {
      550: {
        slidesPerView: 2,
      },
      768: {
        slidesPerView: 2,
      },
      1024: {
        slidesPerView: 3,
      },
   },
});

</script>

</body>
</html>