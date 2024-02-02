<?php 
// Detect the current session
session_start(); 
// Include the Page Layout header
if (!isset($_SESSION["ShopperID"])) { // Check if user logged in 
	// redirect to login page if the session variable shopperid is not set
	header ("Location: login.php");
	exit;
}

$pageName = "Update Your Profile";
include("header.php"); 
include_once("mysql_conn.php");
?>

<link rel="stylesheet" type="text/css" href="css/register.css">

<script type="text/javascript">
function validateForm()
{
    // To Do 1 - Check if password matched
    if(document.register.password.value != document.register.password2.value){
        alert("Password not matched!");
        return false;
    }
	
	// To Do 2 - Check if telephone number entered correctly
	//           Singapore telephone number consists of 8 digits,
	//           start with 6, 8 or 9
    if(document.register.phone.value != ""){
        if(str.length != 8){
            alert("Please enter a 8-digit phone number.");
            return false;
        }
        else if(str.substr(0,1) != "6" &&
                str.substr(0,1) != "8" &&   
                str.substr(0,1) != "9"){
            alert("Phone number in Singapore should start with 6, 8 or 9.");
            return false;
        }
    }

    return true;
}
</script>

</div>
<?php 
if(isset($_SESSION["ShopperEmail"])) {
    $qry = "SELECT * FROM Shopper WHERE Email = ?";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("s", $_SESSION["ShopperEmail"]);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    if($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo "<br>";
        echo '<div style="width:80%; margin:auto;">';
        echo '<form name="register" action="updateProfile.php" method="post" onsubmit="return validateForm();">';
        echo '<div class="mb-3 row">';
        echo '<div class="col-sm-9 offset-sm-3">';
        echo '<span class="page-title">Personal Details</span>';
        echo '</div>';
        echo '</div>';
        echo '<div class="mb-3 row">';
        echo '<label class="col-sm-3 col-form-label" for="name">Name:<span class="required">*</span></label>';
        echo '<div class="col-sm-9">';
        echo "<input class='form-control' name='name' id='name' type='text' value='" . $row['Name'] . "' required />";
        echo '</div>';
        echo '</div>';
        echo '<div class="mb-3 row">';
        echo '<label class="col-sm-3 col-form-label" for="name">Birthday:<span class="required">*</span></label>';
        echo '<div class="col-sm-9">';
        echo "<input class='form-control' type='date' id='birthday' name='birthday' value='" . $row['BirthDate'] . "' required>";
        echo '</div>';
        echo '</div>';
        echo '<div class="mb-3 row">';
        echo '<label class="col-sm-3 col-form-label" for="address">Address:</label>';
        echo '<div class="col-sm-9">';
        echo "<textarea class='form-control' name='address' id='address' cols='25' rows='4'>" . $row['Address'] . "</textarea>";
        echo '</div>';
        echo '</div>';
        echo '<div class="mb-3 row">';
        echo '<label class="col-sm-3 col-form-label" for="country">Country:</label>';
        echo '<div class="col-sm-9">';
        echo "<input class='form-control' name='country' id='country' type='country' value='" . $row['Country'] . "' />";
        echo '</div>';
        echo '</div>';
        echo '<div class="mb-3 row">';
        echo '<label class="col-sm-3 col-form-label" for="phone">Phone:<span class="required">*</span></label>';
        echo '<div class="col-sm-9">';
        echo "<input class='form-control' name='phone' id='phone' type='phone' value='" . $row['Phone'] . "' />";
        echo '</div>';
        echo '</div>';
        echo '<div class="mb-3 row">';
        echo '<label class="col-sm-3 col-form-label" for="email">';
        echo 'Email Address:<span class="required">*</span></label>';
        echo '<div class="col-sm-9">';
        echo "<input class='form-control' name='email' id='email' type='email' value='" . $row['Email'] . "' required />";
        echo '</div>';
        echo '</div>';
        echo '<div class="mb-3 row">';
        echo '<label class="col-sm-3 col-form-label" for="password">';
        echo 'Password:<span class="required">*</span></label>';
        echo '<div class="col-sm-9">';
        echo "<input class='form-control' name='password' id='password' type='password' value='" . $row['Password'] . "' placeholder='Enter a new password to change it' required />";
        echo '</div>';
        echo '</div>';
        echo '<div class="mb-3 row">';
        echo '<label class="col-sm-3 col-form-label" for="password2">';
        echo 'Retype Password:<span class="required">*</span></label>';
        echo '<div class="col-sm-9">';
        echo "<input class='form-control' name='password2' id='password2' type='password' value='" . $row['Password'] . "' placeholder='Retype the new password' required />";
        echo '</div>';
        echo '</div>';
        echo '<div class="mb-3 row">';
        echo '<label class="col-sm-3 col-form-label" for="question">';
        echo 'Question:<span class="required">*</span></label>';
        echo '<div class="col-sm-9">';
        echo "<input class='form-control' name='question' id='question' type='question' value='" . $row['PwdQuestion'] . "' required placeholder='Enter a question only you would know'/>";
        echo '</div>';
        echo '</div>';
        echo '<div class="mb-3 row">';
        echo '<label class="col-sm-3 col-form-label" for="answer">';
        echo 'Answer:<span class="required">*</span></label>';
        echo '<div class="col-sm-9">';
        echo "<input class='form-control' name='answer' id='answer' type='answer' value='" . $row['PwdAnswer'] . "' required placeholder='Enter the answer to the question' />";
        echo '</div>';
        echo '</div>';
        echo '<div class="mb-3 row">';
        echo '<div class="col-sm-9 offset-sm-3">';
        echo '<button type="submit">Update</button>';
        echo '</div>';
        echo '</div>';
        echo '</form>';
        echo '</div>';
        
    }
}
// Include the Page Layout footer
include("footer.php"); 
?>