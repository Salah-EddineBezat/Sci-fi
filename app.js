// Deze functie opent de modal en toont de vraag
function openModal(index) {
  // Zoek het element met de class 'box' en het bijbehorende data-index
  let box = document.querySelector(`.box[data-index='${index}']`);

  // Haal de vraag en het juiste antwoord uit de dataset van de box
  let questionText = box.dataset.question;
  let correctAnswer = box.dataset.answer;

  // Zet de vraagtekst in het modalvenster
  document.getElementById("question").innerText = questionText;

  // Zet het correcte antwoord in de modal, zodat we het later kunnen vergelijken
  document.getElementById("modal").dataset.answer = correctAnswer;

  // Maak het antwoordveld leeg
  document.getElementById("answer").value = "";

  // Toon de overlay en de modal door de display-stijl te veranderen naar 'block'
  document.getElementById("overlay").style.display = "block";
  document.getElementById("modal").style.display = "block";
}

// Deze functie sluit de modal en de overlay
function closeModal() {
  // Zet de overlay en modal weer op 'none' zodat ze niet meer zichtbaar zijn
  document.getElementById("overlay").style.display = "none";
  document.getElementById("modal").style.display = "none";

  // Maak de feedback tekst leeg
  document.getElementById("feedback").innerText = "";
}

// Deze functie controleert of het ingevoerde antwoord correct is
function checkAnswer() {
  // Haal het antwoord van de gebruiker op uit het invoerveld en verwijder onnodige spaties
  let userAnswer = document.getElementById("answer").value.trim();

  // Haal het juiste antwoord op uit de modal
  let correctAnswer = document.getElementById("modal").dataset.answer;

  // Haal het feedback element op om de gebruiker te informeren
  let feedback = document.getElementById("feedback");

  // Vergelijk het antwoord van de gebruiker met het juiste antwoord (hoofdlettergevoeligheid negeren)
  if (userAnswer.toLowerCase() === correctAnswer.toLowerCase()) {
    // Als het antwoord juist is, geef positieve feedback
    feedback.innerText = "Correct! Goed gedaan!";
    feedback.style.color = "green";

    // Sluit de modal na 1 seconde
    setTimeout(closeModal, 1000);
  } else {
    // Als het antwoord fout is, geef negatieve feedback
    feedback.innerText = "Fout, probeer opnieuw!";
    feedback.style.color = "red";
  }
}

let totalTime = 20 * 60; // 20 minuten in seconden
let timeRemaining = localStorage.getItem("timeRemaining") ? parseInt(localStorage.getItem("timeRemaining")) : totalTime;
let timerInterval;
let timerPaused = false;

const timerDisplay = document.getElementById("timerDisplay");
const pauseButton = document.getElementById("pauseButton");
const pauseScreen = document.getElementById("pauseScreen");

// Start de timer of hervat deze
function startTimer() {
  updateTimerDisplay();

  timerInterval = setInterval(function () {
    if (!timerPaused) {
      timeRemaining--;
      localStorage.setItem("timeRemaining", timeRemaining); // Opslaan in localStorage
      updateTimerDisplay();

      if (timeRemaining <= 0) {
        clearInterval(timerInterval);
        localStorage.removeItem("timeRemaining"); // Verwijder tijd als de countdown klaar is
      }
    }
  }, 1000);
}

// Update de timerweergave
function updateTimerDisplay() {
  const minutes = Math.floor(timeRemaining / 60);
  const seconds = timeRemaining % 60;
  timerDisplay.textContent = `${String(minutes).padStart(2, "0")}:${String(seconds).padStart(2, "0")}`;
}

// Pauzeer de timer
function pauseTimer() {
  timerPaused = true;
  pauseScreen.style.display = "block";
  pauseButton.disabled = true;
}

// Hervat de timer
function resumeTimer() {
  timerPaused = false;
  pauseScreen.style.display = "none";
  pauseButton.disabled = false;
}


// Voorkom timer-reset bij refresh
window.onload = function() {
  startTimer();
};
