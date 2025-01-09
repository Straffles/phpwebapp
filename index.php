<?php
// Database connection details
$host = '192.168.40.65'; // Replace with your PostgreSQL server IP
$port = '5432';
$dbname = 'webapp';
$user = 'webapp';
$password = '123456a@';

// Connect to PostgreSQL
$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
    echo json_encode(["error" => "An error occurred while connecting to the database."]);
    exit;
}

// Query the database
$result = pg_query($conn, "SELECT name, branch, semester FROM student");

if (!$result) {
    echo json_encode(["error" => "An error occurred while querying the database."]);
    exit;
}

// Fetch all rows as an associative array
$students = pg_fetch_all($result);

// Return the data as JSON
echo json_encode($students);

// Close the connection
pg_close($conn);
?>
