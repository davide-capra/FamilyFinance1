function aggiungi(){// Funzione che aggiunge un membro al database
    var data={}; 
    data.nome=document.getElementById("nome").value;
    data.cognome=document.getElementById("cognome").value;
    if(data.nome=="" || data.cognome==""){
        alert("Compilare tutti i campi");
    }
    $.ajax({
        method: "POST",
        url: "../php/api.php/AggiungiMembro",
        data: data,
        success: function(risposta){
            alert(risposta);
            window.location.href="../php/dashboard.php";    
        }
    })
}
$(document).ready(function(){
    $("#aggiungi").click(aggiungi);
});