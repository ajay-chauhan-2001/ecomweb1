<!-- Footer Like WoodMart Furniture Demo -->
<footer class="footer-woodmart-furniture pt-5 pb-3 mt-5">
  <div class="container">
    <div class="row gy-4">

      <!-- About/Logo -->
      <div class="col-md-3">
        <img src="assets/images/footer_logo.png" alt="FurniCraft Logo" style="width:180px;" class="mb-2">
        <p class="footer-desc">Premium furniture & decor for modern living. Crafted with care, delivered with pride.</p>
      </div>

      <!-- Useful Links -->
      <div class="col-md-2">
        <h6 class="footer-title">Useful Links</h6>
        <ul class="footer-list">
          <li><a href="shop.php">Shop</a></li>
          <li><a href="gallery.php">Gallery</a></li>
          <li><a href="about.php">About</a></li>
          <li><a href="contact.php">Contact</a></li>
        </ul>
      </div>

      <!-- Customer Service -->
      <div class="col-md-2">
        <h6 class="footer-title">Customer Service</h6>
        <ul class="footer-list">
          <li><a href="#">FAQs</a></li>
          <li><a href="#">Shipping</a></li>
          <li><a href="#">Returns</a></li>
          <li><a href="#">Privacy Policy</a></li>
        </ul>
      </div>

      <!-- Our Stores -->
      <div class="col-md-2">
        <h6 class="footer-title">Our Stores</h6>
        <ul class="footer-list">
          <li>New York</li>
          <li>London SF</li>
          <li>Edinburgh</li>
          <li>Los Angeles</li>
          <li>Chicago</li>
        </ul>
      </div>

      <!-- Newsletter & Social -->
      <div class="col-md-2">
        <h6 class="footer-title">Follow Us</h6>
        <p>
          <a href="mailto:info@furnicraft.com" class="text-decoration-none text-light">
            <i class="fas fa-envelope me-2 text-light"></i>info@furnicraft.com
          </a>
        </p>
        <p>
          <a href="tel:+919925751501" class="text-decoration-none text-light">
            <i class="fas fa-phone me-2 text-light"></i> +91 9925751501
          </a>
        </p>
        <div class="footer-social-icons d-flex mt-3 gap-3">
          <a href="https://wa.me/9925751501" class="social-icon whatsapp" target="_blank" rel="noopener noreferrer" title="WhatsApp">
            <i class="fab fa-whatsapp"></i>
          </a>
          <a href="https://www.facebook.com/people/TechTurtle/61564013990737/" class="social-icon facebook" target="_blank" rel="noopener noreferrer" title="Facebook">
            <i class="fab fa-facebook-f"></i>
          </a>
          <a href="https://www.instagram.com/techturtlesolution/" class="social-icon instagram" target="_blank" rel="noopener noreferrer" title="Instagram">
            <i class="fab fa-instagram"></i>
          </a>
        </div>
      </div>

    </div>

    <!-- Footer Bottom -->
    <div class="d-flex justify-content-between align-items-center flex-wrap small text-muted mt-4 pt-3 border-top">
      <!-- Payment Methods -->
      <div class="d-flex align-items-center mb-2 mb-md-0">
        <span class="me-3 fw-bold">We Accept:</span>
        <div class="payment-icons">
          <img src="assets/images/payment/upi.jfif" alt="UPI" style="height:24px; margin-right:8px;">
          <img src="assets/images/payment/gpay.jfif" alt="Google Pay" style="height:24px; margin-right:8px;">
          <img src="assets/images/payment/Mastercard.jfif" alt="Mastercard" style="height:24px; margin-right:8px;">
          <img src="assets/images/payment/Visa.jfif" alt="Visa" style="height:24px; margin-right:8px;">
          <img src="assets/images/payment/paypal.jfif" alt="Paypal" style="height:24px;">
        </div>
      </div>

      <!-- Copyright -->
      <div class="text-muted text-center text-md-end">
        &copy; 2025 FurniCraft. All rights reserved.
      </div>
    </div>
  </div>
</footer>

<!-- Back to Top Button -->
<button id="backToTop" class="btn btn-primary" aria-label="Back to top" style="position: fixed; bottom: 20px; right: 20px; opacity: 0; visibility: hidden; transition: all 0.4s ease; border-radius: 50%;">
  <i class="fas fa-arrow-up"></i>
</button>

<!-- Scripts -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>

<script>
  AOS.init({ duration: 1000, once: true });

  const backToTopButton = document.getElementById('backToTop');
  window.addEventListener('scroll', () => {
    if (window.scrollY > 300) {
      backToTopButton.style.opacity = "1";
      backToTopButton.style.visibility = "visible";
    } else {
      backToTopButton.style.opacity = "0";
      backToTopButton.style.visibility = "hidden";
    }
  });
  backToTopButton.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });
</script>

<!-- Footer Styles -->
<style>
.footer-woodmart-furniture {
  background: rgb(0, 7, 14);
  color: #e0e6f7;
  border-radius: 18px 18px 0 0;
}
.footer-woodmart-furniture .footer-title {
  color: #91bff5;

  font-weight: 600;
  margin-bottom: 1rem;
  letter-spacing: 1px;
}
.footer-woodmart-furniture .footer-list {
  list-style: none;
  padding-left: 0;
  margin-bottom: 0;
}
.footer-woodmart-furniture .footer-list li {
  margin-bottom: 0.5rem;
  font-size: 0.97rem;
}
.footer-woodmart-furniture .footer-list a {
  color: #e0e6f7 !important;
  text-decoration: none;
  transition: color 0.2s;
}
.footer-woodmart-furniture .footer-list a:hover {
  color: #246ae7 !important;
  text-decoration: underline;
}
.footer-woodmart-furniture .form-control {
  border-radius: 6px;
}
.footer-woodmart-furniture .btn-warning {
  border-radius: 6px;
}
.footer-woodmart-furniture .footer-desc {
  color: #b8c1ec;
  font-size: 0.98rem;
}
.footer-woodmart-furniture hr {
  border-color: #232946;
  opacity: 0.5;
}
.footer-woodmart-furniture .text-muted {
  color: #b8c1ec !important;
}

/* Social Icons */
.footer-social-icons {
  display: flex;
  gap: 12px;
  margin-top: 15px;
}
.social-icon {
  width: 45px;
  height: 45px;
  background: #232946;
  color: white;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
  font-size: 20px;
  text-decoration: none;
  border: 2px solid transparent;
}
.social-icon:hover {
  transform: translateY(-5px);
  border-color: #91bff5;
  background:rgb(0, 2, 3);
  color: #232946;
}
/* Platform Colors */
.social-icon.whatsapp { background: #25D366; }
.social-icon.facebook { background: #3b5998; }
.social-icon.instagram {
  background: radial-gradient(circle at 30% 30%, #fdf497 0%, #fdf497 5%, #fd5949 45%, #d6249f 60%, #285AEB 90%);
}

@media (max-width: 767px) {
  .footer-woodmart-furniture {
    border-radius: 12px 12px 0 0;
    font-size: 0.97rem;
  }
  .footer-woodmart-furniture img[alt="FurniCraft Logo"] {
    max-width: 80px;
  }
}
</style>
