function campiVuoti() {   // funzione che controlla se i campi sono vuoti
    var nome = document.getElementById("nome").value;
    var cognome = document.getElementById("cognome").value;
    var email = document.getElementById("email").value;
    var password = document.getElementById("password").value;
    if (nome == "" || cognome == "" || email == "" || password == "") {
        alert("Compila tutti i campi");
        return false;
    } else {
        return true;
    }
}
function checkemail(input) { // funzione che controlla se l'email è valida
    var validRegex = /^[a-zA-Z0-9.!#$%&'+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:.[a-zA-Z0-9-]+)$/;
    if (input.match(validRegex)) {
        return true;
    } else {
        alert("Invalid email address!");
        return false;


    }
}
function invio() { // funzione che invia i dati presi dall'registrazione.html e li passa al server per la registrazione
    var data = {};
    data.password = document.getElementById("password").value;
    data.nome = document.getElementById("nome").value;
    data.email = document.getElementById("email").value;
    data.cognome = document.getElementById("cognome").value;

    if (campiVuoti() == false) {
        return;
    } else {
        if (checkemail(data.email) == false) {
            return;
        } else {
            $.ajax({
                method: "POST", // 
                url: "../php/api.php/registrazione",
                data: data,
                success: function (risposta) {
                    console.log(risposta);
                    if (risposta == "Registrazione effettuata con successo") {
                    location.href = "../html/login.html";
                }else if(risposta == "Errore durante la registrazione utente "){
                    alert("Errore durante la registrazione");
                    return;
                }
            }

    })
}
}
}
$(document).ready(function () {
    $("#registrati").click(invio);
});