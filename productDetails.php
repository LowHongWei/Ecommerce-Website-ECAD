<?php 
session_start();
$pageName = "Product Details";
include("header.php");
include_once("mysql_conn.php"); 
echo "<br/>";
$pid = $_GET["pid"];
$qry = "SELECT c.* FROM category c INNER JOIN catproduct cp ON c.CategoryID = cp.CategoryID WHERE cp.ProductID = $pid";
$result = $conn->query($qry);
echo "<div class='row' style='padding:5px'>";
while ($row = $result->fetch_array()) {
    $catname = urldecode($row["CatName"]);
    $catID = $row["CategoryID"];
}
?>

<div class="container">
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="products.php">Product Categories</a></li>
            <li class="breadcrumb-item"><a href="<?php echo "catProduct.php?cid=$catID&catName=$catname"; ?>"><?php echo "$catname"; ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo "$_GET[productName]"; ?></li>
        </ol>
    </nav>
</div>
<div class="container mt-5">
    <?php 
    $qry = "SELECT * FROM product WHERE ProductID=?";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("i", $pid);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while($row = $result->fetch_array()){
        $onOffer = $row["Offered"];
        $offerStartDate = new DateTime($row["OfferStartDate"]);
        $offerEndDate = new DateTime($row["OfferEndDate"]);
        $offeredPrice = number_format($row["OfferedPrice"], 2);
        
        // Get today's date
        $todaysDate = new DateTime('now');

        echo "<div class='row'>";
        echo "<div class='col-md-6'>";
        $img = "./Images/products/$row[ProductImage]";
        echo "<img src='$img' class='img-fluid' alt='$row[ProductImage]'>";
        echo "</div>";
        
        echo "<div class='col-md-6'>";
        if ($onOffer == 1 && $todaysDate >= $offerStartDate && $todaysDate <= $offerEndDate) {
            echo "<h2>$row[ProductTitle] <span class='badge bg-danger'>OFFER</span></h2>";
        } else{
            echo "<h2>$row[ProductTitle]</h2>";
        }
        echo "<p>$row[ProductDesc]</p>";

        $qry_spec = "SELECT s.SpecName, ps.SpecVal FROM productspec ps
                INNER JOIN specification s ON ps.SpecID=s.SpecID
                WHERE ps.ProductID=?
                ORDER BY ps.priority";

        $stmt_spec = $conn->prepare($qry_spec);
        $stmt_spec->bind_param("i", $pid);
        $stmt_spec->execute();
        $result_spec = $stmt_spec->get_result();

        while($row_spec = $result_spec->fetch_array()){
            echo "<p><strong>$row_spec[SpecName]:</strong> $row_spec[SpecVal]</p>";
        }
        $stmt_spec->close();

        $formattedPrice = number_format($row["Price"], 2);

        if ($onOffer == 1 && $todaysDate >= $offerStartDate && $todaysDate <= $offerEndDate) {
            $discountPercentage = (($row["Price"] - $row["OfferedPrice"]) / $row["Price"]) * 100;
            $discountPercentage = round($discountPercentage);
            $formattedOfferedPrice = number_format($row["OfferedPrice"], 2);
            echo "Offer Price: <del>S$ $formattedPrice</del> <span class='font-weight-bold text-success'>Now: S$ $formattedOfferedPrice <span class='badge bg-danger'>$discountPercentage% off</span></span>";
        } else{
            echo "<p>Price: <span class='font-weight-bold text-danger'>S$ $formattedPrice</span>";
        }

        $Quantity = $row["Quantity"] <= 0;

        echo "</p>";
        echo "<form action='cartFunctions.php' method='post'>";
        echo "<input type='hidden' name='action' value='add' />";
        echo "<input type='hidden' name='product_id' value='$pid' />";
        echo "<div class='form-group'>";
        echo "<label for='quantity'>Quantity:</label>";
        if ($row["Quantity"] <= 0) {
            echo "<p class='text-danger'><b>Out of Stock</b></p>";
        } else {
            echo "<input type='number' name='quantity' class='form-control' value='1' min='1' max='" . ($row['Quantity'] <= 10 ? $row['Quantity'] : 10) . "' onkeydown='return false' required />";
            echo "<br/>";
        }
        echo "</div>";
        if ($row["Quantity"] <= 0) {
            echo "<button type='submit' class='btn btn-primary' disabled>Add to Cart</button>";
        } else {
            echo "<button type='submit' class='btn btn-primary'>Add to Cart</button>";
        }
        echo "</form>";
        echo "</div>"; // col-md-6
        echo "</div>"; // row
    }
    $stmt->close();
    $conn->close();
    ?>
</div>
</div>

<?php include("footer.php"); ?>
