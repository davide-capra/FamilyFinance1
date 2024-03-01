function checkemail(input) { // funzione che controlla se l'email è valida
    var validRegex = /^[a-zA-Z0-9.!#$%&'+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:.[a-zA-Z0-9-]+)$/;
    if (input.match(validRegex)) {
        return true;
    } else {
        alert("Invalid email address!");
        return false;
    }
}
function campiVuoti() { // funzione che controlla se i campi sono vuoti
    var email = document.getElementById("email").value;
    var password = document.getElementById("password").value;
    if (email == "" || password == "") {
        alert("Compila tutti i campi");
        return false;
    } else {
        return true;
    }
}
function invio() { // funzione che invia i dati al server e se l'utente è registrato lo reindirizza alla dashboard
    var data = {};
    data.password = document.getElementById("password").value;
    data.email = document.getElementById("email").value;
    if (campiVuoti() == false) {
        return;
    } else {
        if (checkemail(data.email) == false) {
            return;
        } else {
            $.ajax({ // funzione che invia i dati al server
                method: "POST",
                url: "../php/api.php/login",
                data: data,
                success: function (risposta) {
                    if (risposta != "Login effettuato con successo") {
                        alert(risposta);
                        return;
                    } else {
                        location.href = "../php/2FA.php";
                    }
                }
            })
        }
    }
}
$(document).ready(function () { // funzione che viene eseguita quando il documento è pronto
    $("#login").click(invio);
});