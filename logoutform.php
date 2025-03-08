<?php  
  include('visitor_out.php');   
  $userM = $_SESSION['user']; 
  if($_SESSION["loggedIn"] == 0)
    header("location: index.php");  
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Checked Out Visitors</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <script src="BootStrap/js/jQuery.min.js"></script>
  <script src="BootStrap/js/bootstrap.min.js"></script>
  <style>
    :root {
      --primary-color: #3949ab;
      --secondary-color: #5c6bc0;
      --accent-color: #7986cb;
      --light-color: #e8eaf6;
      --dark-color: #283593;
      --success-color: #4caf50;
      --danger-color: #f44336;
      --warning-color: #ff9800;
      --text-color: #333;
      --text-light: #666;
      --border-radius: 8px;
      --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      --transition: all 0.3s ease;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
      background-color: #f5f7ff;
      color: var(--text-color);
      line-height: 1.6;
    }

    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 20px;
    }

    /* Navbar */
    .navbar {
      background-color: var(--primary-color);
      box-shadow: var(--box-shadow);
      position: sticky;
      top: 0;
      z-index: 1000;
    }

    .navbar-container {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 20px;
    }

    .navbar-brand {
      color: white;
      font-size: 1.2rem;
      font-weight: 600;
      display: flex;
      align-items: center;
    }

    .navbar-nav {
      display: flex;
      list-style: none;
    }

    .nav-item {
      margin-left: 5px;
    }

    .nav-link {
      color: rgba(255, 255, 255, 0.9);
      text-decoration: none;
      padding: 8px 15px;
      border-radius: var(--border-radius);
      transition: var(--transition);
      font-weight: 500;
    }

    .nav-link:hover {
      background-color: rgba(255, 255, 255, 0.1);
    }

    .nav-link.active {
      background-color: white;
      color: var(--primary-color);
    }

    /* Page header */
    .page-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin: 30px 0 20px;
    }

    .page-title {
      font-size: 1.8rem;
      color: var(--dark-color);
      font-weight: 600;
      position: relative;
    }

    .page-title::after {
      content: '';
      position: absolute;
      bottom: -8px;
      left: 0;
      width: 50px;
      height: 4px;
      background-color: var(--accent-color);
      border-radius: 2px;
    }

    /* Visitor cards grid */
    .visitors-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
      gap: 20px;
      margin-top: 30px;
    }

    .visitor-card {
      background-color: white;
      border-radius: var(--border-radius);
      box-shadow: var(--box-shadow);
      overflow: hidden;
      transition: var(--transition);
    }

    .visitor-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
    }

    .visitor-header {
      background-color: var(--secondary-color);
      color: white;
      padding: 15px;
      text-align: center;
    }

    .visitor-name {
      font-weight: 600;
      margin: 0;
      font-size: 1.2rem;
    }

    .visitor-body {
      padding: 15px;
    }

    .visitor-info {
      margin-bottom: 8px;
      display: flex;
    }

    .info-label {
      font-weight: 500;
      color: var(--text-light);
      width: 80px;
      flex-shrink: 0;
    }

    .info-value {
      font-weight: 500;
    }

    .empty-state {
      text-align: center;
      padding: 40px;
      background-color: white;
      border-radius: var(--border-radius);
      box-shadow: var(--box-shadow);
      margin: 30px 0;
    }

    .empty-icon {
      font-size: 3rem;
      color: var(--accent-color);
      margin-bottom: 20px;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .navbar-container {
        flex-direction: column;
        align-items: flex-start;
      }
      
      .navbar-nav {
        margin-top: 15px;
        flex-wrap: wrap;
      }
      
      .nav-item {
        margin: 5px;
      }
      
      .visitors-grid {
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
      }
    }

    @media (max-width: 480px) {
      .visitors-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>

<body>
  <!-- Navbar -->
  <nav class="navbar">
    <div class="container navbar-container">
      <div class="navbar-brand">
        <i class="fas fa-user-check"></i>
        <span><?php echo "Logged in as: ".$userM; ?></span>
      </div>
      <ul class="navbar-nav">
        <li class="nav-item"><a href="front.php" class="nav-link">Home</a></li>
        <li class="nav-item"><a href="myform.php" class="nav-link">Add Visitor</a></li>
        <li class="nav-item"><a href="logoutform.php" class="nav-link active">Checked Out Visitors</a></li>
        <li class="nav-item"><a href="query_data.php" class="nav-link">View Data</a></li>
        <li class="nav-item"><a href="logout.php" class="nav-link">Logout</a></li>
      </ul>
    </div>
  </nav>

  <!-- Main content -->
  <div class="container">
    <!-- Page header -->
    <div class="page-header">
      <h1 class="page-title">Checked Out Visitors</h1>
    </div>

    <!-- Subheader -->
    <div class="subheader">
      <p class="lead">These visitors were logged out today!</p>
    </div>

    <!-- Visitors grid -->
    <div class="visitors-grid">
      <?php
        include('db_connect_db_new.php');
        $date = date("Y/m/d");
        $query = "SELECT * FROM info_visitor WHERE Date = '$date' AND Status = 'OFFLINE'";
        $res = mysqli_query($link, $query);
        $hasVisitors = false;
        
        while($result = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
          $hasVisitors = true;
          echo '
          <div class="visitor-card">
            <div class="visitor-header">
              <h3 class="visitor-name">'.$result['Name'].'</h3>
            </div>
            <div class="visitor-body">
              <div class="visitor-info">
                <span class="info-label">Receipt ID:</span>
                <span class="info-value">'.$result['ReceiptID'].'</span>
              </div>
              <div class="visitor-info">
                <span class="info-label">Contact:</span>
                <span class="info-value">'.$result['Contact'].'</span>
              </div>
              <div class="visitor-info">
                <span class="info-label">Time In:</span>
                <span class="info-value">'.$result['TimeIN'].'</span>
              </div>
              <div class="visitor-info">
                <span class="info-label">Date:</span>
                <span class="info-value">'.$result['Date'].'</span>
              </div>
              <div class="visitor-info">
                <span class="info-label">Meeting:</span>
                <span class="info-value">'.$result['meetingTo'].'</span>
              </div>
            </div>
          </div>';
        }
        
        if (!$hasVisitors) {
          echo '
          <div class="empty-state" style="grid-column: 1 / -1;">
            <div class="empty-icon">
              <i class="fas fa-clipboard-check"></i>
            </div>
            <h3>No Checked Out Visitors Today</h3>
            <p>All current visitors are still checked in.</p>
          </div>';
        }
      ?>
    </div>
  </div>
</body>
</html>