<?php
session_start();
// Include the Page Layout header
$pageName = "Feedback Successful";
include_once("mysql_conn.php");
include("header.php");

if (isset($_POST["subject"]) && isset($_POST["description"]) ) {
    $description = $_POST["description"];
    $subject = $_POST["subject"];
    $rank = $_POST["rank"];
    $qry = "INSERT INTO Feedback (ShopperID, Subject, Content, Rank) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("issi", $_SESSION["ShopperID"], $subject, $description, $rank);  
    if( $stmt->execute() ) {
        //Retrieve the Shopper ID assigned to the new shopper 
        $qry = "SELECT LAST_INSERT_ID() AS FeedbackId";
        $result = $conn->query($qry); // Execute the SQL and get the returned results
        while($row = $result->fetch_array()) {
            echo '<div style="width:80%;margin:auto;text-align:center;">';
            echo '<h2>Feedback posted successfully</h2>';
            echo "<p>Your feedback Id is $row[FeedbackId]!</p>";
            echo '<p>Thank you for your feedback! We will take it into consideration</p>';
            echo '</div>';
        }
    }
    else {
        echo "<script>
        alert('Error in inserting record');
        </script>";
    }
}
//include footer
include("footer.php"); ?>