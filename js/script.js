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
  

  // index page

  
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


// 
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

AOS.init({
  duration: 800, // animation duration
  once: true     // only once animation on scroll
});

(function () {
  const swiper = new Swiper(".mySwiper", {
    effect: "coverflow",
    grabCursor: true,
    centeredSlides: true,
    loop: true,
    autoplay: {
      delay: 2000,
      disableOnInteraction: false,
    },
    coverflowEffect: {
      rotate: 50,
      stretch: 0,
      depth: 100,
      modifier: 1,
      slideShadows: true,
    },
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
    breakpoints: {
      0: { slidesPerView: 2, spaceBetween: 10 },
      768: { slidesPerView: 2, spaceBetween: 20 },
      992: { slidesPerView: 3, spaceBetween: 30 }
    }
  });
})();


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

const slider = document.querySelector('#mainSlider');
  const bsCarousel = new bootstrap.Carousel(slider, {
    interval: 2000,
    ride: 'carousel',
    pause: false,
    wrap: true
  });