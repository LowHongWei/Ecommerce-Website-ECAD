<?php 
session_start(); // Detect the current session
include_once("mysql_conn.php");
$pageName = "Order Confirmed";
include("header.php"); // Include the Page Layout header

// Fetch order details
$orderID = $_SESSION["OrderID"];
$orderItems = [];

$stmt = $conn->prepare("SELECT ShopCartID FROM OrderData WHERE OrderID = ?");
$stmt->bind_param("i", $orderID);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $shopCartID = $row['ShopCartID'];
}
$stmt->close();

$stmt = $conn->prepare("SELECT * FROM ShopCartItem WHERE ShopCartID = ?");
$stmt->bind_param("i", $shopCartID);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
	$orderItems[] = $row;
}
$stmt->close();
$conn->close();
?>
<style>
    body {
        background-color: #f5f5f5; /* or any desired background color */
    }
    .confirmation-container {
        max-width: 600px;
        background-color: white;
        border-radius: 5px;
        padding: 30px 30px 0px 30px;
        margin: 50px auto;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .order-summary {
        margin-top: 20px;
    }
    .order-item, .order-total {
        border-top: 1px solid #eaeaea;
        padding: 10px 0;
    }
    .text-right {
        text-align: right;
		padding-top: 10px;
    }
</style>

<div class="container">
    <div class="confirmation-container text-center">
        <h4>Order confirmed!</h4>
        <h5>Order number #<?php echo $orderID; ?></h5>
        <div class="order-summary">
            <?php
                $totalAmount = 0;
                foreach ($orderItems as $item) {
                    echo "<div class='order-item row'>";
                    echo "<div class='col-8 text-start'>" . $item['Quantity'] . "x " . $item['Name'] . "</div>";
                    echo "<div class='col-4 text-right'><strong>$" . number_format($item['Price'], 2) . "</strong></div>";
                    echo "</div>";
                    $totalAmount += ($item['Price'] * $item['Quantity']);
                }
            ?>
            <div class="order-total row">
				<div class="col-8 text-start">Delivery Mode</div>
                <div class="col-4 text-right"><?php echo $_SESSION["DeliveryMode"]?></div>
                <div class="col-8 text-start"><strong>Order Total</strong></div>
                <div class="col-4 text-right"><strong>$<?php echo number_format($totalAmount, 2); ?></strong></div>
            </div>
        </div>
		<p><a href="index.php">Continue shopping</a></p>;
    </div>
</div>


<?php 
include("footer.php"); // Include the Page Layout footer
?>


