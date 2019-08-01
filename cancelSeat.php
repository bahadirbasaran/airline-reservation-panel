<?php
  require_once "subsystem.php";

  try
  {
    if(checkInactivity() === FALSE)
    {
      $exc_sessionTimeout = new Exception();
      throw $exc_sessionTimeout;
    }

    if(isset($_POST['seat']) && isset($_SESSION["activeUser"]))
    {
      $dbLink = NULL;
      $res = NULL;
      $seatID = $_POST['seat'];
      $currentUser = $_SESSION["activeUser"];

      $dbLink = new Database();
      $dbLink = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
      if(!$dbLink)
      {
        $exc_dbFailure = new Exception();
        throw $exc_dbFailure;
      }

      if(!($res = $dbLink->query(" SELECT operationType, executedBy FROM operationalData WHERE seatID = '".$seatID."' ")))
      {
        $exc_dbFailure = new Exception();
        throw $exc_dbFailure;
      }

      $data = $res->fetch_row();                            // seatID in operationalData is unique. There will be only one returned value

      if($data[0] == "reservation" && $data[1] == $currentUser)
      {
        if(!($res = $dbLink->query(" DELETE FROM operationalData WHERE seatID = '".$seatID."' AND operationType = 'reservation' AND executedBy = '".$currentUser."' ")))
        {
          $exc_dbFailure = new Exception();
          throw $exc_dbFailure;
        }

        $response = "reservationRemoved";
      }
      else if($data[0] == "reservation" && $data[1] !== $currentUser)
      {
        $response = "reservedByOthers";
      }
      else if($data[0] == "purchase")
      {
        $response = "purchased";
      }

      mysqli_close($dbLink);
    }
    else
    {
      $resonse = "failure";
    }
  }
  catch(Exception $exc)
  {
    if($exc === $exc_sessionTimeout)
      $response = "sessionTimeout";
    else if($exc === $exc_dbFailure)
      $response = "dbFailure";
  }

  finally
  {
    echo $response;
  }
?>
