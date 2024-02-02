<?php 
// Detect the current session
session_start();
// Include the Page Layout header
$pageName = "Home Page";
include("header.php"); 
include_once("mysql_conn.php"); 
echo "<br/>";
?>
<div class='container'>
    <h2 class='text-danger'>Products on Offer</h2>
    <?php 
        $qry = "SELECT * FROM product WHERE Offered = 1 AND CURDATE() BETWEEN OfferStartDate AND OfferEndDate";
        $stmt = $conn->prepare($qry);
        $stmt->execute() ;
        $result = $stmt->get_result();
        $stmt->close();    
        if($result->num_rows > 0) {
            echo "<div class='row row-cols-1 row-cols-md-3 g-4'>";
            while($row = $result->fetch_array()) {
                // Output product details
                $product = "productDetails.php?pid=$row[ProductID]&productName=$row[ProductTitle]";
                $formattedPrice = number_format($row["Price"], 2);
                $img = "./Images/products/$row[ProductImage]";
                $onOffer = $row["Offered"];
                $offerStartDate = new DateTime($row["OfferStartDate"]);
                $offerEndDate = new DateTime($row["OfferEndDate"]);
                $offeredPrice = number_format($row["OfferedPrice"], 2);
                $outOfStock = $row["Quantity"] <= 0;
                $discountPercentage = (($row["Price"] - $row["OfferedPrice"]) / $row["Price"]) * 100;
                $discountPercentage = round($discountPercentage);
            
                // Output card details
                echo "
                <div class='col'>
                    <div class='card h-100'>
                        <img src='$img' class='card-img-top' alt='$row[ProductImage]'>
                        <div class='card-img-overlay d-flex justify-content-end align-items-start' style='pointer-events: none;'>
                            <span class='badge bg-danger'>OFFER</span>
                        </div>
                        <div class='card-body'>
                            <h6><strong class='card-title text-muted'>$row[ProductTitle]</strong></h6>
                            <div class='d-flex justify-content-start align-items-center'>
                                <h5><b class='text-danger'>$$offeredPrice</b></h5>
                                <p class='mb-2 ms-2' style='font-size:17px;'><small class='text-muted'><del>$$formattedPrice</del> <span class='badge bg-danger'>$discountPercentage% off</span></small></p>
                            </div>";
            
                // Check if the product is out of stock
                if($outOfStock){
                    echo "<p class='card-text text-danger'><small class='text-muted'>Out Of Stock</small></p>
                        <div class='mt-auto'>
                            <input type='hidden' name='product_id' value='$row[ProductID]'/>
                            <button type='button' class='btn btn-primary disabled'>Add to Cart</button>
                            <a href='$product' class='btn btn-outline-secondary'>View Details</a>
                            </div>
                            </div>
                        </div>
                    </div>";
                } else {
                    // Output in stock product details
                    echo "
                    <p class='card-text'><small class='text-muted'>Left in stock: $row[Quantity]</small></p>
                        <form action='cartFunctions.php' id='form_$row[ProductID]' method='post' class='d-flex align-items-center'>
                            <input type='hidden' name='action' value='add' />
                            <input type='hidden' name='quantity' value='1'/>
                            <input type='hidden' name='product_id' value='$row[ProductID]'/>
                            <button type='submit' class='btn btn-primary me-2'>Add to Cart</button>
                            <a href='$product' class='btn btn-outline-secondary'>View Details</a>
                        </form>
                        </div>
                        </div>
                    </div>";
                }
            }
            echo "</div>";
        }
    ?>
</div>
<?php 
// Include the Page Layout footer
include("footer.php"); 
?>