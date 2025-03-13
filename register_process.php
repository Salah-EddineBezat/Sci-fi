<?php
session_start();
include ("dbconn.php");

if (isset($_POST["register"])) {

    $name = strip_tags($_POST["name"]);
    $email = strip_tags($_POST["email"]);
    $pwd = $_POST["password"];

    if (empty($name) || empty($email) || empty($pwd)) {
        $_SESSION['message'] = "Vul alle velden in.";
        header("Location: register.php");
        exit(1);
    }

    if (strlen($name) < 3) {
        $_SESSION['message'] = "De naam moet minstens 3 tekens bevatten.";
        header("Location: register.php");
        exit(1);
    }

    $pwd_hashed = password_hash($pwd, PASSWORD_DEFAULT);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['message'] = "Voer een geldig e-mailadres in.";
        header("Location: register.php");
        exit(1);
    }

    if (strlen($_POST["password"]) < 6) {
        $_SESSION['message'] = "Het wachtwoord moet minstens 6 tekens bevatten.";
        header("Location: register.php");
        exit(1);
    }


    try {
        $query = "INSERT INTO client (name, email, password) VALUES (:name, :email, :password)";
        $query_run = $db_connection->prepare($query);
        $client = [
            ':name' => $name,
            ':email' => $email,
            ':password' => $pwd_hashed
        ];

        $query_execute = $query_run->execute($client);

        if ($query_execute) {
            $_SESSION['message'] = "Gelukt, het account is aangemaakt!";
            header("Location: index.php");
            exit(0);
        } else {
            $_SESSION['message'] = "Helaas, het account is niet aangemaakt!";
            header("Location: index.php");
            exit(1);
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}
?>
