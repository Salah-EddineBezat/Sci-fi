<?php
session_start();
include("dbconn.php");

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['join'])) {
    $team_id = $_POST['team_id'];
    $member_id = $_SESSION['id']; // Gebruiker die ingelogd is

    // Controleer of het lid al in dit team zit
    $query = "SELECT * FROM team_members WHERE team_id = :team_id AND member_id = :member_id";
    $query_run = $db_connection->prepare($query);
    $query_run->execute([':team_id' => $team_id, ':member_id' => $member_id]);

    if ($query_run->rowCount() > 0) {
        $_SESSION['message'] = "Je bent al lid van dit team!";
    } else {
        // Voeg lid toe aan team
        $query = "INSERT INTO team_members (team_id, member_id) VALUES (:team_id, :member_id)";
        $query_run = $db_connection->prepare($query);
        $query_execute = $query_run->execute([
            ':team_id' => $team_id,
            ':member_id' => $member_id
        ]);

        if ($query_execute) {
            $_SESSION['message'] = "Je hebt je succesvol bij het team gevoegd!";
        } else {
            $_SESSION['message'] = "Er is iets misgegaan. Probeer het later opnieuw.";
        }
    }

    header("Location: overzicht.php");
    exit();
}
?>
