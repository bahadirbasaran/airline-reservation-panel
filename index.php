<!-- IN ORDER TO ADJUST THE SEAT SCHEMA, PLEASE REFER TO THE FIRST PART OF "subsystem.php" -->

<?php
  require_once "subsystem.php";
  include "header.php";

  if($_SERVER["HTTPS"] !== "on" || empty($_SERVER["HTTPS"]))
  {
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
  }
?>

<!DOCTYPE html>
<html lang="en">

<script type="text/javascript">
  if (!window.navigator.cookieEnabled)
  {
      window.location.replace("notFoundCookies.php");
  }
</script>
<noscript>
        <meta http-equiv="refresh" content="0; URL=notFoundJS.php">
</noscript>

<body>

  <div class="sidebar">

    <a class="active" href="index.php"><b>Home</b></a>
    <a href="account.php"><b>Account</b></a>

  </div>

  <div class="content">

    <div class="airplane">

      <div class="cockpit">
        <h2><i>PEGASUS AIRLINES</i></h2>
      </div>

      <?php
        $numTotalSeats = $rowDynamic * $columnDynamic;

        $instanceDB = new Database();
        $instanceDB->getSeatStatus();

        $numPurchasedSeats     = $_POST["countArrPurchase"];
        $numReservedSeats      = $_POST["countArrReservation"];
        $numFreeSeats          = $numTotalSeats - ($numPurchasedSeats + $numReservedSeats);
  		?>

      <ol class="fuselage">

        <?php generateSeats($rowDynamic, $columnDynamic, $letters, "index"); ?>

      </ol>

    </div>


    <div class="flightStatus">

      <h2><b>Seat Status</b></h2>
      <p><b>Total Number of Seats:&emsp;</b><?php echo "$numTotalSeats"; ?></p>
      <p><b>Reserved: </b><?php echo "$numReservedSeats"; ?>&nbsp;&nbsp;&nbsp;&nbsp;<b>Purchased: </b><?php echo "$numPurchasedSeats"; ?></p>
      <p><b>Free: </b><?php echo "$numFreeSeats"; ?></p>

    </div>

  </div>

</body>

</html>
