<?php
  require_once "subsystem.php";

  $userEmail = $password = "";

  if (isset($_SESSION["activeUser"]))
  {
    destroySession();
  }

  if(isset($_POST["umail"]))
  {
    $userEmail = sanitizeString($_POST["umail"]);
  }
  else {
    return;
  }

  if(isset($_POST["psw"]))
  {
    $userPassword = $_POST["psw"];
  }
  else {
    return;
  }

  $dbCon = new Database();

  $res = $dbCon->loginServer($userEmail, $userPassword);

  if($res == TRUE)
  {
    header("Location: memberArea.php");
  }
  else if($res == "dbFailure")
  {
    echo '<script language="javascript">';
    echo 'alert("An error occured during database operations. Please try again");';
    echo 'window.location.replace("login.php");';
    echo '</script>';
  }
  else if($res == FALSE)
  {
    echo '<script language="javascript">';
    echo 'alert("Authentication Failure! Press OK to try again");';
    echo 'window.location.replace("login.php");';
    echo '</script>';
  }

?>
