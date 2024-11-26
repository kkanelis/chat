<?php
$conn = new mysqli('localhost', 'root', '', 'chats');

// Pārbaudīt savienojumu
if ($conn->connect_error) {
    die("Savienojums neizdevās: " . $conn->connect_error);
}

?>