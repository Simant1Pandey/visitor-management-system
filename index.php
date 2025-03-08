<?php include("db_connect_Login.php"); session_start(); ?>

<?php
$error = $uname = $pass = "";
$count = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($_POST["username"]) || empty($_POST["pass"])) {
        $error = "Please enter all fields!";
    } else {
        $uname = $_POST["username"];
        $pass = $_POST["pass"];

        // Check if the username and password match
        $sql = "SELECT userName, pass FROM login_info WHERE userName = '$uname' AND pass = '$pass'";
        $match = mysqli_query($link, $sql);
        $count = mysqli_num_rows($match);

        if ($count == 1) {
            // Successful login
            $_SESSION["user"] = $uname;
            $_SESSION["loggedIn"] = 1;
            header("location: front.php");  // Redirect to the main page or dashboard
        } else {
            $error = "Invalid Username or Password";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visitor Management System - Login</title>
    <link rel="stylesheet" href="BootStrap/css/bootstrap.min.css">
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
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            color: var(--text-color);
            line-height: 1.6;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--light-color) 0%, #c5cae9 100%);
        }
        
        .login-container {
            width: 100%;
            max-width: 450px;
            padding: 20px;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .login-header h2 {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .login-form {
            background-color: white;
            padding: 40px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
        }
        
        .error-message {
            color: var(--danger-color);
            font-size: 14px;
            margin-bottom: 20px;
            padding: 10px;
            background-color: rgba(244, 67, 54, 0.1);
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
        }
        
        .error-message i {
            margin-right: 8px;
        }
        
        .form-group {
            position: relative;
            margin-bottom: 35px;
        }
        
        .form-control {
            font-size: 16px;
            padding: 10px 15px;
            height: auto;
            border: none;
            border-bottom: 2px solid #ddd;
            border-radius: 0;
            box-shadow: none;
            transition: var(--transition);
            background-color: transparent;
        }
        
        .form-control:focus {
            box-shadow: none;
            border-color: var(--primary-color);
        }
        
        .floating-label {
            position: absolute;
            pointer-events: none;
            top: 10px;
            left: 15px;
            transition: var(--transition);
            color: var(--text-light);
        }
        
        .form-control:focus ~ .floating-label,
        .form-control:not(:placeholder-shown) ~ .floating-label {
            top: -20px;
            left: 0;
            font-size: 12px;
            color: var(--primary-color);
            opacity: 1;
        }
        
        .highlight-bar {
            position: relative;
            display: block;
            width: 100%;
        }
        
        .highlight-bar:before, 
        .highlight-bar:after {
            content: '';
            height: 2px;
            width: 0;
            bottom: 0;
            position: absolute;
            background: var(--primary-color);
            transition: var(--transition);
        }
        
        .highlight-bar:before {
            left: 50%;
        }
        
        .highlight-bar:after {
            right: 50%;
        }
        
        .form-control:focus ~ .highlight-bar:before, 
        .form-control:focus ~ .highlight-bar:after {
            width: 50%;
        }
        
        .login-btn {
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: var(--border-radius);
            padding: 12px 25px;
            font-size: 16px;
            font-weight: 500;
            transition: var(--transition);
            width: 100%;
            margin-top: 10px;
        }
        
        .login-btn:hover {
            background-color: var(--dark-color);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
        }
        
        .register-link {
            margin-top: 25px;
            text-align: center;
            color: var(--text-light);
        }
        
        .register-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }
        
        .register-link a:hover {
            color: var(--dark-color);
            text-decoration: underline;
        }
        
        .input-with-icon {
            position: relative;
        }
        
        .input-icon {
            position: absolute;
            right: 15px;
            top: 12px;
            color: var(--text-light);
            cursor: pointer;
        }
        
        .brand-logo {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .logo-circle {
            width: 80px;
            height: 80px;
            background-color: var(--primary-color);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 36px;
            margin-bottom: 10px;
            box-shadow: var(--box-shadow);
        }
        
        @media (max-width: 576px) {
            .login-container {
                padding: 15px;
            }
            
            .login-form {
                padding: 25px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="brand-logo">
            <div class="logo-circle">
                <i class="glyphicon glyphicon-user"></i>
            </div>
        </div>
        
        <div class="login-header">
            <h2>Visitor Management System</h2>
            <p>Please enter your credentials to login</p>
        </div>
        
        <div class="login-form">
            <?php if($error): ?>
            <div class="error-message">
                <i class="glyphicon glyphicon-exclamation-sign"></i> <?php echo $error; ?>
            </div>
            <?php endif; ?>
            
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <div class="form-group">
                    <input type="text" class="form-control" id="username" name="username" placeholder=" " required autocomplete="off">
                    <span class="highlight-bar"></span>
                    <label for="username" class="floating-label">Username</label>
                </div>
                
                <div class="form-group input-with-icon">
                    <input type="password" class="form-control" id="password" name="pass" placeholder=" " required autocomplete="off">
                    <span class="highlight-bar"></span>
                    <label for="password" class="floating-label">Password</label>
                    <span class="input-icon" id="togglePassword">
                        <i class="glyphicon glyphicon-eye-close"></i>
                    </span>
                </div>
                
                <button type="submit" class="btn login-btn">
                    <i class="glyphicon glyphicon-log-in"></i> Login
                </button>
            </form>
            
            <div class="register-link">
                <p>Don't have an account? <a href="register.php">Register here</a></p>
            </div>
        </div>
    </div>
    
    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('glyphicon-eye-close');
                icon.classList.add('glyphicon-eye-open');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('glyphicon-eye-open');
                icon.classList.add('glyphicon-eye-close');
            }
        });
    </script>
</body>
</html>