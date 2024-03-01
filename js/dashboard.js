$(document).ready(function(){ // Funzione che viene eseguita quando il documento è pronto
    $.ajax({
        method: "GET",
        url: "../php/api.php/ListaMembri", // Funzione che ritorna la lista dei membri della famiglia e li inserisce direttamente nella tabella
        success: function(risposta){
            var membri=JSON.parse(risposta);
            var tabella=document.getElementById("tabella");// tabella in cui inserire i membri
            for(var i=0; i<membri.length; i++){ // ciclo che inserisce i membri nella tabella
                var tr = document.createElement("tr");// riga della tabella
                tr.id = membri[i].id_membri_famiglia;
                var td = document.createElement("td");// cella della tabella
                td.innerHTML = membri[i].nome;
                tr.appendChild(td);
                var td = document.createElement("td");
                td.innerHTML = membri[i].cognome;
                tr.appendChild(td);// inserimento della cella nella riga
                var td = document.createElement("td");
                var button = document.createElement("button");
                tr.appendChild(td);
                tabella.appendChild(tr);
            }
        }
    })
});
function logout(){ // funzione di logout
    $.ajax({
        method: "POST",
        url: "../php/api.php/Logout",
        success: function(risposta){
            console.log(risposta);
            if(risposta=="Logout effettuato con successo")
            location.href = "../html/login.html";
        }
    })
}
$(document).ready(function(){
    $("#logout").click(logout);
});
