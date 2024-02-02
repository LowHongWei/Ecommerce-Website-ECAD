<script type="text/javascript">
function validateForm()
{
    // Check if password matched
	if (document.forgor.answer.value == null || document.forgor.answer.value == "") {
 	    alert("Enter an answer!");
        return false;   // cancel submission
    }
    return true;  // No error found
}
</script>

<link rel="stylesheet" href="css/pass.css">

<?php 

session_start();
$pageName = "Password Recovery";
include("header.php"); 
// Process after user click the submit button
if (isset($_POST["eMail"])) {
    $eMail = $_POST["eMail"];
	include_once("mysql_conn.php");
	$qry = "SELECT * FROM Shopper WHERE Email=?";
	$stmt = $conn->prepare($qry);
	$stmt->bind_param("s", $eMail);
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close();
	if ($result->num_rows > 0) {
		// To Do 1: Update the default new password to shopper"s account
		$row = $result->fetch_array();
        $question = $row["PwdQuestion"];
        $_SESSION["userAnswer"]= $row["PwdAnswer"];
        $_SESSION["pwd"] = $row["Password"];
        echo "<div class='container'>";
        echo '<div class="password-form">';
        echo '<h2 class="form-title">Question to Recover Password</h2>';
        echo '<form name="forgot" action="" method="post" onsubmit="return validateForm()">';
        echo '<div class="form-group">';
        echo '<label for="question">Question:</label>';
        echo '<h4 class="question-text">' . $question . '</h4>';
        echo '</div>';
        echo '<div class="form-group">';
        echo '<label for="answer">Enter your answer:</label>';
        echo '<input class="form-control" type="text" name="answer" id="answer" required />';
        echo '</div>';
        echo '<button type="submit" class="btn btn-primary">Submit</button>';
        echo '</form>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        
	$conn->close();
}
else{
    echo "<div class='forgetContainer'>";
    echo "<div class='password-display'>";
    echo '<div class="col-sm-12" style="text-align:center">';
    echo "<h3 style='width:'100%;'>Email <span style='color:red'>does not exist!</span> <a href='forgetPassword.php'>Please try with a valid one</a></h3>";
    echo '</div>';
    echo "</div>";
    echo "</div>";
}
}
?>
<?php
    if (isset($_POST["answer"])) {
        echo"<div class='forgetContainer'>";
        echo "<div class='password-display'>";
            if (strcasecmp(trim($_SESSION["userAnswer"]), trim($_POST["answer"])) == 0) {
                echo '<h2 class="success-message">Your password is <span class="password">' . $_SESSION["pwd"] . '</span></h2>';
            } else {
                echo '<h2 class="error-message">Wrong Answer!</h2>';
            }
        echo "</div>";
    }
?>
</div>
<?php
include("footer.php"); 
?>