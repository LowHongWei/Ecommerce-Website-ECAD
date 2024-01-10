<?php 
//Display guest welcome message, Login and Registration links
//when shopper has yet to login,
$content1 = "Welcome Guest<br />";
$content2 = "<li class='nav-item'>
		     <a class='nav-link' href='register.php'>Sign Up</a></li>
			 <li class='nav-item'>
		     <a class='nav-link' href='login.php'>Login</a></li>";
$content3 = "<h3>$pageName</h3>";

if(isset($_SESSION["ShopperName"])) { 
	//To Do 1 (Practical 2) - 
    //Display a greeting message, Change Password and logout links 
    //after shopper has logged in.
    $content1 = "Welcome <b>$_SESSION[ShopperName]</b>";
    $content2 = "<li class='nav-item'>
                 <a class='nav-link' href='changePassword.php'>Change Password</a></li>
                 <li class='nav-item'>
                 <a class='nav-link' href='logout.php'>Logout</a></li>";
	
	//To Do 2 (Practical 4) - 
    //Display number of item in cart
    if(isset($_SESSION["NumCartItem"])) {
        $content1 .= ", $_SESSION[NumCartItem] item(s) in shopping cart";
    } else{
        $content1 .= "";
    }
}
?>
<!-- To Do 4 (Practical 1) - 
     Define a collapsible navbar -->
<nav class="navbar navbar-expand-md navbar-dark text-white" style='background-color:#5370a3;'>
    <div class="container-fluid container">
        <!-- Collapsible part of navbar -->
        <div class="collapse navbar-collapse" id="collapsibleNavbar">
            <!-- Left-justified menu items -->
            <ul class="navbar-nav me-auto navbar-text">
                <li class="nav-item ">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="products.php">Product Categories</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="searchProducts.php">Product Search</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="shoppingCart.php">Shopping Cart</a>
                </li>
                <li class="nav-item ">
                    <a class="nav-link" href="feedback.php">Feedback</a>
                </li>
            </ul>
            <!-- Right-justified menu items-->
            <ul class="navbar-nav ms-auto navbar-text">
                <?php echo $content2; ?>
            </ul>
        </div>
    </div>
</nav>
<!-- To Do 3 (Practical 1) - 
     Display a navbar which is visible before or after collapsing -->
<nav class="navbar navbar-expand-md navbar-dark text-white" style='background-color:#5370a3;'>
    <div class="container-fluid container">
        <!-- Dynamic Text Display -->
        <span class="navbar-text ms-md-2 navbar-text" style="max-width: 80%;">
            <?php echo $content3; ?>
        </span>
        <ul class="navbar-nav ms-auto" style="color:#F7BE81;">
            <span class="ms-md-2">
                <?php echo $content1; ?>
            </span>
        </ul>
        <!-- Toggler/Collapsible Button -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>
