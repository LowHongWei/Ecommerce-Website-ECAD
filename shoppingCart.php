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

$_SESSION["SubTotal"] = 0.00;
if (!isset($_SESSION["ShipCharge"])) {
	$_SESSION["ShipCharge"] = 5.00;
}

$subTotal = isset($_SESSION["SubTotal"]) ? $_SESSION["SubTotal"] : 0.00;
$shipCharge = isset($_SESSION["ShipCharge"]) ? $_SESSION["ShipCharge"] : 5.00; // Set default delivery mode if not set
?>
<style>
    @media (max-width: 570px) {
        .product-image-container {
            display: none;
        }
    }
</style>

<div class="container p-3">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
					<h5><strong>Cart</strong></h5>
					<hr/>
                    <?php if (isset($_SESSION["Cart"])) : ?>
                        <?php 
                            $qry = "SELECT sc.*, (sc.Price*sc.Quantity) AS Total, p.Quantity AS pQuan, p.ProductImage
                                    FROM ShopCartItem sc INNER JOIN product p
									ON sc.ProductID = p.ProductID WHERE ShopCartID=?";
                            $stmt = $conn->prepare($qry);
                            $stmt->bind_param("i", $_SESSION["Cart"]);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $stmt->close();

                            if ($result->num_rows > 0) :
                        ?>
						<div class="table-responsive">
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
										$_SESSION["Items"]=array();
                                        while ($row = $result->fetch_array()) :
                                            $formattedPrice = number_format($row["Price"], 2);
                                            $formattedTotal = number_format($row["Total"], 2);
											$_SESSION["Items"][] = array("productId"=>$row["ProductID"],
											"name"=>$row["Name"],
											"price"=>$row["Price"],
											"quantity"=>$row["Quantity"]);
                                            $subTotal += $row["Total"];
                                    ?>
                                        <tr>
											<td style="vertical-align: middle;">
												<div class="d-flex align-items-center">
													<div class="product-image-container me-3" style="max-width: 100%;">
														<img src='./Images/products/<?php echo $row['ProductImage']; ?>' class='img-fluid img-thumbnail rounded' alt='<?php echo $row["ProductImage"]; ?>' style='height: 75px; width: auto; max-width: 100%;'>
													</div>
													<div>
														<?php echo $row["Name"]; ?><br>
														Product ID: <?php echo $row["ProductID"]; ?>
													</div>
												</div>
											</td>
                                            <td style="vertical-align: middle;"><?php echo $formattedPrice; ?></td>
                                            <td style="vertical-align: middle;">
                                                <form action="cartFunctions.php" method="post">
													<input type="number" name="quantity" class="form-control" value="<?php echo $row['Quantity']; ?>" min="1" max="<?php echo ($row['pQuan'] <= 10 ? $row['pQuan'] : 10); ?>" onkeydown="return false" onchange="this.form.submit();">
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
						</div>
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
                <div class="card-body">
                    <div class="pb-2">
						<h6 class="text-muted"><strong>Delivery</strong></h6>
						<form action="cartFunctions.php" method="post" class="btn-group btn-group-sm" role="group" aria-label="Basic radio toggle button group">
							<input type="radio" class="btn-check" name="deliveryMode" id="normalDelivery" value="normal" <?php echo ($shipCharge == 5.00) ? "checked" : ""; ?> onchange="this.form.submit();">
							<label class="btn btn-outline-primary <?php echo ($shipCharge == 5.00) ? "active" : ""; ?>" for="normalDelivery">Normal</label>
							<input type="hidden" name="action" value="updateDelivery" />

							<input type="radio" class="btn-check" name="deliveryMode" id="expressDelivery" value="express" <?php echo ($shipCharge == 10.00) ? "checked" : ""; ?> onchange="this.form.submit();">
							<label class="btn btn-outline-primary <?php echo ($shipCharge == 10.00) ? "active" : ""; ?>" for="expressDelivery">Express</label>
						</form>
					</div>
					<div>
						<?php if ($shipCharge == 5.00) : ?>
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
							<h6><strong>S$<?php 
							$_SESSION["SubTotal"] = round($subTotal, 2);
							echo number_format($subTotal, 2); 
							?></strong></h6>
						</div>
					</div>
					<div class="row justify-content-between">
						<div class="col-md-4">
							<p class="text-muted">Delivery fee</p>
						</div>
						<div class="col-md-4 text-end">
							<?php if ($_SESSION["SubTotal"] <= 200) : ?>
								<p>S$<?php echo number_format($shipCharge, 2); ?></p>
							<?php else : ?>
								<p><s>S$<?php echo number_format($shipCharge, 2); ?></s>  Waived</p>
							<?php endif; ?>
						</div>
					</div>
					<div class="row justify-content-between">
						<div class="col-md-4">
							<p class="text-muted">Taxes</p>
						</div>
						<?php 
							// Get current GST rate from gst table and compute GST amount, round the figure to 2 decimal places
							$qry = "SELECT TaxRate FROM gst WHERE EffectiveDate <= ? ORDER BY EffectiveDate DESC LIMIT 1";
							$stmt = $conn->prepare($qry);
							$today = date("Y-m-d");
							$stmt->bind_param("s", $today); 
							$stmt->execute();
							$result = $stmt->get_result();
							$stmt->close();

							if ($result->num_rows > 0) {
								// Fetch current GST rate
								$row = $result->fetch_assoc();
								$currentGstRate = $row["TaxRate"];
							} else {
								$currentGstRate = 0;
							}

							$taxRate = round($_SESSION["SubTotal"] * ($currentGstRate / 100), 2);
						?>
						<div class="col-md-4 text-end">
							<p>S$ <?= $_SESSION["Tax"] = $taxRate ?></p>
						</div>
					</div>
					<?php if ($_SESSION["SubTotal"] <= 200) : ?>
						<div class="alert alert-info text-center" role="alert">
							<small>Have your subtotal to be <b>over S$200</b> to waive delivery fee!</small>
						</div>
					<?php endif; ?>
					<hr/>
					<div class="row justify-content-between pb-2">
						<div class="col-md-4">
							<h6><strong>Total</strong></h6>
						</div>
						<div class="col-md-4 text-end">
							<?php if ($_SESSION["SubTotal"] > 0) : ?>
								<?php if ($_SESSION["SubTotal"] <= 200) : ?>
									<h6><strong>S$<?php echo number_format($_SESSION["SubTotal"] + $shipCharge + $_SESSION["Tax"], 2); ?></strong></h6>
								<?php else : ?>
									<h6><strong>S$<?php echo number_format($_SESSION["SubTotal"] + $_SESSION["Tax"], 2); ?></strong></h6>
								<?php endif; ?>
							<?php else : ?>
								<h6><strong>S$0.00</strong></h6>
							<?php endif; ?>
						</div>
					</div>
					<div class="row">
						<div class="col">
							<?php if ($_SESSION["SubTotal"] > 0) : ?>
								<form method="post" action="checkoutProcess.php">
									<!--<input type='image' style='float:right;' src='https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif'>-->
									<button type="submit" class="w-100 btn btn-lg btn-primary">Proceed to Checkout</button> 
								</form>
							<?php else : ?>
								<!-- <input type='image' style='float:right;' src='https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif' disabled> -->
								<button type="submit" class="w-100 btn btn-lg btn-primary" disabled>Proceed to Checkout</button> 
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


