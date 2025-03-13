<?php
session_start();
include("dbconn.php");

if (isset($_POST["login"])) {

    $email = $_POST["email"];
    $pwd = $_POST["password"];

    try {
        $query = "SELECT id, name, email, password FROM client WHERE email = :email";
        $query_run = $db_connection->prepare($query);
        $client_info = [
            ':email' => $email,
        ];

        $query_execute = $query_run->execute($client_info);
        $user = $query_run->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if (password_verify($pwd, $user['password'])) {
                $_SESSION['id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['message'] = "Welkom " . $_SESSION['name'] . " u bent successvol ingelogd!";
                header('location: index.php');
                exit(0);
            } else {
                echo "Ongeldig wachtwoord";
            }
        } else {
            $_SESSION["message"] = "Gebruiker is niet gevonden";
            header("location:login.php");
            exit(1);
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}
?>
