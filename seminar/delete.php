<?php
header("Content-Type: text/html; charset=utf-8");

$servername = "localhost";
$username = "root";
$pass = "";
$dbname = "daniel_gluhak_nphp";

$conn = mysqli_connect($servername, $username, $pass, $dbname) or die("Error connecting to MYSQL" . mysqli_connect_error());

$id = $_POST['id'];
$table = $_POST['table'];

$sql = "DELETE FROM $table WHERE id=$id";

if ($conn->query($sql) === TRUE) {
    echo "1";
} else {
    echo "Error deleting record: " . $conn->error;
}

$conn->close();
