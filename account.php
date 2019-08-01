<?php
  include "header.php";

  if($_SERVER["HTTPS"] !== "on" || empty($_SERVER["HTTPS"]))
  {
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
  }
?>

<!DOCTYPE html>
<html lang="en">

<body>

  <script type="text/javascript">

   $(document).ready(function(){

    $(".registerbtn").mouseenter(function(){

        switch(checkCredentials())
        {
          case "notValidEmail":
            alert("Please type a valid E-mail address!");
            break;

          case "notValidPassword":
            alert("Please type a valid password! (at least one lower-case alphabetic character, and at least one other character that is either alphabetical uppercase or numeric)");
            break;

          case "notMatchedPasswords":
            alert("Passwords do not match!");
            break;
        }
    });
   });

 </script>

  <div class="sidebar">

    <a href="index.php"><b>Home</b></a>
    <a class="active" href="account.php"><b>Account</b></a>

  </div>

  <div class="content">

    <form method="post" action="dbRegistration.php">

      <div class="signinContainer">

        <h1>Sign In</h1>
        <p>Already have an account? &nbsp; &nbsp; &nbsp; &nbsp;<button id="btnLogin1" onclick="window.location.href = 'login.php';" style="width:auto;"> Login</button></p>


      </div>

      <div class="signupContainer">

        <h1>Sign Up</h1>
        <p>Please fill in the form below to create an account.</p>
        <hr>
        <label for="email"><b>Email</b></label>
        <input type="text" id="reg_mail" placeholder="Please enter a valid E-mail address" name="email" required>
        <label for="psw"><b>Password</b></label>
        <input type="password" id="reg_pass" placeholder="Please enter a valid password" name="psw" required>
        <label for="psw-repeat"><b>Repeat Password</b></label>
        <input type="password" id="reg_pass_rw" placeholder="Please repeat the password" name="psw-repeat" required>
        <hr>
        <button type="submit" class="registerbtn">Register</button>

      </div>

    </form>

  </div>

</body>

</html>
