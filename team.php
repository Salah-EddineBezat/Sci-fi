<?php
session_start();
include("dbconn.php");
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team aanmaken</title>
    <link rel="stylesheet" href="style.css">
    <!-- Preconnect voor Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Link naar de gebruikte Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Afacad:ital,wght@0,400..700;1,400..700&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Mulish:ital,wght@0,200..1000;1,200..1000&family=Noto+Sans+Display:ital,wght@0,100..900;1,100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Playwrite+GB+S:ital,wght@0,100..400;1,100..400&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Rethink+Sans:ital,wght@0,400..800;1,400..800&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
</head>

<body>
    <?php include "header.php" ?>
    <?php include "nav.php" ?>

    <video autoplay muted loop id="bgVideo">
        <source src="./videos/videoBG2.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>

    <section class="container">
        <div class="containerReg">
            <div class="cardTwo">
                <div class="contentRegister">
                    <h3>Team</h3>
                </div>
                <div class="formOne">
                    <form action="./process_team.php" method="POST">

                        <div class="formRegister">
                            <label for="name">Team naam</label>
                            <input type="text" id="name" name="name" class="form-control">
                        </div>
                        <input type="submit" name="team" value="Aanmaken">
                    </form>
                </div>
            </div>
    </section>

    <?php include "footer.php" ?>
</body>

</html>