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
  