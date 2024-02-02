<?php 
session_start(); // Detect the current session
include("header.php"); // Include the Page Layout header
?>
<link rel="stylesheet" href="css/pass.css">
<!-- Create a cenrally located container -->
<div class="forgetContainer">
    <form method="post" action="showPassword.php" class="password-form">
        <h2 class="form-title">Forget Password</h2>
		<h6>1. Enter your account's email address</h6>
		<h6>2. Answer a security question to recover your forgotten password!</h6>
		<br>
        <div class="form-group">
            <label for="email">Email Address:</label>
            <input class="form-control" name="eMail" id="eMail" type="eMail" required />
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
</div>
<br>

</div> <!-- Closing container -->
<?php 
include("footer.php"); // Include the Page Layout footer
?>