<?php
$pageTitle = 'Home';
require_once 'includes/header.php';

// Get featured products
$featuredProducts = getAllProducts();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <!-- Font Awesome (for icons) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>

<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- AOS CSS and JS -->
<link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
</head>

<!-- Styling -->
<style>


</style>

<body>

    
    <!-- Hero Slider Section -->
<!-- ====== HERO SLIDER START ====== -->
<section class="hero-slider" style="overflow: hidden;">
  <div id="mainSlider" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="3000">

    <!-- Indicators -->
    <!-- <div class="carousel-indicators">
      <button type="button" data-bs-target="#mainSlider" data-bs-slide-to="0" class="active" aria-current="true"></button>
      <button type="button" data-bs-target="#mainSlider" data-bs-slide-to="1"></button>
      <button type="button" data-bs-target="#mainSlider" data-bs-slide-to="2"></button>
    </div> -->

    <!-- Slides -->
    <div class="carousel-inner">

      <!-- Slide 1 -->
      <div class="carousel-item active" style="background: url('assets/images/products/living-room-3428529.jpg') center center/cover no-repeat; height: 100vh;">
        <div class="container h-100 d-flex flex-column justify-content-center align-items-center text-center text-white">
          <div class="slider-text">
            <h1 class="display-4 fw-bold animate__animated animate__backInDown">Elegant Home Decor</h1>
            <p class="lead animate__animated animate__fadeInBottomRight animate__delay-1s">Bring style and warmth to your living spaces.</p>
          </div>
        </div>
      </div>

      <!-- Slide 2 -->
      <div class="carousel-item" style="background: url('assets/images/products/sanju-pandita-YBfU7TAS8sU-unsplash.jpg') center center/cover no-repeat; height: 100vh;">
        <div class="container h-100 d-flex flex-column justify-content-center align-items-center text-center text-white">
          <div class="slider-text">
            <h1 class="display-4 fw-bold animate__animated animate__lightSpeedInLeft">Wonderful Decorations</h1>
            <p class="lead animate__animated animate__zoomIn animate__delay-1s">Turn every corner into a masterpiece.</p>
          </div>
        </div>
      </div>

      <!-- Slide 3 -->
      <div class="carousel-item" style="background: url('assets/images/products/visualsofdana-T5pL6ciEn-I-unsplash.jpg') center center/cover no-repeat; height: 100vh;">
        <div class="container h-100 d-flex flex-column justify-content-center align-items-center text-center text-white">
          <div class="slider-text">
            <h1 class="display-4 fw-bold animate__animated animate__rollIn">Discover New Collections</h1>
            <p class="lead animate__animated animate__fadeInUpBig animate__delay-1s">Fresh styles for a fresh new season.</p>
          </div>
        </div>
      </div>

    </div>

    <!-- Controls -->
    <button class="carousel-control-prev" type="button" data-bs-target="#mainSlider" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#mainSlider" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>

  </div>
</section>


<!-- Icon & Text Slider -->

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<div class="container my-5">
  <div class="swiper featuresSwiper">
    <div class="swiper-wrapper align-items-center">

      <div class="swiper-slide" style="width: auto;">
        <div class="icon-box d-flex align-items-center gap-2">
          <i class="fas fa-credit-card"></i>
          <span>Safe Payment With Any Bank Card</span>
        </div>
      </div>

      <div class="swiper-slide" style="width: auto;">
        <div class="icon-box d-flex align-items-center gap-2">
          <i class="fas fa-headset"></i>
          <span>24/7 Support Always Be There for You</span>
        </div>
      </div>

      <div class="swiper-slide" style="width: auto;">
        <div class="icon-box d-flex align-items-center gap-2">
          
          <img src="assets/images/custom_fun.png" alt="" height="25px">
          <span>Products Can Be Customized to Your Needs</span>
        </div>
      </div>

      <div class="swiper-slide" style="width: auto;">
        <div class="icon-box d-flex align-items-center gap-2">
          <img src="assets/images/low_price.png" alt="" height="25px">
          <span>Low Prices Than in Other Stores</span>
        </div>
      </div>

      <div class="swiper-slide" style="width: auto;">
        <div class="icon-box d-flex align-items-center gap-2">
          <i class="fas fa-truck"></i>
          <span>Nationwide Shipping Available</span>
        </div>
      </div>

    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
  new Swiper(".featuresSwiper", {
    loop: true,
    speed: 5000,
    autoplay: {
      delay: 0,
      disableOnInteraction: false,
    },
    slidesPerView: "auto",
    spaceBetween: 40,
    freeMode: true,
    freeModeMomentum: false,
    grabCursor: true
  });
