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

  <script type="text/javascript">

   $(document).ready(function(){

    $("input[type=checkbox]").on("change", function(){

      if($(this).is(":checked"))
      {
        makeReservation(this.id);
      }
      else if($(this).is(":not(:checked)"))
      {
        cancelReservation(this.id);
      }

     });
   });

 </script>

  <div class="sidebar">

    <a class="active" href="memberArea.php"><b>Home</b></a>
    <a href="memberAccount.php"><b>Account</b></a>

  </div>

  <div class="content">

    <div class="airplane">

      <div class="cockpit">
        <h2>Please Select Your Seat</h2>
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

        <?php generateSeats($rowDynamic, $columnDynamic, $letters, "memberArea"); ?>

      </ol>

    </div>

    <div class="flightStatus">

      <h2><b>Seat Status</b></h2>
      <p><b>Total Number of Seats:&emsp;</b><?php echo "$numTotalSeats"; ?></p>
      <p><b>Reserved: </b><?php echo "$numReservedSeats"; ?>&nbsp;&nbsp;&nbsp;&nbsp;<b>Purchased: </b><?php echo "$numPurchasedSeats"; ?></p>
      <p><b>Free: </b><?php echo "$numFreeSeats"; ?></p>

      <button class="btnUpdate" onclick="window.location.reload();">Update</button>

    </div>

    <div class = "seatSelection">

      <h2><b>User Operation</b></h2>
      <p><b>User:&emsp;</b><?php echo $_SESSION["activeUser"]; ?></p>
      <p>Do you want to proceed</p>
      <p>with current selections?</p>
      <button type="button" class="btnBuy" onclick="purchaseSeat();">BUY!</button>

    </div>

  </div>

</body>

</html>
