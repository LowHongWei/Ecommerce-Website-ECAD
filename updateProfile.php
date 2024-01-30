<?php
session_start(); //Detect the current session

// Read the data input from previous page
$name = $_POST["name"];
$bday = $_POST["birthday"];
$address = $_POST["address"];
$country = $_POST["country"];
$phone = $_POST["phone"];
$email = $_POST["email"];
$password =
//  password_hash(
    $_POST["password"];
// , PASSWORD_DEFAULT);
$question = $_POST["question"];
$answer = $_POST["answer"];
$activityStatus = 1;
$Message = "";
$pageName = "";
include_once("mysql_conn.php");
function updateStudent($conn, $name, $bday, $address, $country, $phone, $email, $password, $question, $answer, &$Message, &$pageName){
    $qry = "UPDATE Shopper SET Name=?, BirthDate=?, Address=?, Country=?, Phone=?, Email=?, Password=?, PwdQuestion=?, PwdAnswer=? WHERE ShopperId=?";
    // define the insert sql statement
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("sssssssssi", $name, $bday, $address, $country, $phone, $email, $password, $question, $answer, $_SESSION["ShopperId"]);  
    
    if($stmt->execute()){  //successful query execution
        //Retrieve the Shopper ID assigned to the new shopper 
        $Message = "Profile Details Updated Successful<br />";
        // Save the Shopper Name in a session variable
        $_SESSION["ShopperName"] = $name;
        $pageName = "Success";
        $_SESSION["ShopperEmail"] = $email;

        //Successful message and shopper ID
    }
    else {
        $Message = "<h3 style='color:red'>Error in updating record</h3>";
        $pageName = "Update Failed";
    }
    
    //realise the resource allocated for prepared statement
    $stmt->close();
    //close db connection
    $conn->close();
}
// Check whether email is unique
$qry = "SELECT * FROM `Shopper` WHERE Email=?";
$stmt = $conn->prepare($qry);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
if( $result->num_rows != 0){
    $row = $result->fetch_assoc();
    if($row["ShopperId"] == $_SESSION["ShopperId"]){
        updateStudent($conn, $name, $bday, $address, $country, $phone, $email, $password, $question, $answer, $Message, $pageName);
    }
    else{
        echo "<script>
        alert('Email already exists. Change to another new one!');
        window.location.href='profile.php';
        </script>";
    }
}
else{
    updateStudent($conn, $name, $bday, $address, $country, $phone, $email, $password, $question, $answer, $Message, $pageName);
}
include("header.php");
//Display msg
echo '<div class="col-sm-12" style="text-align:center">';
echo "<h2 style='width:'100%;'>$Message</h2>";
echo '</div>';
//Display page layout footer
include("footer.php");
?>