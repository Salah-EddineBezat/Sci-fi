<?php
session_start();
include("dbconn.php");

if (!isset($_SESSION['id'])) {
  header("Location: login.php");
  exit();
}

$user_id = $_SESSION['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['leave_team'])) {
  $query = "DELETE FROM team_members WHERE member_id = :user_id";
  $query_run = $db_connection->prepare($query);
  $query_run->bindValue(':user_id', $user_id, PDO::PARAM_INT);

  if ($query_run->execute()) {
    $_SESSION['message'] = "Je hebt het team succesvol verlaten.";
  } else {
    $_SESSION['message'] = "Er is iets misgegaan. Probeer opnieuw.";
  }
}

header("Location: overzicht.php");
exit();
?>
