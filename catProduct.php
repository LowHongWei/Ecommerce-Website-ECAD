<?php 
session_start(); // Detect the current session
$pageName = "$_GET[catName]";
include("header.php"); // Include the Page Layout header

echo "<br/>";
echo "<form action='cartFunctions.php' method='post'>";
echo "<input type='hidden' name='action' value='add' />";
echo "<input type='hidden' name='quantity' value='1'/>"
?>
<!-- Create a container, 60% width of viewport -->
<div class="container">
<!-- Display Page Header - Category's name is read 
     from the query string passed from previous page -->
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="products.php">Product Categories</a></li>
    <li class="breadcrumb-item active" aria-current="page"><?php echo "$_GET[catName]"; ?></li>
  </ol>
</nav>

<?php 
// Include the PHP file that establishes database connection handle: $conn
include_once("mysql_conn.php");

echo "<div class='row row-cols-1 row-cols-md-3 g-4'>";
// To Do:  Starting ....
$cid=$_GET["cid"]; //read cat ID from query string
$qry = "SELECT p.* FROM CatProduct cp INNER JOIN product p ON cp.ProductID=p.ProductID
		WHERE cp.CategoryID=? ORDER BY p.ProductTitle asc" ;
$stmt = $conn->prepare($qry);
$stmt->bind_param("i", $cid); //"i" - integer
$stmt->execute() ;
$result = $stmt->get_result();
$stmt->close();
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
                        <input type='hidden' name='product_id' value='$row[ProductID]'/>
                        <button type='submit' class='btn btn-primary'>Add to Cart</button>
                        <a href='$product' class='btn btn-outline-secondary'>View Details</a>
                        </div>
                        </div>
                    </div>
                </div>";
            } else{
                echo "
                <p class='card-text'><small class='text-muted'>Left in stock: $row[Quantity]</small></p>
                        <input type='hidden' name='product_id' value='$row[ProductID]'/>
                        <button type='submit' class='btn btn-primary'>Add to Cart</button>
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
                    <h6><strong class='card-title text-muted'>$row[ProductTitle]</strong></h6>
                    <h5><b class='text-primary'>$$formattedPrice</b></h5>";
                    
        if($outOfStock){
            echo "<p class='card-text text-danger'><small class='text-muted'>Out Of Stock</small></p>
                <div class='mt-auto'>
                    <input type='hidden' name='product_id' value='$row[ProductID]'/>
                    <button type='submit' class='btn btn-primary'>Add to Cart</button>
                    <a href='$product' class='btn btn-outline-secondary'>View Details</a>
                    </div>
                    </div>
                </div>
            </div>";
        } else{
            echo "<p class='card-text'><small class='text-muted'>Left in stock: $row[Quantity]</small></p>
                    <div class='mt-auto'>
                        <input type='hidden' name='product_id' value='$row[ProductID]'/>
                        <button type='submit' class='btn btn-primary'>Add to Cart</button>
                        <a href='$product' class='btn btn-outline-secondary'>View Details</a>
                    </div>
                    </div>
                </div>
            </div>";
        } 
    }
}

// To Do:  Ending ....

$conn->close(); // Close database connnection
echo "</div>"; // End of container
echo "</form>";
include("footer.php"); // Include the Page Layout footer
?>
