<?php 
// Include the code that contains shopping cart's functions.
// Current session is detected in "cartFunctions.php, hence need not start session here.
include_once("cartFunctions.php");
include_once("mysql_conn.php");
$pageName = "Shopping Cart";
include("header.php"); // Include the Page Layout header

if (! isset($_SESSION["ShopperID"])) { // Check if user logged in 
	// redirect to login page if the session variable shopperid is not set
	header ("Location: login.php");
	exit;
}

echo "<div class='container'>";
echo "<div id='myShopCart' style='margin:auto'>"; // Start a container
if (isset($_SESSION["Cart"])) {
	// To Do 1 (Practical 4): 
	// Retrieve from database and display shopping cart in a table
	$qry = "SELECT *, (Price*Quantity) AS Total
			FROM ShopCartItem WHERE ShopCartID=?";
	$stmt = $conn->prepare($qry);
	$stmt->bind_param("i", $_SESSION["Cart"]);
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close();

	if ($result->num_rows > 0) {
		// To Do 2 (Practical 4): Format and display 
		// the page header and header row of shopping cart page
		echo "<p class='page-title' style='text-align:center'>Shopping Cart</p>"; 
		echo "<div class='table-responsive'>"; // Bootstrap responsive table
		echo "<table class='table table-hover'>"; // Start of table
		echo "<thead class='cart-header'>"; // Start of table's header section
		echo "<tr>"; // Start of header row
		echo "<th width='250px'>Item</th>";
		echo "<th width='90px'>Price (S$)</th>";
		echo "<th width='60px'>Quantity</th>";
		echo "<th width='120px'>Total (S$)</th>";
		echo "<th>&nbsp;</th>";
		echo "</tr>"; // End of header row
		echo "</thead>"; // End of table's header section
		// To Do 5 (Practical 5):
		// Declare an array to store the shopping cart items in session variable 
		$_SESSION["Items"]=array();
			
		// To Do 3 (Practical 4): 
		// Display the shopping cart content
		$subTotal = 0; // Declare a variable to compute subtotal before tax
		echo "<tbody>"; // Start of table's body section
		while ($row = $result->fetch_array()) {
			echo "<tr>";
			echo "<td style='width:50%'>$row[Name]<br />";
			echo "Product ID: $row[ProductID]</td>";
			$formattedPrice = number_format($row["Price"], 2);
			echo "<td>$formattedPrice</td>";
			echo "<td>"; // Column for update quantity of purchase
			echo "<form action='cartFunctions.php' method='post'>";
			echo "<select name='quantity' onChange='this.form.submit();'>";
			for ($i = 1; $i <= 10; $i++) { // Populate drop-down list from 1 to 10
				if ($i == $row["Quantity"]) {
					// Select drop-down list item with value same as the quantity of purchase
					$selected = "selected";
				}
				else {
					$selected = ""; // No specific item is selected
				}
				echo "<option value='$i' $selected>$i</option>";
			}
			echo "</select>";
			echo "<input type='hidden' name='action' value='update' />";
			echo "<input type='hidden' name='product_id' value='$row[ProductID]' />";
			echo "</form>";
			echo "</td>";
			$formattedTotal = number_format($row["Total"], 2);
			echo "<td>$formattedTotal</td>";
			echo "<td>"; // Column for remove item from shopping cart
			echo "<form action='cartFunctions.php' method='post'>";
			echo "<input type='hidden' name='action' value='remove' />";
			echo "<input type='hidden' name='product_id' value='$row[ProductID]' />";
			echo "<input type='image' src='Images/Checkout/trash-can.png' title='Remove Item' />";
			echo "</form>";
			echo "</td>";
			echo "</tr>";
			// To Do 6 (Practical 5):
		    // Store the shopping cart items in session variable as an associate array
			$_SESSION["Items"][] = array("productId"=>$row["ProductID"],
										"name"=>$row["Name"],
										"price"=>$row["Price"],
										"quantity"=>$row["Quantity"]);
				
			// Accumulate the running sub-total
			$subTotal += $row["Total"];
		}
		echo "</tbody>"; // End of table's body section
		echo "</table>"; // End of table
		echo "</div>"; // End of Bootstrap responsive table

		

		echo"
			<label for='deliveryMode'>Delivery Mode:</label>
			<form action='cartFunctions.php' method='post'>
				<select name='deliveryMode' onChange='this.form.submit();'>
					<option value='normal' " . ($_SESSION["ShipCharge"] == 5.00 ? "selected" : "") . ">Normal</option>
					<option value='express' " . ($_SESSION["ShipCharge"] == 10.00 ? "selected" : "") . ">Express</option>
				</select>
				<input type='hidden' name='action' value='updateDelivery' />
			</form>";
		
		if ($_SESSION["ShipCharge"] == 5.00) {
			// Calculate normal delivery date
			$normalDeliveryDate = date('d M', strtotime('+2 days'));

			echo "<p style='margin-top:10px'>Get by <span style='font-weight:bold;'>$normalDeliveryDate</span></p>";
		} 
		else {
			// calculate express delivery date
			$expressDeliveryDate = date('d M', strtotime('+1 day'));
    		echo "<p style='margin-top:10px;'>Get by <span style='font-weight:bold'>$expressDeliveryDate</span></p>";
		}

		if (round($subTotal, 2) <= 200) {
			echo "<p style='text-align:left; font-weight:bold; font-size:15px'> Add S$". 200-number_format($subTotal, 2)." more to waive delivery charges (Spend over S$200)";
		}
		// Display the delivery fee and subtotal at the end of the shopping cart
		if (round($subTotal, 2) <= 200) {
			echo "<p style='text-align:right; font-size:15px'> Delivery fee = S$" . number_format($_SESSION["ShipCharge"], 2) . "</p>";
		} else {
			$_SESSION["ShipCharge"] == 0.00;
			echo "<p style='text-align:right; font-size:15px'> Delivery fee = 
				  <s style='text-align:right; font-size:15px'>" . number_format($_SESSION["ShipCharge"], 2) . "</s>  Waived";
			$_SESSION["ShipCharge"] == 0.00;
		}
		echo "<p style='text-align:right; font-size:20px'> Subtotal = S$". number_format($subTotal, 2);
		$_SESSION["SubTotal"] = round($subTotal, 2);
		// To Do 7 (Practical 5):
		// Add PayPal Checkout button on the shopping cart page
		echo "<form method='post' action='checkoutProcess.php'>";
		echo "<input type='image' style='float:right; margin: 20px;'
						src='https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif'>";
		echo "</form></p>";
				
	}
	else {
		echo "<h3 style='text-align:center; color:red;'>Empty shopping cart!</h3>";
	}
	$conn->close(); // Close database connection
}
else {
	echo "<h3 style='text-align:center; color:red;'>Empty shopping cart!</h3>";
}
echo "</div>"; // End of container
include("footer.php"); // Include the Page Layout footer

?>
