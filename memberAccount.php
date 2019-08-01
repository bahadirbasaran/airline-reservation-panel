<?php
  require_once "subsystem.php";
  include "header.php";

  if($_SERVER["HTTPS"] !== "on" || empty($_SERVER["HTTPS"]))
  {
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
  }

  if(checkInactivity() === FALSE)
  {
    echo '<script language="javascript">';
    echo 'alert("You are timed out! Please login again.");';
    echo 'window.location.replace("index.php");';
    echo '</script>';
    //header("Location: index.php");
    exit();
  }
?>

<!DOCTYPE html>
<html lang="en">

<body>

  <div class="sidebar">

    <a href="memberArea.php"><b>Home</b></a>
    <a class="active" href="memberAccount.php"><b>Account</b></a>

  </div>

  <div class="content">

    <div class="userInfo">

      <h2><b>Current Session</b></h2>

      <?php
        echo "<strong>User Email:&nbsp;&nbsp;&nbsp;</strong>" . $_SESSION["activeUser"];
      ?>
      <BR><BR>

      <form method="post" action="dbLogout.php">

        <button class="btnLogout" type="submit" style="width:auto;">Logout</button>

      </form>

    </div>

  </div>

</body>

</html>
