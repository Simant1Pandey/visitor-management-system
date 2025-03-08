
<?php  include('db_connect_db_new.php');

        include('visitor_out.php');
    
		$user1 = $_SESSION["user"];
		
	if($_SESSION["loggedIn"] == 0)
	 	header("location: index.php");
     $user_ = $_SESSION["user"];

   $tody = date("Y:m:d");
	 $sql = "SELECT Name FROM info_visitor WHERE Date = '$tody'";
  
   $sqlOnline = "SELECT * FROM info_visitor WHERE Status = 'ONLINE' LIMIT 10";

    $sqlRecent = "SELECT * FROM (SELECT * FROM info_visitor ORDER BY Serial DESC LIMIT 10) a ORDER BY Serial DESC";

   $resultToday = mysqli_num_rows(mysqli_query($link,$sql));   //recent Visitors
   $resultS = mysqli_query($link,$sqlOnline);       //Online Visitors
  
   $onlineVsitor = mysqli_num_rows($resultS);

    $sqlResRecent = mysqli_query($link,$sqlRecent);

    if (isset($_GET['delete_id'])) {
      $deleteId = $_GET['delete_id'];
      $deleteQuery = "DELETE FROM info_visitor WHERE ReceiptID = '$deleteId'";
      mysqli_query($link, $deleteQuery);
      header("Location: front.php");
  }

  // EDIT Visitor
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_visitor'])) {
  $receiptId = $_POST['receipt_id'];
  $name = $_POST['name'];
  $contact = $_POST['contact'];
  $purpose = $_POST['purpose'];
  $meetingTo = $_POST['meetingTo'];
  $comment = $_POST['comment'];

  $updateQuery = "UPDATE info_visitor SET Name='$name', Contact='$contact', Purpose='$purpose', meetingTo='$meetingTo', Comment='$comment' WHERE ReceiptID='$receiptId'";
  mysqli_query($link, $updateQuery);
  header("Location: front.php");
}


?>
<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visitor Management System</title>
    <link rel="stylesheet" href="BootStrap/css/bootstrap.min.css">
    <script src="BootStrap/js/jQuery.min.js"></script>
    <script src="BootStrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="front.css">
    
    <style>
      
    </style>
</head>
<body>

<nav class="navbar">
    <div class="container navbar-container">
      <div class="navbar-brand">
        <i class="fas fa-user-check"></i>
        <span id="logged-in-user"><?php echo "Logged in as: ".$user_; ?></span>
      </div>
      <ul class="navbar-nav">
        <li class="nav-item"><a href="front.php" class="nav-link active">Home</a></li>
        <li class="nav-item"><a href="myform.php" class="nav-link ">Add Visitor</a></li>
        <li class="nav-item"><a href="logoutform.php" class="nav-link">Checked Out Visitors</a></li>
        <li class="nav-item"><a href="query_data.php" class="nav-link">View Data</a></li>
        <li class="nav-item"><a href="logout.php" class="nav-link">Logout</a></li>
      </ul>
    </div>
  </nav>
