<!-- Unique Decorative Wave -->
<!-- <div class="footer-wave">
  <svg viewBox="0 0 1440 100" fill="none" xmlns="http://www.w3.org/2000/svg" style="display:block;width:100%;height:60px;"><path fill="url(#footerGradient)" fill-opacity="1" d="M0,64L48,69.3C96,75,192,85,288,80C384,75,480,53,576,53.3C672,53,768,75,864,80C960,85,1056,75,1152,74.7C1248,75,1344,85,1392,90.7L1440,96L1440,0L1392,0C1344,0,1248,0,1152,0C1056,0,960,0,864,0C768,0,672,0,576,0C480,0,384,0,288,0C192,0,96,0,48,0L0,0Z"></path>
    <defs>
      <linearGradient id="footerGradient" x1="0" y1="0" x2="1440" y2="0" gradientUnits="userSpaceOnUse">
        <stop stop-color="#6a11cb"/>
        <stop offset="1" stop-color="#2575fc"/>
      </linearGradient>
    </defs>
  </svg>
</div> -->
<!-- Footer -->
<footer class="footer-unique text-light pt-5 pb-3" style="background: linear-gradient(90deg, #6a11cb 0%, #2575fc 100%); border-radius: 30px 30px 0 0; margin-top:40px;">
    <div class="container">
        <div class="row align-items-center">
            <!-- Logo & About -->
            <div class="col-md-3 mb-4 text-center text-md-start">
                <img src="assets/images/logo.png" alt="FurniCraft Logo" style="max-width:120px; margin-bottom: 15px; border-radius: 16px; box-shadow: 0 4px 16px rgba(0,0,0,0.08); background:#fff; padding:8px;">
                <h5 class="mb-3 fw-bold mt-3">About FurniCraft</h5>
                <p>FurniCraft is your destination for premium furniture, craftsmanship, and home decor innovation.</p>
            </div>
            <!-- Quick Links -->
            <div class="col-md-3 mb-4">
                <h5 class="mb-3 fw-bold">Quick Links</h5>
                <ul class="list-unstyled">
                    <li><a href="index.php" class="footer-link">Home</a></li>
                    <li><a href="shop.php" class="footer-link">Shop</a></li>
                    <li><a href="about.php" class="footer-link">About</a></li>
                    <li><a href="contact.php" class="footer-link">Contact</a></li>
                </ul>
            </div>
            <!-- Newsletter -->
            <div class="col-md-3 mb-4">
                <h5 class="mb-3 fw-bold">Newsletter</h5>
                <form class="footer-newsletter" onsubmit="event.preventDefault(); Swal.fire('Subscribed!','Thank you for subscribing.','success');">
                    <div class="input-group">
                        <input type="email" class="form-control" placeholder="Your email" required>
                        <button class="btn btn-accent" type="submit">Subscribe</button>
                    </div>
                </form>
                <small class="text-light-50">Get updates & offers.</small>
            </div>
            <!-- Contact & Social -->
            <div class="col-md-3 mb-4">
                <h5 class="mb-3 fw-bold">Follow Us</h5>
                <p><i class="fas fa-envelope me-2"></i> info@furnixar.com</p>
                <p><i class="fas fa-phone me-2"></i> +1 234 567 890</p>
                <div class="d-flex mt-3 gap-2">
                    <a href="https://wa.me/1234567890" class="footer-social whatsapp" target="_blank"><i class="fab fa-whatsapp"></i></a>
                    <a href="https://facebook.com" class="footer-social facebook" target="_blank"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://instagram.com" class="footer-social instagram" target="_blank"><i class="fab fa-instagram"></i></a>
                    <a href="https://twitter.com" class="footer-social twitter" target="_blank"><i class="fa-brands fa-x-twitter"></i></a>
                </div>
            </div>
        </div>
        <!-- Payment Methods -->
        <div class="text-center mb-3">
            <span class="fw-bold">We Accept:</span>
            <span class="footer-payment-icons ms-2">
            <img src="assets/images/payment/upi.jfif" alt="UPI" class="pay-icon" style="height:32px;vertical-align:middle;background:#fff;border-radius:6px;padding:2px;">
               
                <img src="assets/images/payment/gpay.jfif" alt="GPay" class="pay-icon" style="height:32px;vertical-align:middle;background:#fff;border-radius:6px;padding:2px;">
                <img src="assets/images/payment/Mastercard.jfif" alt="Mastercard" class="pay-icon" style="height:32px;vertical-align:middle;background:#fff;border-radius:6px;padding:2px;">
                <img src="assets/images/payment/Visa.jfif" alt="Visa" class="pay-icon" style="height:32px;vertical-align:middle;background:#fff;border-radius:6px;padding:2px;">
                <img src="assets/images/payment/gpay.jfif" alt="GPay" class="pay-icon" style="height:32px;vertical-align:middle;background:#fff;border-radius:6px;padding:2px;">
            </span>
        </div>
        <!-- Bottom -->
        <div class="text-center mt-4 small">
            &copy; 2025 FurniCraft. All rights reserved.
        </div>
    </div>
</footer>
<!-- Back to Top Button -->
<button id="backToTop" class="btn btn-primary btn" style="position: fixed; bottom: 20px; right: 20px; opacity: 0; visibility: hidden; transition: all 0.4s ease; border-radius: 30%;">
    <i class="fas fa-arrow-up"></i>
</button>
<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="assets/js/main.js"></script>
<script src="assets/js/script.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script>
  AOS.init({
    duration: 1000,
    once: true,
  });
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
    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
  });
</script>
<!-- Custom Footer Styles -->
<style>
.footer-unique {
  background: linear-gradient(90deg, #6a11cb 0%, #2575fc 100%);
  border-radius: 30px 30px 0 0;
  color: #fff;
  box-shadow: 0 -2px 24px rgba(106,17,203,0.08);
}
.footer-link {
  color: #fff;
  text-decoration: none;
  transition: color 0.2s;
}
.footer-link:hover {
  color: #ffd700;
  text-decoration: underline;
}
.footer-social {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 36px;
  height: 36px;
  border-radius: 50%;
  background: rgba(255,255,255,0.08);
  color: #fff;
  font-size: 1.2rem;
  transition: background 0.2s, color 0.2s;
}
.footer-social:hover {
  background: #ffd700;
  color: #2575fc;
}
@media (max-width: 767px) {
  .footer-unique {
    border-radius: 20px 20px 0 0;
    padding: 2rem 0 1rem 0;
  }
  .footer-wave svg {
    height: 40px;
  }
}
.footer-payment-icons i, .footer-payment-icons img {
  vertical-align: middle;
  margin-right: 6px;
}
</style>
</body>
</html>

</style>