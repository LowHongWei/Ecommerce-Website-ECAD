<?php
//Include the Page Layout header
$pageName = "Login";
include("header.php");
?>
<link rel="stylesheet" type="text/css" href="css/userLogin.css">
<?php 
    if (isset($_GET['error'])) {
            $error_message = $_GET['error'];
            echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars($error_message) . '</div>';
        }
        echo '<body>';
        echo '<br>';
        echo '<br>';
        echo '<div class="login-container" style="width: 80%; margin: 0 auto;">';
        echo '<h2>Login</h2>';
        echo '<form class="login-form" action="checkLogin.php" method="post">';
        echo '<input type="email" name="email" placeholder="Email Address" required>';
        echo '<input type="password" name="password" placeholder="Password" required>';
        echo '<button type="submit">Login</button>';
        echo '<div class="forgot-password">';
        echo '<a href="forgetPassword.php">Forgot Password?</a>';
        echo '</div>';
        echo '</form>';
        echo '</div>'; 
        echo '</body>'; 
        echo '<br>';echo '<br>';

        //include footer
        include("footer.php"); ?>