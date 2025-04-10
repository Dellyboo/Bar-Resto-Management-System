<?php

// Start the session
session_start();
include '../database.php'; // Ensure this file connects to g_bar DB using PDO

// Initialize error variable
$error = "";

// CSRF Token protection with expiry (10 minutes)
$csrf_expiry_time = 10 * 60; // 10 minutes in seconds

// Check for expired or missing CSRF token
if (!isset($_SESSION['csrf_token']) || !isset($_SESSION['csrf_token_time']) || (time() - $_SESSION['csrf_token_time']) > $csrf_expiry_time) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));  // Generate a new token
    $_SESSION['csrf_token_time'] = time();  // Store token creation time
}

// Lockout mechanism variables
$locked_out = false;
$remaining_time = 0;

// Check if the form was submitted (POST request)
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // CSRF token validation
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token'] || (time() - $_SESSION['csrf_token_time']) > $csrf_expiry_time) {
        $error = "Your session has expired. Please refresh the page and try again.";

    } else {
        $username = trim($_POST["username"]);
        $password = trim($_POST["password"]);

        // Basic validation for empty fields
        if (empty($username) || empty($password)) {
            $error = "Username and password are required.";
        } else {
            // Implement brute force protection (rate limiting)
            $max_attempts = 3;  // Max failed attempts
            $lockout_time = 5 * 60;  // 5 minutes lockout (in seconds)

            // Check if the user is locked out due to too many failed login attempts
            if (isset($_SESSION['failed_attempts']) && $_SESSION['failed_attempts'] >= $max_attempts) {
                if (isset($_SESSION['last_failed_attempt']) && time() - $_SESSION['last_failed_attempt'] < $lockout_time) {
                    $remaining_time = $lockout_time - (time() - $_SESSION['last_failed_attempt']);
                    $locked_out = true;
                    $error = "Too many failed login attempts. Please try again in " . gmdate("i:s", $remaining_time) . " minutes.";
                } else {
                    // Reset the failed attempts counter after lockout period
                    unset($_SESSION['failed_attempts']);
                    unset($_SESSION['last_failed_attempt']);
                }
            } else {
                try {
                    // Prepare and execute the SQL query to fetch the password for the given username
                    $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
                    $stmt->execute([$username]);

                    // Check if user exists in the database
                    if ($stmt->rowCount() > 0) {
                        $user = $stmt->fetch(PDO::FETCH_ASSOC);
                        $db_password = $user['password'];

                        // Verify the password
                        if (password_verify($password, $db_password)) {
                            // Successful login: reset failed attempts and store session data
                            $_SESSION["user"] = $username;
                            $_SESSION['failed_attempts'] = 0;

                            // Redirect to the homepage
                            header("Location: ../index.php");
                            exit;
                        } else {
                            // Invalid password: increment failed attempts and log the failure
                            $_SESSION['failed_attempts'] = isset($_SESSION['failed_attempts']) ? $_SESSION['failed_attempts'] + 1 : 1;
                            $_SESSION['last_failed_attempt'] = time();
                            $error = "Incorrect username or password. Please try again.";

                        }
                    } else {
                        // No user found with the given username
                        $error = "Incorrect username or password.";
                    }
                } catch (PDOException $e) {
                    // Log database error and show a generic error message
                    $error = "Database error. Please try again later.";
                    error_log("Login error: " . $e->getMessage()); // Log detailed error message
                }
            }
        }
    }
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ku Kayaga</title>
    <link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="vendors/nprogress/nprogress.css" rel="stylesheet">
    <link href="vendors/animate.css/animate.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <link href="build/css/custom.min.css" rel="stylesheet">

    <style>
        .password-container {
            position: relative;
            width: 100%;
        }

        .password-container input {
            width: 100%;
            padding-right: 40px;
        }

        .password-container .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            font-size: 15px;
            transform: translateY(-50%);
            cursor: pointer;
            color: rgb(38, 83, 101);
            display: none;
        }

        .password-container .toggle-password:hover {
            color: rgb(20, 49, 72);
        }

        .locked-out input {
            pointer-events: none;
            opacity: 0.5;
        }
    </style>
</head>

<body class="login">
    <div class="login_wrapper">
        <div class="animate form login_form">
            <section class="login_content <?php echo $locked_out ? 'locked-out' : ''; ?>">
                <form action="login.php" method="POST">
                    <h1>Se connecter</h1>

                    <?php if (!empty($error)) : ?>
                        <div id="error-message" class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                    <div>
                        <input type="text" name="username" class="form-control" placeholder="Username" required value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>">
                    </div>

                    <div class="password-container">
                        <input type="password" id="password" name="password" class="form-control" placeholder="Password" required oninput="toggleEyeIcon()">
                        <span class="toggle-password" id="toggle-password" onclick="togglePassword()">
                            <i class="fa fa-eye" id="eye-icon"></i>
                        </span>
                    </div>

                    <div>
                        <button type="submit" class="btn btn-default submit">Log in</button>
                    </div>

                    <div class="clearfix"></div>
                    <div class="separator">
                        <br />
                        <div>
                            <p>©2025 Tous droits réservés. Ku Kayaga Resto-Bar</p>
                        </div>
                    </div>
                </form>
            </section>
        </div>
    </div>

    <script>
        setTimeout(function() {
            var errorDiv = document.getElementById("error-message");
            if (errorDiv) {
                errorDiv.style.display = "none";
            }
        }, 3000);

        function togglePassword() {
            var passwordField = document.getElementById("password");
            var eyeIcon = document.getElementById("eye-icon");
            if (passwordField.type === "password") {
                passwordField.type = "text";
                eyeIcon.classList.remove("fa-eye");
                eyeIcon.classList.add("fa-eye-slash");
            } else {
                passwordField.type = "password";
                eyeIcon.classList.remove("fa-eye-slash");
                eyeIcon.classList.add("fa-eye");
            }
        }

        function toggleEyeIcon() {
            var passwordField = document.getElementById("password");
            var toggleIcon = document.getElementById("toggle-password");
            toggleIcon.style.display = passwordField.value.length > 0 ? "block" : "none";
        }

        <?php if ($locked_out): ?>
            let remainingTime = <?php echo $remaining_time; ?>;
            const remainingTimeElement = document.getElementById('remaining-time');
            const interval = setInterval(function() {
                remainingTime--;
                remainingTimeElement.textContent = remainingTime + " seconds";
                if (remainingTime <= 0) {
                    clearInterval(interval);
                    location.reload();
                }
            }, 1000);
        <?php endif; ?>
    </script>
</body>
</html>
