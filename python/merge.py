import csv
from sympy import primerange
from random import sample
from sympy import prod
from random import randrange
from sympy.ntheory.modular import crt
import mysql.connector
import sys

# Connetti al database MySQL
mydb = mysql.connector.connect(
    host="127.0.0.1",
    user="root",
    password="",
    database="tesi"
    )
id_rrns=sys.argv[1]
token1=int(sys.argv[2])
token2=int(sys.argv[3])
token3=int(sys.argv[4])
token4=int(sys.argv[5])
def recupero_moduli(id_rrns):
    # Crea un cursore per eseguire le query
    mycursor = mydb.cursor()

    # Query SQL per selezionare i moduli corrispondenti all'id_rrns
    sql = "SELECT modulo1,modulo2,modulo3,modulo4 FROM rrns_secret WHERE id_rrns = %s"

    # Esegui la query con il valore dell'id_rrns
    mycursor.execute(sql, (id_rrns,))

    # Ottieni il risultato della query
    result = mycursor.fetchone()

    # Chiudi il cursore
    mycursor.close()

    # Restituisci i moduli se trovati, altrimenti None
    if result:
        return result
    else:
        return None

moduli=recupero_moduli(id_rrns)
resti=[token1,token2,token3,token4]
# Converti i moduli in interi
moduli = tuple(int(x) for x in moduli)
y = crt(moduli,resti,symmetric=False)[0]
print(y)

