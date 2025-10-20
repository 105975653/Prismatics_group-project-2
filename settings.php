<?php
// settings.php — use this file anywhere you touch the database

$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';            // brief says no password locally
$DB_NAME = 'jobs_db';  // 

// Turn on mysqli exceptions (easier errors)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

function get_conn(): mysqli {
    global $DB_HOST, $DB_USER, $DB_PASS, $DB_NAME;
    $conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
    $conn->set_charset('utf8mb4');
    return $conn;
}