<div class="container-fluid">
    <div class="row">
        <!-- Check Out Visitor Section -->
        <div class="col-lg-3 col-md-12">
            <div class="card checkout-form">
                <div class="card-header">
                    <i class="fas fa-sign-out-alt"></i> Check Out Visitor
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="rid"><i class="fas fa-receipt"></i> Receipt ID:</label>
                            <input class="form-control" name="rid" type="number" placeholder="Enter Receipt ID" required/>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-block" name="logout" 
                                onclick='return confirm("Are you sure you want to Checkout?")'>
                            <i class="fas fa-check-circle"></i> Checkout
                        </button>
                        
                        <?php if($_SERVER['REQUEST_METHOD'] == 'POST'){ ?>
                            <div class="mt-3 text-center">
                                <?php if($success == 1) { ?>
                                    <div class="alert alert-success">
                                        <i class="fas fa-check-circle"></i> Successfully checked out!
                                    </div>
                                <?php } else { ?>
                                    <div class="alert alert-danger">
                                        <i class="fas fa-exclamation-circle"></i> Sorry! No match found.
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </form>
                </div>
            </div>
            
            <!-- Online Visitors Section -->
            <div class="card mt-4">
                <div class="card-header">
                    <i class="fas fa-users"></i> Online Visitors 
                    <span class="badge badge-pill badge-success"><?php echo $onlineVsitor; ?></span>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group visitor-list">
                        <?php while($result2 = mysqli_fetch_array($resultS, MYSQLI_ASSOC)) { ?>
                            <li class="list-group-item">
                                <div class="status-indicator"></div>
                                <a href="front.php?rid=<?php echo $result2['ReceiptID']; ?>" 
                                   data-html="true" 
                                   title="<b><?php echo $result2['Name']; ?></b>" 
                                   data-toggle="popover" 
                                   data-trigger="hover"
                                   data-content="Contact: <?php echo $result2['Contact']; ?><br>
                                                Time in: <?php echo $result2['TimeIN']; ?><br>
                                                Purpose: <?php echo $result2['Purpose']; ?><br>
                                                R ID: <?php echo $result2['ReceiptID']; ?>">
                                    <?php echo $result2['Name']; ?>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Visitor Details Section -->
        <div class="col-lg-4 col-md-12">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-info-circle"></i> Visitor Details
                </div>
                <div class="card-body">
                    <?php 
                    $showResultFor = 0;
                    if(isset($_GET['rid'])){
                        $showResultFor = $_GET['rid'];
                    }
                    $query = "SELECT * FROM info_visitor WHERE ReceiptID = '$showResultFor' AND Status = 'ONLINE'";
                    $getresult = mysqli_query($link, $query);
                    $resultDetails = mysqli_fetch_array($getresult, MYSQLI_ASSOC);
                    
                    if($resultDetails) { ?>
                        <div class="visitor-details">
                            <div class="visitor-detail-row">
                                <span class="detail-label"><i class="far fa-calendar-alt"></i> Date:</span> 
                                <?php echo $resultDetails['Date']; ?>
                            </div>
                            <div class="visitor-detail-row">
                                <span class="detail-label"><i class="far fa-clock"></i> Time in:</span> 
                                <?php echo $resultDetails['TimeIN']; ?>
                            </div>
                            <div class="visitor-detail-row">
                                <span class="detail-label"><i class="fas fa-user"></i> Name:</span> 
                                <?php echo $resultDetails['Name']; ?>
                            </div>
                            <div class="visitor-detail-row">
                                <span class="detail-label"><i class="fas fa-phone"></i> Contact:</span> 
                                <?php echo $resultDetails['Contact']; ?>
                            </div>
                            <div class="visitor-detail-row">
                                <span class="detail-label"><i class="fas fa-tasks"></i> Purpose:</span> 
                                <?php echo $resultDetails['Purpose']; ?>
                            </div>
                            <div class="visitor-detail-row">
                                <span class="detail-label"><i class="fas fa-user-tie"></i> Meeting:</span> 
                                <?php echo $resultDetails['meetingTo']; ?>
                            </div>
                            <div class="visitor-detail-row">
                                <span class="detail-label"><i class="fas fa-receipt"></i> Receipt ID:</span> 
                                <?php echo $resultDetails['ReceiptID']; ?>
                            </div>
                            <div class="visitor-detail-row">
                                <span class="detail-label"><i class="fas fa-comment"></i> Comment:</span> 
                                <?php echo $resultDetails['Comment']; ?>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="text-center py-5">
                            <i class="fas fa-user-slash fa-4x text-muted"></i>
                            <p class="mt-3 text-muted">Select a visitor to view details</p>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        
        <!-- Recent Visitors Section -->
        <div class="col-lg-5 col-md-12">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-history"></i> Recent Visitors
                    <span class="badge badge-pill badge-primary"><?php echo mysqli_num_rows($sqlResRecent); ?></span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th><i class="fas fa-user"></i> Name</th>
                                    <th><i class="fas fa-phone"></i> Contact</th>
                                    <th><i class="fas fa-tasks"></i> Purpose</th>
                                    <th><i class="fas fa-cogs"></i> Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($visitor = mysqli_fetch_array($sqlResRecent, MYSQLI_ASSOC)) { ?>
                                    <tr>
                                        <td><?php echo $visitor['Name']; ?></td>
                                        <td><?php echo $visitor['Contact']; ?></td>
                                        <td><?php echo $visitor['Purpose']; ?></td>
                                        <td>
                                            <a href="front.php?edit_id=<?php echo $visitor['ReceiptID']; ?>" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="front.php?delete_id=<?php echo $visitor['ReceiptID']; ?>" 
                                               onclick="return confirm('Are you sure?')" 
                                               class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Edit Visitor Form -->
            <?php if (isset($_GET['edit_id'])) {
                $editId = $_GET['edit_id'];
                $editQuery = "SELECT * FROM info_visitor WHERE ReceiptID = '$editId'";
                $editResult = mysqli_query($link, $editQuery);
                $editData = mysqli_fetch_array($editResult, MYSQLI_ASSOC);
            ?>
            <div class="card mt-4">
                <div class="card-header">
                    <i class="fas fa-user-edit"></i> Edit Visitor
                </div>
                <div class="card-body edit-form">
                    <form method="POST" class="row">
                        <input type="hidden" name="receipt_id" value="<?php echo $editData['ReceiptID']; ?>">
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-user"></i> Name:</label>
                                <input type="text" class="form-control" name="name" value="<?php echo $editData['Name']; ?>" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-phone"></i> Contact:</label>
                                <input type="text" class="form-control" name="contact" value="<?php echo $editData['Contact']; ?>" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-tasks"></i> Purpose:</label>
                                <input type="text" class="form-control" name="purpose" value="<?php echo $editData['Purpose']; ?>" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-user-tie"></i> Meeting:</label>
                                <input type="text" class="form-control" name="meetingTo" value="<?php echo $editData['meetingTo']; ?>">
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-group">
                                <label><i class="fas fa-comment"></i> Comment:</label>
                                <input type="text" class="form-control" name="comment" value="<?php echo $editData['Comment']; ?>">
                            </div>
                        </div>
                        
                        <div class="col-12 mt-3">
                            <button type="submit" name="edit_visitor" class="btn btn-success">
                                <i class="fas fa-save"></i> Update
                            </button>
                            <a href="front.php" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>

<!-- Bootstrap Modal -->
<div id="confirmationModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><i class="fas fa-question-circle"></i> Confirmation</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p id="modal-message">Are you sure you want to proceed?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" id="confirmAction">
            <i class="fas fa-check"></i> Yes
        </button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
            <i class="fas fa-times"></i> Cancel
        </button>
      </div>
    </div>
  </div>
</div>

<script>
    $(document).ready(function(){
        $('[data-toggle="popover"]').popover();
        
        // Initialize popover
        $('[data-toggle="popover"]').popover({
            html: true,
            placement: 'right',
            trigger: 'hover'
        });
    });
</script>

</body>
</html>