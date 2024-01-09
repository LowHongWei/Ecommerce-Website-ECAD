<?php
// Detect session
session_start();  
// Include the Page Layout header
include("header.php"); 

// Reading inputs entered in previous page
$email = $_POST["email"];
$pwd = $_POST["password"];

$checkLogin = false;

// include the php file that establishes database connection handle: $conn
include_once("mysql_conn.php");

// To Do 1 (Practical 2): Validate login credentials with database
$qry = "SELECT * from Shopper WHERE Email=? AND Password=?";
$stmt = $conn->prepare($qry);
$stmt->bind_param("ss", $email, $pwd);
$stmt->execute();
$result1 = $stmt->get_result();

if ($result1) {
    if ($result1->num_rows > 0) {
        $row1 = $result1->fetch_assoc();
        $checkLogin = true;
        $_SESSION["ShopperName"] = $row1["Name"];
        $_SESSION["ShopperID"] = $row1["ShopperID"];

        // To Do 2 (Practical 4): Get active shopping cart
        $qry_cart = "SELECT * FROM ShopCart WHERE OrderPlaced = 0 AND ShopperID = ?";
        $stmt_cart = $conn->prepare($qry_cart);
        $stmt_cart->bind_param("i", $_SESSION["ShopperID"]);
        $stmt_cart->execute();
        $result_cart = $stmt_cart->get_result();

        if ($result_cart->num_rows > 0) {
            while ($row = $result_cart->fetch_array()) {
                $_SESSION["Cart"] = $row["ShopCartID"];
                $_SESSION["NumCartItem"] = $row["Quantity"];
            }
        }

        // Redirect to home page
        header("Location: index.php");
        exit;
    } else {
		$error_message = "Invalid Login Credentials";
		header("Location: login.php?error=" . urlencode($error_message));
		exit();
	}
} else {
    // Handle database query error
    $error_message = "Database error: " . $conn->error;
}

// Close db connection
$conn->close();

// Include the Page Layout footer
include("footer.php");
?>