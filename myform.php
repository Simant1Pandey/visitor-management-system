<?php 
include('db_connect_db_new.php');  
session_start();	
if($_SESSION["loggedIn"] == 0)
	 	header("location: index.php");
	$user_ = $_SESSION["user"];

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Visitor Management System</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <script src="BootStrap/js/jQuery.min.js"></script>
  <script src="BootStrap/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="webcam/webmaster/webcam.js"></script>
  <link rel="stylesheet" href="myform.css ">
  <style>
   
  </style>
</head>

<body onload="display_ct()">
  <?php
    $success = 0;
    $displayError = "";
    $name_error = "";
    $cno_error = "";
    $p_error = "";

    if(!$link)
      die("error". mysqli_link_error());

    if($_SERVER["REQUEST_METHOD"] == "POST"){
      if(empty($_POST["name"]))
        $name_error = "Enter the Name Properly !";
      else
        $name = $_POST["name"];
    
      if(strlen($_POST["cno"]) != 10)
        $cno_error = "Enter Valid Contact number";
      else
        $cno = $_POST["cno"];

      if(empty($_POST["purpose"]))
        $p_error = "Enter Valid Purpose";
      else
        $p = $_POST["purpose"];
      
      date_default_timezone_set("Asia/Kathmandu");
      $timein = date("H:i:s");
      $rid = rand(100000,900000);
      $_SESSION["rid"] = $rid;

      $date = date("Y/m/d");
      $comment = $_POST["comment"];
      $day = date("d");
      $month = date("m");
      $year = date("Y");
      $meet = $_POST["MeetingTo"];
      
      if(empty($name) || empty($cno) || empty($p) || strlen($cno)!=10)
        $displayError = "You have not entered the details correctly !"; 
      else{
        $sql = "INSERT INTO info_visitor(Name, Contact, Purpose, meetingTo, day, 
                                    month, year, Date, TimeIN, ReceiptID, Status,
                  Comment, registeredBy) VALUES ('$name','$cno','$p',
                  '$meet', '$day', '$month', '$year', '$date',
                  '$timein','$rid','ONLINE', '$comment', 
                  '$user_')";

        if(mysqli_query($link, $sql)) 
          $success = 1;   //redirection to the printing page.
        else
          echo "Error: " . $sql . "<br>" . mysqli_error($link);
      }

      if($success == 1)
        header('location: user_profile.php');
    }
  ?>

  <!-- Navbar -->
  <nav class="navbar">
    <div class="container navbar-container">
      <div class="navbar-brand">
        <i class="fas fa-user-check"></i>
        <span id="logged-in-user"><?php echo "Logged in as: ".$user_; ?></span>
      </div>
      <ul class="navbar-nav">
        <li class="nav-item"><a href="front.php" class="nav-link">Home</a></li>
        <li class="nav-item"><a href="myform.php" class="nav-link active">Add Visitor</a></li>
        <li class="nav-item"><a href="logoutform.php" class="nav-link">Checked Out Visitors</a></li>
        <li class="nav-item"><a href="query_data.php" class="nav-link">View Data</a></li>
        <li class="nav-item"><a href="logout.php" class="nav-link">Logout</a></li>
      </ul>
    </div>
  </nav>

  <!-- Main content -->
  <div class="container">
    <!-- Page header -->
    <div class="page-header">
      <h1 class="page-title">Add Visitor</h1>
      <div class="time-date">
        <p class="time-display">Time: <span id="t1" class="time-value"></span></p>
        <p class="date-display">Date: <span id="t2" class="date-value"></span></p>
      </div>
    </div>

    <?php if (!empty($displayError)): ?>
      <div class="card" style="background-color: #fff4f4; border-left: 4px solid var(--danger-color); margin-bottom: 20px;">
        <div class="card-body">
          <p style="color: var(--danger-color); margin: 0;"><i class="fas fa-exclamation-triangle"></i> <?php echo $displayError; ?></p>
        </div>
      </div>
    <?php endif; ?>

    <!-- Form card -->
    <div class="card">
      <div class="card-body">
        <form id="visitor-form" action="<?php echo $_SERVER["PHP_SELF"];?>" method="POST">
          <div class="form-row">
            <div class="form-col">
              <div class="form-group">
                <label for="name" class="form-label">Visitor's Name</label>
                <input type="text" id="name" name="name" class="form-control" 
                       placeholder="Enter visitor's full name" required
                       oninvalid="this.setCustomValidity(this.willValidate?'':'Name is required')" 
                       onblur="isEmpty('name')" onfocus="onfo('name')">
                <?php if (!empty($name_error)): ?>
                  <span class="error-text"><?php echo $name_error; ?></span>
                <?php endif; ?>
              </div>
            </div>
            <div class="form-col">
              <div class="form-group">
                <label for="ContactInfo" class="form-label">Contact Number</label>
                <input type="number" id="ContactInfo" name="cno" class="form-control" 
                       placeholder="10-digit mobile number" required min="1000000000" max="9999999999"
                       onkeyup="Ccheck('ContactInfo')" onblur="isEmpty('ContactInfo')" 
                       onfocus="onfo('ContactInfo')"
                       oninvalid="this.setCustomValidity(this.willValidate?'':'Enter correct Contact number please')">
                <?php if (!empty($cno_error)): ?>
                  <span class="error-text"><?php echo $cno_error; ?></span>
                <?php endif; ?>
                <span id="span" style="padding-bottom: 5px; float:right;"></span>
              </div>
            </div>
          </div>

          <div class="form-row">
            <div class="form-col">
              <div class="form-group">
                <label for="Purpose" class="form-label">Purpose of Visit</label>
                <input type="text" id="Purpose" name="purpose" class="form-control" 
                       placeholder="Why are you visiting?" required maxlength="30"
                       oninvalid="this.setCustomValidity(this.willValidate?'':'Enter your Purpose')" 
                       onblur="isEmpty('Purpose')">
                <?php if (!empty($p_error)): ?>
                  <span class="error-text"><?php echo $p_error; ?></span>
                <?php endif; ?>
              </div>
            </div>
            <div class="form-col">
              <div class="form-group">
                <label for="meetingTo" class="form-label">Meeting With</label>
                <input type="text" id="meetingTo" name="MeetingTo" class="form-control" 
                       placeholder="Who are you meeting?" required maxlength="30"
                       oninvalid="this.setCustomValidity(this.willValidate?'':'Whom do you want to meet?')" 
                       onblur="isEmpty('meetingTo')">
              </div>
            </div>
          </div>

          <div class="form-group">
            <label for="comment" class="form-label">Additional Comments</label>
            <input type="text" id="comment" name="comment" class="form-control" 
                   placeholder="Any additional information" maxlength="30">
          </div>

          <input type="hidden" id="mydata" name="mydata">
          <button type="submit" id="submitme" name="submit_post" class="btn btn-success" onclick="return emptys()">
            <i class="fas fa-plus-circle"></i> Add Visitor
          </button>
        </form>
      </div>
    </div>
  </div>

  <script type="text/javascript"> 
    function display_c(){
      var refresh=1000; // Refresh rate in milli seconds
      mytime=setTimeout('display_ct()',refresh)
    }
    
    function display_ct() {
      var date = new Date();
      var hours = date.getHours() > 12 ? date.getHours() - 12 : date.getHours();
      hours = hours === 0 ? 12 : hours; // Handle midnight
      var am_pm = date.getHours() >= 12 ? "PM" : "AM";
      hours = hours < 10 ? "0" + hours : hours;
      var minutes = date.getMinutes() < 10 ? "0" + date.getMinutes() : date.getMinutes();
      var seconds = date.getSeconds() < 10 ? "0" + date.getSeconds() : date.getSeconds();
      time = hours + ":" + minutes + ":" + seconds + " " + am_pm;
      document.getElementById('t1').innerHTML = time;
      
      var x = new Date();
      var month = x.getMonth() + 1;
      month = month < 10 ? "0" + month : month;
      var day = x.getDate() < 10 ? "0" + x.getDate() : x.getDate();
      var x1 = month + "/" + day + "/" + x.getFullYear();
      document.getElementById('t2').innerHTML = x1;
      
      display_c();
    }
    
    // Form validation functions preserved from original code
    function isEmpty(id) {
      var element = document.getElementById(id);
      if(element.value.trim() === "") {
        element.style.borderColor = "var(--danger-color)";
        return false;
      } else {
        element.style.borderColor = "#ddd";
        return true;
      }
    }
    
    function onfo(id) {
      document.getElementById(id).style.backgroundColor = "var(--light-color)";
    }
    
    function Ccheck(id) {
      var element = document.getElementById(id);
      if(element.value.length === 10) {
        document.getElementById("span").innerHTML = "✓";
        document.getElementById("span").style.color = "var(--success-color)";
      } else {
        document.getElementById("span").innerHTML = "✗";
        document.getElementById("span").style.color = "var(--danger-color)";
      }
    }
    
    function emptys() {
      var name = isEmpty('name');
      var contact = isEmpty('ContactInfo');
      var purpose = isEmpty('Purpose');
      var meeting = isEmpty('meetingTo');
      
      return name && contact && purpose && meeting;
    }
  </script>
</body>
</html>