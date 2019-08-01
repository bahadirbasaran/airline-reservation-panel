<?php
  require_once "subsystem.php";

  $userEmail = $password = $passwordReType = $hashedPassword = "";

  if (isset($_SESSION["activeUser"]))
  {
    destroySession();
  }
  if(isset($_POST['email']))
  {
    $userEmail = sanitizeString($_POST['email']);
  }
  else {
    return;
  }

  /* There is no need to sanitize password, it will be hashed anyway */
  if(isset($_POST['psw']))
  {
    $password = $_POST['psw'];
  }
  else {
    return;
  }

  $dbCon = new Database();

  $res = $dbCon->registerServer($userEmail, $password);

  if($res === TRUE)
  {
    echo '<script language="javascript">';
    echo 'if (confirm("Registration is successful! You can proceed to log in") == true)
          {
            window.location.replace("login.php");
          }
          else
          {
            window.location.replace("index.php");
          }';
    echo '</script>';
  }
  else if($res === "dbFailure")
  {
    echo '<script language="javascript">';
    echo 'alert("An error occured during database operations. Please try again");';
    echo 'window.location.replace("login.php");';
    echo '</script>';
  }
  else if($res === "userExists")
  {
    echo '<script language="javascript">';
    echo 'alert("The user does already exist in the system. Please try another mail address.");';
    echo 'window.location.replace("account.php");';
    echo '</script>';
  }

?>
