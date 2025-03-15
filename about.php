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
    <title>About Us</title>

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />

    <!-- Font Awesome CDN Link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- Custom CSS File -->
    <link rel="stylesheet" href="css/style.css">

    <style>
        .heading {
            font-size: 3rem;
            text-align: center;
            margin-bottom: 20px;
            color: #333;
            text-transform: uppercase;
        }

        .about {
            padding: 40px 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .about .row {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 20px;
        }

        .about .image img {
            width: 80%;
            border-radius: 50%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .about .content {
            max-width: 600px;
            text-align: left;
            padding: 20px 50px;
    background: white;
    border-radius: 24px;
        }

        .about .content h3 {
            font-size: 2rem;
            color: #1775bb;
            margin-bottom: 10px;
        }

        .about .content p {
            font-size: 1rem;
            line-height: 1.8;
            color: #666;
            margin-bottom: 20px;
        }

        .about .content .btn {
            background: #1775bb;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            text-transform: uppercase;
            font-size: 1rem;
            transition: background 0.3s;
        }

        .about .content .btn:hover {
            background: #145a90;
        }

        .reviews {
            padding: 40px 20px;
            background: #f9f9f9;
        }

        .reviews .swiper {
            padding: 20px 0;
        }

        .reviews .swiper-slide {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
        }

        .reviews .swiper-slide img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .reviews .swiper-slide p {
            font-size: 2rem;
            line-height: 1.6;
            color: #666;
            margin-bottom: 15px;
        }

        .reviews .swiper-slide .stars i {
            color: #ffc107;
        }

        .reviews .swiper-slide h3 {
            font-size: 1rem;
            color: #333;
        }
    </style>
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="about">
    <div class="row">
        <div class="image">
            <img src="img/logo_1.PNG" style="width: 80%;" alt="Company Logo">
        </div>

        <?php
        $select_cms = $conn->prepare("SELECT * FROM `cms_tb` LIMIT 1");
        $select_cms->execute();
        $cms_data = $select_cms->fetch(PDO::FETCH_ASSOC);

        $home_details = '';
        $about_details = '';

        if ($cms_data) {
            $home_details = $cms_data['home_details'];
            $about_details = $cms_data['about_details'];
        }
        ?>

        <div class="content">
            <h3>DRM Roofing Glass and Aluminum And Iron Works</h3>
            <p><?php echo $about_details; ?></p>
            <a href="contact.php" class="btn">Contact Us</a>
        </div>
    </div>
</section>

<section class="reviews">
    <h1 class="heading">Client Reviews</h1>

    <div class="swiper reviews-slider">
        <div class="swiper-wrapper">

            <div class="swiper-slide slide">
   <img src="images/neo.jpg" alt="">
   <p>"I recently upgraded our office with the latest hardware solutions from DRM Roofing Glass and Aluminum And Iron Works, and I couldn’t be happier with the results. Their team provided insightful recommendations tailored to our specific needs, and the entire setup process was seamless. The quality and durability of the hardware have drastically reduced downtime, allowing our team to work more efficiently than ever."</p>
   <div class="stars">
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
      <i class="fas fa-star-half-alt"></i>
   </div>
   <h3>Neo Jierou A. Apostol</h3>
</div>

<div class="swiper-slide slide">
   <img src="images/jonel.jpg" alt="">
   <p>"What stood out was DRM Roofing Glass and Aluminum And Iron Works commitment to customer service. They didn’t just provide hardware; they offered ongoing support and ensured everything was working perfectly. The new equipment is not only fast and reliable but also integrates seamlessly with our existing systems."</p>
   <div class="stars">
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
      <i class="fas fa-star-half-alt"></i>
   </div>
   <h3>Jonel Garcia</h3>
</div>

<div class="swiper-slide slide">
   <img src="images/norman.jpg" alt="">
   <p>"The customer service at Escalante Garments Trading is exceptional! They go above and beyond to ensure I'm satisfied with each purchase. The quality is unbeatable – I know I'm getting the best every time."</p>
   <div class="stars">
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
      <i class="fas fa-star-half-alt"></i>
   </div>
   <h3>Norman Certifico<h3>
</div>

<div class="swiper-slide slide">
   <img src="images/adriano.jpg" alt="">
   <p>"We decided to switch to DRM Roofing Glass and Aluminum And Iron Works hardware solutions to improve our office’s performance and have been extremely satisfied with the outcome. The new equipment is fast, reliable, and built to handle our daily workload with ease."</p>
   <div class="stars">
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
      <i class="fas fa-star-half-alt"></i>
   </div>
   <h3>Adriano Garcia</h3>
</div>

<div class="swiper-slide slide">
   <img src="images/Glenn.jpg" alt="">
   <p>"They took the time to understand our needs and budget, recommending solutions that met both. Their customer support team was also fantastic, responding promptly to any questions we had along the way."</p>
   <div class="stars">
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
      <i class="fas fa-star-half-alt"></i>
   </div>
   <h3>Glenn Halili</h3>
</div>

<div class="swiper-slide slide">
   <img src="images/Juluis.jpg" alt="">
   <p>"The new hardware has worked flawlessly since day one, and we feel confident knowing we have a reliable partner we can count on."</p>
   <div class="stars">
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
      <i class="fas fa-star-half-alt"></i>
   </div>
   <h3>Julius Brillio</h3>
</div>

<div class="swiper-slide slide">
   <img src="images/nash.jpg" alt="">
   <p>"After our first experience with DRM Roofing Glass and Aluminum And Iron Works hardware, we knew we had found a long-term partner. They helped us upgrade several of our older systems, bringing everything up to date with minimal disruptions."</p>
   <div class="stars">
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
      <i class="fas fa-star-half-alt"></i>
   </div>
   <h3>Gailfred Nash</h3>
</div>
        </div>
        <div class="swiper-pagination"></div>
    </div>
</section>

<?php include 'components/footer.php'; ?>

<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
<script src="js/script.js"></script>

<script>
    var swiper = new Swiper(".reviews-slider", {
        loop: true,
        spaceBetween: 20,
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        breakpoints: {
            0: {
                slidesPerView: 1,
            },
            768: {
                slidesPerView: 2,
            },
            991: {
                slidesPerView: 3,
            },
        },
    });
</script>

</body>
</html>
