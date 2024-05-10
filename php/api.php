<?php
require_once('connessionedb.php');
$request = explode('/', trim($_SERVER['PATH_INFO'], '/'));    //array contenente i dati passati nell'url
$method = $_SERVER['REQUEST_METHOD'];                       //metodo di richiesta
parse_str(file_get_contents('php://input'), $_PUT);        //$_PUT contiene i dati passati con il metodo PUT
parse_str(file_get_contents('php://input'), $_DELETE);     //$_DELETE contiene i dati passati con il metodo DELETE


switch ($method) { //switch per il metodo di richiesta
    case 'GET':
        if ($request[0] == "ListaMembri") { // GET per la lista dei membri della famiglia
            session_start();
            $email = $_SESSION['email']; //email dell'utente loggato
            $q = $pdo->prepare("SELECT * FROM utenti WHERE email = :email"); //query per prendere l'id della famiglia dell'utente loggato
            $q->bindParam(':email', $_SESSION['email'], PDO::PARAM_STR); // La funzione bindParam() viene utilizzata per associare un parametro a una dichiarazione preparata (prepared statement) in PDO (PHP Data Objects).
            $q->execute(); //esecuzione della query
            $utente = $q->fetch(PDO::FETCH_ASSOC);
            $id_famiglia = $utente['id_famiglia']; //id della famiglia dell'utente loggato
            $sql = "SELECT membri_famiglia.nome, membri_famiglia.cognome, membri_famiglia.id_membri_famiglia 
                    FROM membri_famiglia 
                    INNER JOIN utenti ON membri_famiglia.id_famiglia = utenti.id_famiglia 
                    WHERE utenti.id_famiglia = :id_famiglia";
            $q = $pdo->prepare($sql);
            $q->bindParam(':id_famiglia', $id_famiglia, PDO::PARAM_INT);
            $q->execute();
            $membri = $q->fetchAll(PDO::FETCH_ASSOC);//restituisce un array contenente tutte le righe del set di risultati
            echo json_encode($membri);
        } 
        if($request[0]=="Statistiche"){ // GET per le statistiche
        session_start();
        $email = $_SESSION['email'];
        $membri = array();

        $q = $pdo->prepare("SELECT * FROM utenti WHERE email = :email");
        $q->bindParam(':email', $email, PDO::PARAM_STR);
        $q->execute();
        $utente = $q->fetch(PDO::FETCH_ASSOC);
        $id_famiglia = $utente['id_famiglia'];
        
        $sql = "SELECT id_membri_famiglia,membri_famiglia.nome, membri_famiglia.cognome FROM membri_famiglia WHERE id_famiglia = :id_famiglia";
        $q = $pdo->prepare($sql);
        $q->bindParam(':id_famiglia', $id_famiglia, PDO::PARAM_INT);
        $q->execute();
        $id_membri_famiglia = $q->fetchAll(PDO::FETCH_ASSOC);
        foreach ($id_membri_famiglia as $membro){ // ciclo per prendere i membri della famiglia
            $membri[$membro['id_membri_famiglia']] = $membro;
        }

        $sql = "SELECT * FROM voci_spesa WHERE id_membri_famiglia IN (SELECT id_membri_famiglia FROM membri_famiglia WHERE id_famiglia = :id_famiglia)";
        $q = $pdo->prepare($sql); 
        $q->bindParam(':id_famiglia', $id_famiglia, PDO::PARAM_INT); // La funzione bindParam() viene utilizzata per associare un parametro a una dichiarazione preparata (prepared statement) in PDO (PHP Data Objects).
        $q->execute();//esecuzione della query
        $spese = $q->fetchAll(PDO::FETCH_ASSOC);//restituisce un array contenente tutte le righe del set di risultati
        for ($i=0; $i < count($spese); $i++){ // ciclo per prendere le spese
            $spese[$i]['nome'] = $membri[$spese[$i]['id_membri_famiglia']]['nome']; //aggiungo il nome del membro della famiglia che ha inserito la spesa
            $spese[$i]["cognome"] = $membri[$spese[$i]['id_membri_famiglia']]['cognome'];//aggiungo il cognome del membro della famiglia che ha inserito la spesa
        }

        $guadagni="SELECT id_membri_famiglia,membri_famiglia.nome, membri_famiglia.cognome FROM membri_famiglia WHERE id_famiglia = :id_famiglia";
        $q = $pdo->prepare($guadagni);
        $q->bindParam(':id_famiglia', $id_famiglia, PDO::PARAM_INT);
        $q->execute();
        $id_membri_famiglia = $q->fetchAll(PDO::FETCH_ASSOC);
        foreach($id_membri_famiglia as $membro){
            $membri[$membro['id_membri_famiglia']] = $membro;
        }
        $sql = "SELECT * FROM voci_guadagno WHERE id_membri_famiglia IN (SELECT id_membri_famiglia FROM membri_famiglia WHERE id_famiglia = :id_famiglia)";
        $q = $pdo->prepare($sql);
        $q->bindParam(':id_famiglia', $id_famiglia, PDO::PARAM_INT);
        $q->execute();
        $guadagni = $q->fetchAll(PDO::FETCH_ASSOC);
        for ($i=0; $i < count($guadagni); $i++){
            $guadagni[$i]['nome'] = $membri[$guadagni[$i]['id_membri_famiglia']]['nome'];
            $guadagni[$i]["cognome"] = $membri[$guadagni[$i]['id_membri_famiglia']]['cognome'];
        }
        $result = array( //array associativo
            'spese' => $spese,
            'guadagni' => $guadagni
        );
        echo json_encode($result); //codifica in json
        
    }
    if($request[0] == 'profilo'){ // GET per il profilo
        session_start();
        $email = $_SESSION['email'];
        $q = $pdo->prepare("SELECT * FROM utenti WHERE email = :email");
        $q->bindParam(':email', $_SESSION['email'], PDO::PARAM_STR);
        $q->execute();
        $utente = $q->fetch(PDO::FETCH_ASSOC);
        echo json_encode($utente);
    }
    break;

case 'POST':
    if($request[0] == "Logout") {
        session_start();
        session_destroy();
        echo "Logout effettuato con successo";
        }
    if($request[0] == "registrazione") {
        $email= $_POST['email'];
        $nome= $_POST['nome'];
        $cognome= $_POST['cognome'];
        $password= $_POST['password'];
        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        
        if (empty($email) || empty($nome) || empty($cognome) || empty($password)) {
                print("Uno o più campi sono vuoti");
                return;
        }else{
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    print("Email non valida");
                    return;
                } 
            }
    
        $sql= "INSERT INTO utenti (email,password,nome,cognome) VALUES (:email, :password,:nome,:cognome)";
    
        $q=$pdo->prepare($sql);// 
        $q->bindParam(':email', $email, PDO::PARAM_STR); 
        $q->bindParam(':password', $password_hash,PDO::PARAM_STR);
        $q->bindParam(':nome', $nome, PDO::PARAM_STR);
        $q->bindParam(':cognome', $cognome, PDO::PARAM_STR);
    
        if($q->execute()){
            echo "Registrazione effettuata con successo";
        } else {
                echo "Errore durante la registrazione utente ";
            }
        }
    if($request[0]=="login"){
        session_start();
        // prendi i dati dalla richiesta
        $email=$_POST['email'];
        $password=$_POST['password'];
        $_SESSION['email'] = $email;
        // prepara la query
        $q= $pdo->prepare("SELECT * FROM utenti WHERE email= :email");
        $q->bindParam(':email', $email, PDO::PARAM_STR); //
        if(!$q->execute()){
            echo "Email non valida";
            return;
        }else{
            $p=$pdo->prepare("SELECT password FROM utenti WHERE email= :email"); 
            $p->bindParam(':email', $email, PDO::PARAM_STR);
            $p->execute();
            if(!$risultato = $p->fetch(PDO::FETCH_ASSOC)){// 
        }else{
            if(password_verify($password,$risultato['password'])){
                $sql="SELECT * FROM utenti WHERE email = :email";
                $q = $pdo->prepare($sql);
                $q->bindParam(':email', $email, PDO::PARAM_STR);
                $q->execute();
                $utente = $q->fetch(PDO::FETCH_ASSOC);
                $username = $utente['nome'];
                $cognome = $utente['cognome'];
                $sql="SELECT id_rrns FROM utenti WHERE email = :email";
                $q = $pdo->prepare($sql);
                $q->bindParam(':email', $email, PDO::PARAM_STR);
                $q->execute();
                $id_rrns = $q->fetch(PDO::FETCH_ASSOC)['id_rrns'];
                if($id_rrns != NULL){
                    $sql="UPDATE utenti SET id_rrns = NULL WHERE email = :email;
                    DELETE FROM rrns_secret WHERE id_rrns = :id_rrns";
                    $q = $pdo->prepare($sql);
                    $q->bindParam(':email', $email, PDO::PARAM_STR);
                    $q->bindParam(':id_rrns', $id_rrns, PDO::PARAM_STR);
                    $q->execute();
                }
                
                echo "Login effettuato con successo";
                // Percorso del file CSV
                $file_csv = 'dati_utenti.csv';

                // Apri il file CSV in modalità append
                $csv_file = fopen($file_csv, 'a');

                // Verifica se il file CSV è stato aperto correttamente
                if ($csv_file !== FALSE) {
                // Scrivi i dati nel file CSV
                fputcsv($csv_file, array($username, $cognome, $email));

                // Chiudi il file CSV
                fclose($csv_file);
                }

                // Comando per eseguire il file Python
                $command= shell_exec('python C:\xampp\htdocs\ProgTesi\python\rrns.py');
                if($id_rrns != NULL){
                    $sql="SELECT id_rrns FROM utenti WHERE email = :email";
                    $q = $pdo->prepare($sql);
                    $q->bindParam(':email', $email, PDO::PARAM_STR);
                    $q->execute();
                    $id_rrns = $q->fetch(PDO::FETCH_ASSOC)['id_rrns'];
                    $sql="SELECT rrns_token2,rrns_token3,rrns_token4 FROM rrns_secret WHERE id_rrns = :id_rrns";
                    $q = $pdo->prepare($sql);
                    $q->bindParam(':id_rrns', $id_rrns, PDO::PARAM_INT);
                    $q->execute();
                    $result = $q->fetch(PDO::FETCH_ASSOC);
                    if ($result) {
                        $token2= $result['rrns_token2'];
                        $token3= $result['rrns_token3'];
                        $token4= $result['rrns_token4'];
                        $output = shell_exec('python C:\\xampp\\htdocs\\ProgTesi\\python\\invioemail.py '.$email.' '.$token2.' '.$token3.' '.$token4);
                    }
                
                } else {
                    #echo "Nessun risultato trovato";
                }

            }else{
                echo "Errore durante il login";
            }

        }
    }
}
    if($request[0]=="2FA"){
        session_start();
        $email = $_SESSION['email'];
        $token1= $_POST['codice1'];
        $token2= $_POST['codice2'];
        $token3= $_POST['codice3'];
        $token4= $_POST['codice4'];
        $sql="SELECT id_rrns FROM utenti WHERE email = :email";
        $q = $pdo->prepare($sql);
        $q->bindParam(':email', $email, PDO::PARAM_STR);
        $q->execute();
        $id_rrns = $q->fetch(PDO::FETCH_ASSOC)['id_rrns'];
        $output = shell_exec('python C:\\xampp\\htdocs\\ProgTesi\\python\\merge.py '.$id_rrns.' '.$token1.' '.$token2.' '.$token3.' '.$token4.' 2>&1');
        $sql="SELECT rrns_secret FROM rrns_secret WHERE id_rrns = :id_rrns";
        $q = $pdo->prepare($sql);
        $q->bindParam(':id_rrns', $id_rrns, PDO::PARAM_INT);
        $q->execute();
        $result = $q->fetch(PDO::FETCH_ASSOC);
        $rrns_secret = $result['rrns_secret'];
        if($output == $rrns_secret ){
            echo "Autenticazione a 2 fattori effettuata con successo";
        }else{
            echo "Codice errato";
        }

        
        /*$token=$_POST['codice'];
        $sql="SELECT 2FA FROM utenti WHERE email = :email";
        $q = $pdo->prepare($sql);
        $q->bindParam(':email', $_SESSION['email'], PDO::PARAM_STR);
        $q->execute();
        $utente = $q->fetch(PDO::FETCH_ASSOC);
        $tokenDB = $utente['2FA'];
        $part = explode("_", $tokenDB);
        $tokengen= $part[0];
        $expiryTimestamp = $part[1];
            if($token==$tokengen){
                $valid= isTokenValid($expiryTimestamp);
                    if($valid){
                        echo "Autenticazione a 2 fattori effettuata con successo";
                    }else{
                        echo "Codice scaduto";
                    }
                    }else{
                        echo "Codice errato";
                    }
            // Resto del codice*/
    }
    if($request[0]=="CreazioneFamiglia"){
        session_start();
        $nome=$_POST['nome'];
        if(empty($nome)){
            echo "Uno o più campi sono vuoti";
            return;
        }

        $checkSql="SELECT id_famiglia FROM utenti WHERE email = :email AND id_famiglia IS NOT NULL";
        $checkStmt = $pdo->prepare($checkSql);
        $checkStmt->bindParam(':email', $_SESSION['email'], PDO::PARAM_STR);
        $checkStmt->execute();
        $checkResult = $checkStmt->fetch(PDO::FETCH_ASSOC);
    
    if ($checkResult !== false) {
        echo "Sei già membro di una famiglia";
        return;
    }
        $sql= "INSERT INTO famiglie (nome_famiglia) VALUES (:nome)";
        $q=$pdo->prepare($sql);
        $q->bindParam(':nome', $nome, PDO::PARAM_STR);

        if($q->execute()){
            $sql= "UPDATE utenti SET id_famiglia = LAST_INSERT_ID() WHERE email = :email";

            $q=$pdo->prepare($sql);
            $q->bindParam(':email', $_SESSION['email'], PDO::PARAM_STR);
            if($q->execute()){
                echo "Creazione famiglia effettuata con successo";
            } else {
                echo "Errore durante la creazione della famiglia ";
            }

        } else {
            echo "Errore durante la creazione della famiglia ";
        }
}
    if($request[0]=="AggiungiMembro"){
            session_start();
            $nome = $_POST['nome'];
        $cognome = $_POST['cognome'];
        $email = $_SESSION['email'];
        if (isset($_POST['nome']) && !empty($_POST['cognome'])) {
            $nome = $_POST['nome'];
            $cognome = $_POST['cognome'];
        } else {
            echo "Compila il campo nome e cognome";
            return;
        }

        // Ottieni l'id_famiglia dell'utente dal database
        $sql = "SELECT id_famiglia FROM utenti WHERE email = :email";
        $q = $pdo->prepare($sql);
        $q->bindParam(':email', $email, PDO::PARAM_STR);
        $q->execute();
        $id_famiglia = $q->fetch(PDO::FETCH_ASSOC)['id_famiglia'];

        // Salva l'id_famiglia dell'utente in sessione con la chiave "id_famiglia"
        $_SESSION['id_famiglia'] = $id_famiglia;

        if (empty($nome) || empty($cognome)) {
            print("Uno o più campi sono vuoti");
            return;
        }

        $sql= "INSERT INTO membri_famiglia (id_famiglia,nome,cognome) VALUES (:id_famiglia,:nome,:cognome)";
        $q = $pdo->prepare($sql);
        $q->bindParam(':id_famiglia', $id_famiglia, PDO::PARAM_STR);
        $q->bindParam(':nome', $nome, PDO::PARAM_STR);
        $q->bindParam(':cognome', $cognome, PDO::PARAM_STR);

        if ($q->execute()) {
            echo "Aggiunta membro effettuata con successo";
        } else {
              echo "Errore durante l'aggiunta del membro";
        }
}
    if($request[0]=="AggiungiSpesa"){
        session_start();
        $descrizione = $_POST['descrizione'];
        $importo = $_POST['importo'];
        $data = $_POST['data'];
        $nome=$_POST['nome_membro'];
        $email = $_SESSION['email'];
        $sql="SELECT id_membri_famiglia FROM membri_famiglia WHERE nome = :nome";
        $q = $pdo->prepare($sql);
        $q->bindParam(':nome', $nome, PDO::PARAM_STR);
        $q->execute();

        $id_membro_famiglia = $q->fetch(PDO::FETCH_ASSOC)['id_membri_famiglia'];
        $sql="INSERT INTO  voci_spesa(descrizione,importo,data,id_membri_famiglia) VALUES (:descrizione,:importo,:data,:id_membri_famiglia)";
        $q = $pdo->prepare($sql);
        $q->bindParam(':descrizione', $descrizione, PDO::PARAM_STR);
        $q->bindParam(':importo', $importo, PDO::PARAM_STR);
        $q->bindParam(':data', $data, PDO::PARAM_STR);
        $q->bindParam(':id_membri_famiglia', $id_membro_famiglia, PDO::PARAM_STR);
        if ($q->execute()) {
            echo "Aggiunta spesa effettuata con successo";
        } else {
            echo "Errore durante l'aggiunta della spesa";
        }

} 
    if($request[0]=="AggiungiGuadagno"){  //Aggiungi guadagno
        session_start();
        $descrizione = $_POST['descrizione'];
        $importo = $_POST['importo'];
        $data = $_POST['data'];  
        $nome=$_POST['membro'];
        $email = $_SESSION['email'];  // Ottieni l'id_famiglia dell'utente dal database
        $sql="SELECT id_membri_famiglia FROM membri_famiglia WHERE nome = :nome";
        $q = $pdo->prepare($sql);
        $q->bindParam(':nome', $nome, PDO::PARAM_STR);
        $q->execute();
        $id_membro_famiglia=$q->fetch(PDO::FETCH_ASSOC)['id_membri_famiglia'];
        $sql="INSERT INTO  voci_guadagno(descrizione,importo,data,id_membri_famiglia) VALUES (:descrizione,:importo,:data,:id_membri_famiglia)";
        $q = $pdo->prepare($sql);
        $q->bindParam(':descrizione', $descrizione, PDO::PARAM_STR);
        $q->bindParam(':importo', $importo, PDO::PARAM_STR);
        $q->bindParam(':data', $data, PDO::PARAM_STR);
        $q->bindParam(':id_membri_famiglia', $id_membro_famiglia, PDO::PARAM_STR);
        if ($q->execute()) {
            echo "Aggiunta guadagno effettuata con successo";
        } else {
            echo "Errore durante l'aggiunta del guadagno";
        }
}

        break;
    case 'PUT':
        if($request[0]=="update"){ //cambio password
            session_start();
            parse_str(file_get_contents('php://input'),$_PUT);
            $email = $_SESSION['email'];
            $password = $_PUT['password'];
            $new_password = $_PUT['new_password'];
            $sql="SELECT password FROM utenti WHERE email = :email";
            $q = $pdo->prepare($sql);
            $q->bindParam(':email', $email, PDO::PARAM_STR);
            $q->execute();
            $risultato=$q->fetch(PDO::FETCH_ASSOC);
            if($risultato && password_verify($password,$risultato['password'])){
                $new_password_hash = password_hash($new_password, PASSWORD_BCRYPT);
                $sql="UPDATE utenti SET password = :new_password_hash WHERE email = :email";
                $q = $pdo->prepare($sql);
                $q->bindParam(':email', $email, PDO::PARAM_STR);
                $q->bindParam(':new_password_hash', $new_password_hash, PDO::PARAM_STR);
                $q->execute();
                echo "Password aggiornata con successo";
            } else {
                echo "Errore durante l'aggiornamento della password";
            }
    }else{
        echo "Invalid request";
    }
       break;
    case 'DELETE': //elimina membro
        if($request[0]=="EliminaMembro"){
            $id_membri_famiglia = $_DELETE['membro'];
            $sql="DELETE FROM membri_famiglia WHERE id_membri_famiglia = :id_membri_famiglia ";
            //$sql = "UPDATE membri_famiglia SET eliminato=1 WHERE id_membri_famiglia = :id_membri_famiglia";
            $q = $pdo->prepare($sql);
            $q->bindParam(':id_membri_famiglia', $id_membri_famiglia, PDO::PARAM_STR);
            if($q->execute()){
                echo "Eliminazione membro effettuata con successo";
            } else {
                echo "Errore durante l'eliminazione del membro";
            }
        }
        else if ($request[0] == "2FAELIMINAZIONE") {
            session_start();
            $email = $_SESSION['email'];
            $sql = "SELECT id_rrns FROM utenti WHERE email = :email";
            $q = $pdo->prepare($sql);
            $q->bindParam(':email', $email, PDO::PARAM_STR);
            $q->execute();
            $risultato = $q->fetch(PDO::FETCH_ASSOC);
            $sql = "DELETE FROM rrns_secret WHERE id_rrns = :id_rrns;
                    UPDATE utenti SET id_rrns = NULL WHERE email = :email";
            $q = $pdo->prepare($sql);
            $q->bindParam(':id_rrns', $risultato['id_rrns'], PDO::PARAM_INT);
            $q->bindParam(':email', $email, PDO::PARAM_STR);
            if ($q->execute()) {
                echo "Eliminazione 2FA effettuata con successo";
            } else {
                echo "Errore durante l'eliminazione del 2FA";
            }
            
            /*if ($risultato['2FA'] != NULL) {
                $sql = "UPDATE utenti SET id_rrns = NULL WHERE email= :email";
                $q = $pdo->prepare($sql);
                $q->bindParam(':email', $email, PDO::PARAM_STR);
                $q->execute();
                echo "Eliminazione 2FA effettuata con successo";
        } else {
            echo "Errore durante l'eliminazione del 2FA";
        }*/
    }
        break;
    default:
        exit;
}
function generateToken($length = 6) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $token = '';

    for ($i = 0; $i < $length; $i++) {
        $randomIndex = rand(0, $charactersLength - 1);
        $token .= $characters[$randomIndex];
    }

    return $token;
}
    function isTokenValid($expiryTimestamp) {
        // Confronta il timestamp di scadenza con l'istante di tempo corrente
        $currentTimestamp = time();

        if ($currentTimestamp <= $expiryTimestamp) {
            // Il token è ancora valido
            return true;
        } else {
            // Il token è scaduto
            return false;
        }
    }

