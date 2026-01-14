<?php
    $pageTitle = "Report an Issue";

    include "header.php";
 ?>

    <section class="report-selection justify-items-center">

     <h1 class="text-center">SUBMIT YOUR ISSUE BELOW</h1>

     <!-- card conatiner to hold the form --> 
      <div class="report-card"></div>

      <!-- action="" means submit to the same page   
           method="post" sends data securely--> 
           <form action="" method="post" class="report-form" enctype="multipart/form-data">

           <div class="form-row">
            <label for="identification">Room Number</label>

            <input type="number" id="id" name="number" placeholder="Enter your Room Number Here">
           </div>
           <div class="form-row">
            <label for="issue">What is the issue</label>
            <input type="text" id="description" name="issue" rows="4" placeholder="Shortly describe your issue here">
           </div>
           <div class="form-row">
            <label for="image">Upload an image</label>
            <input type="file" id="image" name="image" accept="image/*">
           </div>

           <div class="form-row">
            <label></label>
            <button type="submit" class="submit-btn">
                Submit Issue
            </button>
           </div>
           </form>
    </section>

 <?php 
     include "footer.php";
 ?>
