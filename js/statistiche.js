$(document).ready(function(){ // Funzione che permette di visualizzare le statistiche
    $.ajax({
        method: "GET",
        url: "../php/api.php/Statistiche",
        success: function(risposta){
        var dati = JSON.parse(risposta);
        console.log(dati);

        var spese = dati.spese;
        var guadagni = dati.guadagni;

      // Elabora i dati delle spese
        var tabellaSpese = document.getElementById("tabellaSpese");
        for (var i = 0; i < spese.length; i++) {
        var tr = document.createElement("tr");
        tr.id = spese[i].id_membri_famiglia;
        var td = document.createElement("td");
        td.innerHTML = spese[i].nome;
        tr.appendChild(td);
        td = document.createElement("td");
        td.innerHTML = spese[i].cognome;
        tr.appendChild(td);
        td = document.createElement("td");
        td.innerHTML = spese[i].importo;
        tr.appendChild(td);
        td = document.createElement("td");
        td.innerHTML = spese[i].data;
        tr.appendChild(td);
        td = document.createElement("td");
        td.innerHTML = spese[i].descrizione;
        tr.appendChild(td);
        tabellaSpese.appendChild(tr);
      }

      // Elabora i dati dei guadagni
        var tabellaGuadagni = document.getElementById("tabellaGuadagni"); 
        for (var j = 0; j < guadagni.length; j++) {
        var tr = document.createElement("tr");
        tr.id = guadagni[j].id_membri_famiglia;
        var td = document.createElement("td");
        td.innerHTML = guadagni[j].nome;
        tr.appendChild(td);
        td = document.createElement("td");
        td.innerHTML = guadagni[j].cognome;
        tr.appendChild(td);
        td = document.createElement("td");
        td.innerHTML = guadagni[j].importo;
        tr.appendChild(td);
        td = document.createElement("td");
        td.innerHTML = guadagni[j].data;
        tr.appendChild(td);
        td = document.createElement("td");
        td.innerHTML = guadagni[j].descrizione;
        tr.appendChild(td);
        tabellaGuadagni.appendChild(tr);
      }
    }
  });
});
