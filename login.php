<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inloggen</title>
    <link rel="stylesheet" href="style.css">
    <style>


    </style>
</head>

<body>
    <?php include 'header.php' ?>
    <?php include 'nav.php' ?>


    <video autoplay muted loop id="bgVideo">
        <source src="./videos/bgVideo.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>
    <section class="container">
        <?php
        if (isset($_SESSION['message'])) {
        ?>
            <h5 class="alert alert-success"><?= $_SESSION['message'] ?></h5>

        <?php
            unset($_SESSION['message']);
        }
        ?>
        <div class="containerReg">
            <div class="cardTwo">
                <div class="contentRegister">
                    <h3>Inloggen</h3>
                </div>
                <div class="formOne">
                    <form action="login_process.php" method="POST">

                        <div class="formRegister">
                            <label for="email">E-mail</label>
                            <input type="email" id="email" name="email" class="form-control" required>
                        </div>

                        <div class="formRegister">
                            <label for="password">Wachtwoord</label>
                            <input type="password" id="password" name="password" class="form-control" required>
                        </div>

                        <input type="submit" name="login" value="Inloggen" class="btn btn-primary bg-gradient">
                    </form>
                </div>
            </div>
    </section>

    <?php include 'footer.php' ?>
</body>

</html>