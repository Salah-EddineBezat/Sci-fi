<?php
session_start();
include("dbconn.php");

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
  header("Location: login.php");
  exit();
}

$user_id = $_SESSION['id'];

// Check if the user is a member of a team
$query = "SELECT team_id FROM team_members WHERE member_id = :user_id";
$stmt = $db_connection->prepare($query);
$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user_team = $stmt->fetchColumn(); // Will return the team ID if the user is in a team

// If the user belongs to a team, fetch the team name; otherwise, default to "Geen team"
if ($user_team) {
    $query = "SELECT team_name FROM teams WHERE id = :team_id";
    $stmt = $db_connection->prepare($query);
    $stmt->bindValue(':team_id', $user_team, PDO::PARAM_INT);
    $stmt->execute();
    $teamName = $stmt->fetchColumn();
    $_SESSION['teamnaam'] = $teamName;
} else {
    $_SESSION['teamnaam'] = 'Geen team';
}

// Fetch 4 random questions along with their hints using a LEFT JOIN
try {
    $query = "SELECT q.*, h.hint 
              FROM question q 
              LEFT JOIN hint h ON q.id = h.qid 
              ORDER BY RAND() LIMIT 4";
    $stmt = $db_connection->query($query);
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Databasefout: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Escape Room 1</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <video autoplay muted loop id="bgVideo">
    <source src="./videos/controlRoom.mp4" type="video/mp4">
    Your browser does not support the video tag.
  </video>

  <!-- Timer Display -->
  <div id="timerDisplay">20:00</div>

  <!-- Pause Button -->
  <button id="pauseButton" onclick="pauseTimer()">Pauze</button>

  <!-- Pause Screen -->
  <div id="pauseScreen" class="overlay" style="display: none;">
    <div class="pauseModal">
      <video autoplay muted loop id="bgVideoo">
        <source src="./videos/videoBGP (2).mp4" type="video/mp4">
        Your browser does not support the video tag.
      </video>
      <h2>Timer Gepauzeerd</h2>
      <button onclick="resumeTimer()" class="pauseButton">Hervat</button>
    </div>
  </div>

  <div class="container">
    <div class="containerTwo">
      <h2 class="firstTitle"><?php echo $_SESSION['teamnaam']; ?></h2>
    </div>

    <?php foreach ($questions as $index => $question) : 
      // Ensure 'hint' exists; if not, default to an empty string
      $hint = isset($question['hint']) ? htmlspecialchars($question['hint']) : '';
    ?>
      <div class="box" onclick="openModal(<?php echo $index; ?>)" data-index="<?php echo $index; ?>"
           data-question="<?php echo htmlspecialchars($question['question']); ?>"
           data-answer="<?php echo htmlspecialchars($question['anwser']); ?>"
           data-hint="<?php echo $hint; ?>">
        Box <?php echo $index + 1; ?>
      </div>
    <?php endforeach; ?>
  </div>

  <a href="index.php" class="buttonTwo">Terug</a>

  <section class="overlay" id="overlay" onclick="closeModal()"></section>
  <section class="modal" id="modal">
    <h2>Escape Room Vraag</h2>
    <p id="question"></p>
    <input type="text" id="answer" placeholder="Typ je antwoord">
    <button class="buttonOne" onclick="checkAnswer()">Verzenden</button>
    <p id="feedback"></p>
    <button id="hintButton" onclick="showHint()" style="display: none;">Laat Hint Zien</button>
    <p id="hint" style="display: none;"></p>
  </section>

  <script src="app.js"></script>
  <script>
    // Global variable to track mistakes and current question index
    let mistakes = 0;
    let maxMistakes = 1;

    // Save the index of the currently open question
    function openModal(index) {
      const modal = document.getElementById('modal');
      const questionText = document.getElementById('question');
      const hintButton = document.getElementById('hintButton');
      const hintText = document.getElementById('hint');
      const question = document.querySelector(`.box[data-index="${index}"]`);
      const questionData = question.dataset;

      questionText.textContent = questionData.question;
      modal.dataset.answer = questionData.answer; // Store the answer in modal's data
      modal.dataset.hint = questionData.hint;       // Store the hint in modal's data

      document.getElementById('answer').value = "";
      hintText.style.display = 'none';
      document.getElementById('feedback').textContent = '';
      mistakes = 0;

      // Store current question index in sessionStorage for later reference
      sessionStorage.setItem('currentQuestionIndex', index);

      document.getElementById('overlay').style.display = 'block';
      modal.style.display = 'block';
    }

    // Checks the answer for the current question
    function checkAnswer() {
      const userAnswer = document.getElementById('answer').value.trim();
      const modal = document.getElementById('modal');
      const correctAnswer = modal.dataset.answer;
      const feedback = document.getElementById('feedback');
      const hintButton = document.getElementById('hintButton');

      if (userAnswer.toLowerCase() === correctAnswer.toLowerCase()) {
        feedback.textContent = 'Correct!';
        feedback.style.color = 'green';
        hintButton.style.display = 'none';
        setTimeout(closeModal, 1000);
      } else {
        mistakes++;
        if (mistakes <= maxMistakes) {
          feedback.textContent = 'Fout! Probeer het opnieuw.';
          feedback.style.color = 'red';
        } else {
          feedback.textContent = 'Fout! Hier is een hint.';
          feedback.style.color = 'red';
          hintButton.style.display = 'block';
        }
      }
    }

    // Displays the hint for the current question
    function showHint() {
      const modal = document.getElementById('modal');
      const hintText = document.getElementById('hint');
      const hint = modal.dataset.hint;

      if (hint) {
        hintText.textContent = hint;
        hintText.style.display = 'block';
      } else {
        hintText.textContent = 'Er is geen hint beschikbaar.';
        hintText.style.display = 'block';
      }
    }

    function closeModal() {
      document.getElementById('overlay').style.display = 'none';
      document.getElementById('modal').style.display = 'none';
    }

    // Timer functionality
    let totalTime = 20 * 60; // 20 minutes in seconds
    let timeRemaining = localStorage.getItem("timeRemaining") ? parseInt(localStorage.getItem("timeRemaining")) : totalTime;
    let timerInterval;
    let timerPaused = false;
    const timerDisplay = document.getElementById("timerDisplay");
    const pauseButton = document.getElementById("pauseButton");
    const pauseScreen = document.getElementById("pauseScreen");

    function startTimer() {
      updateTimerDisplay();
      timerInterval = setInterval(function () {
        if (!timerPaused) {
          timeRemaining--;
          localStorage.setItem("timeRemaining", timeRemaining);
          updateTimerDisplay();
          if (timeRemaining <= 0) {
            clearInterval(timerInterval);
            localStorage.removeItem("timeRemaining");
          }
        }
      }, 1000);
    }

    function updateTimerDisplay() {
      const minutes = Math.floor(timeRemaining / 60);
      const seconds = timeRemaining % 60;
      timerDisplay.textContent = `${String(minutes).padStart(2, "0")}:${String(seconds).padStart(2, "0")}`;
    }

    function pauseTimer() {
      timerPaused = true;
      pauseScreen.style.display = "block";
      pauseButton.disabled = true;
    }

    function resumeTimer() {
      timerPaused = false;
      pauseScreen.style.display = "none";
      pauseButton.disabled = false;
    }

    window.onload = function() {
      startTimer();
    };
  </script>
</body>
</html>
