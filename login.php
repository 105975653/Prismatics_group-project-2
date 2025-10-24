<?php
session_start();
require_once("settings.php");

// Connect to database
$conn = @mysqli_connect($DB_HOST, $DB_USER, $DB_PASS);
if (!$conn) {
    die("<p>❌ Could not connect to MySQL server.</p>");
}

// Ensure database exists
@mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS $DB_NAME");
mysqli_select_db($conn, $DB_NAME);

// Create 'users' table if it doesn't exist
$create_users_sql = "
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL
)";
mysqli_query($conn, $create_users_sql);

// Ensure Admin account exists (default credentials: Admin / Admin)
$check_admin = mysqli_query($conn, "SELECT * FROM users WHERE username='Admin'");
if (mysqli_num_rows($check_admin) == 0) {
    $admin_password = hash('sha256', 'Admin');
    mysqli_query($conn, "INSERT INTO users (username, password_hash) VALUES ('Admin', '$admin_password')");
}

$error = "";

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    $stmt = $conn->prepare("SELECT password_hash FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($hash);

    if ($stmt->fetch() && hash('sha256', $password) === $hash) {
        $_SESSION["logged_in"] = true;
        $_SESSION["username"] = $username;
        header("Location: manage.php");
        exit();
    } else {
        $error = "❌ Invalid username or password.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>HR Login | Prismatics</title>
  <link rel="icon" type="image/png" href="images/icon.png">
  <link rel="stylesheet" href="styles/styles.css">
  <link rel="stylesheet" href="styles/login.css">
</head>

<body id="login-page">

  <!-- Centered wrapper -->
  <div class="center-wrapper">
    <div class="login-container">
      <img src="images/logo3.png" alt="Prismatics Logo" class="login-logo">
      <h2 class="login-title">HR Manager Login</h2>

      <!-- Login Form -->
      <form method="post" action="" id="login-form">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>

        <button type="submit" class="login-btn">Login</button>

        <?php if ($error): ?>
          <p class="error-msg"><?php echo $error; ?></p>
        <?php endif; ?>

        <button type="button" class="back-btn" onclick="window.location.href='index.php'">
          ← Back to Home
        </button>
      </form>
    </div>
  </div>

  <!-- Footer -->
  <footer id="login-footer">
    &copy; 2025 Prismatics | Secure HR Portal
  </footer>

</body>
</html>
