<?php
$login = isset($_SESSION["id"]);
?>
<nav>
    <ul>
        <li class="logoTitle">Escaperoom</li>
        <?php if ($login) : ?>

            <span>
                <li><a class="linkOne" href="./index.php">Home</a></li>
            </span>
            <span>
                <li><a class="linkOne" href="./overzicht.php">Overzicht</a></li>
            </span>
            <span>
                <li><a class="linkOne" href="./resultaten.php">Resultaat</a></li>
            </span>

            <span>
                <li><a class="linkOne" href="./team.php">Team</a></li>
            </span>

            <span>
                <li><a class="linkOne" href="logout.php">Uitloggen</a></li>
            </span>
        <?php else : ?>
            <span>
                <li><a class="linkOne" href="register.php">Registreren</a></li>
            </span>
            <span>
                <li><a class="linkOne" href="login.php">Inloggen</a></li>
            </span>
        <?php endif; ?>
    </ul>
</nav>