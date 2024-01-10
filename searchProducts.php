<?php 
session_start(); // Detect the current session
$pageName = "Search Products";
include("header.php"); // Include the Page Layout header
echo "<br/>";
include_once("mysql_conn.php");
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- HTML Form to collect search keyword and submit it to the same page in server -->
<div class='container' style="margin:auto;"> <!-- Container -->
<form class='row' name="frmSearch" method="get" action="">
    <div class="mb-3 col-4">
        <label for="keywords" class="col-sm-3 col-form-label">Product Title:</label>
        <div class="col-sm-6">
            <input class="form-control" name="keywords" id="keywords" type="search" />
        </div>
    </div>

    <div class="mb-3 col-4 form-section"> <!-- Price Range -->
        <label for="priceRange" class="col-sm-3 col-form-label">Price Range:</label>
        <div class="col-sm-6">
            <!-- Dual range slider for price -->
            <input type="range" class="form-range" id="priceRangeMin" name="priceRangeMin" min="0" max="100" value="25">
            <input type="range" class="form-range" id="priceRangeMax" name="priceRangeMax" min="0" max="100" value="75">
            <p id="priceDisplay">Selected Price Range: $25 - $75</p>
        </div>
        <script>
            $(document).ready(function() {
                $('#priceRangeMin, #priceRangeMax').on('input', function() {
                    let selectedMin = parseInt($('#priceRangeMin').val());
                    let selectedMax = parseInt($('#priceRangeMax').val());

                    // Ensure min value doesn't exceed max value
                    if (selectedMin > selectedMax) {
                        $('#priceRangeMin').val(selectedMax);
                        selectedMin = selectedMax;
                    }

                    $('#priceDisplay').text('Selected Price Range: $' + selectedMin + ' - $' + selectedMax);
                });
            });
        </script>
    </div> 

    <div class="mb-3 col-4"> <!-- Occasion Dropdown -->
        <label for="occasion" class="col-sm-3 col-form-label">Occasion:</label>
        <div class="col-sm-6">
            <!-- Fetch occasion values from the database -->
            <!-- Replace 'db_fetch_occasion_values()' with your PHP function -->
            <select class="form-select" id="occasion" name="occasion">
                <?php   
                echo "<option value='*'>All Occasions</option>";
                $qry = "SELECT DISTINCT ps.SpecVal FROM productspec ps INNER JOIN specification s ON s.SpecID = ps.SpecID WHERE s.SpecID=1";
                $stmt = $conn->prepare($qry);
                $stmt->execute() ;
                $result = $stmt->get_result();
                $stmt->close();
                if($result->num_rows > 0) {
                    while($row = $result->fetch_array()) {
                        echo "<option value='$row[SpecVal]'>$row[SpecVal]</option>";
                    }
                }
                ?>
            </select>
        </div>
    </div> <!-- End of Occasion Dropdown -->
    
    <div class='col-3'>
        <button type="submit" class="btn btn-primary">Search</button>
    </div>
</form>
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
           echo " $search &";
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
                            <strong class='card-title text-muted'>$row[ProductTitle]</strong>
                            <h5><b class='text-danger'>$$offeredPrice</b></h5>
                            <p><small class='text-muted'><del>$$formattedPrice</del> <span class='badge bg-danger'>$discountPercentage% off</span></small></p>";
                    if($outOfStock){
                        echo "<p class='card-text text-danger'><small class='text-muted'>Out Of Stock</small></p>
                            <div class='mt-auto'>
                                <button href='#' class='btn btn-primary' disabled>Add to Cart</button>
                                <a href='$product' class='btn btn-outline-secondary'>View Details</a>
                                </div>
                                </div>
                            </div>
                        </div>";
                    } else{
                        echo "
                        <p class='card-text'><small class='text-muted'>Left in stock: $row[Quantity]</small></p>
                                <a href='#' class='btn btn-primary'>Add to Cart</a>
                                <a href='$product' class='btn btn-outline-secondary'>View Details</a>
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
                            <strong class='card-title text-muted'>$row[ProductTitle]</strong>
                            <h5><b class='text-primary'>$$formattedPrice</b></h5>";
                            
                if($outOfStock){
                    echo "<p class='card-text text-danger'><small class='text-muted'>Out Of Stock</small></p>
                        <div class='mt-auto'>
                            <button href='#' class='btn btn-primary' disabled>Add to Cart</button>
                            <a href='$product' class='btn btn-outline-secondary'>View Details</a>
                            </div>
                            </div>
                        </div>
                    </div>";
                } else{
                    echo "<p class='card-text'><small class='text-muted'>Left in stock: $row[Quantity]</small></p>
                            <div class='mt-auto'>
                                <a href='#' class='btn btn-primary'>Add to Cart</a>
                                <a href='$product' class='btn btn-outline-secondary'>View Details</a>
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
<!-- Your JavaScript -->


<!-- SELECT p.*
FROM `product` p
INNER JOIN `productspec` ps ON p.ProductID = ps.ProductID
WHERE (p.ProductTitle LIKE '%er%')
  AND ps.SpecVal LIKE '%give%'
  AND (p.Price >= 25 AND p.Price <= 75); -->