<?php 
session_start(); // Detect the current session
$pageName = "Search Products";
include("header.php"); // Include the Page Layout header
echo "<br/>";
include_once("mysql_conn.php");
?>

<!-- HTML Form to collect search keyword and submit it to the same page in server -->
<div class="container">
    <div class="g-3 bg-light border rounded px-4 pt-3 pb-3">
        <h2 class="search-title">Find Your Perfect Products</h2>
        <form class="search-form" name="frmSearch" method="get" action="">
            <div class="mb-3">
                <label for="keywords" class="form-label">Product Name</label>
                <input class="form-control" name="keywords" id="keywords" type="search" placeholder="Enter Product Title" required>
            </div>
            <div class="mb-3">
                <label for="priceRangeMin" class="form-label">Price Range:</label>
                <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input type="number" class="form-control" id="priceRangeMin" name="priceRangeMin" min="0" max="100" value="25">
                    <span class="input-group-text">to</span>
                    <span class="input-group-text">$</span>
                    <input type="number" class="form-control" id="priceRangeMax" name="priceRangeMax" min="0" max="100" value="75">
                </div>
            </div>
            <div class="mb-3">
                <label for="occasion" class="form-label">Occasion</label>
                <select class="form-select" id="occasion" name="occasion">
                    <option value="*">All Occasions</option>
                    <?php   
                    $qry = "SELECT DISTINCT ps.SpecVal FROM productspec ps INNER JOIN specification s ON s.SpecID = ps.SpecID WHERE s.SpecID=1";
                    $stmt = $conn->prepare($qry);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $stmt->close();
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_array()) {
                            echo "<option value='$row[SpecVal]'>$row[SpecVal]</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
    </div>
    <br/>

<?php
// The non-empty search keyword is sent to server
if (isset($_GET["priceRangeMin"]) && isset($_GET["priceRangeMax"]) ) { //isset($_GET["keywords"]) && trim($_GET['keywords'])
    // To Do (DIY): Retrieve list of product records with "ProductTitle" 
	// contains the keyword entered by shopper, and display them in a table.
    $search = trim($_GET['keywords']);
    $lowest = $_GET["priceRangeMin"];
    $highest =  $_GET["priceRangeMax"];
    $occasion = $_GET["occasion"] == "*" ? "" : $_GET["occasion"];

    if($occasion == ""){
        $qry = "SELECT *
        FROM product
        WHERE ProductTitle LIKE '%$search%'
        AND (
            CASE
                WHEN Offered = 1 AND CURDATE() BETWEEN OfferStartDate AND OfferEndDate THEN OfferedPrice
                ELSE Price
            END
        ) BETWEEN $lowest AND $highest
        ";
    } else{
        $qry = "SELECT p.*
        FROM product p
        INNER JOIN productspec ps
        ON p.ProductID = ps.ProductID
        WHERE ProductTitle LIKE '%$search%'
        AND (
            CASE
                WHEN Offered = 1 AND CURDATE() BETWEEN OfferStartDate AND OfferEndDate THEN OfferedPrice
                ELSE Price
            END
        ) BETWEEN $lowest AND $highest
        AND ps.SpecVal = '$occasion';
        ";

    }
    $stmt = $conn->prepare($qry);
    $stmt->execute() ;
    $result = $stmt->get_result();
    $stmt->close();

    if($occasion == ""){
        echo "<h5 style='font-weight: bold; padding: bottom 5px top 5px;'>Search results for";
        if($search != ""){
           echo " '$search' &";
        }
        if($lowest == $highest){
            $same = true;
            echo " price = $$highest";
        } else{
            echo " price range between $$lowest-$$highest";
        }
        echo ":</h5>";
    } else{
        echo "<h5 style='font-weight: bold; padding: bottom 5px top 5px;'>Search results for";
        if($search != ""){
           echo " $search &";
        }
        if($lowest == $highest){
            $same = true;
            echo " price = $$highest &";
        } else{
            echo " price range between $$lowest-$$highest &";
        }
        echo " $occasion occasion:</h5>";
    }
    if($result->num_rows > 0) {
        echo "<div class='row row-cols-1 row-cols-md-3 g-4'>";
        while($row = $result->fetch_array()) {
            $product = "productDetails.php?pid=$row[ProductID]&productName=$row[ProductTitle]";
            $formattedPrice = number_format($row["Price"], 2);
            $img = "./Images/products/$row[ProductImage]";
            $onOffer = $row["Offered"];
            $offerStartDate = new DateTime($row["OfferStartDate"]);
            $offerEndDate = new DateTime($row["OfferEndDate"]);
            $offeredPrice = number_format($row["OfferedPrice"], 2);
        
            $outOfStock = $row["Quantity"] <= 0;
        
            // Get today's date
            $todaysDate = new DateTime('now');
        
            // Check if the product is on offer and if today's date is within the offer range
            if ($onOffer == 1 && $todaysDate >= $offerStartDate && $todaysDate <= $offerEndDate) {
                $discountPercentage = (($row["Price"] - $row["OfferedPrice"]) / $row["Price"]) * 100;
                $discountPercentage = round($discountPercentage);
        
                echo "
                <div class='col'>
                    <div class='card h-100'>
                        <img src='$img' class='card-img-top' alt='$row[ProductImage]'>
                        <div class='card-img-overlay d-flex justify-content-end align-items-start' style='pointer-events: none;'>
                            <span class='badge bg-danger'>OFFER</span>
                        </div>
                        <div class='card-body'>
                            <h6><strong class='card-title text-muted'>$row[ProductTitle]</strong></h6>
                            <div class='d-flex justify-content-start align-items-center mb-0'>
                                <h5><b class='text-danger'>$$offeredPrice</b></h5>
                                <p class='mb-2 ms-2' style='font-size:17px;'><small class='text-muted'><del>$$formattedPrice</del> <span class='badge bg-danger'>$discountPercentage% off</span></small></p>
                            </div>";
                    if($outOfStock){
                        echo "<p class='card-text text-danger'><small class='text-muted'>Out Of Stock</small></p>
                            <div class='mt-auto'>
                                <button type='submit' class='btn btn-primary disabled'>Add to Cart</button>
                                <a href='$product' class='btn btn-outline-secondary'>View Details</a>
                                </div>
                                </div>
                            </div>
                        </div>";
                    } else{
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
            } else {
                echo "
                <div class='col'>
                    <div class='card h-100'>
                        <img src='$img' class='card-img-top' alt='$row[ProductImage]'>
                        <div class='card-body'>
                            <h6><strong class='card-title text-muted'>$row[ProductTitle]</strong></h6>
                            <h5><b class='text-primary'>$$formattedPrice</b></h5>";
                            
                if($outOfStock){
                    echo "<p class='card-text text-danger'><small class='text-muted'>Out Of Stock</small></p>
                        <div class='mt-auto'>
                            <button type='submit' class='btn btn-primary disabled'>Add to Cart</button>
                            <a href='$product' class='btn btn-outline-secondary'>View Details</a>
                            </div>
                            </div>
                        </div>
                    </div>";
                } else{
                    echo "<p class='card-text'><small class='text-muted'>Left in stock: $row[Quantity]</small></p>
                            <div class='mt-auto'>
                            <form action='cartFunctions.php' id='form_$row[ProductID]' method='post' class='d-flex align-items-center'>
                                <input type='hidden' name='action' value='add' />
                                <input type='hidden' name='quantity' value='1'/>
                                <input type='hidden' name='product_id' value='$row[ProductID]'/>
                                <button type='submit' class='btn btn-primary me-2'>Add to Cart</button>
                                <a href='$product' class='btn btn-outline-secondary'>View Details</a>
                            </form>
                            </div>
                            </div>
                        </div>
                    </div>";
                } 
            }
        }
        echo "</div>";
    } else {
        echo "<div><span style='font-weight: bold; color: red;'>No results found</span></div>";
    }
	
	// To Do (DIY): End of Code
    
    $conn->close(); // Close database connnection
}

echo "</div>"; // End of container
include("footer.php"); // Include the Page Layout footer
?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const minPriceInput = document.getElementById('priceRangeMin');
        const maxPriceInput = document.getElementById('priceRangeMax');

        minPriceInput.addEventListener('input', function() {
            // Ensure the input is a number within the range of 0 to 100
            if (!isValidPriceInput(minPriceInput.value)) {
                minPriceInput.value = '';
            }

            // Check if the minimum price is higher than the maximum price
            if (parseFloat(minPriceInput.value) > parseFloat(maxPriceInput.value)) {
                minPriceInput.setCustomValidity('Minimum price cannot be higher than maximum price');
            } else {
                minPriceInput.setCustomValidity('');
            }
        });

        maxPriceInput.addEventListener('input', function() {
            // Ensure the input is a number within the range of 0 to 100
            if (!isValidPriceInput(maxPriceInput.value)) {
                maxPriceInput.value = '';
            }

            // Check if the maximum price is lower than the minimum price
            if (parseFloat(maxPriceInput.value) < parseFloat(minPriceInput.value)) {
                maxPriceInput.setCustomValidity('Maximum price cannot be lower than minimum price');
            } else {
                maxPriceInput.setCustomValidity('');
            }
        });

        // Function to validate input as a number within the range of 0 to 100
        function isValidPriceInput(value) {
            return /^\d{0,2}(\.\d{0,2})?$/.test(value) && parseFloat(value) >= 0 && parseFloat(value) <= 100;
        }
    });
</script>
