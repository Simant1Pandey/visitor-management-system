<?php include("db_connect_Login.php"); session_start(); ?>

<?php
$error = $uname = $pass = $confirm_pass = "";
$count = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($_POST["username"]) || empty($_POST["pass"]) || empty($_POST["confirm_pass"])) {
        $error = "Please enter all fields!";
    } else {
        $uname = $_POST["username"];
        $pass = $_POST["pass"];
        $confirm_pass = $_POST["confirm_pass"];

        // Check if password matches the confirmation password
        if ($pass != $confirm_pass) {
            $error = "Passwords do not match!";
        } else {
            // Check if username already exists
            $sql = "SELECT userName FROM login_info WHERE userName = '$uname'";
            $result = mysqli_query($link, $sql);
            $count = mysqli_num_rows($result);

            if ($count > 0) {
                $error = "Username already exists!";
            } else {
                // Insert the new user into the database
                $sql_insert = "INSERT INTO login_info (userName, pass) VALUES ('$uname', '$pass')";
                if (mysqli_query($link, $sql_insert)) {
                    $_SESSION["user"] = $uname;
                    $_SESSION["loggedIn"] = 1;
                    header("Location: index.php");
                } else {
                    $error = "Error in registering. Please try again.";
                }
            }
        }
    }
}
?>

<!DOCTYPE HTML5>
<html>
<head>
    <link rel="stylesheet" href="BootStrap/css/bootstrap.min.css">
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

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-color);
            color: var(--text-color);
            line-height: 1.6;
        }

        #register {
            position: relative;
            max-width: 500px;
            margin: 50px auto;
            padding: 0;
            background: unset;
        }

        #head {
            margin-bottom: 20px;
            text-align: center;
            color: var(--primary-color);
        }

        #head h2 {
            font-weight: 600;
            margin-bottom: 10px;
        }

        #form {
            background-color: white;
            padding: 30px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            border: none;
            transition: var(--transition);
        }

        #form:hover {
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        #error {
            font-size: 14px;
            color: var(--danger-color);
            padding-bottom: 15px;
            text-align: center;
        }

        .group {
            position: relative;
            margin-bottom: 35px;
        }

        input {
            font-size: 16px;
            padding: 10px 10px 10px 5px;
            display: block;
            width: 100%;
            border: none;
            border-bottom: solid 2px var(--text-light);
            background-color: transparent;
            transition: var(--transition);
        }

        input:focus {
            outline: none;
            border-bottom: solid 2px var(--primary-color);
        }

        label {
            color: var(--text-light);
            font-size: 16px;
            font-weight: normal;
            position: absolute;
            pointer-events: none;
            left: 5px;
            top: 10px;
            transition: var(--transition);
        }

        input:focus ~ label, input:valid ~ label {
            top: -20px;
            font-size: 14px;
            color: var(--primary-color);
        }

        .bar {
            position: relative;
            display: block;
            width: 100%;
        }

        .bar:before, .bar:after {
            content: '';
            height: 2px;
            width: 0;
            bottom: 0;
            position: absolute;
            background: var(--primary-color);
            transition: var(--transition);
        }

        .bar:before {
            left: 50%;
        }

        .bar:after {
            right: 50%;
        }

        input:focus ~ .bar:before, input:focus ~ .bar:after {
            width: 50%;
        }

        .btn-register {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: var(--border-radius);
            font-size: 16px;
            cursor: pointer;
            transition: var(--transition);
            width: 100%;
            margin-top: 10px;
        }

        .btn-register:hover {
            background-color: var(--dark-color);
            transform: translateY(-2px);
        }
        
        .login-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: var(--text-light);
        }
        
        .login-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }
        
        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div id="register">
    <div id="head">
        <h2>Visitor Management System</h2>
        <p>Create a new account</p>
    </div>
    <div id="form">
        <p id="error"><?php echo $error; ?></p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">

            <div class="group">
                <input autocomplete="off" type="text" name="username" required>
                <span class="highlight"></span>
                <span class="bar"></span>
                <label>Username</label>
            </div>

            <div class="group">
                <input autocomplete="off" type="password" name="pass" required>
                <span class="highlight"></span>
                <span class="bar"></span>
                <label>Password</label>
            </div>

            <div class="group">
                <input autocomplete="off" type="password" name="confirm_pass" required>
                <span class="highlight"></span>
                <span class="bar"></span>
                <label>Confirm Password</label>
            </div>

            <button class="btn-register" type="submit">Register</button>
        </form>
        <div class="login-link">
            Already have an account? <a href="index.php">Login here</a>
        </div>
    </div>
</div>

</body>
</html>