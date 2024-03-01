$(document).ready(function(){
    $.ajax({
        method: "GET",
        url: "../php/api.php/ListaMembri", // Funzione che ritorna la lista dei membri della famiglia e li inserisce direttamente nella tabella
        success: function (risposta) {
            console.log(risposta);
            var membri = JSON.parse(risposta);
            console.log(membri);
            var tabella = document.getElementById("tabella");
            for (var i = 0; i < membri.length; i++) {
                var tr = document.createElement("tr");
                tr.id = membri[i].id_membri_famiglia;
                var td = document.createElement("td");
                td.innerHTML = membri[i].nome;
                tr.appendChild(td);
                var td = document.createElement("td");
                td.innerHTML = membri[i].cognome;
                tr.appendChild(td);
                var td = document.createElement("td");
                var button = document.createElement("button");
                button.innerHTML = "Elimina"; //creo il bottone
                button.className = "btn btn-danger eliminazione";//aggiungo la classe per il css
                td.appendChild(button);//aggiungo il bottone al td
                tr.appendChild(td);//aggiungo il bottone alla riga
                tabella.appendChild(tr);//aggiungo la riga alla tabella
            }
            $(".eliminazione").click(function(e){ // Funzione che viene eseguita quando si clicca sul bottone elimina che serve per eliminare un membro della famiglia
                var data={};
                data.membro=e.delegateTarget.parentElement.parentElement.id;
                if(data.membro==""){
                    alert("Selezione un Membro che vuoi eliminare");
                    return;
                }
                $.ajax({
                    method: "DELETE",
                    url: "../php/api.php/EliminaMembro",
                    data: data,
                    success: function(risposta){
                        console.log(risposta);
                        alert(risposta);
                        window.location.href="../php/dashboard.php";
                    }
                })
            }); 
        }
    })
})  

