<?php 
session_start(); // Detect the current session
$pageName = "Order Confirmed";
include("header.php"); // Include the Page Layout header

if(isset($_SESSION["OrderID"])) {
	echo "<div class='container p-5'>";
	echo "<div class='card'>";
	echo "<div class='card-body'>";
	echo "<h5><strong style='color:#5370a3;'>Thank you for shopping with us</strong></h5>";
	echo "<hr/>";
	echo "<p>Checkout successful. Your order number is <span style='font-weight:bold;color:#5370a3;'>$_SESSION[OrderID]</span></p>";
	echo "<p>Thank you for your purchase.&nbsp;&nbsp;";
	echo '<a href="index.php">Continue shopping</a></p>';
	echo "</div>";
	echo "</div>";
	echo "</div>";
} 

include("footer.php"); // Include the Page Layout footer
?>
