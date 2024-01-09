<?php
//Delete the current session
session_start();
session_unset();
session_destroy();
$_SESSION = array();
//Include the Page Layout header
include("login.php");
?>