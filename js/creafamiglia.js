function creazione(){  // Funzione che permette la creazione di una famiglia
    var data={};
    data.nome=document.getElementById("nomeFamiglia").value;
    if(data.nome==""){
        alert("Inserire un nome per la famiglia");
        return;
    }
    $.ajax({
        method: "POST",
        url: "../php/api.php/CreazioneFamiglia",
        data: data,
        success: function(risposta){
            alert(risposta);
            window.location.href="../php/dashboard.php";
            }
})
}
$(document).ready(function(){
    $("#creazione").click(creazione);
});