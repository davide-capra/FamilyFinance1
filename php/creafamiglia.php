<?php
require_once('connessionedb.php');
session_start();
if(!isset($_SESSION['email'])) {
    header("Location: login.php");
}
$email = $_SESSION['email'];
$q = $pdo->prepare("SELECT * FROM utenti WHERE email = :email");
$q->bindParam(':email', $_SESSION['email'], PDO::PARAM_STR);
$q->execute();

$utente = $q->fetch(PDO::FETCH_ASSOC);

if (!$utente) {
    header("Location: login.php");
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Crea Famiglia</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="../js/creafamiglia.js"></script>
    <!-- Montserrat Font -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>
<body>
    <div class="grid-container">
        <header class="header">
            <?php echo "<h2>Benvenuto: " . $utente['nome'] . " " . $utente['cognome'] . "</h2>"; ?>
            <h2>Family Finance</h2>
            <div class="header-left"></div>
            <div class="header-right">
                <a href="../php/profilo.php">
                    <span class="material-icons-outlined">account_box</span>
                </a>
            </div>
        </header>

        <aside id="sidebar">
            <div class="sidebar-brand">
                <img src="../img/Logos.png" alt="Logo del tuo sito web">
            </div>
            <div class="sidebar-title">
                <span class="material-icons-outlined" onclick="closeSidebar()">close</span>
            </div>
            <ul class="sidebar-list">
                <li class="sidebar-list-item">
                    <span class="material-icons-outlined"></span> Menu
                </li>
                <li class="sidebar-list-item">
                    <a class="link" href="dashboard.php">
                        <span class="material-icons-outlined">dashboard</span> Dashboard
                    </a>
                </li>
                <li class="sidebar-list-item">
                    <a href="creafamiglia.php">
                        <span class="material-icons-outlined">diversity_1</span> Crea la tua Famiglia
                    </a>
                </li>
                <li class="sidebar-list-item">
                    <a href="aggiuntamembri.php">
                        <span class="material-icons-outlined">add</span> Aggiungi Membri
                    </a>
                </li>
                <li class="sidebar-list-item">
                    <a href="eliminazionemembri.php">
                        <span class="material-icons-outlined">edit</span> Elimina Membri
                    </a>
                </li>
                <li class="sidebar-list-item">
                    <a href="aggiuntaspese.php">
                        <span class="material-icons-outlined">attach_money</span> Spese
                    </a>
                </li>
                <li class="sidebar-list-item">
                    <a href="statistiche.php">
                        <span class="material-icons-outlined">bar_chart</span> Statistiche
                    </a>
                </li>
                <li class="sidebar-list-item">
                    <a href="aggiuntaguadagni.php">
                        <span class="material-icons-outlined">savings</span> Entrate
                    </a>
                </li>
                <li class="sidebar-list-item">
                    <span class="material-icons-outlined" id="logout">logout</span> Logout
                </li>
            </ul>
        </aside>

        <main class="main-container">
            <div class="form" id="form">
                <h2>Inserisci il nome della tua Famiglia</h2>
                <label id="inserimento" for="familyName">Inserisci il nome della tua famiglia:</label>
                <input type="text" id="nomeFamiglia" name="familyName" required>
                <br>
                <button class="buttoncreafamiglia" type="submit" id="creazione">Crea la tua famiglia</button>
            </div>
        </main>
    </div>
</body>
<script>
    function logout() {
        $.ajax({
            method: "POST",
            url: "../php/api.php/Logout",
            success: function(risposta) {
                if (risposta == "Logout effettuato con successo")
                    location.href = "../html/login.html";
            }
        })
    }
    $(document).ready(function() {
        $("#logout").click(logout);
    });
</script>
</html>
