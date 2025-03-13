<?php
session_start();
include("dbconn.php");

if (!isset($_SESSION['id'])) {
  header("Location: login.php");
  exit();
}

$user_id = $_SESSION['id'];

// Haal alle teams op uit de database
$query = "SELECT * FROM teams";
$query_run = $db_connection->prepare($query);
$query_run->execute();
$teams = $query_run->fetchAll(PDO::FETCH_ASSOC);

// Controleer of de gebruiker al lid is van een team
$query = "SELECT team_id FROM team_members WHERE member_id = :user_id";
$query_run = $db_connection->prepare($query);
$query_run->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$query_run->execute();
$user_team = $query_run->fetchColumn(); // Haalt het team-ID op als de gebruiker in een team zit

?>




<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Overzicht</title>
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


  <h1 class="firstTitle">Overzicht</h1>

  <main>
    <?php
    if (isset($_SESSION['message'])) {
    ?>
      <h5 class="message"><?= $_SESSION['message'] ?></h5>

    <?php
      unset($_SESSION['message']);
    }
    ?>
    <div class="contentOverzicht">
      <h2 class="titleCenter">Teams:</h2>
      <ul class="team-list">
        <?php foreach ($teams as $team): ?>
          <li class="team-item">
            <h3><?php echo htmlspecialchars($team['name']); ?></h3>

            <?php if ($user_team == $team['id']): ?>
              <p class="status">Je bent lid van dit team</p>
              <form action="leave_team_process.php" method="POST">
                <button type="submit" name="leave_team" class="buttonLeave">Verlaat Team</button>
              </form>
            <?php elseif ($user_team): ?>
              <p class="status">Je moet eerst je huidige team verlaten</p>
            <?php else: ?>
              <form action="join_team_process.php" method="POST">
                <input type="hidden" name="team_id" value="<?php echo $team['id']; ?>">
                <button type="submit" name="join" class="buttonJoin">Join Team</button>
              </form>
            <?php endif; ?>
          </li>
        <?php endforeach; ?>
      </ul>

    </div>
  </main>


  <?php include "footer.php" ?>
  <script src="script.js"></script>
</body>

</html>