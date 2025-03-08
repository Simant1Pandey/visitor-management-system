<?php session_start();
	include("db_connect_db_new.php");
	if($_SESSION["loggedIn"] == 0)
	 	header("location: index.php");
$user2 = $_SESSION["user"];


	 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visitor Management</title>
    <link rel="stylesheet" href="BootStrap/css/bootstrap.min.css">
    <script src="BootStrap/js/jQuery.min.js"></script>
    <script src="BootStrap/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="query_data.css">
    <style>
        
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">
                    <i class="glyphicon glyphicon-user"></i> <?php echo "Hello, ".$user2;?>
                </a>
            </div>
            
            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="front.php"><i class="glyphicon glyphicon-home"></i> Home</a></li>
                    <li><a href="myform.php"><i class="glyphicon glyphicon-plus"></i> Add Visitor</a></li>
                    <li><a href="logoutform.php"><i class="glyphicon glyphicon-log-out"></i> Checked Out</a></li>
                    <li class="active"><a href="query_data.php"><i class="glyphicon glyphicon-search"></i> View Data</a></li> 
                    <li><a href="logout.php"><i class="glyphicon glyphicon-off"></i> Logout</a></li> 
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid main-container">
        <div class="row">
            <!-- Sidebar for Search Options -->
            <div class="col-md-3">
                <div class="sidebar">
                    <h2><i class="glyphicon glyphicon-filter"></i> Search Options</h2>
                    
                    <script>
                    function undisable() {
                        document.getElementById("dateF").disabled=false;
                        document.getElementById("dateP").disabled=false;
                    }
                    function disable() {
                        document.getElementById("dateF").disabled=true;
                        document.getElementById("dateP").disabled=true;
                    }
                    </script>
                    
                    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
                        <div class="form-group">
                            <div class="form-label">
                                <label for="all">View All Entries</label>
                                <input type="radio" id="allData" name="search" value="all" onclick="disable()">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="form-label">
                                <label for="active">View Active Visitors</label>
                                <input type="radio" name="search" id="active" value="active" onclick="disable()">
                            </div>
                        </div>
                        
                        
                        
                        <script type="text/javascript">
                        function onDateInput() {				
                            var inputDateA = document.getElementById('dateP').value;
                            if(inputDateA)
                                document.getElementById('dateF').setAttribute('min', inputDateA);
                        }
                        
                        document.getElementById('dateP').addEventListener('change', onDateInput);
                        </script>
                        
                        <button type="submit" name="submit" class="btn search-btn">
                            <i class="glyphicon glyphicon-search"></i> Search
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Main Content Area -->
            <div class="col-md-9">
                <div class="visitor-info">
                    <?php
                    if($_SERVER["REQUEST_METHOD"] == "POST") {
                        if(!isset($_POST["search"])) {
                            echo "<div class='error-message'><i class='glyphicon glyphicon-exclamation-sign'></i> Please select a search option!</div>";
                            exit();
                        }

                        /*-----------------------------------DISPAYING ALL DATA------------------------------------------*/
                        if(isset($_POST["search"]) && $_POST["search"]=="all") {
                            $query = "SELECT * FROM info_visitor";
                            $result = mysqli_query($link, $query);
                            $count = mysqli_num_rows($result);
                                        
                            if($count) {
                                echo "<h3 class='info-heading'><i class='glyphicon glyphicon-list'></i> Information of all visitors</h3>";
                                echo "<div class='row visitor-grid'>";
                                headingMake($result);
                                echo "</div>";
                            } else {
                                echo "<div class='error-message'><i class='glyphicon glyphicon-info-sign'></i> No entries to display</div>";
                            }
                        }
                                
                        /* -----------------------------------DISPLAY ACTIVE VISITORS------------------------------------*/
                        else if(isset($_POST["search"]) && $_POST["search"]=="active") {
                            if(empty($_POST["day_start"]) && empty($_POST["day_end"]) && empty($_POST["active"])) {
                                $sql = "SELECT * FROM info_visitor WHERE Status = 'ONLINE'";
                                $result = mysqli_query($link, $sql);
                                $count = mysqli_num_rows($result);
                                        
                                if($count) {
                                    echo "<h3 class='info-heading'><i class='glyphicon glyphicon-user'></i> Active Visitors</h3>";
                                    echo "<div class='row visitor-grid'>";
                                    headingMake($result);
                                    echo "</div>";
                                } else {
                                    echo "<div class='error-message'><i class='glyphicon glyphicon-info-sign'></i> No active visitors at the moment</div>";
                                }
                            } else {
                                echo "<div class='error-message'><i class='glyphicon glyphicon-exclamation-sign'></i> Select one option at a time</div>";
                            } 
                        }

                        /*-------------------------DISPALYING SELECTED DATA--------------------------------------------------*/
                        else if(isset($_POST["search"]) && $_POST["search"]=="dates") {
                            if(empty($_POST["dateP"]) || empty($_POST["dateF"])) {
                                echo "<div class='error-message'><i class='glyphicon glyphicon-exclamation-sign'></i> Please select valid date range</div>";
                            } else {
                                $datePF = $_POST['dateP'];
                                $dateFP = $_POST['dateF'];
                                $dateP = explode('-', $_POST['dateP']);
                                $dateF = explode('-', $_POST['dateF']);		     
                                                                                            
                                $day_start = $dateP[2];
                                $day_end = $dateF[2];
                                $month_start = $dateP[1];
                                $month_end = $dateF[1];
                                $year_start = $dateP[0];
                                $year_end = $dateF[0];
                                $inputDate = array("$day_start", "$day_end", "$month_start", "$month_end", "$year_start", "$year_end");
                                        
                                if($day_end >= $day_start && $month_end >= $month_start && $year_end >= $year_start) {
                                    if($day_start <= 31 && $day_end <= 31) {
                                        $sql = "SELECT * FROM info_visitor WHERE day >= '$day_start' AND day <= '$day_end' AND year >= '$year_start' 
                                            AND year <= '$year_end' AND month >= '$month_start' AND month <='$month_end'";
                                        $result = mysqli_query($link, $sql);
                                        $count = mysqli_num_rows($result);
                                        
                                        if($count) {
                                            if($inputDate[0] == $inputDate[1]) {
                                                echo "<h3 class='info-heading'><i class='glyphicon glyphicon-calendar'></i> Visitors on {$dateP[0]}-{$dateP[1]}-{$dateP[2]}</h3>";
                                            } else {
                                                echo "<h3 class='info-heading'><i class='glyphicon glyphicon-calendar'></i> Visitors from {$dateP[0]}-{$dateP[1]}-{$dateP[2]} to {$dateF[0]}-{$dateF[1]}-{$dateF[2]}</h3>";
                                            }
                                            
                                            echo "<div class='row visitor-grid'>";
                                            headingMake($result);
                                            echo "</div>";
                                        } else {
                                            echo "<div class='error-message'><i class='glyphicon glyphicon-info-sign'></i> No visitors found for the selected date range</div>";
                                        }
                                    } else {
                                        echo "<div class='error-message'><i class='glyphicon glyphicon-exclamation-sign'></i> There are maximum 31 days in any month!</div>";
                                    }
                                } else {
                                    echo "<div class='error-message'><i class='glyphicon glyphicon-exclamation-sign'></i> Please select a valid date range (from past to future)</div>";
                                }
                            }
                        } 
                    }

                    function echoDetails($row) {
                        if($row["TimeOUT"] == NULL) $row["TimeOUT"] = "Not Yet Logged out";
                        echo "<tr><td>" .$row['Name']."</td><td>"
                                        .$row['Contact'] ."</td><td>"
                                        .$row["Purpose"]."</td><td>"
                                        .$row["Date"]."</td><td>"
                                        .$row["TimeIN"]."</td><td>".$row["TimeOUT"]. "</td><td>"
                                        .$row["Comment"]."</td><td>"
                                        .$row["Status"]."</td><td>"
                                        .$row["registeredBy"]."</td></tr>";  
                    }
                        
                    function headingMake($res) {
                        while($result = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
                            echo '<div class="col-sm-6 col-md-4 visitor-card">
                                    <div class="thumbnail">
                                        <p style="text-align:center;"><strong>'.$result['Name'].'</strong></p>
                                        <p><i class="glyphicon glyphicon-tag"></i> Receipt ID: '.$result['ReceiptID'].'</p>
                                        <p><i class="glyphicon glyphicon-phone"></i> Contact: '.$result['Contact'].'</p>
                                        <p><i class="glyphicon glyphicon-time"></i> Time In: '.$result['TimeIN'].'</p>
                                        <p><i class="glyphicon glyphicon-calendar"></i> Date: '.$result['Date'].'</p>
                                        <p><i class="glyphicon glyphicon-user"></i> Meeting: '.$result['meetingTo'].'</p>
                                    </div>
                                </div>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>