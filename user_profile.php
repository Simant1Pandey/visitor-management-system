<?php 
include('db_connect_db_new.php');  
  session_start();
  $r_id = $_SESSION['rid'];
  $sql = "SELECT * FROM info_visitor WHERE ReceiptID = $r_id";
  $re = mysqli_query($link, $sql);
  $result = mysqli_fetch_array($re, MYSQLI_ASSOC);

  // Handle delete request
  if (isset($_POST['delete'])) {
      $delete_sql = "DELETE FROM info_visitor WHERE ReceiptID = $r_id";
      if (mysqli_query($link, $delete_sql)) {
          echo "<script>alert('Visitor deleted successfully!'); window.location.href='front.php';</script>";
      } else {
          echo "<script>alert('Error deleting visitor.');</script>";
      }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visitor Details</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="userprofile.css">
    <style>
      
    </style>
</head>
<body>
    <div class="container">
        <div class="page-header">
            <h1 class="page-title">Visitor Details</h1>
        </div>

        <div class="card">
            <div class="card-header">
                <h2 class="card-title"><i class="fas fa-id-card"></i> Visitor Information</h2>
            </div>
            <div class="card-body">
                <div class="visitor-details">
                    <div class="detail-item">
                        <span class="detail-label">Date</span>
                        <div class="detail-value"><?php echo $result['Date'];?></div>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Time In</span>
                        <div class="detail-value"><?php echo $result['TimeIN'] ?></div>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Name</span>
                        <div class="detail-value"><?php echo $result['Name']; ?></div>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Contact No</span>
                        <div class="detail-value"><?php  echo $result['Contact'] ?></div>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Purpose</span>
                        <div class="detail-value"><?php echo $result['Purpose'] ?></div>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Meeting</span>
                        <div class="detail-value"><?php echo $result['meetingTo']; ?></div>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Receipt ID</span>
                        <div class="detail-value"><?php echo $result['ReceiptID'] ?></div>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Comment</span>
                        <div class="detail-value"><?php echo $result['Comment'];?></div>
                    </div>
                </div>

                <div class="badge-note">
                    <i class="fas fa-info-circle"></i> NOTE: This visitor badge is only valid for x hours, please return it at the exit!
                </div>

                <div class="actions">
                    <a href="front.php" class="btn btn-back">
                        <i class="fas fa-arrow-left"></i> Back to home
                    </a>
                    <form method="POST" onsubmit="return confirm('Are you sure you want to delete this visitor?');">
                        <button type="submit" name="delete" class="btn btn-delete">
                            <i class="fas fa-trash-alt"></i> Delete Visitor
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>