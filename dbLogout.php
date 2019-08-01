<?php
  require_once "subsystem.php";

  $instanceDB = new Database();

  $res = $instanceDB->logoutServer();

  if($res === TRUE)
  {
    header("Location: index.php");
  }
  else if($res == "dbFailure")
  {
    echo '<script language="javascript">';
    echo 'alert("An error occured during database operations. Please try again");';
    echo 'window.location.replace("login.php");';
    echo '</script>';
  }

?>
