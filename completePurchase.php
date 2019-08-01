<?php
  require_once "subsystem.php";

  try
  {
    if(checkInactivity() === FALSE)
    {
      $exc_sessionTimeout = new Exception();
      throw $exc_sessionTimeout;
    }

    if(isset($_POST["arrResSeats"]) && isset($_SESSION["activeUser"]))
    {
      $arrReservedSeats  = $_POST["arrResSeats"];
      $activeUser        = $_SESSION["activeUser"];
      $terminatePurchase = FALSE;
      $dbLink   = NULL;
      $res      = NULL;
      $response = "";
      $data     = array();
      $temp     = array();

      $dbLink = new Database();
      $dbLink = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
      if(!$dbLink)
      {
        $exc_dbFailure = new Exception();
        throw $exc_dbFailure;
      }

      /* Fetch all seats reserved by activeUser */
      if(!($res = $dbLink->query(" SELECT seatID FROM operationalData WHERE executedBy ='".$activeUser."' AND operationType = 'reservation' ")))
      {
        $exc_dbFailure = new Exception();
        throw $exc_dbFailure;
      }

      if ($res->num_rows > 0)
      {
        $i = 0;
        while($r = $res->fetch_array())
        {
          $data[$i] = $r;
          $i++;
        }

        /* More simple compared to the above ones.*/
        if(count($arrReservedSeats) !== count($data))
        {
          $terminatePurchase = TRUE;
        }

        if($terminatePurchase == FALSE)
        {
          foreach ($arrReservedSeats as $seat)
          {
            if(!($res = $dbLink->query(" UPDATE operationalData SET executedBy = '".$activeUser."' , operationType = 'purchase' WHERE seatID = '".$seat."' ")))
            {
              $exc_dbFailure = new Exception();
              throw $exc_dbFailure;
            }
          }

          $response = "success";
        }
        else
        {
          /* We should free all seats reserved by active user except the one(s) has been taken by other user(s) */
          if(!($res = $dbLink->query(" DELETE FROM operationalData WHERE executedBy ='".$activeUser."' AND operationType = 'reservation' ")))
          {
            $exc_dbFailure = new Exception();
            throw $exc_dbFailure;
          }

          $response = "forbiddenOperation";
        }

      }
      else
      {
        $response = "forbiddenOperation";
      }

      mysqli_close($dbLink);
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
