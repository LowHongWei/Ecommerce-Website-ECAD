<?php
session_start();
// Include the Page Layout header
$pageName = "Registration Successful";
include("header.php");
?>

<div style="width: 80%; margin: auto; text-align: center;">
    <h2>Registration Successful</h2>
    <p>Thank you for registering, <?php echo $_SESSION["ShopperName"]; ?>!</p>
    <p>Your ShopperID is <?php echo $_SESSION["ShopperID"]; ?></p>
    <!-- Add any additional information or links here -->
</div>

<?php
// Include the Page Layout footer
include("footer.php");
?>