<?php 
session_start(); // Detect the current session
include("header.php"); // Include the Page Layout header
?>
<!-- Create a cenrally located container -->
<div style="width:80%; margin:auto;">
<form method="post" action="showPassword.php">
	<div class="form-group row">
		<div class="col-sm-9 offset-sm-3">
			<span class="page-title">Forget Password</span>
		</div>
	</div>
	<div class="form-group row">
		<label class="col-sm-3 col-form-label" for="eMail">
         Email Address:</label>
		<div class="col-sm-9">
			<input class="form-control" name="eMail" id="eMail"
                   type="email" required />
		</div>
	</div>
	<div class="form-group row">      
		<div class="col-sm-9 offset-sm-3">
			<button type="submit">Submit</button>
		</div>
	</div>
</form>

</div> <!-- Closing container -->
<?php 
include("footer.php"); // Include the Page Layout footer
?>