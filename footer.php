<?php 
    //close of the <main> tag opened in header.php
?>

</main>

<footer id="footer">
    <p>
        <?php
        echo "&copy; " . date("Y") . " Upschool1";
         ?>
    </p>
</footer>

<?php 
    //close the document
 ?>
 <script>
    const toggle = document.querySelector('.menu-toggle');
    const menu = document.querySelector('.nav-menu');

    toggle.addEventListener('click', () => {
        menu.classList.toggle('active');
    });

    setTimeout(() => {
        const overlay = document.getElementById("intro-overlay");
        if(overlay) {
            overlay.remove();
        }
    }, 3500);
    
    document.addEventListener('DOMContentLoaded', () => {
        const observerOptions = {
            threshold: 0.2,
            rootMargin: '0px 0px -100px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('show');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        const scrollElements = document.querySelectorAll('.scroll-content');
        scrollElements.forEach(el => observer.observe(el));
    });
 </script>
 </body>

</html>
