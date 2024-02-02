<?php 
// Detect the current session
//session_start(); 
// Include the Page Layout header
$pageName = "Register";
include("header.php"); 
include_once("mysql_conn.php");
?>
<link rel="stylesheet" type="text/css" href="css/register.css">
<script type="text/javascript">
function validateForm()
{
    if(document.register.password.value != document.register.password2.value){
        alert("Password not matched!");
        return false;
    }
    if(document.register.phone.value != ""){
        if(str.length != 8){
            alert("Please enter a 8-digit phone number.");
            return false;
        }
        else if(str.substr(0,1) != "6" &&
                str.substr(0,1) != "8" &&   
                str.substr(0,1) != "9"){
            alert("Phone number in Singapore should start with 6, 8 or 9.");
            return false;
        }
    }

    return true;
}
</script>


<div class="memberContainer">
    <form name="register" action="addMember.php" method="post" onsubmit="return validateForm();">
        <h2 class="page-title">Membership Registration</h2>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" for="name">Name<span class="required">*</span>:</label>
            <div class="col-sm-9">
                <input class="form-control" name="name" id="name" type="text" required>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" for="birthday">Birthday<span class="required">*</span>:</label>
            <div class="col-sm-9">
                <input class="form-control" type="date" id="birthday" name="birthday" required>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" for="address">Address:</label>
            <div class="col-sm-9">
                <textarea class="form-control" name="address" id="address" rows="4"></textarea>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" for="country">Country:</label>
            <div class="col-sm-9">
                <input class="form-control" name="country" id="country" type="text" /> 
            </div>
        </div>
        <div class="form-group row">
        <label class="col-sm-3 col-form-label" for="phone">Phone:</label>
        <div class="col-sm-9">
            <input class="form-control" name="phone" id="phone" type="text" />
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label" for="email">
            Email Address<span class="required">*</span>:</label>
        <div class="col-sm-9">
            <input class="form-control" name="email" id="email" 
                   type="email" required />
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label" for="password">
            Password<span class="required">*</span>:</label>
        <div class="col-sm-9">
            <input class="form-control" name="password" id="password" 
                   type="password" required />
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label" for="password2">
            Retype Password:<span class="required">*</span></label>
        <div class="col-sm-9">
            <input class="form-control" name="password2" id="password2" 
                   type="password" required />
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label" for="password2">
            Question<span class="required">*</span>: </label>
        <div class="col-sm-9">
            <input class="form-control" name="question" id="question" 
                   type="text" required placeholder="Enter a question only you would know" />
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label" for="password2">
            Answer<span class="required">*</span>:</label>
        <div class="col-sm-9">
            <input class="form-control" name="answer" id="answer" 
                   type="text" required placeholder="Enter the answer to the question" />
        </div>
    </div>
    <div class="form-group row">       
            <div class="col-sm-9 offset-sm-3">
                <button type="submit" class="btn btn-primary">Register</button>
            </div>
        </div>
    </form>
</div>


<?php 
// Include the Page Layout footer
include("footer.php"); 
?>