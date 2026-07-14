<?php

$db = new mysqli('localhost', 'root', '', 'fefogudang');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

$password = 'foi12345';
$preparedPassword = base64_encode(hash('sha384', $password, true));
$hash = password_hash($preparedPassword, PASSWORD_DEFAULT);

$stmt = $db->prepare("UPDATE users SET password_hash = ?");
$stmt->bind_param("s", $hash);
$stmt->execute();

echo "Passwords updated correctly with Myth Auth algorithm.\n";