/*$file = fopen('C:\xampp\htdocs\ProgTesi\token_data.csv', 'r');
                $data = fgetcsv($file); // Leggi la prima riga del file CSV
                $secret = $data[0];
                $remainder_1 = $data[1];
                $remainder_2 = $data[2];
                $remainder_3 = $data[3];
                $remainder_4 = $data[4];
                fclose($file);*/
                /*$token=generateToken(6);
                $expiry = 20*60; // 20 minuti di scadenza
                $expiryTimestamp = time() + $expiry; // Calcola il timestamp di scadenza
                $tokenWithExpiry = $token . '_' . $expiryTimestamp;
                $sql="UPDATE utenti SET 2FA = :tokenWithExpiry WHERE email = :email";
                $q = $pdo->prepare($sql);
                $q->bindParam(':tokenWithExpiry', $tokenWithExpiry, PDO::PARAM_STR);
                $q->bindParam(':email', $email, PDO::PARAM_STR);
                $q->execute();
                $to = $email;
                $subject = "Codice di autentificazione";
                $txt = "Ciao $username $cognome, ecco qui il tuo codice per autentificazione a 2 fattori: $token";
                $headers = "From: gamesslayers87@gmail.com" . "\r\n" ."CC:";
                mail($to,$subject,$txt,$headers);*/