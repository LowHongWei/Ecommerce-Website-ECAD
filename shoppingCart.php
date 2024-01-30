<?php 
// Include the code that contains shopping cart's functions.
// Current session is detected in "cartFunctions.php, hence need not start session here.
include_once("cartFunctions.php");
include_once("mysql_conn.php");
$pageName = "Shopping Cart";
include("header.php"); // Include the Page Layout header

if (!isset($_SESSION["ShopperID"])) { // Check if user logged in 
    // redirect to login page if the session variable shopperid is not set
    header ("Location: login.php");
    exit;
}
$subTotal = 0.00;
?>

<div class="container p-3">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    Shopping Cart
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION["Cart"])) : ?>
                        <?php 
                            $qry = "SELECT *, (Price*Quantity) AS Total
                                    FROM ShopCartItem WHERE ShopCartID=?";
                            $stmt = $conn->prepare($qry);
                            $stmt->bind_param("i", $_SESSION["Cart"]);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $stmt->close();

                            if ($result->num_rows > 0) :
                        ?>
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">Item</th>
                                        <th scope="col">Price (S$)</th>
                                        <th scope="col">Quantity</th>
                                        <th scope="col">Total (S$)</th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        while ($row = $result->fetch_array()) :
                                            $formattedPrice = number_format($row["Price"], 2);
                                            $formattedTotal = number_format($row["Total"], 2);
                                            $subTotal += $row["Total"];
                                    ?>
                                        <tr>
                                            <td style="vertical-align: middle;"><?php echo $row["Name"]; ?><br>Product ID: <?php echo $row["ProductID"]; ?></td>
                                            <td style="vertical-align: middle;"><?php echo $formattedPrice; ?></td>
                                            <td style="vertical-align: middle;">
                                                <form action="cartFunctions.php" method="post">
													<input type="number" name="quantity" class="form-control" value="<?php echo $row["Quantity"]; ?>" min="1" max="10" onkeydown="return false" onchange="this.form.submit();">
                                                    <input type="hidden" name="action" value="update" />
                                                    <input type="hidden" name="product_id" value="<?php echo $row["ProductID"]; ?>" />
                                                </form>
                                            </td>
                                            <td style="vertical-align: middle;"><?php echo $formattedTotal; ?></td>
                                            <td style="vertical-align: middle;">
                                                <form action="cartFunctions.php" method="post">
                                                    <input type="hidden" name="action" value="remove" />
                                                    <input type="hidden" name="product_id" value="<?php echo $row["ProductID"]; ?>" />
													<button type="submit" class="btn-close" aria-label="Close"></button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        <?php else : ?>
                            <h3 class="text-center text-danger">Empty shopping cart!</h3>
                        <?php endif; ?>
                    <?php else : ?>
                        <h3 class="text-center text-danger">Empty shopping cart!</h3>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    Cart Summary
                </div>
                <div class="card-body">
                    <div class="pb-2">
						<h6 class="text-muted"><strong>Delivery</strong></h6>
						<form action="cartFunctions.php" method="post" class="btn-group" role="group" aria-label="Basic radio toggle button group">
							<input type="radio" class="btn-check" name="deliveryMode" id="normalDelivery" value="normal" <?php echo ($_SESSION["ShipCharge"] == 5.00) ? "checked" : ""; ?> onchange="this.form.submit();">
							<label class="btn btn-outline-primary <?php echo ($_SESSION["ShipCharge"] == 5.00) ? "active" : ""; ?>" for="normalDelivery">Normal: $5.00</label>
							<input type="hidden" name="action" value="updateDelivery" />

							<input type="radio" class="btn-check" name="deliveryMode" id="expressDelivery" value="express" <?php echo ($_SESSION["ShipCharge"] == 10.00) ? "checked" : ""; ?> onchange="this.form.submit();">
							<label class="btn btn-outline-primary <?php echo ($_SESSION["ShipCharge"] == 10.00) ? "active" : ""; ?>" for="expressDelivery">Express: $10.00</label>
						</form>
					</div>
					<div>
						<?php if ($_SESSION["ShipCharge"] == 5.00) : ?>
							<p class='text-muted'>Get by <?php echo date('d M', strtotime('+2 days')); ?></p>
						<?php else : ?>
							<p class='text-muted'>Get by <?php echo date('d M', strtotime('+1 day')); ?></p>
						<?php endif; ?>
					</div>
					<hr/>
					<div class="row justify-content-between pb-2">
						<div class="col-md-4">
							<h6 class="text-muted"><strong>Subtotal</strong></h6>
						</div>
						<div class="col-md-4 text-end text-muted">
							<h6><strong>S$<?php echo number_format($subTotal, 2); ?></strong></h6>
						</div>
					</div>
					<div class="row justify-content-between">
						<div class="col-md-4">
							<p class="text-muted">Delivery fee</p>
						</div>
						<div class="col-md-4 text-end">
							<?php if ($subTotal <= 200) : ?>
								<p>S$<?php echo number_format($_SESSION["ShipCharge"], 2); ?></p>
							<?php else : ?>
								<p><s>S$<?php echo number_format($_SESSION["ShipCharge"], 2); ?></s>  Waived</p>
							<?php endif; ?>
						</div>
					</div>
						<?php if ($subTotal <= 200) : ?>
							<div class="alert alert-info text-center" role="alert">
								<small>Add <b>S$<?php echo number_format(200 - $subTotal, 2); ?></b> more to waive delivery fee (Spend over S$200)</small>
							</div>
						<?php endif; ?>
					<hr/>
					<div class="row justify-content-between pb-2">
						<div class="col-md-4">
							<h6 class="text-muted"><strong>Total</strong></h6>
						</div>
						<div class="col-md-4 text-end">
							<?php if ($subTotal > 0) : ?>
								<?php if ($subTotal <= 200) : ?>
									<h6><strong>S$<?php echo number_format($subTotal + $_SESSION["ShipCharge"], 2); ?></strong></h6>
								<?php else : ?>
									<h6><strong>S$<?php echo number_format($subTotal, 2); ?></strong></h6>
								<?php endif; ?>
							<?php else : ?>
								<p>S$0.00</p>
							<?php endif; ?>
						</div>
					</div>
					<div class="row">
						<div class="col">
							<?php if ($subTotal > 0) : ?>
								<form method="post" action="checkoutProcess.php">
									<input type='image' style='float:right;' src='https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif'>
									<!-- <button type="submit" class="w-100 btn btn-lg btn-primary">Proceed to Checkout</button>  yaswee check this out why cannot use this button -->
								</form>
							<?php else : ?>
								<input type='image' style='float:right;' src='https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif' disabled>
								<!-- <button type="button" class="w-100 btn btn-lg btn-primary" disabled>Checkout</button> -->
							<?php endif; ?>
						</div>
					</div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
include("footer.php"); // Include the Page Layout footer
?>