</script>

<!-- Categories Section -->
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />

<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />

<style>
  .swiper {
    width: 100%;
    padding-top: 30px;
    padding-bottom: 30px;
  }
  .swiper-slide {
    display: flex;
    justify-content: center;
    align-items: stretch;
  }
  .category-card {
    width: 100%;
    max-width: 350px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
  }
  .category-card:hover {
    transform: translateY(-5px);
  }
  .category-card img {
    height: 300px;
    object-fit: cover;
  }
  .card-img-overlay {
    background: rgba(0, 0, 0, 0.4);
    transition: background 0.3s;
  }
  .category-card:hover .card-img-overlay {
    background: rgba(0, 0, 0, 0.6);
  }
</style>

<!-- category section -->
<section class="categories py-5 bg-light">
    <div class="container">
      <h2 class="text-center mb-4 new">Shop by Category</h2>

      <!-- Swiper Container -->
      <div class="swiper categorySwiper">
        <div class="swiper-wrapper">
            <?php 
            $categories = getAllCategories();
            foreach ($categories as $category): 
                $productCount = getProductCountByCategory($category['id']);
            ?>
            <div class="swiper-slide">
              <div class="card category-card h-100">
                <a href="shop.php?category=<?php echo htmlspecialchars($category['name']); ?>" class="text-decoration-none">
                  <img src="assets/images/categories/<?php echo htmlspecialchars($category['image']); ?>" 
                      class="card-img" 
                      alt="<?php echo htmlspecialchars($category['name']); ?>">
                  <div class="card-img-overlay d-flex align-items-center justify-content-center">
                    <i class="fas fa-arrow-right text-white fa-2x"></i>
                  </div>
                </a>
                <div class="card-footer bg-white text-center">
                  <h3 class="h5 mb-1"><?php echo htmlspecialchars($category['name']); ?></h3>
                  <p class="text-muted mb-0"><?php echo $productCount; ?> Products</p>
                </div>
              </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Swiper Controls -->
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
        <!-- <div class="swiper-pagination mt-3"></div> -->
      </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
  const categorySwiper = new Swiper(".categorySwiper", {
    loop: true,
    autoplay: {
      delay: 2500,
      disableOnInteraction: false,
      pauseOnMouseEnter: true
    },
    spaceBetween: 30,
    slidesPerView: 1,
    breakpoints: {
      576: { slidesPerView: 2 },
      768: { slidesPerView: 3 },
      992: { slidesPerView: 4 },
      1200: { slidesPerView: 5 }
    },
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev"
    },
    pagination: {
      el: ".swiper-pagination",
      clickable: true
    }
  });
</script>


