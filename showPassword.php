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
        echo '<div style="width:80%; margin:auto;">';
        echo '<form name="forgor" action="" method="post" onsubmit="return validateForm()">';
        // 1st row - Header Row
        echo '<div class="mb-3 row">';
        echo '<span class="page-title">Question to recover password</span>';
        echo '</div>';
        
        // 2nd row - Entry of email address
        echo '<div class="mb-3 row">';
        echo '<label class="col-sm-3 col-form-label" for="question">';
        echo 'Question:';
        echo '</label>';
        echo '<div class="col-sm-9">';
        echo "<h4 class='form-control' type='text' name='question' id='question' required />$question</h4>";
        echo '</div>';
        echo '</div>';
        
        // 3rd row - Entry of password
        echo '<div class="mb-3 row">';
        echo '<label class="col-sm-3 col-form-label" for="answer">';
        echo 'Enter your answer:';
        echo '</label>';
        echo '<div class="col-sm-9">';
        echo '<input class="form-control" type="text" name="answer" id="answer" required />';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        
        // 4th row - Login button
        echo '<div class="mb-3 row">';
        echo '<div class="col-sm-9 offset-sm-10">';
        echo '<button type="submit" class="btn btn-outline-primary btn-sm">Submit</button>';
        echo '</div>';
        echo '</div>';
        echo '</form>';
	$conn->close();
}
}
if (isset($_POST["answer"])) {
	if(strcasecmp(trim($_SESSION["userAnswer"]), trim($_POST["answer"])) == 0){
        echo '<div class="col-sm-12" style="text-align:center">';
        echo "<h2 style='width:'100%;'>Your password is <span style='color:red'>$_SESSION[pwd]</span></h2>";
        echo '</div>';
    }
    else{
        echo '<div class="col-sm-12" style="text-align:center">';
        echo "<h2 style='width:'100%;'><span style='color:red'>Wrong Answer!</span></h2>";
        echo '</div>';
    }
}
include("footer.php"); 
?>