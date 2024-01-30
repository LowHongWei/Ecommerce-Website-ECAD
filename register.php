<?php 
// Detect the current session
//session_start(); 
// Include the Page Layout header
$pageName = "Register";
include("header.php"); 
include_once("mysql_conn.php");
?>

<script type="text/javascript">
function validateForm()
{
    // To Do 1 - Check if password matched
    if(document.register.password.value != document.register.password2.value){
        alert("Password not matched!");
        return false;
    }
	
	// To Do 2 - Check if telephone number entered correctly
	//           Singapore telephone number consists of 8 digits,
	//           start with 6, 8 or 9
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



<div style="width:80%; margin:auto;">
<form name="register" action="addMember.php" method="post" onsubmit="return validateForm();">
    <div class="mb-3 row">
        <div class="col-sm-9 offset-sm-3">
            <span class="page-title">Membership Registration</span>
        </div>
    </div>
    <div class="mb-3 row">
        <label class="col-sm-3 col-form-label" for="name">Name<span style="color:red">*</span>:</label>
        <div class="col-sm-9">
            <input class="form-control" name="name" id="name" 
            type="text" required />
        </div>
    </div>
    <div class="mb-3 row">
        <label class="col-sm-3 col-form-label" for="name">Birthday<span style="color:red">*</span>:</label>
        <div class="col-sm-9">
            <input class="form-control" type="date" id="birthday" name="birthday" required>
        </div>
    </div>
    <div class="mb-3 row">
        <label class="col-sm-3 col-form-label" for="address">Address:</label>
        <div class="col-sm-9">
            <textarea class="form-control" name="address" id="address"
                      cols="25" rows="4"   ></textarea>
        </div>
    </div>
    <div class="mb-3 row">
        <label class="col-sm-3 col-form-label" for="country">Country:</label>
        <div class="col-sm-9">
            <input class="form-control" name="country" id="country" type="text" /> 
        </div>
    </div>
    <div class="mb-3 row">
        <label class="col-sm-3 col-form-label" for="phone">Phone:</label>
        <div class="col-sm-9">
            <input class="form-control" name="phone" id="phone" type="text" />
        </div>
    </div>
    <div class="mb-3 row">
        <label class="col-sm-3 col-form-label" for="email">
            Email Address<span style="color:red">*</span>:</label>
        <div class="col-sm-9">
            <input class="form-control" name="email" id="email" 
                   type="email" required />
        </div>
    </div>
    <div class="mb-3 row">
        <label class="col-sm-3 col-form-label" for="password">
            Password<span style="color:red">*</span>:</label>
        <div class="col-sm-9">
            <input class="form-control" name="password" id="password" 
                   type="password" required />
        </div>
    </div>
    <div class="mb-3 row">
        <label class="col-sm-3 col-form-label" for="password2">
            Retype Password:</label>
        <div class="col-sm-9">
            <input class="form-control" name="password2" id="password2" 
                   type="password" required />
        </div>
    </div>
    <div class="mb-3 row">
        <label class="col-sm-3 col-form-label" for="password2">
            Question<span style="color:red">*</span>: </label>
        <div class="col-sm-9">
            <input class="form-control" name="question" id="question" 
                   type="text" required placeholder="Enter a question only you would know" />
        </div>
    </div>
    <div class="mb-3 row">
        <label class="col-sm-3 col-form-label" for="password2">
            Answer<span style="color:red">*</span>:</label>
        <div class="col-sm-9">
            <input class="form-control" name="answer" id="answer" 
                   type="text" required placeholder="Enter the answer to the question" />
        </div>
    </div>
    <div class="mb-3 row">       
        <div class="col-sm-9 offset-sm-3">
            <button type="submit">Register</button>
        </div>
    </div>
</form>
</div>
<?php 
// Include the Page Layout footer
include("footer.php"); 
?>