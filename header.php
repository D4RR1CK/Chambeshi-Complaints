<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <?php 
        //check if the page title exist
        if(isset($pageTitle)) {
            echo "<title>$pageTitle | Upschool1</title>";
        } else {
            echo"<title>Upschool1</title>";
        }
    ?>
    <!-- LINK TO CSS FILE -->
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <div id="intro-overlay">
        <div class="intro-text">
            A Place To Vent Your Hostel Issues.
        </div>
    </div>
    <header id="header" class="site-header rounded-b-lg">
        <div class="nav-container">
        <div class="logo">
            <!-- static website name -->
             <h2><a href="index.php">Upschool1</a></h2>
        </div>

        <button class="menu-toggle" aria-label="Toggle menu">☰</button>

        <!-- navigation for major links --> 
        <nav class="nav-menu">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="reporting.php">Reporting Issues</a></li>
                <li><a href="tracking.php">Issue Tracking</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="#footer">Contact</a></li>
            </ul>
        </nav>
        </div>
    </header>
    <?php 
        //<main>opens here so page content goes inside it
    ?>
    <main>

    <script>

        if(sessionStorage.getItem('hasVisited')){
            document.getElementById('intro-overlay').style.display = 'none';
        } else {
            sessionStorage.setItem('hasVisited', 'true');
        }
    </script>
</body>
</html>