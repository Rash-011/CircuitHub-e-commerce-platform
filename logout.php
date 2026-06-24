<?php
session_start();
// Unset all active user profile session indicators
session_unset();
// Destroy session engine instance
session_destroy();

// Redirect clean back to store portal login interface
header("Location: login.php");
exit();
?>