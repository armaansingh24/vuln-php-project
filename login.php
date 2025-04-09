<?php
require_once('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // Vulnerable to SQL Injection (no sanitization)
    $query = "SELECT * FROM users WHERE username = '$user' AND password = '$pass'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        echo "Welcome $user";
    } else {
        echo "Invalid credentials";
    }
}
?>

<form method="POST">
  <input name="username" placeholder="Username" />
  <input name="password" type="password" placeholder="Password" />
  <button type="submit">Login</button>
</form>