<!-- New Products Section -->
<section class="featured-products py-5">
    <div class="container">
        <h2 class="text-center mb-4 new">New Products</h2>
        <div class="row">
            <?php 
            $displayProducts = array_slice($featuredProducts, 0, 4); // Show only first 4 products
            foreach ($displayProducts as $product): 
            ?>
            <div class="col-md-3 mb-4">
                <div class="card product-card">
                    <div class="product-image-wrapper" style="height: 250px;">
                        <?php if ($product['sale_price'] && $product['sale_price'] < $product['price']): ?>
                            <div class="discount-badge">
                                <?php 
                                $discount = round((($product['price'] - $product['sale_price']) / $product['price']) * 100);
                                echo $discount . '% OFF';
                                ?>
                            </div>
                        <?php endif; ?>

                          
                        <a href="product.php?id=<?php echo $product['id']; ?>">
                                        <img src="assets/images/products/<?php echo htmlspecialchars($product['image'] ?: 'default.jpg'); ?>" 
                                             class="card-img-top w-100" 
                                             alt="<?php echo htmlspecialchars($product['name']); ?>"
                                             style="object-fit: cover;">
                                    </a>
                                    
                        
                       
                      
                        
                        <div class="hover-icons">
                            <a href="product.php?id=<?php echo $product['id']; ?>" class="btn">
                                <i class="fas fa-eye text-primary"></i>
                            </a>
                            <form action="cart.php" method="POST" class="d-inline add-to-cart-form">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <input type="hidden" name="action" value="add">
                                <button type="submit" class="btn">
                                    <i class="fas fa-shopping-cart text-success"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="price-container">
                                <?php if ($product['sale_price'] && $product['sale_price'] < $product['price']): ?>
                                    <!-- <span class="original-price">₹<?php echo number_format($product['price'], 2); ?></span> -->
                                    <span class="sale-price">₹<?php echo number_format($product['sale_price'], 2); ?></span>
                                <?php else: ?>
                                    <span class="sale-price">₹<?php echo number_format($product['price'], 2); ?></span>
                                <?php endif; ?>
                            </div>
                            <span class="badge bg-success">New</span>
                        </div>
                        <h3 class="card-title h5"><?php echo htmlspecialchars($product['name']); ?></h3>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php if (count($featuredProducts) > 4): ?>
        <div class="text-center mt-4">
            <a href="shop.php" class="btn btn-primary btn-lg">
                <i class="fas fa-store"></i> View All Products
            </a>
        </div>
        <?php endif; ?>
    </div>
</section>


<!-- Reels Section -->
<section class="reels-section">
  <div class="video-row">
    <div class="video-half">
      <video muted autoplay loop playsinline width="300">
        <source src="assets/video/video1.mp4" type="video/mp4">
      </video>
    </div>
    <div class="video-half">
      <video muted autoplay loop playsinline width="300">
        <source src="assets/video/video3.mp4" type="video/mp4">
      </video>
    </div>
  </div>
  </section>


