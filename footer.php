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

    const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('show');
                } else {
                    // Optional: remove if you want it to animate every time
                   // entry.target.classList.remove('show');
                }
            });
        }, {
            threshold: 0.2 // Trigger when 20% visible
        });

        const hiddenElements = document.querySelectorAll('.scroll-content, .scroll-content-right');
        hiddenElements.forEach((el) => observer.observe(el));
 </script>
 </body>

</html>