<?php 
session_start(); // Detect the current session
include("header.php"); // Include the Page Layout header
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- HTML Form to collect search keyword and submit it to the same page in server -->
<div style="width:80%; margin:auto;"> <!-- Container -->
<form name="frmSearch" method="get" action="">
    <div class="mb-3 row"> <!-- 1st row -->
        <div class="col-sm-9 offset-sm-3">
            <h2 class="page-title">Product Search</h2>
        </div>
    </div> <!-- End of 1st row -->

    <div class="mb-3 row"> <!-- 2nd row -->
        <label for="keywords" class="col-sm-3 col-form-label">Product Title:</label>
        <div class="col-sm-6">
            <input class="form-control" name="keywords" id="keywords" type="search" />
        </div>
    </div> <!-- End of 2nd row -->

    <div class="mb-3 row"> <!-- Price Range -->
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
       
        <!-- Add some visual display of the selected range, if needed -->
        <!-- You'll need JS to update this display based on the slider position -->
    </div> <!-- End of Price Range -->

    <div class="form-section">
        <div class="form-section-title">
            <input type="checkbox" id="checkboxOccasion" checked>
            <div class="mb-3 row"> <!-- Occasion Dropdown -->
                <label for="occasion" class="col-sm-3 col-form-label">Occasion:</label>
                <div class="col-sm-6">
                    <!-- Fetch occasion values from the database -->
                    <!-- Replace 'db_fetch_occasion_values()' with your PHP function -->
                    <select class="form-select" id="occasion" name="occasion">
                        <?php

                        //$occasionValues = db_fetch_occasion_values(); // Fetch values from DB
                        foreach ($occasionValues as $value) {
                            echo "<option value='$value'>$value</option>";
                        }
                        ?>
                    </select>
                </div>
            </div> <!-- End of Occasion Dropdown -->
        </div>
    </div>
    
    <button type="submit" class="btn btn-primary col-9">Search</button>
</form>

<?php
// The non-empty search keyword is sent to server
if (isset($_GET["keywords"]) && trim($_GET['keywords']) != "") {
    // To Do (DIY): Retrieve list of product records with "ProductTitle" 
	// contains the keyword entered by shopper, and display them in a table.
    $search = $_GET["keywords"];
    $lowest = $_GET["priceRangeMin"];
    $highest =  $_GET["priceRangeMax"];
    include_once("mysql_conn.php");

    $qry = "SELECT * FROM product WHERE ProductTitle LIKE '%$search%' OR ProductDesc LIKE '%$search%'";
    $stmt = $conn->prepare($qry);
    $stmt->execute() ;
    $result = $stmt->get_result();
    $stmt->close();

    echo "<h5 style='font-weight: bold; padding: bottom 5px top 5px;'>Search results for $search:</h5>";
    if($result->num_rows > 0) {
        while($row = $result->fetch_array()) {
            $product = "productDetails.php?pid=$row[ProductID]";
            echo "<div class='col-8'>";
            echo "<p><a href=$product>$row[ProductTitle]</a></p>";
            echo "</div>";
        }
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