<!-- FAQ Section with Enhanced Animations -->
<section class="faq-section py-5 bg-light">
  <div class="container">
    <h2 class="text-center mb-5" data-aos="fade-up">Frequently Asked Questions</h2>

    <div class="accordion" id="faqAccordion">

      <!-- FAQ Item 1 -->
      <div class="accordion-item" data-aos="fade-right" data-aos-delay="100">
        <h2 class="accordion-header" id="headingOne">
          <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
            <i class="fas fa-truck me-2 icon-rotate"></i> How long does delivery take?
          </button>
        </h2>
        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
          <div class="accordion-body">
            Delivery usually takes 5–10 business days, depending on your location and stock availability.
          </div>
        </div>
      </div>

      <!-- FAQ Item 2 -->
      <div class="accordion-item" data-aos="fade-left" data-aos-delay="200">
        <h2 class="accordion-header" id="headingTwo">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
            <i class="fas fa-tools me-2 icon-rotate"></i> Do you offer installation services?
          </button>
        </h2>
        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
          <div class="accordion-body">
            Yes! Free installation is provided for most products. Details will be shared at the time of delivery.
          </div>
        </div>
      </div>

      <!-- FAQ Item 3 -->
      <div class="accordion-item" data-aos="fade-right" data-aos-delay="300">
        <h2 class="accordion-header" id="headingThree">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
            <i class="fas fa-undo me-2 icon-rotate"></i> What is your return policy?
          </button>
        </h2>
        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
          <div class="accordion-body">
            Products can be returned within 7 days of delivery if they are unused and in original condition.
          </div>
        </div>
      </div>

      <!-- FAQ Item 4 -->
      <div class="accordion-item" data-aos="fade-left" data-aos-delay="400">
        <h2 class="accordion-header" id="headingFour">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
            <i class="fas fa-pencil-ruler me-2 icon-rotate"></i> Can I customize the furniture?
          </button>
        </h2>
        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
          <div class="accordion-body">
            Absolutely! Contact us to discuss your customization requirements. We love creating unique pieces!
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- Why Choose Us Section -->
<section class="why-choose-us py-5">
    <div class="container">
        <h2 class="text-center mb-5" data-aos="fade-down" data-aos-duration="800">Why Choose FurniCraft</h2>
        <div class="row g-4">
            <!-- Features -->
            <div class="col-md-4" data-aos="zoom-in" data-aos-delay="100">
                <div class="feature-card-glow text-center p-4 h-100">
                    <div class="feature-icon mb-4">
                        <i class="fas fa-gem fa-3x text-primary"></i>
                    </div>
                    <h3 class="h4 mb-3">Premium Quality</h3>
                    <p class="text-muted">Only the finest materials and top craftsmanship for furniture that lasts generations.</p>
                </div>
            </div>

            <div class="col-md-4" data-aos="zoom-in" data-aos-delay="200">
                <div class="feature-card-glow text-center p-4 h-100">
                    <div class="feature-icon mb-4">
                        <i class="fas fa-paint-brush fa-3x text-primary"></i>
                    </div>
                    <h3 class="h4 mb-3">Custom Designs</h3>
                    <p class="text-muted">Bring your dream pieces to life with fully customizable designs tailored to you.</p>
                </div>
            </div>

            <div class="col-md-4" data-aos="zoom-in" data-aos-delay="300">
                <div class="feature-card-glow text-center p-4 h-100">
                    <div class="feature-icon mb-4">
                        <i class="fas fa-truck fa-3x text-primary"></i>
                    </div>
                    <h3 class="h4 mb-3">Fast Delivery</h3>
                    <p class="text-muted">From our workshop to your doorstep — safely, quickly, and reliably.</p>
                </div>
            </div>

            <div class="col-md-4" data-aos="zoom-in" data-aos-delay="400">
                <div class="feature-card-glow text-center p-4 h-100">
                    <div class="feature-icon mb-4">
                        <i class="fas fa-tools fa-3x text-primary"></i>
                    </div>
                    <h3 class="h4 mb-3">Expert Craftsmanship</h3>
                    <p class="text-muted">Decades of skilled artistry go into each and every piece we create.</p>
                </div>
            </div>

            <div class="col-md-4" data-aos="zoom-in" data-aos-delay="500">
                <div class="feature-card-glow text-center p-4 h-100">
                    <div class="feature-icon mb-4">
                        <i class="fas fa-leaf fa-3x text-primary"></i>
                    </div>
                    <h3 class="h4 mb-3">Sustainability</h3>
                    <p class="text-muted">We proudly use eco-friendly materials and green production practices.</p>
                </div>
            </div>

            <div class="col-md-4" data-aos="zoom-in" data-aos-delay="600">
                <div class="feature-card-glow text-center p-4 h-100">
                    <div class="feature-icon mb-4">
                        <i class="fas fa-headset fa-3x text-primary"></i>
                    </div>
                    <h3 class="h4 mb-3">24/7 Support</h3>
                    <p class="text-muted">Our friendly customer team is here to help anytime you need us.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Customer Reviews  -->
<style>
.review-card {
  transition: all 0.3s ease;
}

/* Hover animations */
@keyframes bounce {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-10px); }
}
@keyframes pulse {
  0%, 100% { transform: scale(1); }
  50% { transform: scale(1.05); }
}
@keyframes rotate {
  0% { transform: rotate(0); }
  100% { transform: rotate(1deg); }
}
@keyframes shake {
  0%, 100% { transform: translateX(0); }
  25% { transform: translateX(-5px); }
  75% { transform: translateX(5px); }
}
@keyframes zoom {
  0%, 100% { transform: scale(1); }
  50% { transform: scale(1.08); }
}

