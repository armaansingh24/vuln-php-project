<?php
require 'vendor/autoload.php';
require 'db.php';

// XSS vulnerability
$name = $_GET['name'] ?? 'Guest';

// SQL Injection vulnerability
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM users WHERE id = $id"; // 🚨 no sanitization
    $result = $conn->query($query);
    $user = $result->fetch_assoc();
}

// Insecure Deserialization (via Monolog)
if (isset($_POST['log'])) {
    $data = $_POST['log'];
    $logger = new Monolog\Logger('app');
    $handler = unserialize($data); // 🚨 unsafe
    $logger->pushHandler($handler);
    $logger->info("Testing insecure deserialization");
}
?>

<!DOCTYPE html>
<html>
<head><title>phpsploit</title></head>
<body>
    <h2>Hello <?= $name ?></h2> <!-- 🚨 XSS -->

    <h3>SQL Injection</h3>
    <form method="GET">
        Enter user ID: <input type="text" name="id" />
        <input type="submit" value="Fetch" />
    </form>
    <?php if (!empty($user)): ?>
        <p>User: <?= $user['username'] ?></p>
    <?php endif; ?>

    <hr/>

    <h3>Insecure Deserialization</h3>
    <form method="POST">
        <textarea name="log" rows="5" cols="50">{serialized_object_here}</textarea><br/>
        <button type="submit">Send Log</button>
    </form>
</body>
</html>
