<?php
// Database credentials
$con = mysqli_connect("localhost", "root", "", "circuithub_db", 3307);

// Check connection
if (mysqli_connect_errno()) {
    die("MySQL connection failed: " . mysqli_connect_error());
}
