<?php 
session_start(); //Detect the current session

// Read the data input from previous page
$name = $_POST["name"];
$address = $_POST["address"];
$country = $_POST["country"];
$phone = $_POST["phone"];
$email = $_POST["email"];
$password = password_hash($_POST["password"], PASSWORD_DEFAULT);

// include the php file that establishes database connection handle: $conn
include_once("mysql_conn.php");

// define the insert sql statement
$qry = "INSERT INTO Shopper (Name, Address, Country, Phone, Email, Password)
        VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($qry);
// "ssssss" - 6 string parameters
$stmt->bind_param("ssssss", $name, $address, $country, $phone, $email, $password);  

if($stmt->execute()){  //successful query execution
    //Retrieve the Shopper ID assigned to the new shopper 
    $qry = "SELECT LAST_INSERT_ID() AS ShopperID";
    $result = $conn->query($qry); // Execute the SQL and get the returned results
    while($row = $result->fetch_array()) {
        $_SESSION["ShopperID"] = $row["ShopperID"];
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
include("header.php");
//Display msg
echo $Message;
//Display page layout footer
include("footer.php");
?>