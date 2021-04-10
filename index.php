<!DOCTYPE html>
<html>
<head>
    <title>Vaccination Stats</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  
    <link rel="stylesheet" href="style.php" media="screen">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  
  </head>
<body>
<script src="https://code.jquery.com/jquery.js"></script>

    <h1>Vaccination Stats</h1>

    <?php 

    $msgE = $msgS = $nameErr = $mobileErr = $vaccinatedArmErr = "";
    $msg = $name = $mobile = $vaccinated = "";
    $vaccinatedArm = "None";
    $vaccinated = 1;

    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //Validations
        if (empty($_POST["name"])) {
            $nameErr = "Name is required";
        } else {
            $name = test_input($_POST["name"]);
            if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
                $nameErr = "Only letters and white space allowed";
            }
        }
        if (empty($_POST["mobile"])) {
            $mobileErr = "Name is required";
        } else {
            $mobile = test_input($_POST["mobile"]);
            if (!preg_match("/^[0-9]{10}$/", $mobile)) {
                $mobileErr = "Only numbers allowed";
            }
        }

        if (empty($_POST["vaccinatedArm"]) && isset($_POST['vaccinated'])) {
            $vaccinatedArmErr = "vaccinatedArm is required";
          } else {
            $VaccinatedArm = test_input($_POST["VaccinatedArm"]);
          }

        if (!isset($_POST['vaccinated'])) {
            $vaccinated = 1;
            $vaccinatedArm = "None";

        } else {
            $vaccinated = 0;
        }
    }

    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }


   ?>
<div class="row text-center p-b-5">
    <form method="post" id="submitingForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div>
            Name: <input type="text" name="name" value="">
            <span class="error"> <?php echo $nameErr; ?></span>
            Mobile: <input type="text" maxlength="10" name="mobile" value="">
            <span class="error"> <?php echo $mobileErr; ?></span>
            Vaccinated: <input type="checkbox" name="vaccinated"  onclick="showMe('Arm')" value="0">
        </div>
        <div id="Arm"  style="display:none">

            Vaccinated Arm:
  <input type="radio" name="VaccinatedArm" <?php if (isset($VaccinatedArm) && $VaccinatedArm=="Right") echo "checked";?> value="Right">Right
  <input type="radio" name="VaccinatedArm" <?php if (isset($VaccinatedArm) && $VaccinatedArm=="Left") echo "checked";?> value="Left">Left

  <script>
$(document).ready(function(){
    $('input[type="checkbox"]').click(function(){
        $("#Arm").toggle();
        
    });
});

var form = document.getElementById("submitingForm");
function handleForm(event) { event.preventDefault(); } 
form.addEventListener('Submit', handleForm);

</script>

        </div>
        <div class="row m-t-5 text-center" style="padding-top: 10px; padding-bottom: 10px">
            <input type="submit" name="submit" value="Submit">
        </div>
    </form>
<?php
    $link = mysqli_connect("localhost", "root", "", "vaccinedb");

// Check connection
if ($link === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

// Attempt insert query execution
if (isset($_POST['submit'])) {
if($nameErr = "" && $mobileErr = "" && $VaccinatedArmErr = ""){
$sql = "INSERT INTO vaccine (PaientName, mobile, vaccinated, vaccinatedarm) VALUES ('$name', $mobile, $vaccinated, '$vaccinatedArm')";
if (mysqli_query($link, $sql)) {
    $msgS = "Records inserted successfully.";
} else {
    $msgE = "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}
}
}
?>
<div class="tbl-header" style="margin: auto;width: 80%;">
<?php

$sqlDisplay = "SELECT * FROM vaccine";
$result = $link->query($sqlDisplay);

if ($result->num_rows > 0) {
echo "<table cellpadding='0' cellspacing='0' border='0'><tr><th class='header'>ID</th><th class='header'>Name</th><th class='header'>Mobile</th><th class='header'>Vaccianted</th><th class='header'>VaccinatedArm</th></tr>";

// output data of each row
while($row = $result->fetch_assoc()) {
echo "<tr><td>" . $row["id"]. "</td><td>" . $row["PaientName"]. "</td><td>" . $row["Mobile"]. "</td><td>" . $row["Vaccinated"] . "</td><td>" . $row["VaccinatedArm"] . "</td></tr>";
}
echo "</table>";

} else {
echo "0 results";
}

// Close connection
mysqli_close($link);

?>
    
    <?php if($msgS != "") echo "<div class='row alert-success alert'>$msgS</div>"; ?>
    <?php if($msgS != "") echo "<div class='row alert-danger alert'>$msgE</div>"; ?>

</body>

<style  type="text/css">

h1{
  font-size: 30px;
  color: #fff;
  text-transform: uppercase;
  font-weight: 300;
  text-align: center;
  margin-bottom: 15px;
}
table{
  width:100%;
  table-layout: fixed;
}
.tbl-header{
  background-color: rgba(255,255,255,0.3);
 }
.tbl-content{
  height:300px;
  overflow-x:auto;
  margin-top: 0px;
  border: 1px solid rgba(255,255,255,0.3);
}
th{
  padding: 20px 15px;
  text-align: left;
  font-weight: 500;
  font-size: 12px;
  color: #fff;
  text-transform: uppercase;
}
td{
  padding: 15px;
  text-align: left;
  vertical-align:middle;
  font-weight: 300;
  font-size: 12px;
  color: #fff;
  border-bottom: solid 1px rgba(255,255,255,0.1);
}


/* demo styles */

@import url(https://fonts.googleapis.com/css?family=Roboto:400,500,300,700);
body{
  background: -webkit-linear-gradient(left, #25c481, #25b7c4);
  background: linear-gradient(to right, #25c481, #25b7c4);
  font-family: 'Roboto', sans-serif;
}
section{
  margin: 50px;
}


/* follow me template */
.made-with-love {
  margin-top: 40px;
  padding: 10px;
  clear: left;
  text-align: center;
  font-size: 10px;
  font-family: arial;
  color: #fff;
}
.made-with-love i {
  font-style: normal;
  color: #F50057;
  font-size: 14px;
  position: relative;
  top: 2px;
}
.made-with-love a {
  color: #fff;
  text-decoration: none;
}
.made-with-love a:hover {
  text-decoration: underline;
}


/* for custom scrollbar for webkit browser*/

::-webkit-scrollbar {
    width: 6px;
} 
::-webkit-scrollbar-track {
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3); 
} 
::-webkit-scrollbar-thumb {
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3); 
}
</style>
</html>