.hover-animate-1:hover { animation: bounce 0.6s ease; }
.hover-animate-2:hover { animation: pulse 0.6s ease; }
.hover-animate-3:hover { animation: rotate 0.6s ease; }
.hover-animate-4:hover { animation: shake 0.6s ease; }
.hover-animate-5:hover { animation: zoom 0.6s ease; }

</style>

<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
<!-- Customer Reviews Section -->
<section class="customer-reviews py-5">
  <div class="container">
  <h2 class="text-center mb-4 new"
    data-aos="fade-up"
    data-aos-duration="1200"
    data-aos-easing="ease-in-out"
    data-aos-once="true">
  Customer Reviews
</h2>


    <div class="swiper reviewSwiper">
      <div class="swiper-wrapper">
        <?php
        $reviews = [
          ["name" => "Sarah L.", "rating" => 4, "text" => "Absolutely stunning craftsmanship! Highly recommend FurniCraft."],
          ["name" => "Daniel K.", "rating" => 5, "text" => "Amazing quality and great custom design options."],
          ["name" => "Priya S.", "rating" => 3, "text" => "Fast delivery and excellent support. Will buy again!"],
          ["name" => "John M.", "rating" => 5, "text" => "Outstanding experience from order to delivery."],
          ["name" => "Emily T.", "rating" => 4, "text" => "Love the finish and attention to detail!"],
        ];

        // Duplicate reviews to fill continuous loop
        $loopReviews = array_merge($reviews, $reviews);
        $hoverAnimations = ['hover-animate-1', 'hover-animate-2', 'hover-animate-3', 'hover-animate-4', 'hover-animate-5'];
        $i = 0;

        foreach ($loopReviews as $review):
          $hoverClass = $hoverAnimations[$i % count($hoverAnimations)];
        ?>
          <div class="swiper-slide">
            <div class="review-card <?= $hoverClass ?> p-4 bg-white shadow rounded text-center h-100 d-flex flex-column justify-content-between">
              <div>
                <div class="stars mb-3">
                  <?php for ($s = 1; $s <= 5; $s++): ?>
                    <i class="fa<?= $s <= $review['rating'] ? 's' : 'r' ?> fa-star text-warning"></i>
                  <?php endfor; ?>
                </div>
                <p class="text-muted fst-italic">"<?= htmlspecialchars($review['text']) ?>"</p>
              </div>
              <h6 class="mt-3 fw-bold text-primary">— <?= htmlspecialchars($review['name']) ?></h6>
            </div>
          </div>
        <?php
        $i++;
        endforeach;
        ?>
      </div>

      <!-- Controls -->
      <div class="swiper-button-next"></div>
      <div class="swiper-button-prev"></div>
      <!--<div class="swiper-pagination mt-3"></div>-->
    </div>
  </div>
</section>




<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<!-- AOS -->
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" />

<script>
AOS.init({ duration: 800, once: true });

const reviewSwiper = new Swiper(".reviewSwiper", {
  loop: true,
  speed: 900,
  autoplay: {
    delay: 2500,
    disableOnInteraction: false,
    pauseOnMouseEnter: true,
  },
  spaceBetween: 30,
  slidesPerView: 1,
  breakpoints: {
    768: { slidesPerView: 2 },
    992: { slidesPerView: 3 }
  },
  navigation: {
    nextEl: ".swiper-button-next",
    prevEl: ".swiper-button-prev"
  },
  pagination: {
    el: ".swiper-pagination",
    clickable: true
  }
});
</script>



<!-- Gallery Section -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />

