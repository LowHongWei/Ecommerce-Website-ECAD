<?php 
session_start(); // Detect the current session
$pageName = "Feedback Forum";
include("header.php"); // Include the Page Layout header

echo "<br/>";
?>
<!-- Create a container, 60% width of viewport -->
<div class="container">
<?php 
// Include the PHP file that establishes database connection handle: $conn
include_once("mysql_conn.php");

echo "<div class='row row-cols-1 row-cols-md-3 g-4'>";
echo "<div class='col' style='width: 100%'>";
echo "<br>";
echo "<a href='submitFeedback.php'> <button class='btn btn-primary'>Add Feedback</button> </a>";
echo "<br>";
echo "</div>";
// To Do:  Starting ....
$qry = "SELECT * FROM Feedback" ;
$stmt = $conn->prepare($qry);
$stmt->execute() ;
$result = $stmt->get_result();
$stmt->close();   
while($row = $result->fetch_array()) {
    echo "<script>console.log('Debug Objects: " . "1" . "' );</script>";
    $subject = $row["Subject"];
    $conetnt = $row["Content"];
    $rank = $row["Rank"];
    $shopperId = $row["ShopperID"];
    $qry1 = "SELECT * FROM Shopper WHERE ShopperId = ?";
    $stmt = $conn->prepare($qry1);
    $stmt->bind_param("i", $shopperId);  
    $stmt->execute();
    $result1 = $stmt->get_result();
    $stmt->close();
    if($result1->num_rows > 0) {
        $userName = $result1->fetch_assoc()["Name"];
            echo "
            <div class='col' style='width: 100%'>
            <div class='card'>
                <div class='card-body'>
                    <div class='row row-cols-2'>
                    <div class='col-9'>
                    <h6><strong class='card-title page-title'>$row[Subject]</strong></h6>
                </div>
                <div class='col-3'>
                    <h6><strong style='text-align:center' class='card-title page-title'>Rating: $row[Rank]</strong></h6>
                </div>
                    </div>
                    <div class='d-flex justify-content-start align-items-center mb-0'>
                        <p class='mb-2 ms-2' style='font-size:17px;'><small class='text-muted'>$row[Content]</small></p>
                    </div>
                    <p class='mb-2 ms-2' style='font-size:17px;'><small class='text-muted'>User: $userName</small></p>
                </div>
            </div>
        </div>";
    }
}

// To Do:  Ending ....

$conn->close(); // Close database connnection
echo "</div>"; // End of container
include("footer.php"); // Include the Page Layout footer
?>
