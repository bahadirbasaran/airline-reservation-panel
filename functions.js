
function forbidClick(element)
{
  element.checked = !element.checked;

  if (confirm("You need to be authorized to operate. In order to register or login, please press OK") == true)
  {
    window.location.replace("account.php");
  }
  else
    return true;
}

function checkCredentials()
{
  var check = $('#reg_mail').val();

  if(!check.includes('@'))
    return "notValidEmail";

  check = $('#reg_pass').val();

  if(check.search(/[a-z]/) < 0)
    return "notValidPassword";
  else if(check.search(/[0-9A-Z]/) < 0)
    return "notValidPassword";

  var check_2 = $('#reg_pass_rw').val();

  if(check === check_2)
    return TRUE;
  else {
    return "notMatchedPasswords";
  }
}

function makeReservation(element)
{
  $.ajax({
           url:  'reserveSeat.php',
           type: 'POST',
           data: { "seat" : element },
           dataType: 'text'
           })
             .done(function(response) {

               if(response == "seatNotExist")
               {
                 element.checked = !element.checked;
                 document.getElementById("lbl_" + element).style.background = "red";
                 alert(element + " has been purchased! You can not make any operation on purchased seats!");
               }
               else if(response == "seatReserved")
               {
                 document.getElementById("lbl_" + element).style.background = "yellow";
                 alert(element + " has been reserved!");
               }
               else if(response == "failure")
               {
                 element.checked = !element.checked;
                 alert("An error occured during the operation. Please try again.");
               }
               else if(response == "sessionTimeout")
               {
                 element.checked = !element.checked;
                 alert("You are timed out! Please login again.");
                 window.location.replace("index.php");
               }
               else if(response == "dbFailure")
               {
                 element.checked = !element.checked;
                 alert("An error occured during database operations. Please try again.");
               }
             })
               .fail(function(jqXHR, status, error) {

                  element.checked = !element.checked;
                  //alert(jqXHR.response);
               })
                 .always(function(){

                    $(".flightStatus").load(location.href + " .flightStatus>*","");
                 });
}

function cancelReservation(element)
{
  $.ajax({
           url:  'cancelSeat.php',
           type: 'POST',
           data: { "seat" : element },
           dataType: 'text'
           })
             .done(function(response) {

               if(response == "reservationRemoved")
               {
                 document.getElementById("lbl_" + element).style.background = "green";
                 alert(element + " has been released!");
               }
               else if(response == "purchased")
               {
                 element.checked = !element.checked;
                 alert(element + " has been purchased! You can not make any operation on purchased seats!");
               }
               else if(response == "reservedByOthers")
               {
                 element.checked = !element.checked;
                 alert("You can not make any operation on seat that belongs other user!");
               }
               else if(response == "failure")
               {
                 element.checked = !element.checked;
                 alert("An error occured during the operation. Please try again.");
               }
               else if(response == "sessionTimeout")
               {
                 element.checked = !element.checked;
                 alert("You are timed out! Please login again.");
                 window.location.replace("index.php");
               }
               else if(response == "dbFailure")
               {
                 element.checked = !element.checked;
                 alert("An error occured during database operations. Please try again.");
               }
             })
               .fail(function(jqXHR, status, error) {

                  element.checked = !element.checked;
                  //alert(jqXHR.response);
               })
                 .always(function(){

                    $(".flightStatus").load(location.href + " .flightStatus>*","");
                 });
}

function purchaseSeat()
{
  var arrReservedSeats = [];
  var timeoutOccured = false;

  $("input[type=checkbox]").each(function() {

    var color = document.getElementById("lbl_" + this.id).style.backgroundColor;

    if (color === 'yellow')
    {
        arrReservedSeats.push(this.id);
    }
  });

  if(arrReservedSeats.length == 0)
  {
    alert("There is no reserved seat to buy!");
    return;
  }

  $.ajax({
           url:  'completePurchase.php',
           type: 'POST',
           data: {"arrResSeats" : arrReservedSeats},
           dataType: 'text'
           })
             .done(function(response) {

               if(response == "success")
               {
                 alert("Purchase operation has been accomplished!");
               }
               else if(response == "forbiddenOperation")
               {
                 alert("Your reserved seat(s) are not present anymore! Please try again.");
               }
               else if(response == "failure")
               {
                 alert("An error occured during the operation. Please try again.");
               }
               else if(response == "sessionTimeout")
               {
                 timeoutOccured = true;
                 alert("You are timed out! Please login again.");
		             window.location.replace("index.php");
               }
               else if(response == "dbFailure")
               {
                 alert("An error occured during database operations. Please try again.");
               }
             })
               .fail(function(jqXHR, status, error) {

                  //alert(jqXHR.response);
               })
                 .always(function(){

                   if(timeoutOccured !== true)
                   {
                     window.location.reload("memberArea.php");
                   }
                 });
}

/* .includes() method is not supported by internet explorer. In order to polyfill this method: */
if (!String.prototype.includes)
{
  String.prototype.includes = function(search, start) {
    'use strict';
    if (typeof start !== 'number') {
      start = 0;
    }

    if (start + search.length > this.length) {
      return false;
    } else {
      return this.indexOf(search, start) !== -1;
    }
  };
}
