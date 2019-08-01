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
      $activeUser = $_SESSION["activeUser"];

      $dbLink = new Database();
      $dbLink = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
      if(!$dbLink)
      {
        $exc_dbFailure = new Exception();
        throw $exc_dbFailure;
      }

      if(!($res = $dbLink->query(" SELECT operationType FROM operationalData WHERE seatID = '".$seatID."' ")))
      {
        $exc_dbFailure = new Exception();
        throw $exc_dbFailure;
      }

      $data = $res->fetch_row();                            // seatID in operationalData is unique. There will be only one returned value

      if($data[0] == "purchase")
      {
        mysqli_close($dbLink);

        $response = "seatNotExist";
      }
      else if($data[0] == "reservation")
      {
        if(!$dbLink->query(" UPDATE operationalData SET executedBy = '".$activeUser."' WHERE seatID = '".$seatID."' "))
        {
          $exc_dbFailure = new Exception();
          throw $exc_dbFailure;
        }

        $response = "seatReserved";

        mysqli_close($dbLink);
      }
      else
      {
        if(!$dbLink->query("INSERT INTO operationalData (operationID, seatID, executedBy, operationType)
                            VALUES (NULL, '$seatID', '".$activeUser."', 'reservation')"))
        {
          $exc_dbFailure = new Exception();
          throw $exc_dbFailure;
        }

        $response = "seatReserved";

        mysqli_close($dbLink);
      }
    }
    else
    {
      $resonse = "failure";
    }
  }
  catch(Exception $e)
  {
    if($e === $exc_sessionTimeout)
      $response = "sessionTimeout";
    else if($e === $exc_dbFailure)
      $response = "dbFailure";
  }
  finally
  {
    echo $response;
  }
?>