<style>
  .gallery-img-wrapper {
    width: 100%;
    height: 200px;
    overflow: hidden;
    border-radius: 10px;
  }
  .gallery-img-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  .swiper-button-next,
  .swiper-button-prev {
    color: #000;
    background-color: rgba(255, 255, 255, 0.8);
    width: 40px;
    height: 40px;
    border-radius: 50%;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
    z-index: 10;
  }
</style>

<section class="gallery-section py-5 bg-white">
  <div class="container">
    <h2 class="text-center mb-3">Our Gallery</h2>
    <div class="swiper gallerySwiper">
      <div class="swiper-wrapper">
        <?php
        $galleryImages = glob("assets/images/categories/*.{jpg,jpeg,png,jfif,webp}", GLOB_BRACE);
        foreach ($galleryImages as $imgPath): ?>
          <div class="swiper-slide">
            <div class="gallery-img-wrapper">
              <img src="<?= $imgPath ?>" class="img-fluid" alt="Gallery Image">
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <!-- Navigation -->
      <div class="swiper-button-next"></div>
      <div class="swiper-button-prev"></div>

      <!-- Pagination (optional) -->
      <!-- <div class="swiper-pagination mt-3"></div> -->
    </div>
  </div>
</section>





<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
  const gallerySwiper = new Swiper(".gallerySwiper", {
    loop: true,
    autoplay: {
      delay: 2500,
      disableOnInteraction: false,
      pauseOnMouseEnter: true
    },
    spaceBetween: 30,
    slidesPerView: 1,
    breakpoints: {
      576: { slidesPerView: 2 },
      768: { slidesPerView: 3 },
      992: { slidesPerView: 4 },
      1200: { slidesPerView: 5 }
    },
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev"
    },
    pagination: {
      el: ".swiper-pagination",
      clickable: true
    }
  });
</script>


<!-- Make in India Section -->
<section class="py-5 bg-light text-center" data-aos="flip-left" data-aos-easing="ease-out-cubic" data-aos-duration="1500">
    <div class="container">
        <h2 class="mb-4" style="color: #ff9933;">🇮🇳 Make in India</h2>
        <p class="lead">
            We proudly create products crafted by Indian artisans, blending tradition with innovation.
        </p>
        <img src="assets/images/make-in-india.jfif" alt="Make in India" style="max-width: 300px;">
    </div>
</section>

<!-- Available On Section -->
<!-- Section -->
<section class="py-5 text-center" data-aos="fade-down-right" data-aos-duration="1500">
  <div class="container">
    <h3 class="mb-4">Available On</h3>

    <!-- Swiper Container -->
    <div class="swiper platformSwiper">
      <div class="swiper-wrapper">
        <!-- Amazon -->
        <div class="swiper-slide">
          <a target="_blank" rel="noopener noreferrer">
            <img src="assets/images/amazon-logo.jfif" alt="Amazon" class="img-fluid logo-float" style="width: 150px;">
          </a>
        </div>

        <!-- Flipkart -->
        <div class="swiper-slide">
          <a target="_blank" rel="noopener noreferrer">
            <img src="assets/images/Flipkart logo.jfif" alt="Flipkart" class="img-fluid logo-float" style="width: 150px;">
          </a>
        </div>

        <!-- Meesho -->
        <div class="swiper-slide">
          <a target="_blank" rel="noopener noreferrer">
            <img src="assets/images/meesho.jfif" alt="Meesho" class="img-fluid logo-float" style="width: 150px;">
          </a>
        </div>
      </div>

      <!-- Optional Pagination -->
      <div class="swiper-pagination mt-3"></div>
    </div>
  </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<!-- Font Awesome (for icons) -->
<script src="https://kit.fontawesome.com/your-fontawesome-kit-code.js" crossorigin="anonymous"></script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>

<script>
  const slider = document.querySelector('#mainSlider');

//   Swiper Continuous Scroll Init

  new Swiper(".featuresSwiper", {
    loop: true,
    speed: 5000,
    autoplay: {
      delay: 0,
      disableOnInteraction: false,
    },
    slidesPerView: "auto",
    spaceBetween: 40,
    freeMode: true,
    freeModeMomentum: false,
    grabCursor: true
  });


