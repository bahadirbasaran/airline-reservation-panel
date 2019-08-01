<?php
  session_start();

  /* SEAT GENERATION - IN CASE OF DESIRE TO CHANGE SEAT LAYOUT, PLEASE DO NOT FORGET TO CHANGE "$letters" ARRAY ACCORDING TO IT. */
  $letters = array("","A","B","C","D","E","F");
  $columnDynamic = 6;
  $rowDynamic = 10;
  /* SEAT GENERATION - IN CASE OF DESIRE TO CHANGE SEAT LAYOUT, PLEASE DO NOT FORGET TO CHANGE "$letters" ARRAY ACCORDING TO IT. */

  function destroySession()
  {
    $_SESSION = array();
    session_destroy();
  }

  /* "mysqli_real_escape_string" method is applied after input is sanitized and Database instance is instantiated. */
  function sanitizeString($var)
  {
    $var = strip_tags($var);
    $var = htmlentities($var);
    $var = stripslashes($var);
    return $var;
  }

  function parseSeatStatus($seatID)
  {
    if(empty($_POST["arrSeatholders"]))
      return "green";
    else
      $arrSeatholders = $_POST["arrSeatholders"];

    if(isset($_SESSION["activeUser"]))                                    /* If the function is being called in memberArea.php */
    {
      if(array_search($seatID, $_POST["arrPurchase"]) !== FALSE)
        return "red";
      else if(array_search($seatID, $_POST["arrReservation"]) !== FALSE)  /* Authorized user's reserved seats should be yellow, other reserved ones should be orange */
      {
        if($arrSeatholders[$seatID] == $_SESSION["activeUser"])
          return "yellow";
        else
          return "orange";
      }
      else
        return "green";
    }
    else
    {
      if(array_search($seatID, $_POST["arrPurchase"]) !== FALSE)
        return "red";
      else if(array_search($seatID, $_POST["arrReservation"]) !== FALSE)
        return "orange";
      else
        return "green";
    }
  }

  /* This function should return "checked" ONLY in case of purchased seats and reserved by active user */
  function parseCheckboxStatus($seatID)
  {
    if(empty($_POST["arrSeatholders"]))
      return;

    $arrReservedSeats      = $_POST["arrReservation"];
    $arrPurchasedSeats     = $_POST["arrPurchase"];
    $arrSeatholders        = $_POST["arrSeatholders"];
    $authenticatedUser     = $_SESSION["activeUser"];

    if(in_array($seatID, $arrPurchasedSeats))
    {
      return "checked";
    }
    else
    {
      if(in_array($seatID, $arrReservedSeats))
      {
        if($arrSeatholders[$seatID] == $authenticatedUser)
          return "checked";
      }
    }
  }

  class Database
  {
    public  $dbLink = NULL;
    private $res = NULL;

    public function __construct()
    {
      define('DB_SERVER', 'localhost');
      define('DB_USERNAME', 's261412');
      define('DB_PASSWORD', 'ingstred');
      define('DB_NAME', 's261412');
    }

    public function loginServer($user, $pass)
    {
      $dbLink = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

      if(!$dbLink)
      {
        return "dbFailure";
      }

      $user = mysqli_real_escape_string($dbLink, $user);

      if(!($res = $dbLink->query("SELECT userPass FROM userData WHERE userEmail='".$user."'")))
      {
         return "dbFailure";
      }

      if ($res->num_rows > 0)
      {
        $data = $res->fetch_row();

        if(password_verify($pass, $data[0]))
        {
          if (!$dbLink->query("UPDATE userData SET userStatus = 'active' WHERE userEmail = '".$user."' AND userPass = '".$data[0]."'"))
          {
            return "dbFailure";
          }

          $_SESSION["activeUser"] = $user;
          $_SESSION['time'] = time();

          mysqli_close($dbLink);
          return TRUE;
        }
        else
        {
          mysqli_close($dbLink);
          return FALSE;
        }
      }
      else
      {
        mysqli_close($dbLink);
        return FALSE;
      }
    }

    public function logoutServer()
    {
      $dbLink = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

      if(!$dbLink)
      {
        return "dbFailure";
      }

      $currentUser = $_SESSION["activeUser"];

      if(!($res = $dbLink->query("UPDATE userData SET userStatus = 'passive' WHERE userEmail = '".$currentUser."'")))
      {
        return "dbFailure";
      }

      destroySession();

      mysqli_close($dbLink);

      return TRUE;
    }

    public function registerServer($user, $pass)
    {
      $dbLink = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

      if(!$dbLink)
      {
        return "dbFailure";
      }

      $user = mysqli_real_escape_string($dbLink, $user);
      $hashedPass = password_hash($pass, PASSWORD_DEFAULT);

      /* userID is inserted NULL because it is autoincremented - userEmail is unique in db.
      That means in case of attempt to registeration of existing user, this block throws an exception */
      if (!$dbLink->query("INSERT INTO userData (userID, userEmail, userPass, userStatus) VALUES (NULL, '$user', '$hashedPass', 'passive')"))
      {
        mysqli_close($dbLink);
        return "userExists";
      }

      mysqli_close($dbLink);
      return TRUE;
    }

    public function __destruct()
    {
      $dbLink = NULL;
      $res = NULL;
    }

    /* In case of error in queries of this function, page should be loaded from the scratch because this malfunction effects the whole content. */
    public function getSeatStatus()
    {
      $arrPurchase = [];
      $arrReservation = [];
      $arrSeatholders = [];
      $i = 0;

      $dbLink = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

      if(!$dbLink)
        die("Database Connection Error: " . mysqli_connect_error() . "Please refresh the website.");

      if(!($res = $dbLink->query("SELECT seatID, operationType FROM operationalData")))
        die("Database Connection Problem!");

      $data = $res->fetch_all(MYSQLI_ASSOC);

      if($data == NULL || $data == 0)
      {
        $_POST["countArrPurchase"] = 0;
        $_POST["countArrReservation"] = 0;
        $_POST["arrPurchase"] = [];
        $_POST["arrReservation"] = [];
        mysqli_close($dbLink);
        return;
      }

      $out = array_column($data, 'operationType', 'seatID');

      foreach ($out as $key => $value)
      {
        if($value == "purchase")
        {
          $arrPurchase[$i] = $key;
          $i++;
        }
        else if($value == "reservation")
        {
          $arrReservation[$i] = $key;
          $i++;
        }
      }

      $_POST["arrPurchase"] = $arrPurchase;
      $_POST["arrReservation"] = $arrReservation;
      $_POST["countArrPurchase"] = count($arrPurchase);
      $_POST["countArrReservation"] = count($arrReservation);


      if(!($res = $dbLink->query("SELECT seatID, executedBy FROM operationalData")))
        die("Database Connection Problem!");

      $data = $res->fetch_all(MYSQLI_ASSOC);

      if($data == NULL || $data == 0)
      {
        $_POST["arrSeatholders"] = 0;
        mysqli_close($dbLink);
        return;
      }

      $arrSeatholders = array_column($data, 'executedBy', 'seatID');
      $_POST["arrSeatholders"] = $arrSeatholders;

      mysqli_close($dbLink);
    }
  }

  function checkInactivity()
  {
    if (isset($_SESSION['time']))
    {
      $expirationTime = 120;
      $elapsedTime = 0;

      $t = time();
      $t0 = $_SESSION['time'];
      $elapsedTime = ($t-$t0);

      if($elapsedTime > $expirationTime)
      {
        $instanceDB = new Database();
        $instanceDB->logoutServer();

        return FALSE;
      }
      else
      {
        $_SESSION['time'] = time();
        return TRUE;
      }
    }
  }

  function generateSeats($row, $column, $arrLetters, $page)
  {
    switch($page)
    {
      case "memberArea":

        $lblID = "lbl_";

        for ($x = 1; $x <= $row; $x++)
        { ?>

          <li class="row row--1">

            <ol class="seats" type="A">

            <?php
              for ($y = 1; $y <= $column; $y++)
              {
                $ids=$arrLetters[$y].$x;
                $labelID = $lblID.$ids; ?>

                <li class="seat">

                  <input type="checkbox" id="<?php echo $ids; ?>" <?php echo parseCheckboxStatus($ids);?> />
                  <label id="<?php echo $labelID;?>" style="background: <?php echo parseSeatStatus($ids);?>" for="<?php echo $ids; ?>"><?php echo $ids; ?></label>

                </li>

            <?php
              } ?>

            </ol>

          </li>

      <?php
        }

        break;

      case "index":

        for ($x = 1; $x <= $row; $x++)
        { ?>

          <li class="row row--1">

            <ol class="seats" type="A">

            <?php
              for ($y = 1; $y <= $column; $y++)
              {
                $ids=$arrLetters[$y].$x; ?>

                <li class="seat">

                  <input type="checkbox" onchange="forbidClick(this)" id="<?php echo $ids; ?>" />
                  <label style="background: <?php echo parseSeatStatus($ids);?>" for="<?php echo $ids; ?>"><?php echo $ids; ?></label>

                </li>

            <?php
              } ?>


            </ol>

          </li>

      <?php
        }

        break;
    }

  }

?>
