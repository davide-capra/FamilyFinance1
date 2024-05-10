function invio(){
    var data = {};
    data.codice1 = document.getElementById("codice1").value;
    data.codice2= document.getElementById("codice2").value;
    data.codice3= document.getElementById("codice3").value;
    data.codice4= document.getElementById("codice4").value;
    if (data.codice == "") {
        alert("Inserisci il codice");
        return;
    }else{
            $.ajax({ // funzione che invia i dati al server
                method: "POST",
                url: "../php/api.php/2FA",
                data: data,
                success: function (risposta) {
                    if (risposta != "Autenticazione a 2 fattori effettuata con successo") {
                        alert(risposta);
                        return;
                }else{
                    $.ajax({
                        method: "DELETE",
                        url: "../php/api.php/2FAELIMINAZIONE",
                        success: function(risposta) {
                            console.log(risposta);
                            if (risposta != "Eliminazione 2FA effettuata con successo") {
                                //alert(risposta);
                            }else{
                                window.location.href = "../php/dashboard.php";
                            }
                            // Altre azioni da eseguire dopo l'eliminazione del token, se necessario
                        },
                        error: function(xhr, status, error) {
                            //alert("Errore nella richiesta di eliminazione del token");
                        }
                    });

                    /*$.ajax({
                        method: "DELETE",
                        url: "../php/api.php/2FAELIMINAZIONE",
                        success: function(risposta) {
                            console.log(risposta);
                            if (risposta != "Eliminazione 2FA effettuata con successo") {
                                //alert(risposta);
                            }else{
                                window.location.href = "../php/dashboard.php";
                            }
                            // Altre azioni da eseguire dopo l'eliminazione del token, se necessario
                        },
                        error: function(xhr, status, error) {
                            //alert("Errore nella richiesta di eliminazione del token");
                        }
                    });*/

                    
                }

            }
            })
            

        }
    }
$(document).ready(function(){
    $("#verifica").click(invio);
});