//   cetgory section

new Swiper(".categorySwiper", {
    loop: true,
    autoplay: {
      delay: 2500,
      disableOnInteraction: false
    },
    spaceBetween: 30,
    slidesPerView: 1,
    breakpoints: {
      576: { slidesPerView: 2 },
      768: { slidesPerView: 3 },
      992: { slidesPerView: 4 },
      1200: { slidesPerView: 5 }
    },
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev"
    },
    pagination: {
      el: ".swiper-pagination",
      clickable: true
    }
  });



AOS.init({
    duration: 800, // animation duration
    once: true     // only once animation on scroll
  });

//  availble on 

var platformSwiper = new Swiper(".platformSwiper", {
    loop: true,
    autoplay: {
      delay: 2000,
      disableOnInteraction: false
    },
    pagination: {
      el: ".swiper-pagination",
      clickable: true
    },
    breakpoints: {
      0: {
        slidesPerView: 1,
        spaceBetween: 20
      },
      576: {
        slidesPerView: 2,
        spaceBetween: 30
      },
      768: {
        slidesPerView: 3,
        spaceBetween: 40
      }
    }
  });


  
// add to cart 

$(document).ready(function() {
    $('.add-to-cart-form').submit(function(e) {
        e.preventDefault(); // Stop the form from submitting normally
        var form = $(this);
        
        $.ajax({
            url: 'ajax/add_cart.php',
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                // You can handle success here
                alert('Product added to cart!');
                // Optionally update cart count if you have it
                // $("#cart-count").text(newCount);
            },
            error: function() {
                alert('Something went wrong. Please try again.');
            }
        });
    });
});
// reels-swiper initialization (for video reels)
document.addEventListener('DOMContentLoaded', function() {
    var swiper = new Swiper('.reels-swiper', {
      direction: 'vertical',
      slidesPerView: 1,
      spaceBetween: 0,
      mousewheel: true,
      loop: true,
      on: {
        slideChange: function () {
          // Pause all videos
          document.querySelectorAll('.swiper-slide video').forEach(function(video) {
            video.pause();
          });
          // Play videos in active slide
          var activeSlide = document.querySelector('.swiper-slide-active');
          if (activeSlide) {
            activeSlide.querySelectorAll('video').forEach(function(video) {
              video.play();
            });
          }
        }
      }
    });
  
    // Play videos in the first slide on page load
    const firstActiveSlide = document.querySelector('.swiper-slide-active');
    if (firstActiveSlide) {
      firstActiveSlide.querySelectorAll('video').forEach(function(video) {
        video.play();
      });
    }
  
    // Initialize Bootstrap Carousel (for other banners if needed)
    const carouselElement = document.getElementById('mainSlider');
    if (carouselElement) {
      const carousel = new bootstrap.Carousel(carouselElement, {
        interval: 3000,
        pause: 'hover',
        wrap: true
      });
  
      carousel.cycle();
  
      // Animation when slide changes
      carouselElement.addEventListener('slide.bs.carousel', function () {
        const activeSlide = this.querySelector('.carousel-item.active');
        if (activeSlide) {
          const textElement = activeSlide.querySelector('.slider-text');
          const imageElement = activeSlide.querySelector('.slider-image');
  
          if (textElement && imageElement) {
            textElement.classList.remove('animate__fadeInLeft');
            imageElement.classList.remove('animate__fadeInRight');
  
            // Force reflow
            void textElement.offsetWidth;
            void imageElement.offsetWidth;
  
            textElement.classList.add('animate__fadeInLeft');
            imageElement.classList.add('animate__fadeInRight');
          }
        }
      });
  
      window.addEventListener('load', function() {
        carousel.cycle();
      });
    }
  });
</script>
</body>
</html>

<?php require_once 'includes/footer.php'; ?>