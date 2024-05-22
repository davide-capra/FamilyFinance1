import csv
from sympy import primerange
from random import sample
from sympy import prod
from random import randrange
from sympy.ntheory.modular import crt
import mysql.connector

# Connetti al database MySQL
mydb = mysql.connector.connect(
    host="127.0.0.1",
    user="root",
    password="",
    database="tesi"
    )
# Funzione per eseguire la query e ottenere l'id_utente corrispondente all'email
def get_user_id(email):
    # Crea un cursore per eseguire le query
    mycursor = mydb.cursor()

    # Query SQL per selezionare l'id_utente corrispondente all'email
    sql = "SELECT id_utente FROM utenti WHERE email = %s"

    # Esegui la query con il valore dell'email
    mycursor.execute(sql, (email,))

    # Ottieni il risultato della query
    result = mycursor.fetchone()

    # Chiudi il cursore
    mycursor.close()

    # Restituisci l'id_utente se trovato, altrimenti None
    if result:
        return result[0]
    else:
        return None
    

# Funzione per inserire i dati nella tabella rrns_secret
def insert_rrns_secret(secret, remainder_1, remainder_2, remainder_3, remainder_4,modulo_1,modulo_2,modulo_3,modulo_4):
    # Crea un cursore per eseguire le query
    mycursor = mydb.cursor()

    # Query SQL per l'inserimento dei dati nella tabella rrns_secret
    sql = "INSERT INTO rrns_secret (rrns_secret, rrns_token1, rrns_token2, rrns_token3, rrns_token4, modulo1, modulo2, modulo3, modulo4) VALUES (%s, %s, %s, %s, %s,%s,%s,%s,%s)"

    # Valori da inserire nella query
    values = (secret, remainder_1, remainder_2, remainder_3, remainder_4,modulo_1,modulo_2,modulo_3,modulo_4)

    # Esegui la query di inserimento
    mycursor.execute(sql, values)

    # Fai il commit delle modifiche al database
    mydb.commit()
     # Query per ottenere l'ultimo id_rrns inserito
    sql_last_id_rrns = "SELECT LAST_INSERT_ID() FROM rrns_secret"

    # Esegui la query per ottenere l'ultimo id_rrns inserito
    mycursor.execute(sql_last_id_rrns)

    # Ottieni l'ultimo id_rrns inserito
    last_id_rrns = mycursor.fetchone()[0]

    # Leggi e consuma il risultato della query
    mycursor.fetchall()

    sql_update_utenti = "UPDATE utenti SET id_rrns = %s WHERE email = %s"

    # Esegui la query per aggiornare l'id_rrns dell'utente
    mycursor.execute(sql_update_utenti, (last_id_rrns, email))

    # Fai il commit delle modifiche al database
    mydb.commit()

    # Chiudi il cursore
    mycursor.close()

    


    
# Percorso del file CSV
file_csv = r'C:\xampp\htdocs\ProgTesi\php\dati_utenti.csv'


    # Inizializza le variabili per i dati
nome = ''
cognome = ''
email = ''

# Leggi l'ultima riga del file CSV
with open(file_csv, 'r') as csv_file:
    csv_reader = csv.reader(csv_file)
    # Leggi tutte le righe del file CSV
    righe_csv = list(csv_reader)
    # Estrai l'ultima riga (la riga appena inserita)
    ultima_riga = righe_csv[-1]

    # Verifica se ci sono abbastanza colonne nella riga
    if len(ultima_riga) >= 3:
        # Estrai i dati dalle colonne
        nome = ultima_riga[0]
        cognome = ultima_riga[1]
        email = ultima_riga[2]

# Genera una lista casuale di numeri primi come moduli
def generate_random_moduli(num_moduli, min_prime, max_prime):
    primes = list(primerange(min_prime, max_prime))
    return sample(primes, num_moduli)

# Numero di moduli desiderati
num_moduli = 4

# Range per la generazione casuale dei numeri primi
min_prime = 5
max_prime = 13

# Genera la lista casuale di moduli
moduli=generate_random_moduli(num_moduli, min_prime, max_prime)
print("Moduli generati:", moduli)

# Calcola il prodotto dei moduli
M =prod(moduli)
print("Prodotto dei moduli:", M)
# Genera un numero casuale minore di M
x =randrange(M)

# Calcola i resti della divisione di x per ciascun modulo
remainders = [x % m for m in moduli]


# Esegui la query per ottenere l'id_utente corrispondente all'email
id_utente = get_user_id(email)
print("id_utente:", id_utente)

# Chiamata alla funzione per inserire i dati nella tabella rrns_secret
insert_rrns_secret(x, remainders[0], remainders[1], remainders[2], remainders[3],moduli[0],moduli[1],moduli[2],moduli[3])

# Stampa l'id_utente ottenuto
# Stampa l'id_utente ottenuto
# if id_utente:
#     print("L'id_utente corrispondente all'email", email, "è:", id_utente)
# else:
#     print("Nessun id_utente trovato per l'email", email)

# Utilizza il teorema cinese del resto per trovare y
#y = crt(moduli, remainders, symmetric=False)[0]

# Stampa i risultati
#print("Numero casuale x:", x)
#print("Resti:", remainders)
#print("Valore calcolato y:", y)






