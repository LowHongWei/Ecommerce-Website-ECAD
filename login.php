<?php
//Include the Page Layout header
$pageName = "Login";
include("header.php");
?>
<!-- Create a centrally located container -->
<div style="width:80%; margin:auto;">
    <!-- Create a HTML form within the container -->
    <?php 
        if (isset($_GET['error'])) {
            $error_message = $_GET['error'];
            echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars($error_message) . '</div>';
        } ?>
    <form action="checkLogin.php" method="post">
        <!-- 1st row - Header Row -->
        <div class="mb-3 row">
            <span class="page-title">Member Login</span>
        </div>
        <!-- 2nd row - Entry of email address -->
        <div class="mb-3 row">
            <label class="col-sm-3 col-form-label" for="email">
                Email Address:
            </label>
            <div class="col-sm-9">
                <input class="form-control" type="email"
                name="email" id="email" required />
            </div>
        </div>
        <!-- 3rd row - Entry of password -->
        <div class="mb-3 row">
            <label class="col-sm-3 col-form-label" for="password">
                Password:
            </label>
            <div class="col-sm-9">
                <input class="form-control" type="password"
                name="password" id="password" required />
            </div>
        </div>
        <!-- 4th row - Login button -->
        <div class="mb-3 row">
            <div class="col-sm-9 offset-sm-3">
                <button type="submit" class="btn btn-outline-primary btn-sm">Login</button>
                <p>Please sign up if you do not have an account.</p>
                <p><a href="forgetPassword.php">Forget Password</a></p>
            </div>
        </div>
        <?php 
        //include footer
        include("footer.php"); ?>
    </form>
</div>