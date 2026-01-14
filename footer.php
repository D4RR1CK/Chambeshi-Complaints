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
 </script>
 </body>

</html>
