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
    <title>Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
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
        
                <?php echo "<h2>Benvenuto: " . $utente['nome'] . " " . $utente['cognome']."</h2>";?>
                <h2>Family Finance</h2>
            <div class="header-left"></div>
            <div class=header-right>
                <a href="../php/profilo.php">
                    <span class="material-icons-outlined">account_box</span>
                </a>    
            </div>

             
        </header>
        
        <aside id="sidebar">
            <div class="sidebar-brand">
                <img src="../img/Logos.png" alt="Logo del tuo sito web">
                <figcaption>Family Finance</figcaption>
                
            </div>
            <div class="sidebar-title">
                <span class="material-icons-outlined" onclick="closeSidebar()">close</span>
            </div>
            <ul class=sidebar-list>
                <li class="sidebar-list-item">
                <span class="material-icons-outlined"></span> Menu
                </li>

                <li class="sidebar-list-item">
                    <a class="link"href="dashboard.php">
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
                </li>
                <li class="sidebar-list-item">
                    <a href="aggiuntaguadagni.php">
                        <span class="material-icons-outlined">savings</span> Entrate
                    </a>
                <li class="sidebar-list-item">
                        <span class="material-icons-outlined" id="logout">logout</span> Logout
                </li>
            </ul>
        </aside>

        <main class="main-container">
            <div class="main-title"> 
                <h2 style="font-family:'Times New Roman', Times, serif">Profilo</h2>
            </div>
            <div class="main-content">
                <div class="profilo">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="validationDefault01" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="nome" readonly>
                        </div>
                        <div class="col-md-4">
                            <label for="validationDefault02" class="form-label">Cognome</label>
                            <input type="text" class="form-control" id="cognome" readonly>
                        </div>
                        <div class="col-md-4">
                            <label for="validationDefaultUsername" class="form-label">Email</label>
                        <div class="input-group">
                            <span class="input-group-text" id="inputGroupPrepend2">@</span>
                            <input type="text" class="form-control" id="email" readonly>
                        </div>
                        </div>
                        <h4 class="mb-1">Se vuoi cambiare la tua password</h4>
                    <div class="col-md-6">
                        <label for="password" class="form-label">Password Corrente</label>
                        <input type="password" class="form-control" id="password" placeholder="Password Corrente">
                    </div>
                    <div class="col-md-6">
                        <label for="new_password" class="form-label">Nuova Password</label>
                        <input type="password" class="form-control" id="new_password" placeholder="Nuova Password">
                    </div>
                    <div class="col-md-6">
                        <button id="update" type="submit" name="update" class="btn btn-primary">Update Password</button>
                    </div>
                    </div>
                </div>
            </div>
 <!-- jQuery per visualizzare i dati dell'utente  -->           
<script>
    $.ajax({
            url: "api.php/profilo",
            type: "GET",
            success: function (risposta){
                var data = JSON.parse(risposta);
                $('#nome').val(data.nome);
                $('#cognome').val(data.cognome);
                $('#email').val(data.email);
            }
        });
</script>

<!-- jQuery per modificare la password dell'utente  -->
<script>
    $('#update').click(function (){
        var data= {};
        data.password= document.getElementById('password').value;
        data.new_password= document.getElementById('new_password').value;
        console.log(data);
        $.ajax({
            type: "PUT",
            url: "api.php/update",
            data: data,
            success: function (data) { // Se la chiamata ha successo viene effettuato anche il logout
                if (data == "Password aggiornata con successo") {
                    $.ajax({
                        method: "POST",
                        url: "api.php/Logout",
                        success: function (data) {
                            if (data == "Logout effettuato con successo")
                                location.href = "../html/login.html";
                            else
                                alert(data);
                        }
                    });
                }
                else
                    alert(data);
            }
        })
    })
</script>
</html>