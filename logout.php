<?php
session_start();
$_SESSION['loggedIn'] = false;
$_SESSION['user'] =['', 'Guest',''];
header("Location:index.php");
?>