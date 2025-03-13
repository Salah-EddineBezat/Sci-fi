<?php
session_start();
include("dbconn.php");
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}
?>