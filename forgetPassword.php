<?php 
session_start(); // Detect the current session
include("header.php"); // Include the Page Layout header
?>
<link rel="stylesheet" href="css/pass.css">
<!-- Create a cenrally located container -->
<div class="container">
    <form method="post" action="showPassword.php" class="password-form">
        <h2 class="form-title">Forget Password</h2>
        <div class="form-group">
            <label for="email">Email Address:</label>
            <input class="form-control" name="eMail" id="eMail" type="eMail" required />
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
</div>

</div> <!-- Closing container -->
<?php 
include("footer.php"); // Include the Page Layout footer
?>