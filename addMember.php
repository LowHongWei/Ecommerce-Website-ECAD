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
// include the php file that establishes database connection handle: $conn

include_once("mysql_conn.php");
// Check whether email is unique
$qry = "SELECT * FROM `Shopper` WHERE Email=?";
$stmt = $conn->prepare($qry);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
if( $result->num_rows > 0){
    echo "<script>
    alert('Email has been used before. Sign up with a different one!');
    window.location.href='register.php';
    </script>";
}
else{

    // define the insert sql statement
    $qry = "INSERT INTO Shopper (Name, BirthDate, Address, Country, Phone, Email, Password, PwdQuestion, PwdAnswer, ActiveStatus)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("sssssssssi", $name, $bday, $address, $country, $phone, $email, $password, $question, $answer, $activityStatus);  
    
    if($stmt->execute()){  //successful query execution
        //Retrieve the Shopper ID assigned to the new shopper 
        $qry = "SELECT LAST_INSERT_ID() AS ShopperID";
        $result = $conn->query($qry); // Execute the SQL and get the returned results
        while($row = $result->fetch_array()) {
            $_SESSION["ShopperID"] = $row["ShopperID"];
            $_SESSION["ShopperEmail"] = $email;
        }
    
        //Successful message and shopper ID
        $Message = "Registration successful<br />
                    Your ShopperID is $_SESSION[ShopperID]<br />";
        // Save the Shopper Name in a session variable
        $_SESSION["ShopperName"] = $name;
        $pageName = "Registration Successful";
        
    }
    else {
        $Message = "<h3 style='color:red'>Error in inserting record</h3>";
        $pageName = "Registration Failed";
    }
    
    //realise the resource allocated for prepared statement
    $stmt->close();
    //close db connection
    $conn->close();
    
    //Display page layout header with updated session states and links
}
include("header.php");
//Display msg
echo $Message;
//Display page layout footer
include("footer.php");
?>