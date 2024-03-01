function aggiungiguadagno(){ //funzione che aggiunge un guadagno al database 
    var data={};
    data.descrizione=document.getElementById("descrizione").value;
    data.importo=document.getElementById("importo").value;
    data.data=document.getElementById("data").value;
    data.membro=document.getElementById("nome").value;
    if(data.descrizione=="" || data.importo=="" || data.data=="" || data.membro==""){
        alert("Compilare tutti i campi");
        return;
    }
    $.ajax({
        method: "POST",
        url: "../php/api.php/AggiungiGuadagno",
        data: data,
        success: function(risposta){
            alert(risposta);
            window.location.href="../php/dashboard.php";
        }
    })
}





$(document).ready(function(){
    $("#aggiungiguadagno").click(aggiungiguadagno);
});
