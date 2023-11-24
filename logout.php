<?php
session_start(); // starts session
session_abort();// destroys all currently running session
$url='index.php';
header('Location: '.$url); // redirects to home page
?>