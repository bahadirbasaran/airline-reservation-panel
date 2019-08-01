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

  <div class="sidebar">
    <a href="index.php"><b>Home</b></a>
    <a class="active" href="account.php"><b>Account</b></a>

  </div>

  <div class="content">

    <form method="post" class="modal-content animate" action="dbLogin.php">

      <div class="imgContainer">

        <img src="image/avatar.png" style="width:175px; height:175px;" alt="Avatar" class="avatar">

      </div>

      <div class="loginContainer">

        <label for="umail"><b>E-Mail</b></label>

        <input type="text" placeholder="Please enter your E-mail address" name="umail" required>

        <label for="psw"><b>Password</b></label>

        <input type="password" placeholder="Please enter your Password" name="psw" required>

        <button id="btnLogin2" type="submit">Login</button>

      </div>

      <div class="cancelContainer" style="background-color:#f1f1f1">

        <button type="button" onclick="window.location.href = 'account.php'" class="cancelbtn">Cancel</button>

      </div>

    </form>

  </div>

</body>

</html>
