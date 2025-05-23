<!-- WhatsApp Floating Button (appears after 3 seconds) -->
<div id="whatsapp-float" style="display: none;">
  <a href="https://wa.me/+919925751501" class="whatsapp-float" target="_blank" title="Chat with us on WhatsApp">
    <img src="https://img.icons8.com/color/48/000000/whatsapp--v1.png" alt="WhatsApp Icon" />
  </a>
</div>

<style>
  .whatsapp-float {
    position: fixed;
    bottom: 20px;
    left: 20px; /* 👈 Changed from right: 20px to left: 20px */
    z-index: 9999;
    animation: bounce 2s infinite;
    transition: transform 0.3s ease;
    background: #25D366;
    border-radius: 50%;
    padding: 10px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.2);
  }

  .whatsapp-float img {
    width: 50px;
    height: 50px;
    display: block;
  }

  @keyframes bounce {
    0%, 20%, 50%, 80%, 100% {
      transform: translateY(0);
    }
    40% {
      transform: translateY(-10px);
    }
    60% {
      transform: translateY(-5px);
    }
  }

  .whatsapp-float:hover {
    transform: scale(1.1);
  }
</style>

<script>
  // Delay showing WhatsApp icon by 3 seconds
  setTimeout(function() {
    document.getElementById("whatsapp-float").style.display = "block";
  }, 3000);
</script>
