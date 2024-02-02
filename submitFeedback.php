<style>
.slidecontainer {
  width: 100%;
}

.slider {
  -webkit-appearance: none;
  width: 100%;
  height: 25px;
  background: #d3d3d3;
  outline: none;
  opacity: 0.7;
  -webkit-transition: .2s;
  transition: opacity .2s;
}

.slider::-webkit-slider-thumb {
  -webkit-appearance: none;
  appearance: none;
  width: 25px;
  height: 25px;
  background: #04AA6D;
  cursor: pointer;
}

.slider::-moz-range-thumb {
  width: 25px;
  height: 25px;
  background: #04AA6D;
  cursor: pointer;
}
</style>

<?php
session_start();
if (!isset($_SESSION["ShopperID"])) { // Check if user logged in 
	// redirect to login page if the session variable shopperid is not set
	header ("Location: login.php");
	exit;
}
//Include the Page Layout header
$pageName = "Feedback";
include("header.php");
include_once("mysql_conn.php");
?>
<!-- Create a centrally located container -->
<div style="width:80%; margin:auto;">
    <!-- Create a HTML form within the container -->
    <?php 
        if (isset($_GET['error'])) {
            $error_message = $_GET['error'];
            echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars($error_message) . '</div>';
        } ?>
    <form action="feedbackSuccess.php" method="post">
        <!-- 1st row - Header Row -->
        <br>
        <!-- 2nd row - Entry of email address -->
        <div class="mb-3 row">
            <span class="page-title">Subject</span>
            <div class="col-sm-12">
                <input class="form-control" type="text"
                name="subject" id="subject" required />
            </div>
        </div>
        <!-- 3rd row - Entry of password -->
        <div class="mb-3 row">
            <label class="col-sm-3 col-form-label" for="description">
                Description:
            </label>
            <div class="col-sm-12">
                <textarea class="form-control" type="text"
                name="description" id="description" required></textarea>
            </div>
        </div>
        <div class="page-title">
            <span class="mb-3 col" id="rating"></span>
            <div class="col-sm-12">
                <div class="slidecontainer">
                    <input type="range" min="1" max="10" value="5" class="slider" id="rank" name="rank">
                </div>
            </div>
        </div>
        <br>
        <!-- 4th row - Login button -->
        <div class="col-sm-12">
            <button type="submit" style="width: 20%;" class="btn btn-outline-primary btn-sm">Submit Rating</button>
            </div>
    </form>
</div>

<script>
    var slider = document.getElementById("rank");
    var output = document.getElementById("rating");
    output.innerHTML = 'Rating: ' + slider.value;
    
    slider.oninput = function() {
        output.innerHTML = 'Rating: ' + this.value;
    }
</script>