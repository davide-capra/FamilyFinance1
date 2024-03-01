<?php
require_once('connessionedb.php');
session_start();
if (!isset($_SESSION['email'])) {
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
    <title>Aggiungi Le tue Spese</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="../js/aggiuntaspese.js"></script>
     <!-- Montserrat Font -->
     <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">

</head>
<body>
    <div class="grid-container">

        <header class="header">
            <?php echo "<h2>Benvenuto: " . $utente['nome'] . " " . $utente['cognome'] . "</h2>"; ?>
            <h2>Family Finance</h2>

            <div class="menu-icon" onclick="openSidebar()">
                <span class="material-icons-outlined" id="menu">menu</span>
            </div>
            <div class="header-left"></div>
            <div class="header-right">
                <a href="profilo.php">
                    <span class="material-icons-outlined">person</span> Profilo
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
                    <a href="dashboard.php">
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
                <h1>Aggiungi la spesa sostenuta</h1>
                <label for="descrizione">Descrizione:</label>
                <input type="text" id="descrizione" name="descrizione">

                <label for="importo">Importo:</label>
                <input type="number" id="importo" name="importo" step="0.01" min="0">

                <label for="data">Data:</label>
                <input type="date" id="data" name="data">

                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome">

                <button class="aggiungi" type="submit" id="aggiungispesa">Clicca Qui</button>
            </div>
        </main>
    </div>
</body>
<script>
    function logout() {
        $.ajax({
            method: "POST",
            url: "../php/api.php/Logout",
            success: function (risposta) {
                console.log(risposta);
                if (risposta == "Logout effettuato con successo")
                    location.href = "../html/login.html";
            }
        });
    }

    $(document).ready(function () {
        $("#logout").click(logout);
    });
</script>
</html>
