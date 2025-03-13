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
  <title>Escape Room</title>
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
    <source src="./videos/bgVideo.mp4" type="video/mp4">
    Your browser does not support the video tag.
  </video>


  <h1 class="firstTitle">Welkom</h1>

  <main>
    <?php
    if (isset($_SESSION['message'])) {
    ?>
      <h5 class="message"><?= $_SESSION['message'] ?></h5>

    <?php
      unset($_SESSION['message']);
    }
    ?>
    <!-- Uitleg escaperoom -->
    <div class="contentHome">
      <h2 class="titleCenter">Sci-fi</h2>

      <p>Jullie bevinden je aan boord van het onderzoeksschip Nova-9, dat door de Melkweg reist.
        Tijdens een routinevlucht raakt het schip beschadigd door een mysterieuze tijdskloof. De
        bemanning is verdwenen en het schip wordt langzaam naar een zwart gat getrokken. Jullie
        hebben precies 20 minuten om het schip te repareren en te ontsnappen, voordat het in het
        zwarte gat verdwijnt.</p>

      <h3 class="titleCenter">Doel:</h3>
      <p>Herstart het schip door drie belangrijke taken te voltooien:</p>
      <p>1. Herstart de kernreactor in de controlekamer om het schip van stroom te voorzien.
        <br>
        2. Bepaal de juiste koers in de navigatiehub om het schip op een veilige route te
        brengen.
        <br>
        3. Activeer de ontsnappingsmodule in het laboratorium om het schip te verlaten.
      </p>


      <button class="buttonOne"><a href="./room_1.php" class="roomOne">Start nu!</a></button>
    </div>
  </main>

  <?php include "footer.php" ?>
  <script src="script.js"></script>
</body>

</html>