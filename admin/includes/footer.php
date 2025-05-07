</div> <!-- End of main-content -->
</div> <!-- End of container-fluid -->

<!-- Footer -->
<footer class="footer mt-auto py-3 bg-dark text-white">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <p class="mb-0">&copy; <?php echo date('Y'); ?> FurniCraft. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer>

<!-- Back to Top Button (OUTSIDE Footer) -->
<button onclick="topFunction()" id="backToTopBtn" title="Go to top">
    ↑
</button>

<!-- Styles -->
<style>
.footer {
    position: fixed;
    bottom: 0;
    width: calc(100% - 250px); /* sidebar width */
    left: 250px;
    background-color: #343a40;
    color: white;
    padding: 1rem 0;
    z-index: 1000;
    box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: 250px;
    height: 100%;
    background: #212529;
    color: white;
    z-index: 999;
}

.main-content {
    margin-left: 250px;
    margin-bottom: 100px;
}

/* Back to Top Button */
#backToTopBtn {
    display: none;
    position: fixed;
    bottom: 80px; /* higher than footer */
    right: 30px;
    z-index: 1100; /* higher than footer and sidebar */
    font-size: 18px;
    border: none;
    outline: none;
    background-color: #007bff;
    color: white;
    cursor: pointer;
    padding: 12px 16px;
    border-radius: 50%;
    box-shadow: 0 4px 6px rgba(0,0,0,0.3);
    transition: background-color 0.3s, transform 0.3s;
}

#backToTopBtn:hover {
    background-color: #0056b3;
    transform: scale(1.1);
}
</style>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
let backToTopBtn = document.getElementById("backToTopBtn");

window.onscroll = function() {
    if (document.body.scrollTop > 150 || document.documentElement.scrollTop > 150) {
        backToTopBtn.style.display = "block";
    } else {
        backToTopBtn.style.display = "none";
    }
};

function topFunction() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
}
</script>

</body>
</html>
