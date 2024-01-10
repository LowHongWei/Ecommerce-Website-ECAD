<?php 
session_start(); // Detect the current session
include("header.php"); // Include the Page Layout header
?>
<!-- Create a container, 60% width of viewport -->
<div style="width:60%; margin:auto;">
<!-- Display Page Header -->
<div class="row" style="padding:5px"> <!-- Start of header row -->
    <div class="col-12">
        <span class="page-title">Product Categories</span>
        <p>Select a category listed below:</p>
    </div>
</div> <!-- End of header row -->

<?php 
// Include the PHP file that establishes database connection handle: $conn
include_once("mysql_conn.php");

// To Do: Starting ....
$qry = "SELECT * FROM Category";
$result = $conn->query($qry);

echo "<div class='row' style='padding:5px'>";
while ($row = $result->fetch_array()) {
    $catname = urldecode($row["CatName"]);
    $catproduct = "catProduct.php?cid=$row[CategoryID]&catName=$catname";
    $img = "./Images/category/$row[CatImage]";
    echo "
    <div class='col-12'>
        <a href='$catproduct' class='card-link'>
            <div class='card mb-3'>
                <div class='row g-0'>
                    <div class='col-md-4'>
                        <img src='$img' class='img-fluid rounded-start' alt='$row[CatImage]'>
                    </div>
                    <div class='col-md-8'>
                        <div class='card-body'>
                            <h5 class='card-title'>$catname</h5>
                            <p class='card-text'>$row[CatDesc]</p>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>";
}
// To Do: Ending ....

$conn->close(); // Close database connection
echo "</div>";
include("footer.php"); // Include the Page Layout footer
?>