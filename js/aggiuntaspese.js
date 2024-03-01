function aggiungispesa(){ // Funzione che aggiunge una spesa al database
    var data={};
    data.descrizione=document.getElementById("descrizione").value;
    data.importo=document.getElementById("importo").value;
    data.data=document.getElementById("data").value;
    data.nome_membro=document.getElementById("nome").value;
    if(data.descrizione=="" || data.importo=="" || data.data=="" || data.nome_membro==""){
        alert("Compilare tutti i campi");
        return;
    }
    $.ajax({
        method: "POST",
        url: "../php/api.php/AggiungiSpesa",
        data: data,
        success: function(risposta){
            alert(risposta);
            window.location.href="../php/dashboard.php";
        }
    })
}
$(document).ready(function(){
    $("#aggiungispesa").click(aggiungispesa);
});