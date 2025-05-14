<?php


// Handle contact form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $message = $_POST['message'] ?? '';

    // Basic validation
    $errors = [];

    if (empty($name)) {
        $errors[] = 'Name is required';
    }

    if (empty($email)) {
        $errors[] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format';
    }

    if (empty($subject)) {
        $errors[] = 'Subject is required';
    }

    if (empty($message)) {
        $errors[] = 'Message is required';
    }

    // If no errors, process the form
    if (empty($errors)) {
        // Here you can send an email or save to database
        $success = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Furnixar</title>

    
 <!-- ✅ Favicon: Use Absolute URL -->
 <link rel="icon" type="image/x-icon" href="https://furnicraft.techturtle.in/assets/images/favicon/favicon.ico">
    <link rel="icon" type="image/png" sizes="16x16" href="https://furnicraft.techturtle.in/assets/images/favicon/favicon-16x16.png">
    <link rel="icon" type="image/png" sizes="32x32" href="https://furnicraft.techturtle.in/assets/images/favicon/favicon-32x32.png">
    <link rel="apple-touch-icon" sizes="180x180" href="https://furnicraft.techturtle.in/assets/images/favicon/favicon-180x180.png">
    <link rel="icon" sizes="192x192" href="https://furnicraft.techturtle.in/assets/images/favicon/favicon-192x192.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />

    <style>
        /* Animate on scroll */
        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-50px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(50px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .animate-left {
            animation: slideInLeft 1s ease forwards;
            opacity: 0;
        }

        .animate-right {
            animation: slideInRight 1s ease forwards;
            opacity: 0;
        }

        .animate-visible {
            opacity: 1;
        }
    </style>

</head>
<body>

    <!-- Header Section -->
    <?php include 'includes/header.php'; ?>

    <!-- Contact Section -->
    <section class="contact py-5">
        <div class="container">
            <div class="row">
                
                <!-- Contact Form -->
                <div class="col-md-6 animate-left">
    <div class="card shadow-lg rounded-4 p-4">
        <div class="card-body">
            <h2 class="mb-3 fw-bold text-primary text-center">Send Us a Message</h2>
            <p class="text-muted mb-4 text-center">Have a question, suggestion, or just want to say hello? Fill out the form below and we'll get back to you!</p>

            <?php if (isset($success)): ?>
                <div class="alert alert-success text-center">
                    Thank you for your message! We'll get back to you soon.
                </div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form id="contactForm" class="needs-validation" novalidate>
           
                <div class="form-floating mb-3">
                    <div class="input-group">
                        <span class="input-group-text bg-primary text-white"><i class="fas fa-user"></i></span>
                        <input type="text" class="form-control" id="name" name="name"
                            value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" placeholder="Name" required>
                    
                        <div class="invalid-feedback">Please enter your name.</div>
                    </div>
                </div>
                <div class="form-floating mb-3">
                    <div class="input-group">
                        <span class="input-group-text bg-primary text-white"><i class="fas fa-envelope"></i></span>
                        <input type="email" class="form-control" id="email" name="email"
                            value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" placeholder="Email" required>
                    
                        <div class="invalid-feedback">Please enter a valid email.</div>
                    </div>
                </div>


                <div class="form-floating mb-3">
                    <div class="input-group">
                        <span class="input-group-text bg-primary text-white"><i class="fas fa-tag"></i></span>
                        <input type="text" class="form-control" id="subject" name="subject"
                            value="<?php echo htmlspecialchars($_POST['subject'] ?? ''); ?>" placeholder="Subject" required>
                    
                        <div class="invalid-feedback">Please enter a subject.</div>
                    </div>
                </div>


                <div class="form-floating mb-3">
                    <div class="input-group">
                        <span class="input-group-text bg-primary text-white"><i class="fas fa-comment-dots"></i></span>
                        <textarea class="form-control" id="message" name="message" placeholder="Message" style="height: 150px" required><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
                    
                        <div class="invalid-feedback">Please enter your message.</div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-sm rounded-3">Send Message</button>
            </form>
        </div>
    </div>
</div>


                <!-- Contact Details Card -->
                <div class="col-md-6 animate-right">
    <div class="card shadow-lg border-0">
        <div class="card-body p-4">
            <h2 class="card-title mb-4 text-primary fw-bold">Get in Touch</h2>

            <ul class="list-unstyled mb-4">
                <li class="d-flex align-items-start mb-3">
                    <div class="me-3 text-primary">
                        <i class="fas fa-map-marker-alt fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Our Location</h6>
                        <p class="mb-0">123 Ahmedabad, Gujarat, India</p>
                    </div>
                </li>

                <li class="d-flex align-items-start mb-3">
                    <div class="me-3 text-primary">
                        <i class="fas fa-phone fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Phone</h6>
                        <p>
                            <a href="tel:+919925751501" class="text-decoration-none text-dark">
                                +91 9925751501
                            </a>
                        </p>
                    </div>
                </li>

                <li class="d-flex align-items-start mb-3">
                    <div class="me-3 text-primary">
                        <i class="fas fa-envelope fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Email</h6>
                        <p>
                            <a href="mailto:info@furnixar.com" class="text-decoration-none text-dark">
                                info@furnixar.com
                            </a>
                        </p>
                    </div>
                </li>

                <li class="d-flex align-items-start">
                    <div class="me-3 text-primary">
                        <i class="fas fa-clock fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Business Hours</h6>
                        <p class="mb-0">
                            Mon - Fri: 9:00 AM - 6:00 PM<br>
                            Sat: 10:00 AM - 4:00 PM<br>
                            Sun: Closed
                        </p>
                    </div>
                </li>
            </ul>

            <h5 class="fw-bold mb-3">Follow Us</h5>
            <div class="footer-social-icons d-flex mt-3 gap-3">
        <a href="https://wa.me/9925751501" class="social-icon whatsapp" target="_blank" title="WhatsApp">
            <i class="fab fa-whatsapp"></i>
        </a>
        <a href="https://www.facebook.com/people/TechTurtle/61564013990737/" class="social-icon facebook" target="_blank" title="Facebook">
            <i class="fab fa-facebook-f"></i>
        </a>
        <a href="https://www.instagram.com/techturtlesolution/" class="social-icon instagram" target="_blank" title="Instagram">
            <i class="fab fa-instagram"></i>
        </a>
      
    </div>
        </div>
    </div>
</div>

            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="map py-4">
        <div class="container">
            <div class="ratio ratio-21x9">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3022.2155710122!2d-73.987844924164!3d40.757339971389!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c25855c6480299%3A0x55194ec5a1ae072e!2sTimes%20Square!5e0!3m2!1sen!2sus!4v1680000000000!5m2!1sen!2sus" 
                        width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

<script>
$(document).ready(function() {
    $('#contactForm').on('submit', function(e) {
        e.preventDefault();

        var form = this;

        if (!form.checkValidity()) {
            e.stopPropagation();
            $(form).addClass('was-validated');
            return;
        }

        $.ajax({
            type: 'POST',
            url: 'save_contact.php', // Backend file
            data: $(form).serialize(),
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Thank you for your message!',
                    text: "We'll get back to you soon.",
                    confirmButtonColor: '#3085d6'
                });
                $(form)[0].reset();
                $(form).removeClass('was-validated');
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops!',
                    text: "Something went wrong. Please try again later."
                });
            }
        });
    });
});
</script>

    <!-- Animation on Scroll Script -->
    <script>
    // Simple animation on scroll
    const observer = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('animate-visible');
        }
      });
    });

    document.querySelectorAll('.animate-left, .animate-right').forEach(el => {
      observer.observe(el);
    });
    </script>

</body>
</html>
