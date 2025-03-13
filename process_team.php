<?php
session_start();
include("dbconn.php");

// Controleer of het formulier is ingediend
if (isset($_POST["team"])) {
    
    // Verkrijg de ingevoerde naam en member (lid)
    $name = strip_tags($_POST["name"]);
    $member = $_SESSION['id']; // Gebruikers-ID uit de sessie halen

    // Valideer of alle velden zijn ingevuld
    if (empty($name)) {
        $_SESSION['message'] = "Vul alle velden in.";
        header("Location: team.php");
        exit();
    }

    // Valideer of de teamnaam minimaal 3 tekens bevat
    if (strlen($name) < 3) {
        $_SESSION['message'] = "De naam moet minstens 3 tekens bevatten.";
        header("Location: team.php");
        exit();
    }

    try {
        // Begin een transactie
        $db_connection->beginTransaction();

        // Stap 1: Voeg het team toe aan de teams-tabel
        $query = "INSERT INTO teams (name) VALUES (:name)";
        $query_run = $db_connection->prepare($query);
        $query_run->bindValue(":name", $name, PDO::PARAM_STR);
        $query_run->execute();

        // Verkrijg het ID van het nieuw aangemaakte team
        $team_id = $db_connection->lastInsertId();

        // Stap 2: Voeg het lid toe aan de team_members-tabel
        $query_member = "INSERT INTO team_members (team_id, member_id) VALUES (:team_id, :member_id)";
        $query_member_run = $db_connection->prepare($query_member);
        $query_member_run->bindValue(":team_id", $team_id, PDO::PARAM_INT);
        $query_member_run->bindValue(":member_id", $member, PDO::PARAM_INT);
        $query_member_run->execute();

        // Commit de transactie
        $db_connection->commit();

        $_SESSION['message'] = "Team succesvol aangemaakt en lid toegevoegd!";
        header("Location: overzicht.php");
        exit();
    } catch (Exception $e) {
        // Rol de transactie terug bij een fout
        $db_connection->rollBack();
        $_SESSION['message'] = "Er is een fout opgetreden: " . $e->getMessage();
        header("Location: team.php");
        exit();
    }
}
?>